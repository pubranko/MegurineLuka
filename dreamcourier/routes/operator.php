<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('operator')->user();

    //dd($users);

    return view('operator.home');
})->name('home');

