<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_details', function (Blueprint $table) {
            $table->processFormData();

            $table->unsignedInteger('number_of_virtual_producers_per_offering')->nullable();
            $table->unsignedInteger('minimum_number_of_participant_per_offering')->nullable();
            $table->unsignedInteger('maximum_number_of_participant_per_offering')->nullable();
            $table->unsignedInteger('optimal_number_of_participant_per_offering')->nullable();
            $table->unsignedInteger('waiting_list_maximum_size')->nullable();
            $table->string('session_template')->nullable();
            $table->boolean('external_delivery')->nullable();
            $table->boolean('should_be_published')->nullable();
            $table->text('note_content')->nullable();
            $table->text('disclaimer_content')->nullable();
            $table->text('summary_content')->nullable();
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
        Schema::dropIfExists('operational_details');
    }
}
