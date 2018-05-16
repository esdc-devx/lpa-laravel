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
            $table->unsignedInteger('current_editor')->nullable();
            $table->auditable();
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
