<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialGroupHasPropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('material_group_has_properties', function(Blueprint $table) {

            $table->unsignedInteger('material_group_id');
            $table->foreign('material_group_id')
                ->references('id')->on('material_groups')
                ->onDelete('cascade');

            $table->unsignedInteger('properties_id');
            $table->foreign('properties_id')
                ->references('id')->on('properties')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('material_group_has_properties');
    }

}
