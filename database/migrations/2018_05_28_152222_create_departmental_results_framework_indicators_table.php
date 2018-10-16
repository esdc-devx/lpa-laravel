<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentalResultsFrameworkIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departmental_results_framework_indicators', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_departmental_results_framework_indicator', function (Blueprint $table) {
            $table->pivot('business_cases', 'departmental_results_framework_indicators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departmental_results_framework_indicators');
        Schema::dropIfExists('business_case_departmental_results_framework_indicator');
    }
}
