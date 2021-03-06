<?php

use App\Http\Controllers\AktivitasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NomorSuratController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\OtorisasiSuratController;
use App\Http\Controllers\SatuanKerjaController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\CheckerController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\CheckerDisposisiController;
use App\Http\Controllers\GenerateLaporanController;
use App\Http\Controllers\ForwardController;
use App\Http\Controllers\OtorisasiBaruController;
use App\Http\Controllers\TujuanBidangCabangController;
use App\Http\Controllers\TujuanDepartemenController;
use App\Http\Controllers\ForwardCabangController;
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

Route::get('/', [HomeController::class, 'index'])->middleware('auth');

Route::resource('satuanKerja', SatuanKerjaController::class);
Route::resource('departemen', DepartemenController::class);

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/listuser', [AuthController::class, 'listUser'])->middleware('auth');

Route::get('/registration', [AuthController::class, 'registration'])->middleware('auth');
Route::post('/registration', [AuthController::class, 'register']);

// Penomoran Surat
Route::get('/nomorSurat/suratHapus', [NomorSuratController::class, 'listSuratHapus'])->middleware('auth');
Route::get('/nomorSurat/allSurat', [NomorSuratController::class, 'allSurat'])->middleware('auth');
Route::get('/nomorSurat/hapusPermanen', [NomorSuratController::class, 'hapusPermanen'])->middleware('auth');
Route::post('/getSatuanKerja', [NomorSuratController::class, 'getSatuanKerja']);
Route::post('/getLevel', [NomorSuratController::class, 'getLevel']);
Route::resource('nomorSurat', NomorSuratController::class)->middleware('auth');

// Otorisasi surat
Route::post('otorisasi/approvedOtorSatu/{id}', [OtorisasiSuratController::class, 'approvedOtorSatu'])->middleware('auth');
Route::post('otorisasi/disApprovedOtorSatu/{id}', [OtorisasiSuratController::class, 'disApprovedOtorSatu'])->middleware('auth');
Route::post('otor/approvedOtorSatu/{id}', [OtorisasiBaruController::class, 'approvedOtorSatu'])->middleware('auth');
Route::post('otor/disApprovedOtorSatu/{id}', [OtorisasiBaruController::class, 'disApprovedOtorSatu'])->middleware('auth');
Route::resource('otorisasi', OtorisasiSuratController::class)->middleware('auth');
Route::resource('otor', OtorisasiBaruController::class)->middleware('auth');
Route::resource('suratMasuk', SuratMasukController::class)->middleware('auth')->name('index', 'suratMasuk');
Route::resource('suratKeluar', SuratKeluarController::class)->middleware('auth');
Route::resource('laporan', GenerateLaporanController::class)->middleware('auth');

Route::post('forward/selesaikan/{id}', [ForwardController::class, 'selesaikan'])->middleware('auth');
Route::post('forward/baca/{id}', [ForwardController::class, 'baca'])->middleware('auth');
Route::resource('forward', ForwardController::class)->middleware('auth');
Route::post('tujuanDepartemen/selesaikan/{id}', [TujuanDepartemenController::class, 'selesaikan'])->middleware('auth');
Route::resource('tujuanDepartemen', TujuanDepartemenController::class)->middleware('auth');

Route::post('forwardCabang/selesaikan/{id}', [ForwardCabangController::class, 'selesaikan'])->middleware('auth');
Route::post('forwardCabang/baca/{id}', [ForwardCabangController::class, 'baca'])->middleware('auth');
Route::resource('forwardCabang', ForwardCabangController::class)->middleware('auth');

Route::post('cabang/selesaikan/{id}', [TujuanBidangCabangController::class, 'selesaikan'])->middleware('auth');
Route::resource('cabang', TujuanBidangCabangController::class)->middleware('auth');

Route::resource('checker', CheckerController::class)->middleware('auth');
Route::resource('checkerDisposisi', CheckerDisposisiController::class)->middleware('auth');
Route::post('disposisi/selesai/{id}', [DisposisiController::class, 'selesai'])->middleware('auth');
Route::resource('disposisi', DisposisiController::class)->middleware('auth');

// Log aktivitas
Route::resource('aktivitas', AktivitasController::class)->middleware('auth');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');
