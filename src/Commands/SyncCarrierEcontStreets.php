<?php

namespace Gdinko\Econt\Commands;

use Illuminate\Console\Command;
use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Illuminate\Support\Facades\Validator;
use Gdinko\Econt\Models\CarrierEcontStreet;

class SyncCarrierEcontStreets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-streets {--timeout=20 : Econt API Call timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Econt streets and saves them in database';

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
        $this->info('-> Carrier Econt Import Streets');

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
        $cities = Econt::getStreets();

        $bar = $this->output->createProgressBar(count($cities));

        $bar->start();

        if (!empty($cities)) {
            CarrierEcontStreet::truncate();

            foreach ($cities as $city) {
                $validated = $this->validator($city);

                CarrierEcontStreet::create([
                    'econt_street_id' => $validated['id'],
                    'econt_city_id' => $validated['cityID'],
                    'name' => $validated['name'],
                    'name_en' => $validated['nameEn'],
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
            'cityID' => 'integer|required',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
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
