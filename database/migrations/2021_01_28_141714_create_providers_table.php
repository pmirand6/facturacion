<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('erp_id')->unique()->nullable();
            $table->string('provider_email')->unique();
            $table->string('identification_type');
            $table->string('identification_number');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('provider_address')->nullable();
            $table->string('street_name');
            $table->string('street_number');
            $table->string('zip_code');
            $table->string('country_code')->nullable();
            $table->string('state_code')->nullable();
            $table->string('locality_code')->nullable();
            $table->string('marketplace_id')->nullable();
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
        Schema::dropIfExists('providers');
    }
}
