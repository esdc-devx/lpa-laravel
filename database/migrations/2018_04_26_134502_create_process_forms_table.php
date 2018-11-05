<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_key');
            $table->string('name_en');
            $table->string('name_fr');
            $table->unsignedInteger('process_step_id');
            $table->integer('display_sequence');

            $table->referenceOn('process_step_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_forms');
    }
}
