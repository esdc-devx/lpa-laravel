<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlannedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planned_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('sub_type_id')->nullable();
            $table->integer('quantity')->nullable();

            // Foreign keys.
            $table->referenceOn('type_id', 'learning_product_types');
            $table->referenceOn('sub_type_id', 'learning_product_types');
        });

        Schema::create('architecture_plan_planned_product', function (Blueprint $table) {
            $table->pivot('architecture_plans', 'planned_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planned_products');
    }
}
