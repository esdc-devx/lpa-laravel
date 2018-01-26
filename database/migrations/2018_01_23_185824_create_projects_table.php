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
            $table->integer('owner_id')->unsigned();
            $table->integer('organization_unit_id')->unsigned();
            //$table->string('process_instance_id')->unique();
            $table->timestamps();

            // @todo: Add indexes.

            /*$table->foreign('owner_id')
                ->references('id')->on('users');*/

            /*$table->foreign('organization_unit_id')
                ->references('id')->on('organization_units');*/
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
