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
            $table->unsignedInteger('primary_contact')->nullable();
            $table->unsignedInteger('manager')->nullable();

            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            // Foreing keys.
            $table->referenceOn('organizational_unit_id');
            $table->referenceOn('state_id');
            $table->referenceOn('process_instance_id');
            $table->referenceOn('project_id')->onDelete('cascade');
            $table->referenceOn('type_id', 'learning_product_types');
            $table->referenceOn('sub_type_id', 'learning_product_types');
            $table->referenceOn('primary_contact', 'users');
            $table->referenceOn('manager', 'users');
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
