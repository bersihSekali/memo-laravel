<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NomorSuratController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\OtorisasiSuratController;
use App\Models\SuratMasuk;

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

// Route::get('/', function () {
//     return view('templates/index');
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/registration', [AuthController::class, 'registration']);
Route::post('/registration', [AuthController::class, 'register']);

//Penomoran Surat
// Route::get('/nomorSurat', [NomorSuratController::class, 'index']);
Route::resource('nomorSurat', NomorSuratController::class);
Route::resource('otorisasi', OtorisasiSuratController::class);
Route::resource('suratMasuk', SuratMasukController::class)->middleware('auth');

Route::post('/logout', [AuthController::class, 'logout']);
