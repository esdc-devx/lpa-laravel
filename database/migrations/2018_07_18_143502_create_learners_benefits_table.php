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
            $table->unsignedInteger('business_case_id');
            $table->unsignedInteger('learners_benefit_type_id')->nullable();
            $table->string('learners_benefit_type_other')->nullable();
            $table->text('rationale')->nullable();

            $table->referenceOn('business_case_id', 'business_cases')->onDelete('cascade');
            $table->referenceOn('learners_benefit_type_id', 'learners_benefit_types');
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
