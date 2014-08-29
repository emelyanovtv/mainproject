<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsPropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('events_properties', function(Blueprint $table) {
            $table->increments('id');
            $table->string("name",128);
            $table->enum('is_required', array('0', '1'))->default('0');
            $table->unsignedInteger('measures_id');
            $table->foreign('measures_id')
                        ->references('id')->on('measures')
                            ->onDelete('cascade')
                                ->onUpdate('cascade');
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
        Schema::drop('events_properties');
	}

}
