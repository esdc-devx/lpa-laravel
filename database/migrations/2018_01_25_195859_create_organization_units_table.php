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
            // @todo: Add localization.
            $table->increments('id');
            $table->string('name');
            $table->timestamps();

            /*
            name_french
            name_english
            acronym_french
            acronym_english
            director_first_name
            director_last_name
            email_address
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
        Schema::dropIfExists('organization_units');
    }
}
