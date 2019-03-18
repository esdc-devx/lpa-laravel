<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestSpeakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_speakers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operational_details_id');
            $table->text('required_profile_en')->nullable();
            $table->text('required_profile_fr')->nullable();
            $table->text('schedule_en')->nullable();
            $table->text('schedule_fr')->nullable();

             // Foreign keys.
             $table->referenceOn('operational_details_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_speakers');
    }
}
