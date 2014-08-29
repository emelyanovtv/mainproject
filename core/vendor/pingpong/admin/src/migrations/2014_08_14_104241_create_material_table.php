<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('material', function(Blueprint $table) {
            $table->increments('id');
            $table->string("name",255);
            $table->unsignedInteger('material_group_id');
            $table->foreign('material_group_id')
                ->references('id')->on('material_group')
                ->onDelete('cascade');
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
        Schema::drop('material_group');
	}

}
