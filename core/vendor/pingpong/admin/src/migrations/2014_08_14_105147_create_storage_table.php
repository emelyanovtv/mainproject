<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('storage', function(Blueprint $table) {
            $table->increments('id');
            $table->string("name",255);
            $table->unsignedInteger('parent_id');
            $table->foreign('parent_id')
                ->references('id')->on('storage')
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
        Schema::drop('storage');
    }

}
