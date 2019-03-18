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
            $table->processFormData();

            $table->string('title_en')->nullable();
            $table->string('title_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('keywords_en')->nullable();
            $table->text('keywords_fr')->nullable();
            $table->text('note_en')->nullable();
            $table->text('note_fr')->nullable();
            $table->text('disclaimer_en')->nullable();
            $table->text('disclaimer_fr')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('summary_fr')->nullable();
            $table->text('comments')->nullable();
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
