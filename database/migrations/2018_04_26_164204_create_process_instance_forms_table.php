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
            $table->integer('process_form_id')->unsigned()->nullable();
            $table->integer('process_instance_step_id')->unsigned();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('current_editor')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('process_form_id')
                ->references('id')
                ->on('process_forms')
                ->onDelete('set null'); //@note: Cascade delete when deleting definition?

            $table->foreign('process_instance_step_id')
                ->references('id')
                ->on('process_instance_steps')
                ->onDelete('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('set null');

            $table->foreign('current_editor')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('process_instance_forms');
    }
}
