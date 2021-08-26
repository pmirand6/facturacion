<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokensToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->string('access_token')->after('marketplace_id')->nullable();
            $table->string('token_type')->after('access_token')->nullable();
            $table->string('expires_in')->after('token_type')->nullable();
            $table->string('scope')->after('expires_in')->nullable();
            $table->string('refresh_token')->after('scope')->nullable();
            $table->string('public_key')->after('refresh_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            //
        });
    }
}
