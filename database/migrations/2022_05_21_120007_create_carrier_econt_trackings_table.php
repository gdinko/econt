<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrierEcontTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_econt_trackings', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('parcel_id')->unique()->index();
            $table->string('carrier_signature')->index();
            $table->string('carrier_account')->index();
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrier_econt_trackings');
    }
}
