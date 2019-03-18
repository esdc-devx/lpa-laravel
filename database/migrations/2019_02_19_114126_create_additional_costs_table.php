<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('design_id');
            $table->text('rationale')->nullable();
            $table->unsignedInteger('costs')->nullable();
            // Add foreing keys.
            $table->referenceOn('design_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_costs');
    }
}
