<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->unsignedInteger('organizational_unit_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('process_instance_id')->unique()->nullable();
            // Form data entities.
            $table->unsignedInteger('business_case_id')->unique()->nullable();
            $table->unsignedInteger('business_case_assessment_id')->unique()->nullable();
            $table->unsignedInteger('architecture_plan_id')->unique()->nullable();
            $table->unsignedInteger('architecture_plan_assessment_id')->unique()->nullable();
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign keys.
            $table->referenceOn('organizational_unit_id', 'organizational_units')->onDelete('set null');
            $table->referenceOn('state_id', 'states')->onDelete('set null');
            $table->referenceOn('process_instance_id', 'process_instances')->onDelete('set null');
            $table->referenceOn('business_case_id', 'business_cases')->onDelete('set null');
            $table->referenceOn('business_case_assessment_id', 'business_case_assessments')->onDelete('set null');
            $table->referenceOn('architecture_plan_id', 'architecture_plans')->onDelete('set null');
            $table->referenceOn('architecture_plan_assessment_id', 'architecture_plan_assessments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
