<?php

use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Route::get('/admin/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'documento' => ['required'],
        'contrasena' => ['required'],
    ]);

    if (! Auth::attempt(['Documento' => $request->documento, 'password' => $request->contrasena])) {
        return back()->withErrors(['documento' => 'Documento o contraseña incorrectos']);
    }

    $request->session()->regenerate();

    return redirect()->route('dashboard');
});

Route::post('/admin/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/admin/password/reset', [PasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/admin/password/email', [PasswordResetController::class, 'sendCode'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/admin/password/verify', [PasswordResetController::class, 'showVerifyForm'])
    ->middleware('guest')
    ->name('password.verify.form');

Route::post('/admin/password/verify', [PasswordResetController::class, 'verifyCode'])
    ->middleware('guest')
    ->name('password.verify');

Route::get('/admin/password/new', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/admin/password/reset', [PasswordResetController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');
