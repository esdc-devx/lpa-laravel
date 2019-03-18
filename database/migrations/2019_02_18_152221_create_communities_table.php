<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('business_case_community', function (Blueprint $table) {
            $table->pivot('business_cases', 'communities');
        });

        Schema::create('design_community', function (Blueprint $table) {
            $table->pivot('designs', 'communities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_community');
        Schema::dropIfExists('design_community');
        Schema::dropIfExists('communities');
    }
}
