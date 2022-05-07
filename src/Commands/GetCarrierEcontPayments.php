<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Hydrators\Payment;
use Gdinko\Econt\Models\CarrierEcontPayment;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;

class GetCarrierEcontPayments extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:get-payments {--date_from} {--date_to} {--timeout=20 : Econt API Call timeout}';

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

                CarrierEcontPayment::create([
                    'num' => $validated['num'],
                    'type' => $validated['type'],
                    'pay_type' => $validated['payType'],
                    'pay_date' => $validated['payDate'],
                    'amount' => $validated['amount'],
                    'currency' => $validated['currency'],
                    'created_time' => $validated['createdTime'],
                ]);

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
            'payDate' => 'date_format:Y-m-d|required',
            'amount' => 'numeric|required',
            'currency' => 'string|required',
            'createdTime' => 'date_format:Y-m-d H:i:s|required',
        ];
    }
}
