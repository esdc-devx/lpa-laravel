<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessInstanceStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_instance_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_step_id')->nullable();
            $table->unsignedInteger('process_instance_id');
            $table->unsignedInteger('state_id')->nullable();
            $table->timestamps();

            $table->referenceOn('process_step_id', 'process_steps');
            $table->referenceOn('process_instance_id', 'process_instances')->onDelete('cascade');
            $table->referenceOn('state_id', 'states')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_instance_steps');
    }
}
