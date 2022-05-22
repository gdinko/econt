<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Events\CarrierEcontPaymentEvent;
use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Hydrators\Payment;
use Gdinko\Econt\Models\CarrierEcontPayment;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GetCarrierEcontPayments extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:get-payments
                            {--account= : Set Econt API Account} 
                            {--date_from=} 
                            {--date_to=}
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt payments and saves them in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('-> Carrier Econt Import Payments');

        try {
            $this->setAccount();

            $this->clear();

            Econt::setTimeout(
                $this->option('timeout')
            );

            $dateFrom = $this->option('date_from') ?: date('Y-m-d');
            $dateTo = $this->option('date_to') ?: date('Y-m-d');

            $this->info("Date From: {$dateFrom} - Date To: {$dateTo}");

            $this->import($dateFrom, $dateTo);

            $this->newLine(2);
        } catch (EcontImportValidationException $eive) {
            $this->newLine();
            $this->error(
                $eive->getMessage()
            );
            $this->info(
                print_r($eive->getData(), true)
            );
            $this->error(
                print_r($eive->getErrors(), true)
            );
        } catch (\Exception $e) {
            $this->newLine();
            $this->error(
                $e->getMessage()
            );
        }

        return 0;
    }

    /**
     * setAccount
     *
     * @return void
     */
    protected function setAccount()
    {
        if ($this->option('account')) {
            Econt::setAccountFromStore(
                $this->option('account')
            );
        }
    }

    /**
     * clear
     *
     * @return void
     */
    protected function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Carrier Econt Import Payments : Clearing entries older than: {$clearDate}");

            CarrierEcontPayment::where('created_at', '<=', $clearDate)->delete();
        }
    }

    /**
     * import
     *
     * @param  mixed $dateFrom
     * @param  mixed $dateTo
     * @return void
     */
    protected function import($dateFrom, $dateTo)
    {
        $payments = Econt::paymentReport(new Payment([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]));

        $bar = $this->output->createProgressBar(count($payments));

        $bar->start();

        if (! empty($payments)) {
            foreach ($payments as $payment) {
                $validated = $this->validated($payment);

                $carrierEcontPayment = CarrierEcontPayment::create([
                    'carrier_signature' => Econt::getSignature(),
                    'carrier_account' => Econt::getUserName(),
                    'num' => $validated['num'],
                    'type' => $validated['type'],
                    'pay_type' => $validated['payType'],
                    'pay_date' => $validated['payDate'],
                    'amount' => $validated['amount'],
                    'currency' => $validated['currency'],
                    'created_time' => $validated['createdTime'],
                ]);

                CarrierEcontPaymentEvent::dispatch(
                    $carrierEcontPayment,
                    Econt::getUserName()
                );

                $bar->advance();
            }
        }

        $bar->finish();
    }

    /**
     * validationRules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'num' => 'required',
            'type' => 'string|required',
            'payType' => 'string|required',
            'payDate' => 'required',
            'amount' => 'numeric|required',
            'currency' => 'string|required',
            'createdTime' => 'date_format:Y-m-d H:i:s|required',
        ];
    }
}
