<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningProductCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_product_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('comments')->nullable();
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_content_source_code');
        Schema::dropIfExists('design_mandatory_prerequisite');
        Schema::dropIfExists('design_recommended_prerequisite');
        Schema::dropIfExists('design_complementary_learning_product');
        Schema::dropIfExists('learning_product_codes');
    }
}
