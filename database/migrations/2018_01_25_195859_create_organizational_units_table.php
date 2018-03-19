<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_key')->unique();
            $table->boolean('owner');
            $table->string('email');
            $table->string('director_first_name');
            $table->string('director_last_name');
            $table->timestamps();
        });

        Schema::create('organizational_unit_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->integer('organizational_unit_id')->unsigned()->index();
            $table->string('name');
            $table->string('acronym');

            $table->foreign('organizational_unit_id')
                ->references('id')
                ->on('organizational_units')
                ->onDelete('cascade');

            $table->unique(['organizational_unit_id', 'locale'], 'organizational_unit_index_unique');
        });

        Schema::create('organizational_unit_user', function (Blueprint $table) {
            $table->integer('organizational_unit_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->foreign('organizational_unit_id')
                ->references('id')
                ->on('organizational_units');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['organizational_unit_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizational_unit_translations');
        Schema::dropIfExists('organizational_unit_user');
        Schema::dropIfExists('organizational_units');
    }
}
