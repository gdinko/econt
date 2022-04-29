<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontOffice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class SyncCarrierEcontOffices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-offices {--timeout=20 : Econt API Call timeout}';

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
        $offices = Econt::getOffices();

        $bar = $this->output->createProgressBar(count($offices));

        $bar->start();

        if (! empty($offices)) {
            CarrierEcontOffice::truncate();

            foreach ($offices as $office) {
                $validated = $this->validator($office);

                CarrierEcontOffice::create([
                    'econt_office_id' => $validated['id'],
                    'code' => $validated['code'],
                    'country_code3' => $validated['address']['city']['country']['code3'],
                    'econt_city_id' => $validated['address']['city']['id'],
                    'is_mps' => $validated['isMPS'],
                    'is_aps' => $validated['isAPS'],
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
                ]);

                $bar->advance();
            }
        }

        $bar->finish();
    }

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
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
