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
            $table->integer('organization_unit_id')->unsigned();
            $table->string('name');
            $table->string('acronym');

            /*
            $table->unique(['organization_unit_id', 'locale']);
            $table->foreign('organization_unit_id')
                ->references('id')
                ->on('organization_units')
                ->onDelete('cascade');
            */
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
        Schema::dropIfExists('organization_units');
    }
}
