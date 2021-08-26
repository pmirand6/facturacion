<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('item_name');
            $table->double('item_quantity');
            $table->double('item_unit_price');
            $table->double('item_subtotal');
            $table->string('delivery_type');
            $table->double('delivery_cost');
            $table->double('delivery_cost_iva');
            $table->double('delivery_cost_net');
            $table->string('payment_status');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')
                ->references('id')->on('payments');
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
        Schema::dropIfExists('items');
    }
}
