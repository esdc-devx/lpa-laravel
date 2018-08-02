<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCaseAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_case_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_instance_form_id');
            $table->dateTime('assessment_date')->nullable();
            // Gate assessment fields.
            $table->boolean('is_process_cancelled')->nullable();
            $table->text('process_cancellation_rationale')->nullable();

            // Foreign keys.
            $table->referenceOn('process_instance_form_id', 'process_instance_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_assessments');
    }
}
