<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_resources', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_internal_resource', function (Blueprint $table) {
            $table->pivot('business_cases', 'internal_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_internal_resource');
        Schema::dropIfExists('internal_resources');
    }
}
