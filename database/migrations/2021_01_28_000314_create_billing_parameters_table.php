<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('billing_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('mercado_pago_rsn', 128)->nullable()->default('NULL');
            $table->string('mercado_pago_alitaware', 128)->nullable()->default('NULL');
            $table->double('commission_percent')->nullable();
            $table->double('iva_percent')->nullable();
            $table->double('commission_percent_rsn')->nullable();
            $table->double('commission_percent_alitaware')->nullable();
            $table->double('cost_until_20kg')->nullable();
            $table->double('cost_higher_20kg')->nullable();
            $table->double('cost_per_km_until_20kg')->nullable();
            $table->double('cost_per_km_higher_20kg')->nullable();
            $table->string('url_api_rsn')->nullable()->default('NULL');
            $table->string('url_api_alitaware')->nullable()->default('NULL');
            $table->string('user')->nullable()->default('NULL');
            $table->string('password')->nullable()->default('NULL');
            $table->string('client_id')->nullable()->default('NULL');
            $table->string('secret_key')->nullable()->default('NULL');
            $table->string('company', 128)->nullable()->default('NULL');
            $table->string('email_admin_rsn')->nullable()->default('NULL');
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
        Schema::dropIfExists('billing_parameters');
    }
}
