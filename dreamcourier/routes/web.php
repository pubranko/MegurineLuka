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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'operator'], function () {
  Route::get('/login', 'OperatorAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'OperatorAuth\LoginController@login');
  Route::post('/logout', 'OperatorAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'OperatorAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'OperatorAuth\RegisterController@register');

  Route::post('/password/email', 'OperatorAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'OperatorAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'OperatorAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'OperatorAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'member'], function () {
  Route::get('/login', 'MemberAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'MemberAuth\LoginController@login');
  Route::post('/logout', 'MemberAuth\LoginController@logout')->name('logout');

  Route::get('/registerin', 'MemberAuth\RegisterController@registrationInForm')->name('registerin');        #会員登録画面（入力）
  Route::get('/registercheck', 'MemberAuth\RegisterController@registrationCheckForm')                       #会員登録画面（確認）
        ->middleware('membersconvert')->name('register.check');
  Route::post('/register', 'MemberAuth\RegisterController@register')->name('register');                     #会員登録（処理）

  Route::post('/password/email', 'MemberAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'MemberAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'MemberAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'MemberAuth\ResetPasswordController@showResetForm');

  #Route::resource('rest','AddressMastersController');
});
Route::get('/addresssearch', 'AddressMastersController@postCodeSearch')->name('postcodesearch');        #郵便番号から住所を取得
