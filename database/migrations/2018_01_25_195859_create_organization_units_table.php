<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_key')->unique();
            $table->boolean('owner');
            $table->string('email');
            $table->string('director_first_name');
            $table->string('director_last_name');
            $table->timestamps();
        });

        Schema::create('organization_unit_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->integer('organization_unit_id')->unsigned()->index();
            $table->string('name');
            $table->string('acronym');

            $table->foreign('organization_unit_id')
                ->references('id')
                ->on('organization_units')
                ->onDelete('cascade');

            $table->unique(['organization_unit_id', 'locale'], 'organization_unit_index_unique');
        });

        Schema::create('organization_unit_user', function (Blueprint $table) {
            $table->integer('organization_unit_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->foreign('organization_unit_id')
                ->references('id')
                ->on('organization_units');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['organization_unit_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_unit_translations');
        Schema::dropIfExists('organization_unit_user');
        Schema::dropIfExists('organization_units');
    }
}
