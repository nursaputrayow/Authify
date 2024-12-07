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

Route::get('/403', function () {
    return response()->view('errors.403', [], 403);
})->name('403');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/401', function () {
    return response()->view('errors.401', [], 401);
})->name('401');

Route::get('/500', function () {
    return response()->view('errors.500', [], 500);
})->name('500');

Route::get('/429', function () {
    return response()->view('errors.429', [], 429);
})->name('429');

Route::get('/503', function () {
    return response()->view('errors.503', [], 503);
})->name('503');

Route::get('/419', function () {
    return response()->view('errors.419', [], 419);
})->name('419');

Route::get('/418', function () {
    return response()->view('errors.418', [], 418);
})->name('418');

Route::get('/400', function () {
    return response()->view('errors.400', [], 400);
})->name('400');

Route::get('/402', function () {
    return response()->view('errors.402', [], 402);
})->name('402');

Route::get('/405', function () {
    return response()->view('errors.405', [], 405);
})->name('405');

Route::get('/408', function () {
    return response()->view('errors.408', [], 408);
})->name('408');

Route::get('/451', function () {
    return response()->view('errors.451', [], 451);
})->name('451');

Route::get('/502', function () {
    return response()->view('errors.502', [], 502);
})->name('502');