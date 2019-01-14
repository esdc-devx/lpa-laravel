<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationalUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->listable();
            $table->boolean('owner');
            $table->string('email');
            $table->unsignedInteger('director')->nullable();
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
            $table->timestamps();

            $table->referenceOn('director', 'users')->onDelete('set null');
        });

        Schema::create('organizational_unit_user', function (Blueprint $table) {
            $table->pivot('organizational_units', 'users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizational_unit_user');
        Schema::dropIfExists('organizational_units');
    }
}
