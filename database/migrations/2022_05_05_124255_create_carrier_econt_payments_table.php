<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarrierEcontPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_econt_payments', function (Blueprint $table) {
            $table->id();

            $table->string('carrier_signature')->index();
            $table->string('carrier_account')->index();
            $table->string('num')->index()->unique();
            $table->string('type')->index();
            $table->string('pay_type');
            $table->date('pay_date')->index();
            $table->decimal('amount');
            $table->char('currency', 3);
            $table->timestamp('created_time');

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
        Schema::dropIfExists('carrier_econt_payments');
    }
}
