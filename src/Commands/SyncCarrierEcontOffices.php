<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontOffice;
use Gdinko\Econt\Traits\ValidatesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SyncCarrierEcontOffices extends Command
{
    use ValidatesImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'econt:sync-offices
                            {--timeout=20 : Econt API Call timeout}
                            {--country_code= : Country ALPHA 3 ISO 3166 code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt offices and saves them in database';

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
        $this->info('-> Carrier Econt Import Offices');

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

        $offices = Econt::getOffices(
            $countryCode
        );

        $bar = $this->output->createProgressBar(count($offices));

        $bar->start();

        if (! empty($offices)) {
            // CarrierEcontOffice::truncate();

            if (empty($countryCode)) {
                CarrierEcontOffice::truncate();
            } else {
                CarrierEcontOffice::where(
                    'country_code3',
                    $countryCode
                )->delete();
            }

            foreach ($offices as $office) {
                $validated = $this->validated($office);

                CarrierEcontOffice::create([
                    'econt_office_id' => $validated['id'],
                    'code' => $validated['code'],
                    'country_code3' => $validated['address']['city']['country']['code3'],
                    'econt_city_id' => $validated['address']['city']['id'],
                    'is_mps' => $validated['isMPS'],
                    'is_aps' => $validated['isAPS'],
                    'is_robot' => $validated['isAPS'],
                    'name' => $validated['name'],
                    'name_en' => $validated['nameEn'],
                    'phones' => $validated['phones'],
                    'emails' => $validated['emails'],
                    'address' => $validated['address'],
                    'info' => $validated['info'],
                    'currency' => $validated['currency'],
                    'language' => $validated['language'],
                    'normal_business_hours_from' => Carbon::createFromTimestampMs(
                        $validated['normalBusinessHoursFrom'],
                        'Europe/Sofia'
                    )->format('H:i:s'),

                    'normal_business_hours_to' => Carbon::createFromTimestampMs(
                        $validated['normalBusinessHoursTo'],
                        'Europe/Sofia'
                    )->format('H:i:s'),

                    'half_day_business_hours_from' => Carbon::createFromTimestampMs(
                        $validated['halfDayBusinessHoursFrom'],
                        'Europe/Sofia'
                    )->format('H:i:s'),

                    'half_day_business_hours_to' => Carbon::createFromTimestampMs(
                        $validated['halfDayBusinessHoursTo'],
                        'Europe/Sofia'
                    )->format('H:i:s'),

                    'shipment_types' => $validated['shipmentTypes'],
                    'partner_code' => $validated['partnerCode'],
                    'hub_code' => $validated['hubCode'],
                    'hub_name' => $validated['hubName'],
                    'hub_name_en' => $validated['hubNameEn'],
                    'meta' => $validated,
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
            'id' => 'integer|required',
            'code' => 'string|required',
            'isMPS' => 'boolean|nullable',
            'isAPS' => 'boolean|nullable',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
            'phones' => 'array|nullable',
            'emails' => 'array|nullable',
            'address' => 'array|required',
            'address.city.id' => 'integer|required',
            'address.city.country.code3' => 'string|required',
            'address.city.country.id' => 'sometimes|nullable',
            'address.city.country.code2' => 'sometimes|nullable',
            'address.city.country.name' => 'sometimes|nullable',
            'address.city.country.nameEn' => 'sometimes|nullable',
            'address.city.country.isEU' => 'sometimes|nullable',
            'address.city.country.postCode' => 'sometimes|nullable',
            'address.city.country.regionName' => 'sometimes|nullable',
            'address.city.country.regionNameEn' => 'sometimes|nullable',
            'address.city.country.phoneCode' => 'sometimes|nullable',
            'address.city.country.location' => 'sometimes|nullable',
            'address.city.country.expressCityDeliveries' => 'sometimes|nullable',
            'address.city.country.monday' => 'sometimes|nullable',
            'address.city.country.tuesday' => 'sometimes|nullable',
            'address.city.country.wednesday' => 'sometimes|nullable',
            'address.city.country.thursday' => 'sometimes|nullable',
            'address.city.country.friday' => 'sometimes|nullable',
            'address.city.country.saturday' => 'sometimes|nullable',
            'address.city.country.sunday' => 'sometimes|nullable',
            'address.fullAddress' => 'sometimes|nullable',
            'address.fullAddressEn' => 'sometimes|nullable',
            'address.quarter' => 'sometimes|nullable',
            'address.street' => 'sometimes|nullable',
            'address.num' => 'sometimes|nullable',
            'address.other' => 'sometimes|nullable',
            'address.location' => 'sometimes|nullable|array',
            'address.zip' => 'sometimes|nullable',
            'info' => 'string|nullable',
            'currency' => 'string|nullable',
            'language' => 'string|nullable',
            'normalBusinessHoursFrom' => 'numeric|nullable',
            'normalBusinessHoursTo' => 'numeric|nullable',
            'halfDayBusinessHoursFrom' => 'numeric|nullable',
            'halfDayBusinessHoursTo' => 'numeric|nullable',
            'shipmentTypes' => 'array|nullable',
            'partnerCode' => 'string|nullable',
            'hubCode' => 'string|nullable',
            'hubName' => 'string|nullable',
            'hubNameEn' => 'string|nullable',

        ];
    }
}
