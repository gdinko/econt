<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontCities;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SyncCarrierEcontCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-cities {--timeout=20 : Econt API Call timeout}';

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

    protected function import()
    {
        $cities = Econt::getCities();

        $bar = $this->output->createProgressBar(count($cities));

        $bar->start();

        if (! empty($cities)) {
            CarrierEcontCities::truncate();

            foreach ($cities as $city) {
                $validated = $this->validator($city);

                CarrierEcontCities::create([
                    'econt_id' => $validated['id'],
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

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
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
        ]);

        if ($validator->fails()) {
            throw new EcontImportValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }
}
