<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('provider_id_marketplace');
            $table->string('purchase_order');
            $table->double('amount_total_disbursement');
            $table->double('amount_application_fee');
            $table->double('amount_application_net_fee')->nullable();
            $table->double('amount_rsn_fee_net')->nullable();
            $table->double('amount_alitaware_fee_net')->nullable();
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
        Schema::dropIfExists('disbursements');
    }
}
