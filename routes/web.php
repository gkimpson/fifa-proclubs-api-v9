<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'App\Http\Controllers\MyDashboardController@index')->middleware(['auth'])->name('dashboard'); 
    Route::get('/debug', 'App\Http\Controllers\MyDashboardController@debug')->middleware(['auth'])->name('debug'); 

    // Route::get('/club', 'App\Http\Controllers\MyDashboardController@club')->middleware(['auth'])->name('club');
    // Route::get('/squad', 'App\Http\Controllers\MyDashboardController@squad')->middleware(['auth'])->name('squad');
    // Route::get('/league', 'App\Http\Controllers\MyDashboardController@league')->middleware(['auth'])->name('league');
    // Route::get('/cup', 'App\Http\Controllers\MyDashboardController@cup')->middleware(['auth'])->name('cup');
    // Route::get('/league/form', 'App\Http\Controllers\MyDashboardController@form')->middleware(['auth'])->name('leagueform');
    // Route::get('/league/rank', 'App\Http\Controllers\MyDashboardController@rank')->middleware(['auth'])->name('leaguerank');
    // Route::get('/cup/form', 'App\Http\Controllers\MyDashboardController@form')->middleware(['auth'])->name('cupform');
    // Route::get('/cup/rank', 'App\Http\Controllers\MyDashboardController@rank')->middleware(['auth'])->name('cuprank');
    // Route::get('/media', 'App\Http\Controllers\MyDashboardController@media')->middleware(['auth'])->name('media');  
});

/* My routes */
Route::get('/clubsinfo', 'App\Http\Controllers\StatsController@clubsInfo');
Route::get('/careerstats', 'App\Http\Controllers\StatsController@careerStats');
Route::get('/memberstats', 'App\Http\Controllers\StatsController@memberStats');
Route::get('/seasonstats', 'App\Http\Controllers\StatsController@seasonStats');
Route::get('/matchstats', 'App\Http\Controllers\StatsController@matchStats');
Route::get('/search', 'App\Http\Controllers\StatsController@search');
Route::get('/settings', 'App\Http\Controllers\StatsController@settings');

Route::get('/leaderboard/club', 'App\Http\Controllers\StatsController@leaderboard');
// Route::get('/seasonleaderboard', 'App\Http\Controllers\StatsController@seasonalLeaderboard');
// Route::get('/clubleaderboard', 'App\Http\Controllers\StatsController@clubLeaderboard');
Route::get('/command', 'App\Http\Controllers\StatsController@runCommand');


require __DIR__.'/auth.php';
