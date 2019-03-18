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
            $table->string('name');
            $table->text('outline');
            $table->unsignedInteger('organizational_unit_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('process_instance_id')->unique()->nullable();
            // Form data entities.
            $table->unsignedInteger('business_case_id')->unique()->nullable();
            $table->unsignedInteger('planned_product_list_id')->unique()->nullable();
            $table->unsignedInteger('gate_one_approval_id')->unique()->nullable();
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign keys.
            $table->referenceOn('organizational_unit_id');
            $table->referenceOn('state_id');
            $table->referenceOn('process_instance_id');
            $table->referenceOn('business_case_id');
            $table->referenceOn('planned_product_list_id');
            $table->referenceOn('gate_one_approval_id');
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
