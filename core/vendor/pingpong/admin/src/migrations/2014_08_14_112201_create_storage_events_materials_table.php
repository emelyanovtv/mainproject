<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageEventsMaterialsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_events_materials', function(Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('storage_id');
            $table->foreign('storage_id')
                ->references('id')->on('storage')
                ->onDelete('cascade');

            $table->unsignedInteger('storage_events_id');
            $table->foreign('storage_events_id')
                ->references('id')->on('storage_events')
                ->onDelete('cascade');

            $table->unsignedInteger('material_id');
            $table->foreign('material_id')
                ->references('id')->on('material')
                ->onDelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('value');
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
        Schema::drop('storage_events_materials');
    }

}
