<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformationSheetDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_sheet_definitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->string('name_key');
            $table->string('name_en');
            $table->string('name_fr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_sheet_definitions');
    }
}
