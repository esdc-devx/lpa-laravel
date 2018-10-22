<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpendingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spendings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_case_id');
            $table->unsignedInteger('internal_resource_id')->nullable();
            $table->text('cost_description')->nullable();
            $table->unsignedInteger('cost')->nullable();
            $table->unsignedInteger('recurrence_id')->nullable();
            $table->text('comments')->nullable();

            // Foreign keys.
            $table->referenceOn('business_case_id')->onDelete('cascade');
            $table->referenceOn('internal_resource_id');
            $table->referenceOn('recurrence_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spendings');
    }
}
