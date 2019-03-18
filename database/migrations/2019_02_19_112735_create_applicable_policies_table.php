<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicablePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicable_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('design_id');
            $table->string('name')->nullable();
            // Foreign keys.
            $table->referenceOn('design_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicable_policies');
    }
}
