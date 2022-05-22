<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Events\CarrierEcontTrackingEvent;
use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontTracking;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

abstract class TrackCarrierEcontBase extends Command
{
    protected $parcels = [];

    protected $muteEvents = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:track 
                            {--account= : Set Econt API Account}
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track Econt parcels';

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
        $this->info('-> Carrier Econt Parcel Tracking');

        try {
            $this->setAccount();

            $this->setup();

            $this->clear();

            Econt::setTimeout(
                $this->option('timeout')
            );

            $this->track();

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
     * setup
     *
     * @return void
     */
    abstract protected function setup();

    /**
     * clear
     *
     * @return void
     */
    protected function clear()
    {
        if ($days = $this->option('clear')) {
            $clearDate = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

            $this->info("-> Carrier Econt Parcel Tracking : Clearing entries older than: {$clearDate}");

            CarrierEcontTracking::where('created_at', '<=', $clearDate)->delete();
        }
    }

    /**
     * track
     *
     * @return void
     */
    protected function track()
    {
        $bar = $this->output->createProgressBar(
            count($this->parcels)
        );

        $bar->start();

        if (! empty($this->parcels)) {
            $trackingInfo = Econt::getShipmentStatuses(
                $this->parcels
            );

            if (! empty($trackingInfo)) {
                $this->processTracking($trackingInfo, $bar);
            }
        }

        $bar->finish();
    }

    /**
     * processTracking
     *
     * @param  array $trackingInfo
     * @param  mixed $bar
     * @return void
     */
    protected function processTracking(array $trackingInfo, $bar)
    {
        foreach ($trackingInfo as $tracking) {
            CarrierEcontTracking::updateOrCreate(
                [
                    'parcel_id' => $tracking['status']['shipmentNumber'],
                ],
                [
                    'carrier_signature' => Econt::getSignature(),
                    'carrier_account' => Econt::getUserName(),
                    'meta' => $tracking['status'],
                ]
            );

            if (! $this->muteEvents) {
                CarrierEcontTrackingEvent::dispatch(
                    array_pop($tracking['status']['trackingEvents']),
                    Econt::getUserName()
                );
            }

            $bar->advance();
        }
    }
}
