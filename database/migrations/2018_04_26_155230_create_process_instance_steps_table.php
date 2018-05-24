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

            $table->foreign('process_step_id')
                ->references('id')
                ->on('process_steps');

            $table->foreign('process_instance_id')
                ->references('id')
                ->on('process_instances')
                ->onDelete('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('set null');
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
