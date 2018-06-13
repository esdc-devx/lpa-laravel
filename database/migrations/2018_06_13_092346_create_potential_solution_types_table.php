<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotentialSolutionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_solution_types', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_potential_solution_type', function (Blueprint $table) {
            $table->pivot('business_cases', 'potential_solution_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_potential_solution_type');
        Schema::dropIfExists('potential_solution_types');
    }
}
