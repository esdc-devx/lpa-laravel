<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_priorities', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_school_priority', function (Blueprint $table) {
            $table->pivot('business_cases', 'school_priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_school_priority');
        Schema::dropIfExists('school_priorities');
    }
}
