<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_sources', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_request_source', function (Blueprint $table) {
            $table->pivot('business_cases', 'request_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_request_source');
        Schema::dropIfExists('request_sources');
    }
}
