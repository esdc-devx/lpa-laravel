<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePossibleOfferingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('possible_offering_types', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('design_possible_offering_type', function (Blueprint $table) {
            $table->pivot('designs', 'possible_offering_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_possible_offering_type');
        Schema::dropIfExists('possible_offering_types');
    }
}
