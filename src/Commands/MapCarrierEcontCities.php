<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierCityMap;
use Gdinko\Econt\Models\CarrierEcontCountry;
use Gdinko\Econt\Models\CarrierEcontOffice;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class MapCarrierEcontCities extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:map-cities
                            {country_code : Country ALPHA 2 ISO 3166 code}
                            {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt cities and makes carriers city map in database';

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
        $this->info('-> Carrier Econt Map Cities');

        try {
            Econt::setTimeout(
                $this->option('timeout')
            );

            $this->import();

            $this->newLine(2);
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
        $countryCode = $this->argument('country_code');

        $country = CarrierEcontCountry::where(
            'code2',
            $countryCode
        )->firstOrFail();

        $cities = Econt::getCities(
            $countryCode
        );

        if (! CarrierEcontOffice::where('country_code3', $country->code3)->count()) {
            $this->newLine();
            $this->warn('[WARN] Import econt offices first to map office city ...');
            $this->newLine();
        }

        $bar = $this->output->createProgressBar(
            count($cities)
        );

        $bar->start();

        if (! empty($cities)) {
            CarrierCityMap::where(
                'carrier_signature',
                Econt::getSignature()
            )
            ->where('country_code', $country->code3)
            ->delete();

            foreach ($cities as $city) {
                try {
                    $validated = $this->validated($city);

                    $name = $this->normalizeCityName(
                        $validated['name']
                    );

                    $nameSlug = $this->getSlug($name);

                    $slug = $this->getSlug(
                        $nameSlug . ' ' . $validated['postCode']
                    );

                    $data = [
                        'carrier_signature' => Econt::getSignature(),
                        'carrier_city_id' => $validated['id'],
                        'country_code' => $country->code3,
                        'region' => Str::title($validated['regionName']),
                        'name' => $name,
                        'name_slug' => $nameSlug,
                        'post_code' => $validated['postCode'],
                        'slug' => $slug,
                        'uuid' => $this->getUuid($slug),
                    ];

                    CarrierCityMap::create(
                        $data
                    );

                    //set city_uuid to all offices with this econt_city_id
                    CarrierEcontOffice::where(
                        'econt_city_id',
                        $validated['id']
                    )->update([
                        'city_uuid' => $data['uuid'],
                    ]);
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
                }

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
            'id' => 'integer|required',
            'country' => 'array',
            'country.code3' => 'string|required',
            'postCode' => 'string|nullable',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
            'regionName' => 'string|nullable',
            'regionNameEn' => 'string|nullable',
            'phoneCode' => 'string|required',
        ];
    }

    /**
     * normalizeCityName
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeCityName(string $name): string
    {
        return Str::title(
            explode(',', $name)[0]
        );
    }

    /**
     * getSlug
     *
     * @param  string $string
     * @return string
     */
    protected function getSlug(string $string): string
    {
        return Str::slug($string);
    }

    /**
     * getUuid
     *
     * @param  string $string
     * @return string
     */
    protected function getUuid(string $string): string
    {
        return Uuid::uuid5(
            Uuid::NAMESPACE_URL,
            $string
        )->toString();
    }
}
