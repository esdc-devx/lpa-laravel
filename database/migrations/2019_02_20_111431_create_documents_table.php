<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operational_details_id');
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('document_category_id')->nullable();
            $table->string('document_category_other_en')->nullable();
            $table->string('document_category_other_fr')->nullable();
            $table->unsignedInteger('document_denominator_id')->nullable();
            $table->string('version')->nullable(); // @note: Review with Patrick why string ?
            $table->string('title_en')->nullable();
            $table->string('title_fr')->nullable();
            $table->text('link_en')->nullable();
            $table->text('link_fr')->nullable();
            $table->text('printing_specifications_en')->nullable();
            $table->text('printing_specifications_fr')->nullable();
            $table->boolean('reusable')->nullable();
            $table->text('notes_en')->nullable();
            $table->text('notes_fr')->nullable();

            // Foreign keys.
            $table->referenceOn('operational_details_id')->onDelete('cascade');
            $table->referenceOn('document_category_id')->onDelete('cascade');
            $table->referenceOn('document_denominator_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
