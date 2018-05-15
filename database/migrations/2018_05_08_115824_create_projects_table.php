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
            $table->integer('organizational_unit_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->integer('process_instance_id')->unsigned()->unique()->nullable();
            $table->integer('business_case_id')->unsigned()->unique()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('organizational_unit_id')
                ->references('id')
                ->on('organizational_units')
                ->onDelete('set null');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('set null');

            $table->foreign('process_instance_id')
                ->references('id')
                ->on('process_instances')
                ->onDelete('set null');

            $table->foreign('business_case_id')
                ->references('id')
                ->on('business_cases')
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
        Schema::dropIfExists('projects');
    }
}
