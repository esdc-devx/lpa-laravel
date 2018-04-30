<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_key')->unique();
        });

        Schema::create('project_state_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->integer('project_state_id')->unsigned()->index();
            $table->string('name');

            $table->foreign('project_state_id')
                ->references('id')
                ->on('project_states')
                ->onDelete('cascade');

            $table->unique(['project_state_id', 'locale'], 'project_state_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_states');
        Schema::dropIfExists('project_state_translations');
    }
}
