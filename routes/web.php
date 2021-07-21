<?php
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


Route::get('/upload', function () {
    return view('upload', [
        'id' => request()->query('id'),
        'path' => request()->query('path')
    ]);
});

Route::post('/upload', function () {
    $server = request()->server_id;
    request()->file->storeAs('uploads', $server . '-' . request()->file->getClientOriginalName());
    return view('upload', [
        'id' => request()->query('id'),
        'path' => request()->query('path')
    ]);
})->name('upload');

// No Auth Routes
Route::get('/', function () {
    return view('index');
});

Route::get('/gear/{player}/{server}/{region}', 'RaidController@gear')->name('gear');


Route::get('/darkmode', 'UserController@darkmode')->name('darkmode');
Route::get('/logout', 'UserController@logout')->name('logout');
Route::get('/dashboard/admin', 'DashboardController@admin')->name('dashboard.admin');
Route::get('/characters/search/{name}', 'CharacterController@search')->name('character.search');

// Player Routes
Route::group(['middleware' => ['oauth']], function() {
    // Dashboard Routes
    Route::group(['middleware' => 'admin'], function() {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('/dashboard/{id}', 'DashboardController@dashboard')->name('dashboard');
        Route::get('/dashboard/logs/{id}', 'DashboardController@logs')->name('logs');
        Route::get('/dashboard/settings/{id}', 'DashboardController@settings')->name('settings');
        Route::post('/dashboard/settings/{id}', 'DashboardController@postSettings')->name('settings.post');
        Route::get('/dashboard/setup/{id}', 'DashboardController@setup')->name('setup');
        Route::get('/dashboard/setup/save/{id}', 'DashboardController@setupSave')->name('setup.save');
        Route::get('/dashboard/install/{id}', 'DashboardController@install')->name('install');
    });

    Route::get('/OAuth', 'GoodBotController@OAuth')->name('OAuth');
    // Characters 
    Route::get('/characters', 'CharacterController@index')->name('character.servers');
    Route::get('/characters/{serverID}', 'CharacterController@server')->name('character.list');
    Route::any('/characters/save/{serverID}/{characterID}', 'CharacterController@save')->name('character.save');

    // Signups
    Route::get('/s/{id}', 'GoodBotController@signups')->name('s');
    Route::get('/signups/{id}', 'GoodBotController@signups')->name('signups');
    
    // Raids
    Route::get('/raids', 'RaidController@index')->name('raids');
    Route::get('/raids/reserves/{id}', 'RaidController@reserves')->name('raids.reserves');
    Route::get('/raids/lineup/{id}', 'RaidController@lineup')->name('raids.lineup');
    Route::get('/raids/manage/{id}', 'RaidController@manage')->name('raids.manage');
    Route::get('/raids/{id}/character/{mainID}', 'RaidController@character')->name('raids.character');
    Route::post('/raids/save/{id?}', 'RaidController@postSave')->name('raids.save.post');
    Route::get('/raids/{id}/confirm/{signupID}', 'RaidController@confirm')->name('raids.confirm');
    Route::get('/raids/{id}/unconfirm/{signupID}', 'RaidController@unconfirm')->name('raids.unconfirm');
    Route::get('/raids/{id}/command/{type}', 'RaidController@command')->name('raids.command');
    Route::get('/raids/new/{id?}', 'RaidController@new')->name('raids.new');

    // Reserves
    Route::get('/r/{id}', 'GoodBotController@reserves')->name('r');
    Route::get('/reserves/{id}', 'GoodBotController@reserves')->name('reserves');
    Route::get('/reserve/{signupID}/{itemID}', 'GoodBotController@reserve');
    Route::get('{raid}', 'GoodBotController@index');

});

