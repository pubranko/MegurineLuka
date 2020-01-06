<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('member')->user();

    //dd($users);

    return view('member.home');
})->name('home');

