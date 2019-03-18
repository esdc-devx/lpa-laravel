<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operational_details_id');
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('room_layout_id')->nullable();
            $table->text('room_layout_other_en')->nullable();
            $table->text('room_layout_other_fr')->nullable();
            $table->unsignedInteger('room_type_id')->nullable();
            $table->unsignedInteger('room_usage_id')->nullable();
            $table->text('special_requirement_description_en')->nullable();
            $table->text('special_requirement_description_fr')->nullable();

            // Foreign keys.
            $table->referenceOn('operational_details_id')->onDelete('cascade');
            $table->referenceOn('room_layout_id');
            $table->referenceOn('room_type_id');
            $table->referenceOn('room_usage_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
