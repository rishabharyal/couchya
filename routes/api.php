<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('App\Http\Controllers\Api')->group(function() {
	Route::post('login', 'UserController@login');
	Route::post('register', 'UserController@register');

	Route::get('movies', 'MovieController@get');
	Route::post('movie/like', 'GameController@likeMovie');

	Route::get('team', 'TeamController@index');
	Route::post('team', 'TeamController@store');

	Route::post('game/join', 'GameController@joinGame');
	Route::post('game/invite', 'GameController@inviteFriend');
});