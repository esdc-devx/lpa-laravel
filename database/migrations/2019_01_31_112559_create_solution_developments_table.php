<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_developments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_instance_form_id');

            // Foreign keys.
            $table->referenceOn('process_instance_form_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solution_developments');
    }
}
