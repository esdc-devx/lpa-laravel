<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferingRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offering_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('offering_and_enrolment_estimates_id');
            $table->unsignedInteger('region_id')->nullable();
            $table->unsignedInteger('regional_annual_bilingual_offering_number')->nullable();
            $table->unsignedInteger('regional_annual_english_offering_number')->nullable();
            $table->unsignedInteger('regional_annual_french_offering_number')->nullable();
            $table->unsignedInteger('regional_annual_simultaneous_interpretation_offering_number')->nullable();

            // Foreign keys.
            $table->referenceOn('offering_and_enrolment_estimates_id')->onDelete('cascade');
            $table->referenceOn('region_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offering_regions');
    }
}
