<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_cases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_instance_form_id');
            // Business Drivers fields.
            $table->string('request_source_other')->nullable();
            $table->text('business_issue')->nullable();
            // Proposal fields.
            $table->text('learning_response_strategy')->nullable();
            $table->text('potential_solution_type_other')->nullable();
            $table->boolean('is_required_training')->nullable();
            // Timeframe fields.
            $table->unsignedInteger('timeframe_id')->nullable();
            $table->text('timeframe_rationale')->nullable();
            // Audience fields.
            $table->unsignedInteger('expected_annual_participant_number')->nullable();
            // Cost fields.
            $table->string('cost_center')->nullable();
            $table->unsignedInteger('maintenance_fund_id')->nullable();
            $table->text('maintenance_fund_rationale')->nullable();
            $table->unsignedInteger('salary_fund_id')->nullable();
            $table->text('salary_fund_rationale')->nullable();
            // Internal Resources fields.
            $table->text('internal_resource_other')->nullable();
            // Comment field.
            $table->text('comment')->nullable();

            // Foreign keys.
            $table->referenceOn('process_instance_form_id', 'process_instance_forms');
            $table->referenceOn('timeframe_id', 'timeframes');
            $table->referenceOn('maintenance_fund_id', 'maintenance_funds');
            $table->referenceOn('salary_fund_id', 'salary_funds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_cases');
    }
}
