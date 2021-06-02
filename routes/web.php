<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\UrlController;
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

Route::redirect('/','/create');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/list', [UrlController::class, 'index'])->name('short-urls.index')->middleware('auth');
Route::get('/create', [UrlController::class, 'create'])->name('short-urls.create');
Route::post('/store', [UrlController::class, 'store'])->name('short-urls.store');
Route::put('/update/{id}', [UrlController::class, 'update'])->name('short-urls.update')->middleware('auth');
Route::delete('/delete/{id}', [UrlController::class, 'destroy'])->name('short-urls.destroy')->middleware('auth');
Route::get('/edit/{id}', [UrlController::class, 'edit'])->name('short-urls.edit')->middleware('auth');

Route::get('{code}', [RedirectController::class, 'redirect'])->name('short-urls.redirect');
