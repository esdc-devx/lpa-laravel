<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessInstanceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_instance_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_form_id')->nullable();
            $table->unsignedInteger('process_instance_step_id');
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('organizational_unit_id')->nullable();
            $table->unsignedInteger('current_editor')->nullable();
            $table->string('engine_task_id')->unique()->index()->nullable();
            $table->auditable();
            $table->timestamps();

            $table->referenceOn('process_form_id', 'process_forms');
            $table->referenceOn('process_instance_step_id', 'process_instance_steps')->onDelete('cascade');
            $table->referenceOn('state_id', 'states');
            $table->referenceOn('organizational_unit_id', 'organizational_units');
            $table->referenceOn('current_editor', 'users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_instance_forms');
    }
}
