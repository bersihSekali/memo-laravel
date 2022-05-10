<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
<<<<<<< HEAD
use App\Http\Controllers\HomeNomorSuratController;
=======
use App\Http\Controllers\SuratMasukController;
use App\Models\SuratMasuk;
>>>>>>> yud

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

Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/registration', [AuthController::class, 'registration']);
Route::post('/registration', [AuthController::class, 'register']);

//Penomoran Surat
Route::get('/', [HomeNomorSuratController::class, 'index']);
Route::resource('suratmasuk', SuratMasukController::class);
