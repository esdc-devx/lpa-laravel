<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGovernmentPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('government_priorities', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_government_priority', function (Blueprint $table) {
            $table->pivot('business_cases', 'government_priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_government_priority');
        Schema::dropIfExists('government_priorities');
    }
}
