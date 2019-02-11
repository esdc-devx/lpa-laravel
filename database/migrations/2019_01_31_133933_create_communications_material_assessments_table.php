<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationsMaterialAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communications_material_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_instance_form_id');
            $table->dateTime('assessment_date')->nullable();
            // Gate assessment fields.
            $table->boolean('is_entity_cancelled')->default(0);
            $table->text('entity_cancellation_rationale')->nullable();

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
        Schema::dropIfExists('communications_material_assessments');
    }
}