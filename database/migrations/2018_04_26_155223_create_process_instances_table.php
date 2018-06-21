<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('process_definition_id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->string('engine_process_instance_id')->unique()->index();
            $table->string('engine_auth_token');
            $table->unsignedInteger('entity_previous_state_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->auditable();
            $table->timestamps();

            $table->referenceOn('process_definition_id', 'process_definitions');
            $table->referenceOn('state_id', 'states')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_instances');
    }
}
