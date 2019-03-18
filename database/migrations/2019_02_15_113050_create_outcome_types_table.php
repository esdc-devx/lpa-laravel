<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutcomeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcome_types', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('design_outcome_type', function (Blueprint $table) {
            $table->pivot('designs', 'outcome_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_outcome_type');
        Schema::dropIfExists('outcome_types');
    }
}
