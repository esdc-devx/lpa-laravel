<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessDefinitionEntityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_definition_entity_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_definition_id');
            $table->string('entity_type');

            // Add foreign keys.
            $table->referenceOn('process_definition_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_definition_entity_types');
    }
}
