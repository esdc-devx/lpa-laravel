<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformationSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('information_sheet_definition_id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->softDeletes();

            // Foreign Keys.
            $table->referenceOn('information_sheet_definition_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_sheets');
    }
}
