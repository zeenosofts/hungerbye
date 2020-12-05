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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
Route::group([ 'middleware' => ['api']], function() {
    Route::POST('api_login', 'ApiController@login')->name('api_login');//
    Route::POST('api_register', 'ApiController@registerDonor')->name('api_register');//
    Route::POST('api_find_token', 'ApiController@findToken')->name('api_find_token');//
});
Route::GET('/', function(){
  echo "sasafsafsafsa";
});//

//
Route::POST('register', 'ApiController@register')->name('register');//
Route::POST('send_verification_code', 'ApiController@send_verification_code')->name('send_verification_code');
Route::POST('update_complete_profile', 'ApiController@update_complete_profile')->name('update_complete_profile');
Route::POST('saveItem', 'ApiController@saveItem')->name('saveItem');
Route::POST('saveOffer', 'ApiController@saveOffer')->name('saveOffer');
Route::POST('getItems', 'ApiController@getItems')->name('getItems'); //
Route::POST('getOffers', 'ApiController@getOffers')->name('getOffers');
Route::POST('postARequest', 'ApiController@postARequest')->name('postARequest');
Route::POST('PostedRequests', 'ApiController@PostedRequests')->name('PostedRequests');
Route::POST('deleteItem', 'ApiController@deleteItem')->name('deleteItem');
Route::POST('deleteOffer', 'ApiController@deleteOffer')->name('deleteOffer');
