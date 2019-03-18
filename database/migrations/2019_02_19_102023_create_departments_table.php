<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('design_prescribed_by_department', function (Blueprint $table) {
            $table->pivot('designs', 'departments');
        });

        Schema::create('design_recommended_by_department', function (Blueprint $table) {
            $table->pivot('designs', 'departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
