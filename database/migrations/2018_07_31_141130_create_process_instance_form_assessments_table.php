<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessInstanceFormAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_instance_form_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->string('assessed_process_form');
            $table->unsignedInteger('process_form_decision_id')->nullable();
            $table->text('comment')->nullable();

            // Foreign keys.
            $table->referenceOn('process_form_decision_id', 'process_form_decisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_instance_form_assessments');
    }
}
