<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayableListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playable_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('song_id')->unsigned();
            $table->bigInteger('artist_id')->unsigned();
            $table->integer('status');
            $table->timestamps();
            //外部キー制約
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playable_lists');
    }
}
