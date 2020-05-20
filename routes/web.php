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



Route::get('auth/google', 'Auth\LoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\LoginController@handleGoogleCallback');
Route::post('loginCheck', 'UserController@loginCheck')->name('loginCheck');//
Route::POST('saveToken', 'UserController@saveToken')->name('saveToken');//
Route::GET('forgot', 'UserController@forgot')->name('forgot');//
Route::POST('send_verification_code', 'UserController@send_verification_code')->name('send_verification_code');//
Route::GET('password/reset/{code}', 'UserController@password_reset')->name('password/reset');//
Route::POST('update_password_code', 'UserController@update_password_code')->name('update_password_code');
Auth::routes();
Route::group([ 'middleware' => ['auth']], function() {
    Route::get('stripe/connect', 'UserController@stripeConnect');//
    Route::POST('getRole', 'UserController@getRole')->name('getRole');
    Route::get('/choose_role', function () {
        return view('auth.choose_role');
    })->name('choose_role');//
    Route::get('/', 'HomeController@index')->name('home');//
    Route::get('home', 'HomeController@index')->name('home');//
    Route::GET('get_logined_user_information', 'UserController@get_logined_user_information')->name('get_logined_user_information');
    Route::POST('getRoles', 'UserController@getRoles')->name('getRoles');//
    Route::POST('getRolesForEdit', 'UserController@getRolesForEdit')->name('getRolesForEdit');
    Route::POST('saveUser', 'UserController@saveUser')->name('saveUser');
    Route::POST('logoutNow', 'UserController@logout')->name('logoutNow');//
    Route::POST('getAllUsers', 'UserController@getAllUsers')->name('getAllUsers');
    Route::POST('change_status', 'UserController@change_status')->name('change_status');//
    Route::POST('getUsersWithThisID', 'UserController@getUsersWithThisID')->name('getUsersWithThisID');//
    Route::POST('updateUser', 'UserController@updateUser')->name('updateUser');//
    Route::POST('save_bank_details', 'UserController@save_bank_details')->name('save_bank_details');//
    Route::POST('user_save_bank_details', 'UserController@user_save_bank_details')->name('user_save_bank_details');//
    Route::POST('getUserBankDetails', 'UserController@getUserBankDetails')->name('getUserBankDetails');//
    Route::POST('getAdminBankDetails', 'UserController@getAdminBankDetails')->name('getAdminBankDetails');//
    Route::POST('getAllPartners', 'UserController@getAllPartners')->name('getAllPartners');
    Route::POST('getAllPartnersWithStatus', 'UserController@getAllPartnersWithStatus')->name('getAllPartnersWithStatus');
    Route::POST('getAllDonors', 'UserController@getAllDonors')->name('getAllDonors');//
    Route::GET('complete_profile', 'UserController@complete_profile')->name('complete_profile');
    Route::POST('update_complete_profile', 'UserController@update_complete_profile')->name('update_complete_profile');
    Route::GET('pending_approval', 'UserController@pending_approval')->name('pending_approval');//
    Route::POST('saveItem', 'UserController@saveItem')->name('saveItem');//
    Route::POST('saveOffer', 'UserController@saveOffer')->name('saveOffer');
    Route::POST('getAllItems', 'UserController@getAllItems')->name('getAllItems');//
    Route::POST('getAllOffers', 'UserController@getAllOffers')->name('getAllOffers');//
    Route::POST('getAllOffersForThisID', 'UserController@getAllOffersForThisID')->name('getAllOffersForThisID');
    Route::POST('getAllOffersWithNames', 'UserController@getAllOffersWithNames')->name('getAllOffersWithNames');
    Route::POST('deleteItem', 'UserController@deleteItem')->name('deleteItem');//
    Route::POST('deleteOffer', 'UserController@deleteOffer')->name('deleteOffer');
    Route::POST('getItemForthis', 'UserController@getItemForthis')->name('getItemForthis');//
    Route::POST('getOfferForthis', 'UserController@getOfferForthis')->name('getOfferForthis');//
    Route::POST('updateItem', 'UserController@updateItem')->name('updateItem');//
    Route::POST('updateOffer', 'UserController@updateOffer')->name('updateOffer');
    Route::POST('postArequest', 'UserController@postArequest')->name('postArequest');//
    Route::GET('get_items_for_this_donor', 'UserController@get_items_for_this_donor')->name('get_items_for_this_donor');
    Route::GET('get_requests_to_show', 'UserController@get_requests_to_show')->name('get_requests_to_show');//
    Route::GET('get_requests_to_show_opened', 'UserController@get_requests_to_show_opened')->name('get_requests_to_show_opened');//
    Route::GET('get_requests_to_show_open_24', 'UserController@get_requests_to_show_open_24')->name('get_requests_to_show_open_24');//
    Route::GET('get_my_requests_to_show_open_24', 'UserController@get_my_requests_to_show_open_24')->name('get_my_requests_to_show_open_24');//
    Route::GET('get_requests_to_show_contributed', 'UserController@get_requests_to_show_contributed')->name('get_requests_to_show_contributed');
    Route::POST('donate', 'PaymentController@donate')->name('donate');//
    Route::POST('get_item_to_donate', 'PaymentController@get_item_to_donate')->name('get_item_to_donate');//
    Route::POST('save_bank', 'PaymentController@save_bank')->name('save_bank');//
    Route::POST('getCardDetails', 'PaymentController@getCardDetails')->name('getCardDetails');//
    Route::POST('deleteCard', 'PaymentController@deleteCard')->name('deleteCard');//
    Route::POST('get_my_dashboard', 'UserController@get_my_dashboard')->name('get_my_dashboard');//
    Route::POST('donate_via_saved', 'PaymentController@donate_via_saved')->name('donate_via_saved');//
    Route::POST('update_role_profile', 'UserController@update_role_profile')->name('update_role_profile');//
    Route::POST('getPublicKey', 'PaymentController@getPublicKey')->name('getPublicKey');
    Route::POST('delete_employee', 'UserController@delete_employee')->name('delete_employee');//
    Route::GET('getNotifications', 'UserController@getNotifications')->name('getNotifications');//
    Route::POST('deleteRequest', 'UserController@deleteRequest')->name('deleteRequest');
    Route::POST('markAsRead', 'UserController@markAsRead')->name('markAsRead');//
    Route::POST('markAsReadThisNotif', 'UserController@markAsReadThisNotif')->name('markAsReadThisNotif');//
    Route::POST('getAllPartnersWithRequest', 'UserController@getAllPartnersWithRequest')->name('getAllPartnersWithRequest');
    Route::POST('getPartnerWithServedRequests', 'UserController@getPartnerWithServedRequests')->name('getPartnerWithServedRequests');
    Route::POST('getUserStripe', 'UserController@getUserStripe')->name('getUserStripe');//
    Route::POST('showDescription', 'PaymentController@showDescription')->name('showDescription');
});
//