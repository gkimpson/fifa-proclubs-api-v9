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
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedTinyInteger('home_team_player_count')->default(0)->after('away_team_goals');
            $table->unsignedTinyInteger('away_team_player_count')->default(0)->after('home_team_player_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('home_team_player_count');
            $table->dropColumn('away_team_player_count');
        });
    }
};
