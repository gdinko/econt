<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontCity;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;

class SyncCarrierEcontCities extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:sync-cities
                            {--timeout=20 : Econt API Call timeout}
                            {--country_code= : Country ALPHA 3 ISO 3166 code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt cities and saves them in database';

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
        $this->info('-> Carrier Econt Import Cities');

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
        $countryCode = $this->option('country_code');

        if (empty($countryCode)) {
            $countryCode = '';
        }

        $cities = Econt::getCities(
            $countryCode
        );

        $bar = $this->output->createProgressBar(count($cities));

        $bar->start();

        if (! empty($cities)) {
            if (empty($countryCode)) {
                CarrierEcontCity::truncate();
            } else {
                CarrierEcontCity::where(
                    'country_code3',
                    $countryCode
                )->delete();
            }

            foreach ($cities as $city) {
                $validated = $this->validated($city);

                CarrierEcontCity::create([
                    'econt_city_id' => $validated['id'],
                    'country_code3' => $validated['country']['code3'],
                    'post_code' => $validated['postCode'],
                    'name' => $validated['name'],
                    'name_en' => $validated['nameEn'],
                    'region_name' => $validated['regionName'],
                    'region_name_en' => $validated['regionNameEn'],
                    'phone_code' => $validated['phoneCode'],
                    'location' => $validated['location'],
                    'express_city_deliveries' => $validated['expressCityDeliveries'],
                    'meta' => $validated['country'],
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
            'country' => 'array',
            'country.code3' => 'string|required',
            'postCode' => 'string|nullable',
            'name' => 'string|nullable',
            'nameEn' => 'string|nullable',
            'regionName' => 'string|nullable',
            'regionNameEn' => 'string|nullable',
            'phoneCode' => 'string|nullable',
            'location' => 'string|nullable',
            'expressCityDeliveries' => 'boolean|nullable',
        ];
    }
}
