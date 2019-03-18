<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementAccountabilityFrameworkAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_accountability_framework_areas', function (Blueprint $table) {
            $table->listable();
        });

        Schema::create('design_management_accountability_framework_area', function (Blueprint $table) {
            $table->pivot('designs', 'management_accountability_framework_areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_management_accountability_framework_area');
        Schema::dropIfExists('management_accountability_framework_areas');
    }
}
