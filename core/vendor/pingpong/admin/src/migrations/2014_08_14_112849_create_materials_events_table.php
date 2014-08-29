<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('materials_events', function(Blueprint $table) {

            $table->unsignedInteger('storage_has_material_id');
            $table->foreign('storage_has_material_id')
                ->references('id')->on('storage_has_material')
                ->onDelete('cascade');

            $table->string('event_name', 20);
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
        Schema::drop('materials_events');
    }

}
