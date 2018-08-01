<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('risk_type_id')->nullable();
            $table->string('risk_type_other')->nullable();
            $table->unsignedInteger('risk_impact_level_id')->nullable();
            $table->unsignedInteger('risk_probability_level_id')->nullable();
            $table->text('rationale')->nullable();

            // Foreign keys.
            $table->referenceOn('risk_type_id', 'risk_types');
            $table->referenceOn('risk_impact_level_id', 'risk_impact_levels');
            $table->referenceOn('risk_probability_level_id', 'risk_probability_levels');
        });

        Schema::create('business_case_risk', function (Blueprint $table) {
            $table->pivot('business_cases', 'risks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_risk');
        Schema::dropIfExists('risks');
    }
}
