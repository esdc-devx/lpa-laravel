<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_cases', function (Blueprint $table) {
            $table->processFormData();

            $table->string('request_origins_other')->nullable();
            $table->text('business_issue')->nullable();
            $table->text('short_term_learning_response')->nullable();
            $table->text('medium_term_learning_response')->nullable();
            $table->text('long_term_learning_response')->nullable();
            $table->boolean('is_required_training')->nullable();
            $table->unsignedInteger('expected_annual_participant_number')->nullable();
            $table->string('cost_centre')->nullable();
            $table->text('other_operational_considerations')->nullable();
            $table->text('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_cases');
    }
}
