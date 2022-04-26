<?php

namespace Gdinko\Econt\Commands;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Gdinko\Econt\Models\CarrierEcontCountries;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SyncCarrierEcontCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-countries {--timeout=20 : Econt API Call timeout}';

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
        $countries = Econt::getCountries();

        $bar = $this->output->createProgressBar(count($countries));

        $bar->start();

        if (! empty($countries)) {
            CarrierEcontCountries::truncate();

            foreach ($countries as $country) {
                $validated = $this->validator($country);

                CarrierEcontCountries::create([
                    'econt_id' => $validated['id'],
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

    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'id' => 'integer|nullable',
            'code2' => 'string|nullable',
            'code3' => 'string|required',
            'name' => 'string|required',
            'nameEn' => 'string|nullable',
            'isEU' => 'boolean|nullable',
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
