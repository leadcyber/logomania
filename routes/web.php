<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// use App\Http\Controllers\Controller;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('', [Controller::class, 'index']);

// DASHBOARDS //
Route::get('/', [HomeController::class, 'index']);
Route::get('/signin', [AuthController::class, 'signin']);
Route::get('/signup', [AuthController::class, 'signup']);
Route::get('/reset-password', [AuthController::class, 'reset_password']);
Route::get('dashboard', [DashboardController::class, 'index']);