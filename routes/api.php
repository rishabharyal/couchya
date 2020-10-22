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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
    	'success' => true,
    	'data' => $request->user()
    ]);
});

Route::namespace('App\Http\Controllers\Api')->group(function() {
	Route::post('login', 'UserController@login');
	Route::post('register', 'UserController@register');

	Route::get('movies', 'MovieController@get');
	Route::post('movie/like', 'GameController@likeMovie')->middleware('auth:sanctum');

	Route::get('team', 'TeamController@index')->middleware('auth:sanctum');
	Route::post('team/invite', 'TeamController@inviteFriend')->middleware('auth:sanctum');
	Route::get('team/join/{id}', 'TeamController@join')->middleware('auth:sanctum');
	Route::get('team/{id}', 'TeamController@show')->middleware('auth:sanctum');
	Route::post('matches/team', 'TeamController@getMatches')->middleware('auth:sanctum');
	Route::post('team', 'TeamController@store')->middleware('auth:sanctum');

	Route::get('/invitations', 'TeamController@getInvitations')->middleware('auth:sanctum');

});