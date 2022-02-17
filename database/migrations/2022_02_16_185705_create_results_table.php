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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('match_id')->unique();               // EA unique identifier
            $table->integer('home_team_id');
            $table->integer('away_team_id');
            $table->integer('home_team_goals');
            $table->integer('away_team_goals');
            $table->enum('outcome', ['homewin', 'awaywin', 'draw']);
            $table->timestamp('match_date');
            $table->enum('platform', ['ps4', 'ps5', 'xboxone', 'xboxseriessx', 'pc']);
            $table->json('properties')->nullable();
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
        Schema::dropIfExists('results');
    }
};
