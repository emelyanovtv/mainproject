<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageEventsHasEventsPropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('storage_events_has_events_properties', function(Blueprint $table) {

            $table->unsignedInteger('storage_events_id');
            $table->foreign('storage_events_id')
                ->references('id')->on('storage_events')
                ->onDelete('cascade');

            $table->unsignedInteger('event_prop_id');
            $table->foreign('event_prop_id')
                ->references('id')->on('events_properties')
                ->onDelete('cascade');

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
        Schema::drop('storage_events_has_events_properties');
	}

}
