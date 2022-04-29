<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrierEcontOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_econt_offices', function (Blueprint $table) {
            $table->id();

            $table->integer('econt_office_id')->index();
            $table->string('code')->index();
            $table->string('country_code3')->index();
            $table->string('econt_city_id')->index();
            $table->boolean('is_mps')->nullable()->default(0)->index();
            $table->boolean('is_aps')->nullable()->default(0)->index();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->json('phones')->nullable();
            $table->json('emails')->nullable();
            $table->json('address')->nullable();
            $table->text('info')->nullable();
            $table->string('currency')->nullable();
            $table->string('language')->nullable();
            $table->time('normal_business_hours_from')->nullable();
            $table->time('normal_business_hours_to')->nullable();
            $table->time('half_day_business_hours_from')->nullable();
            $table->time('half_day_business_hours_to')->nullable();
            $table->json('shipment_types')->nullable();
            $table->string('partner_code')->nullable();
            $table->string('hub_code')->nullable();
            $table->string('hub_name')->nullable();
            $table->string('hub_name_en')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrier_econt_offices');
    }
}
