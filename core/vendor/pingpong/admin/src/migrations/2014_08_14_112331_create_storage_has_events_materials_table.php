<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageHasEventsMaterialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('storage_has_events_materials', function(Blueprint $table) {

            $table->unsignedInteger('storage_to_material_id');
            $table->foreign('storage_to_material_id')
                ->references('id')->on('storage_has_material')
                ->onDelete('cascade');

            $table->unsignedInteger('storage_events_materials_id');
            $table->foreign('storage_events_materials_id')
                ->references('id')->on('storage_events_materials')
                ->onDelete('cascade');

            $table->integer('total_value');
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
        Schema::drop('storage_has_events_materials');
    }

}
