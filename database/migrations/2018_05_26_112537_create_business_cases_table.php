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
            // Project Objective fields.
            $table->string('request_origins_other')->nullable();
            $table->text('business_issue')->nullable();
            // Proposed Solution fields.
            $table->text('short_term_learning_response')->nullable();
            $table->text('medium_term_learning_response')->nullable();
            $table->text('long_term_learning_response')->nullable();
            // School Priorities fields.
            $table->boolean('is_required_training')->nullable();
            // Target Audience fields.
            $table->unsignedInteger('expected_annual_participant_number')->nullable();
            // Costs and Resources fields.
            $table->string('cost_centre')->nullable();
            $table->text('other_operational_considerations')->nullable();
            // Comments field.
            $table->text('comments')->nullable();

            // Foreign keys.
            $table->referenceOn('process_instance_form_id')->onDelete('cascade');
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
