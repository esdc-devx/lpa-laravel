<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationsMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communications_materials', function (Blueprint $table) {
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
        Schema::dropIfExists('communications_materials');
    }
}
