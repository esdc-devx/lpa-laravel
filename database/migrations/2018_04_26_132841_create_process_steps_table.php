<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_key');
            $table->string('name_en');
            $table->string('name_fr');
            $table->integer('process_definition_id')->unsigned();
            $table->integer('display_sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_steps');
    }
}
