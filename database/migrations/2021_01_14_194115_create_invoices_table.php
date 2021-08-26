<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->string('email');
            $table->string('name');
            $table->string('last_name');
            $table->string('identification_type');
            $table->string('identification_number');
            $table->string('purchase_order');
            $table->string('payment_marketplace_id');
            $table->string('item_code');
            $table->enum('invoice_status', ['pending', 'processed', 'failed_process'])->default('pending');
            $table->enum('payment_status', ['approved', 'rejected']);
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
        Schema::dropIfExists('invoices');
    }
}
