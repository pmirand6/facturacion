<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{



    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('erp_id')->unique();
            $table->string('customer_email')->unique();
            $table->string('identification_type');
            $table->string('identification_number');
            $table->string('customer_address');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('zip_code');
            $table->string('street_name');
            $table->string('street_number');
            $table->string('country_code');
            $table->string('state_code');
            $table->string('locality_code');
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
        Schema::dropIfExists('customers');
    }
}
