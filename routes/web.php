<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/verify', function () {
    return view('verify');
});

Route::get('/set-new-password', function () {
    return view('set-new-password');
});

Route::get('/reset-password', function () {
    return view('reset-password');
});

Route::get('/logout', function () {
    return view('logout');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/update-profile', function () {
    return view('update-profile');
});