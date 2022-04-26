<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrierEcontCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_econt_countries', function (Blueprint $table) {
            $table->id();

            $table->integer('econt_id')->nullable();
            $table->char('code2', 2)->nullable();
            $table->char('code3', 3)->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('name_en')->nullable();
            $table->tinyInteger('is_eu')->nullable()->default(0);

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
        Schema::dropIfExists('carrier_econt_countries');
    }
}
