<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_developments', function (Blueprint $table) {
            $table->processFormData();

            $table->unsignedInteger('design_provider_id')->nullable();
            $table->string('design_provider_other')->nullable();
            $table->unsignedInteger('implementation_provider_id')->nullable();
            $table->string('implementation_provider_other')->nullable();
            $table->unsignedInteger('effort_required')->nullable();
            $table->boolean('language_content_qa_completed')->nullable();
            $table->boolean('instructional_designer_qa_completed')->nullable();
            $table->boolean('functional_qa_completed')->nullable();
            $table->boolean('accessibility_qa_completed')->nullable();
            $table->boolean('client_acceptance_test_completed')->nullable();
            $table->text('comments')->nullable();

            // Foreign keys.
            $table->referenceOn('design_provider_id', 'content_providers')->onDelete('cascade');
            $table->referenceOn('implementation_provider_id', 'content_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solution_developments');
    }
}
