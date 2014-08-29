<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('storage_events', function(Blueprint $table) {
            $table->increments('id');
            $table->string("name",255);
            $table->text('description');
            $table->char('char', 10);
            $table->enum('is_arifmetic', array('0', '1'))->default('0');
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
        Schema::drop('storage_events');
    }

}
