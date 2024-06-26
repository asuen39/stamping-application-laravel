<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\AttendanceController;
use App\Models\Attendances;
use App\Http\Controllers\UserController;

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

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::get('/attendance/previous_day/{currentDay}', [AttendanceController::class, 'previousDay'])->name('previous_day');
Route::get('/attendance/next_day/{currentDay}', [AttendanceController::class, 'nextDay'])->name('next_day');


Route::get('/mypage/user', [UserController::class, 'index'])->name('user');
Route::get('/user-attendance-list', [UserController::class, 'userAttendanceList'])->name('userAttendanceList');
Route::post('/user-attendance-list', [UserController::class, 'userAttendanceList'])->name('userAttendanceList');
