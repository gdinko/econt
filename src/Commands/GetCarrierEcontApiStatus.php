<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontApiStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GetCarrierEcontApiStatus extends Command
{
    public const API_STATUS_OK = 200;
    public const API_STATUS_NOT_OK = 404;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:api-status
                            {--clear= : Clear Database table from records older than X days}
                            {--timeout=5 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt API Status and saves it in database';

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
        $this->info('-> Carrier Econt Api Status');

        try {
            $this->clear();

            Econt::setTimeout(
                $this->option('timeout')
            );

            $countries = Econt::getCountries();

            if (! empty($countries)) {
                CarrierEcontApiStatus::create([
                    'code' => self::API_STATUS_OK,
                ]);

                $this->info('Status: ' . self::API_STATUS_OK);
            }
        } catch (\Exception $e) {
            CarrierEcontApiStatus::create([
                'code' => self::API_STATUS_NOT_OK,
            ]);

            $this->newLine();
            $this->error('Status: ' . self::API_STATUS_NOT_OK);
            $this->error(
                $e->getMessage()
            );
        }

        return 0;
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

            $this->info("-> Carrier Econt Api Status : Clearing entries older than: {$clearDate}");

            CarrierEcontApiStatus::where('created_at', '<=', $clearDate)->delete();
        }
    }
}
