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
    protected $signature = 'econt:sync-all
                            {--timeout=20 : Econt API Call timeout}';

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
        $this->call('econt:sync-countries', [
            '--timeout' => $this->option('timeout'),
        ]);

        $this->call('econt:sync-cities', [
            '--timeout' => $this->option('timeout'),
        ]);

        $this->call('econt:sync-offices', [
            '--timeout' => $this->option('timeout'),
        ]);

        $this->call('econt:sync-streets', [
            '--timeout' => $this->option('timeout'),
        ]);

        $this->call('econt:sync-quarters', [
            '--timeout' => $this->option('timeout'),
        ]);

        return 0;
    }
}
