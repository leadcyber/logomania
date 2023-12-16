<?php

use App\Http\Controllers\FontController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\DashboardController;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

// Auth routes
Auth::routes(['verify' => true]);
Route::get('/login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);
Route::get('/login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);
Route::get('/login/twitter', [App\Http\Controllers\Auth\LoginController::class, 'redirectToTwitter'])->name('login.twitter');
Route::get('/login/twitter/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleTwitterCallback']);
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('/topic', [TopicController::class, 'index'])->name('topic');
Route::post('/topic', [TopicController::class, 'store'])->name('topic');

Route::get('/logos', [LogoController::class, 'index'])->name('logos.list');
Route::get('/logos/render', [LogoController::class, 'renderLogos'])->name('logos.render');
Route::post('/logos/favorites', [LogoController::class, 'favorites'])->name('logos.favorites');
Route::get('/logos/{id}/edit', [LogoController::class, 'edit'])->name('logos.edit');

Route::get('/logo/svgs/font', [LogoController::class, 'generateSVGsFont'])->name('logo.svgs.font');
Route::get('/logo/svgs/icon', [LogoController::class, 'generateSVGsIcon'])->name('logo.svgs.icon');
Route::get('/logo/svgs/palette', [LogoController::class, 'generateSVGsPalette'])->name('logo.svgs.palette');

Route::get('/fonts/rendered', [FontController::class, 'fontsRendered'])->name('fonts.rendered');

// Private routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
});
