<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoyoutubesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ycats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('yid')->unique();
            $table->string('title');             
            $table->timestamps();
        });
        
       
        Schema::create('yplaylists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('yid')->unique();
            $table->string('title');
            $table->string('thumb_url');
            $table->integer('cat_id');
            $table->integer('item_count')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('status')->default(0);
             $table->timestamps();
        });
        
         Schema::create('yvideos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('yid')->unique();
            $table->string('title');
            $table->string('thumb_url');
            $table->integer('view_count')->default(0);
            $table->string('time')->nullable();
            $table->string('channel_id')->nullable();
            $table->integer('has_sub')->default(0);
            $table->string('note');
            $table->timestamps();
        });
        
        // Create table for associating roles to users (Many-to-Many)
        Schema::create('yvideo_playlist', function (Blueprint $table) {
            $table->integer('video_id')->unsigned();
            $table->integer('playlist_id')->unsigned();

            $table->foreign('video_id')->references('id')->on('yvideos')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('yplaylists')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['video_id', 'playlist_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ycats');
        Schema::drop('yplaylists');
        Schema::drop('yvideos');
        Schema::drop('yvideo_playlist');
    }
}
