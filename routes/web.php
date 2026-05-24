<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login', ['component' => 'LoginPage']);
})->name('login');

Route::get('/{any}', function () {
    return Inertia::render('Auth/Login', ['component' => 'LoginPage']);
})->where('any', '^(?!api(/|$)|build(/|$)|vendor(/|$)).*$');