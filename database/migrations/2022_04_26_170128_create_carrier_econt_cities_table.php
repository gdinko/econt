<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrierEcontCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_econt_cities', function (Blueprint $table) {
            $table->id();

            $table->integer('econt_city_id')->nullable();
            $table->char('country_code3', 3)->nullable()->index();
            $table->string('post_code')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('region_name')->nullable();
            $table->string('region_name_en')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('location')->nullable();
            $table->tinyInteger('express_city_deliveries')->nullable()->default(0);
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
        Schema::dropIfExists('carrier_econt_cities');
    }
}
