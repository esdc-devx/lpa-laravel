<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearningProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->string('name')->unique();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('sub_type_id')->nullable();
            $table->unsignedInteger('organizational_unit_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('process_instance_id')->unique()->nullable();

            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreing keys.
            $table->referenceOn('organizational_unit_id', 'organizational_units')->onDelete('set null');
            $table->referenceOn('state_id', 'states')->onDelete('set null');
            $table->referenceOn('process_instance_id', 'process_instances')->onDelete('set null');
            $table->referenceOn('project_id', 'projects')->onDelete('cascade');
            $table->referenceOn('type_id', 'learning_product_types');
            $table->referenceOn('sub_type_id', 'learning_product_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_products');
    }
}
