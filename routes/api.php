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

////Route::middleware('auth:api')->post('getRoles', function (Request $request) {
////    return $request->user();
////});
////Route::group([ 'middleware' => ['api']], function() {
//    Route::POST('api_login', 'ApiController@login')->name('api_login');//
////});
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::POST('api_login', array('middleware' => 'api', 'uses' => 'ApiController@login'))->name('api_login');

Route::GET('/', function(){
  echo "sasafsafsafsa";
});//
//
