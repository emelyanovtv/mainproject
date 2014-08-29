<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('material_group', function(Blueprint $table) {
            $table->increments('id');
            $table->string("name",128);
            $table->unsignedInteger('parent_id');
            $table->foreign('parent_id')
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
