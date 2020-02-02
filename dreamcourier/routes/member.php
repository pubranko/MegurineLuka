<?php
/*
Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
    return view('member.home');
})->name('home');
*/

Route::get('/home', 'SalesSiteController@siteTop', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.home');
Route::get('/tag', 'SalesSiteController@siteProduct', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.site.tag');
Route::get('/keyword', 'ProductListController@productSearch', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.site.keyword');
Route::get('/show', 'ProductListController@productShow', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.site.show');

#カートリストへの追加・削除関係
Route::get('/cart_add', 'MemberMenu\ProductCartListController@cartAdd', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.cart.add');
Route::get('/cart_delete', 'MemberMenu\ProductCartListController@cartDelete', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.cart.delete');

#商品購入手続き関係
Route::get('/cart_index', 'MemberMenu\ProductTransactionController@cartLists', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.cart.lists');

Route::get('/delivery_address', 'MemberMenu\ProductTransactionController@deliveryAddress', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.address');
Route::post('/delivery_address', 'MemberMenu\ProductTransactionController@deliveryAddressCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.address_check');

Route::get('/delivery_datetime', 'MemberMenu\ProductTransactionController@deliveryDatetime', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.datetime');
Route::post('/delivery_datetime', 'MemberMenu\ProductTransactionController@deliveryDatetimeCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.datetime_check');

Route::get('/delivery_payment', 'MemberMenu\ProductTransactionController@deliveryPayment', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.payment');
Route::post('/delivery_payment', 'MemberMenu\ProductTransactionController@deliveryPaymentCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.payment_check');

Route::get('/delivery_check', 'MemberMenu\ProductTransactionController@deliveryCheck', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.check');

Route::post('/delivery_register', 'MemberMenu\ProductTransactionController@deliveryRegister', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();
})->name('member.delivery.register');
