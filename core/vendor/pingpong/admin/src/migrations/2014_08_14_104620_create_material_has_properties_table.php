<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialHasPropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('material_has_properties', function(Blueprint $table) {

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')
                ->references('id')->on('material')
                ->onDelete('cascade');

            $table->unsignedInteger('properties_id');
            $table->foreign('properties_id')
                ->references('id')->on('properties')
                ->onDelete('cascade');

            $table->enum('is_cnt', array('0', '1'))->default('0');
            $table->string('value', 255);
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('material_has_properties');
	}

}
