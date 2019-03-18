<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->processFormData();

            $table->unsignedInteger('learning_product_code_id')->nullable();
            $table->unsignedInteger('sequence')->nullable();
            $table->unsignedInteger('version')->nullable();
            $table->text('description')->nullable();
            $table->text('learning_objectives')->nullable();
            $table->text('target_audience_description')->nullable();
            $table->boolean('is_required_training')->nullable();
            $table->unsignedInteger('total_duration')->nullable();
            $table->unsignedInteger('registration_mode_id')->nullable();
            $table->unsignedInteger('expected_annual_participant_number')->nullable();
            $table->unsignedInteger('content_source_type_id')->nullable();
            $table->unsignedInteger('curriculum_type_id')->nullable();
            $table->unsignedInteger('target_audience_knowledge_level_id')->nullable();
            $table->boolean('prescribed_by_tbs')->nullable();
            $table->date('expected_pilot_start_date')->nullable();
            $table->date('expected_launch_date')->nullable();
            $table->unsignedInteger('recommended_review_interval')->nullable();
            $table->string('internal_order')->nullable();
            $table->text('comments')->nullable();

            // Foreign keys.
            $table->referenceOn('learning_product_code_id')->onDelete('cascade');
            $table->referenceOn('registration_mode_id')->onDelete('cascade');
            $table->referenceOn('content_source_type_id')->onDelete('cascade');
            $table->referenceOn('curriculum_type_id')->onDelete('cascade');
            $table->referenceOn('target_audience_knowledge_level_id')->onDelete('cascade');
        });

        Schema::create('design_content_source_code', function (Blueprint $table) {
            $table->pivot('designs', 'learning_product_codes');
        });

        Schema::create('design_mandatory_prerequisite', function (Blueprint $table) {
            $table->pivot('designs', 'learning_product_codes');
        });

        Schema::create('design_recommended_prerequisite', function (Blueprint $table) {
            $table->pivot('designs', 'learning_product_codes');
        });

        Schema::create('design_complementary_learning_product', function (Blueprint $table) {
            $table->pivot('designs', 'learning_product_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designs');
    }
}
