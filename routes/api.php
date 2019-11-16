<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/login', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('login', 'AdminController@login');
    Route::post('splash', 'AdminController@splash');
});

Route::group(['middleware' => ['auth']], function () {
});

// Route::group(['namespace' => 'API'], function () {
//     Route::post('login', 'AdminController@login');
// });
