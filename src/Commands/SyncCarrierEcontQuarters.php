<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontQuarter;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;

class SyncCarrierEcontQuarters extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:sync-quarters
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt quarters and saves them in database';

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
        $this->info('-> Carrier Econt Import Quarters');

        try {
            Econt::setTimeout(
                $this->option('timeout')
            );

            $this->import();

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
     * import
     *
     * @return void
     */
    protected function import()
    {
        $quarters = Econt::getQuarters();

        $bar = $this->output->createProgressBar(count($quarters));

        $bar->start();

        if (! empty($quarters)) {
            CarrierEcontQuarter::truncate();

            foreach ($quarters as $quarter) {
                $validated = $this->validated($quarter);

                CarrierEcontQuarter::create([
                    'econt_quarter_id' => $validated['id'],
                    'econt_city_id' => $validated['cityID'],
                    'name' => $validated['name'],
                    'name_en' => $validated['nameEn'],
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
            'id' => 'integer|nullable',
            'cityID' => 'integer|required',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
        ];
    }
}
