<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id, publishedAt, title, description, 
        Schema::create('youtube_playlist_items', function (Blueprint $table) {
            $table->id();
            $table->string('video_id')->nullable();
            $table->string('playlist_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['video_id', 'playlist_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_playlist_items');
    }
};
