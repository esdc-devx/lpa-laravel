<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_case_id');
            $table->unsignedInteger('risk_type_id')->nullable();
            $table->string('risk_type_other')->nullable();
            $table->text('rationale')->nullable();

            // Foreign keys.
            $table->referenceOn('business_case_id')->onDelete('cascade');
            $table->referenceOn('risk_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risks');
    }
}
