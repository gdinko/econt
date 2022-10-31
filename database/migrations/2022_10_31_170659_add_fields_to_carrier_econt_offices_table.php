<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCarrierEcontOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carrier_econt_offices', function (Blueprint $table) {
            $table
                ->char('city_uuid', 36)
                ->nullable()
                ->after('econt_city_id')
                ->index();

            $table
                ->tinyInteger('is_robot')
                ->nullable()
                ->default(0)
                ->after('is_aps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrier_econt_offices', function (Blueprint $table) {
            $table->dropColumn('city_uuid');
            $table->dropColumn('is_robot');
        });
    }
}
