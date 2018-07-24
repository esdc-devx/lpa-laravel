<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnersBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learners_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('learners_benefit_type_id')->nullable();
            $table->string('learners_benefit_type_other')->nullable();
            $table->text('rationale')->nullable();

            $table->referenceOn('learners_benefit_type_id', 'learners_benefit_types');
        });

        Schema::create('business_case_learners_benefit', function (Blueprint $table) {
            $table->pivot('business_cases', 'learners_benefits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_case_learners_benefit');
        Schema::dropIfExists('learners_benefits');
    }
}
