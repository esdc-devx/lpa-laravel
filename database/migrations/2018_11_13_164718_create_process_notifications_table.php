<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_definition_id');
            $table->string('name_key');
            $table->string('name_en');
            $table->string('name_fr');
            $table->string('subject_en');
            $table->string('subject_fr');
            $table->text('body_en');
            $table->text('body_fr');
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign Keys.
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
        Schema::dropIfExists('process_notifications');
    }
}
