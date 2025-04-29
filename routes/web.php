<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ImageArchiveController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Default Route
Route::get('login', [SessionsController::class, 'create'])->name('login');

// Google Authentication
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Group Middleware Guest
Route::group(['middleware' => 'guest'], function () {
    Route::post('login-session', [SessionsController::class, 'store'])->name('login.store');
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register-session', [RegisterController::class, 'store'])->name('register.store');
	Route::get('forgot-password', [ResetController::class, 'create'])->name('password.forgot');
	Route::post('change-password', [ResetController::class, 'sendEmail'])->name('password.request');
	Route::put('change-password-session', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Group Middleware Auth 
Route::middleware(['auth', 'roleAccess'])->group(function () {
    Route::get('/', [HomeController::class, 'home']);

	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

	// Image Archive
	Route::get('image-archive', [ImageArchiveController::class, 'index'])->name('image-archive.index');
	Route::post('image-archive', [ImageArchiveController::class, 'store'])->name('image-archive.store');
	Route::get('image-archive/data', [ImageArchiveController::class, 'data'])->name('image-archive.data');
	Route::get('image-archive/{id}/edit', [ImageArchiveController::class, 'edit'])->name('image-archive.edit');
	Route::put('image-archive/{id}', [ImageArchiveController::class, 'update'])->name('image-archive.update');
	Route::delete('image-archive/{id}', [ImageArchiveController::class, 'destroy'])->name('image-archive.destroy');

	// User Profile
	Route::get('profile', [UserController::class, 'index'])->name('user.index');
	Route::put('profile', [UserController::class, 'update'])->name('user.update');

	// User Management
	Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management.index');
	Route::get('user-management/data', [UserManagementController::class, 'data'])->name('user-management.data');	
	Route::get('user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
	Route::put('user-management', [UserManagementController::class, 'store'])->name('user-management.store');
	Route::get('user-management/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
	Route::put('user-management/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
	Route::delete('user-management/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
	
	// Sessions
    Route::get('logout', [SessionsController::class, 'destroy'])->name('logout');
	Route::post('switch-account', [SessionsController::class, 'switchAccount'])->name('switch-account');
});

// Error Handling
Route::fallback(function () {
    return response()->view('errors.not-found', [], 404);
});
