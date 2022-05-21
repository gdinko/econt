<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCarrierEcontPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carrier_econt_payments', function (Blueprint $table) {
            $table->string('carrier_signature')->after('id')->nullable()->index();
            $table->string('carrier_account')->after('carrier_signature')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrier_econt_payments', function (Blueprint $table) {
            $table->dropColumn('carrier_signature');
            $table->dropColumn('carrier_account');
        });
    }
}
