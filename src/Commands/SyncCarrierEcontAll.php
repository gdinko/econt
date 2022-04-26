<?php

namespace Gdinko\Econt\Commands;

use Illuminate\Console\Command;

class SyncCarrierEcontAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-all {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync All Econt nomenclatures';

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
        $this->call('sync:carrier-econt-countries', [
            '--timeout' => $this->option('timeout')
        ]);

        $this->call('sync:carrier-econt-cities', [
            '--timeout' => $this->option('timeout')
        ]);

        return 0;
    }
}
