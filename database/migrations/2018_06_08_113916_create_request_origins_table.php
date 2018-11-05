<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestOriginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_origins', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_request_origin', function (Blueprint $table) {
            $table->pivot('business_cases', 'request_origins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_request_origin');
        Schema::dropIfExists('request_origins');
    }
}
