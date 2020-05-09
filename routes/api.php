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
    Route::get('splash', 'AdminController@splash');
});

Route::group(['middleware' => ['auth']], function () {
    Route::apiResource('payment', 'PaymentController');
    Route::apiResource('student', 'StudentController');
    Route::apiResource('admin', 'AdminController');
    // Route::post('admin/create', 'AdminController@create');
    // Route::put('admin/{admin}', 'AdminController@update');
    // Route::delete('admin/{admin}', 'AdminController@destroy');
});
