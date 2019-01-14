<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessFormAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_form_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('assessed_process_form');
            $table->unsignedInteger('process_form_id');

            // Foreign keys.
            $table->referenceOn('process_form_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_form_assessments');
    }
}
