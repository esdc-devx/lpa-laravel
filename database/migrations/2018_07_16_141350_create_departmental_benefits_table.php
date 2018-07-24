<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentalBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departmental_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('departmental_benefit_type_id')->nullable();
            $table->string('departmental_benefit_type_other')->nullable();
            $table->text('rationale')->nullable();

            $table->referenceOn('departmental_benefit_type_id', 'departmental_benefit_types');
        });

        Schema::create('business_case_departmental_benefit', function (Blueprint $table) {
            $table->pivot('business_cases', 'departmental_benefits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_departmental_benefit');
        Schema::dropIfExists('departmental_benefits');
    }
}
