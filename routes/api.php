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

//Route::middleware('auth:api')->post('getRoles', function (Request $request) {
//    return $request->user();
//});
//Route::group([ 'middleware' => ['api']], function() {
    Route::POST('api_login', 'ApiController@login')->name('api_login');//
//});
Route::GET('/', function(){
  echo "sasafsafsafsa";
});//
//
