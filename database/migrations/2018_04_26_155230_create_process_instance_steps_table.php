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
            $table->integer('process_step_id')->unsigned()->nullable();
            $table->integer('process_instance_id')->unsigned();
            $table->integer('state_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('process_step_id')
                ->references('id')
                ->on('process_steps')
                ->onDelete('set null'); //@note: Cascade delete when deleting definition?

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
