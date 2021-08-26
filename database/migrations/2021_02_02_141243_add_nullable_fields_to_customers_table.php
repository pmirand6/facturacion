<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('identification_type')->nullable()->change();
            $table->string('identification_number')->nullable()->change();
            $table->string('customer_address')->nullable()->change();
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('zip_code')->nullable()->change();
            $table->string('street_name')->nullable()->change();
            $table->string('street_number')->nullable()->change();
            $table->string('country_code')->nullable()->change();
            $table->string('state_code')->nullable()->change();
            $table->string('locality_code')->nullable()->change();
            $table->string('erp_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
