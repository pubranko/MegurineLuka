<?php
#オペレーターメインメニュー
Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
    return view('operator.home');
})->name('home');

#商品管理
Route::get('/product/menu', 'OperatorMenu\ProductRegisterController@registerMenu', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.menu');
Route::get('/product/in', 'OperatorMenu\ProductRegisterController@registerIn', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.in');
Route::post('/product/check', 'OperatorMenu\ProductRegisterController@registerCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->middleware('productregisterconvert')->name('product.register.check');
#Route::get('/product/check', 'OperatorMenu\ProductRegisterController@registerCheck', function () {
#    $users[] = Auth::user();
#    $users[] = Auth::guard()->user();
#    $users[] = Auth::guard('operator')->user();
#})->name('get.product.register.check');
Route::post('/product/register', 'OperatorMenu\ProductRegisterController@register', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register');
Route::post('/product/result', 'OperatorMenu\ProductRegisterController@registerResult', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();
})->name('product.register.result');
