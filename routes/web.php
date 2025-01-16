<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ParseController;

Route::get('/parse', [ParseController::class, 'parseHtml']);

Route::get('/', function () {
    return view('home');
})->name('home');

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/checkUser', [LoginController::class, 'checkUser'])->name('checkUser');

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/checkData', [RegisterController::class, 'checkData'])->name('checkData');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/profile', [ProfileController::class,'show'])->name('profile');
Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.delete');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
