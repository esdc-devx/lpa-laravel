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
            $table->string('name_key')->unique();
            $table->string('name_en');
            $table->string('name_fr');
            $table->boolean('owner');
            $table->string('email');
            $table->integer('director')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('director')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        Schema::create('organizational_unit_user', function (Blueprint $table) {
            $table->integer('organizational_unit_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('organizational_unit_id')
                ->references('id')
                ->on('organizational_units')
                ->onDelete('cascade');

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
        Schema::dropIfExists('organizational_unit_user');
        Schema::dropIfExists('organizational_units');
    }
}
