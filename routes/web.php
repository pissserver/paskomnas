<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
// Auth
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetController;
use App\Http\Controllers\Auth\SessionsController;
use App\Http\Controllers\Auth\GoogleController;
// Views
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ImageArchiveController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\OrderController;


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
	Route::post('forgot-password-session', [ResetController::class, 'sendEmail'])->name('password.request');
	Route::get('change-password', [ChangePasswordController::class, 'create'])->name('password.change');
	Route::put('change-password-session', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Group Middleware Auth 
Route::middleware(['auth', 'roleAccess'])->group(function () {
    Route::get('/', [HomeController::class, 'home']);

	Route::get('dashboard', function () {
		return view('pages.dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('pages.billing');
	})->name('billing');

	// Order
	Route::get('order', [OrderController::class, 'index'])->name('order.index');
	Route::get('buat-order/{customer_id}', [OrderController::class, 'create'])->name('order.create');
	Route::post('buat-order/{customer_id}', [OrderController::class, 'store'])->name('order.store');
	Route::get('buat-order/data/{customer_id}', [OrderController::class, 'data'])->name('order.data');
	Route::get('buat-order/{customer_id}/detail/{order_id}', [OrderController::class, 'show'])->name('order.show');
	Route::put('buat-order/{customer_id}/detail/{order_id}', [OrderController::class, 'update'])->name('order.update');

	// Image Archive
	Route::get('arsip-gambar', [ImageArchiveController::class, 'index'])->name('image-archive.index');
	Route::post('arsip-gambar', [ImageArchiveController::class, 'store'])->name('image-archive.store');
	Route::get('arsip-gambar/data', [ImageArchiveController::class, 'data'])->name('image-archive.data');
	Route::get('arsip-gambar/{id}/edit', [ImageArchiveController::class, 'edit'])->name('image-archive.edit');
	Route::put('arsip-gambar/{id}', [ImageArchiveController::class, 'update'])->name('image-archive.update');
	Route::delete('arsip-gambar/{id}', [ImageArchiveController::class, 'destroy'])->name('image-archive.destroy');
	Route::post('arsip-gambar/delete-selected', [ImageArchiveController::class, 'deleteAllSelected'])->name('image-archive.delete-selected');

	// Customer
	Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
	Route::get('customer/data', [CustomerController::class, 'data'])->name('customer.data');
	Route::get('customer/tambah', [CustomerController::class, 'create'])->name('customer.create');
	Route::put('customer', [CustomerController::class, 'store'])->name('customer.store');
	Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
	Route::put('customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
	Route::delete('customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

	// Goods
	Route::get('barang-komoditi', [GoodsController::class, 'index'])->name('goods.index');
	Route::get('barang-komoditi/data', [GoodsController::class, 'data'])->name('goods.data');
	Route::get('barang-komoditi/tambah', [GoodsController::class, 'create'])->name('goods.create');
	Route::put('barang-komoditi', [GoodsController::class, 'store'])->name('goods.store');
	Route::get('barang-komoditi/{id}/edit', [GoodsController::class, 'edit'])->name('goods.edit');
	Route::put('barang-komoditi/{id}', [GoodsController::class, 'update'])->name('goods.update');
	Route::delete('barang-komoditi/{id}', [GoodsController::class, 'destroy'])->name('goods.destroy');

	// User Management
	Route::get('kelola-pengguna', [UserManagementController::class, 'index'])->name('user-management.index');
	Route::get('kelola-pengguna/data', [UserManagementController::class, 'data'])->name('user-management.data');	
	Route::get('kelola-pengguna/tambah', [UserManagementController::class, 'create'])->name('user-management.create');
	Route::put('kelola-pengguna', [UserManagementController::class, 'store'])->name('user-management.store');
	Route::get('kelola-pengguna/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
	Route::put('kelola-pengguna/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
	Route::delete('kelola-pengguna/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
	
	// User Profile
	Route::get('profil', [UserController::class, 'index'])->name('user.index');
	Route::put('profil', [UserController::class, 'update'])->name('user.update');

	// Virtual Reality
	Route::get('virtual-reality', function () {
		return view('pages.virtual-reality');
	})->name('virtual-reality');

	// Sessions
    Route::get('logout', [SessionsController::class, 'destroy'])->name('logout');
	Route::post('switch-account', [SessionsController::class, 'switchAccount'])->name('switch-account');
});

// Error Handling
Route::fallback(function () {
    return response()->view('errors.not-found', [], 404);
});
