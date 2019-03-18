<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operational_details_id');
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('material_item_id')->nullable();
            $table->string('material_item_other_en')->nullable();
            $table->string('material_item_other_fr')->nullable();
            $table->unsignedInteger('material_denominator_id')->nullable();
            $table->unsignedInteger('material_location_id')->nullable();
            $table->boolean('reusable')->nullable();
            $table->text('notes_en')->nullable();
            $table->text('notes_fr')->nullable();

            // Foreign keys.
            $table->referenceOn('operational_details_id')->onDelete('cascade');
            $table->referenceOn('material_item_id')->onDelete('cascade');
            $table->referenceOn('material_denominator_id')->onDelete('cascade');
            $table->referenceOn('material_location_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
