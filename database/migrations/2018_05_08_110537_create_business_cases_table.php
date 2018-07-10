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
        Schema::dropIfExists('business_cases');
    }
}
