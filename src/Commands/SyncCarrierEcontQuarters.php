<?php

namespace Gdinko\Econt\Commands;

use Illuminate\Console\Command;
use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Gdinko\Econt\Facades\Econt;
use Illuminate\Support\Facades\Validator;
use Gdinko\Econt\Models\CarrierEcontQuarter;

class SyncCarrierEcontQuarters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carrier-econt-quarters {--timeout=20 : Econt API Call timeout}';

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
        $quarters = Econt::getQuarters();

        $bar = $this->output->createProgressBar(count($quarters));

        $bar->start();

        if (!empty($quarters)) {
            CarrierEcontQuarter::truncate();

            foreach ($quarters as $quarter) {
                $validated = $this->validator($quarter);

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
