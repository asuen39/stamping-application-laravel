<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\StampController;

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

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/', [StampController::class, 'index'])->name('stamp');
Route::post('/workStart', [StampController::class, 'workStart'])->name('workStart');
Route::post('/workEnd', [StampController::class, 'workEnd'])->name('workEnd');
Route::post('/breakStart', [StampController::class, 'breakStart'])->name('breakStart');
Route::post('/breakEnd', [StampController::class, 'breakEnd'])->name('breakEnd');
