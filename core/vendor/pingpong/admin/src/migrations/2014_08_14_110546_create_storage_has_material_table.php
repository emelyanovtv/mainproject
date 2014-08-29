<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageHasMaterialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('storage_has_material', function(Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('storage_id');
            $table->foreign('storage_id')
                ->references('id')->on('storage')
                ->onDelete('cascade');

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')
                ->references('id')->on('material')
                ->onDelete('cascade');

            $table->integer('total');
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
        Schema::drop('storage_has_material');
    }

}
