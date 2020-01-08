<?php
#オペレーターメインメニュー
Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
    return view('operator.home');
})->name('home');

#オペレーターメニュー選択
Route::get('/product/menu', 'OperatorMenu\MenuController@ProductMenu', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.menu');

#商品登録関連
Route::get('/product/register/in', 'OperatorMenu\ProductRegisterController@registerIn', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.in');
Route::post('/product/register/check', 'OperatorMenu\ProductRegisterController@registerCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->middleware('productregisterconvert')->name('product.register.check');
Route::get('/product/register/checkview', 'OperatorMenu\ProductRegisterController@registerCheckView', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->middleware('productregisterconvert')->name('product.register.check');
Route::post('/product/register', 'OperatorMenu\ProductRegisterController@register', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register');
Route::post('/product/register/result', 'OperatorMenu\ProductRegisterController@registerResult', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.result');

#商品検索・参照
#Route::get('/product/search', 'OperatorMenu\ProductReferenceController@searchIn', function () {
#    $users[] = Auth::user();
#    $users[] = Auth::guard()->user();
#    $users[] = Auth::guard('operator')->user();
#})->name('product.search.in');
Route::get('/product/search', 'OperatorMenu\ProductReferenceController@search', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->middleware('productregisterconvert')->name('product.search');
Route::get('/product/show', 'OperatorMenu\ProductReferenceController@show', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.show');


