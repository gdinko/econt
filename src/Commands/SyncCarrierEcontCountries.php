<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontCountry;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;

class SyncCarrierEcontCountries extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:sync-countries
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt countries and saves them in database';

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
        $this->info('-> Carrier Econt Import Countries');

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
        $countries = Econt::getCountries();

        $bar = $this->output->createProgressBar(count($countries));

        $bar->start();

        if (! empty($countries)) {
            CarrierEcontCountry::truncate();

            foreach ($countries as $country) {
                $validated = $this->validated($country);

                CarrierEcontCountry::create([
                    'econt_country_id' => $validated['id'],
                    'code2' => $validated['code2'],
                    'code3' => $validated['code3'],
                    'name' => $validated['name'],
                    'name_en' => $validated['nameEn'],
                    'is_eu' => $validated['isEU'],
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
            'code2' => 'string|nullable',
            'code3' => 'string|required',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
            'isEU' => 'boolean|nullable',
        ];
    }
}
