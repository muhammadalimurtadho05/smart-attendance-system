<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);

Route::get('/mahasiswa',[MahasiswaController::class, 'index']);

Route::get('/acara',[AcaraController::class,'index'])->name('acara');
Route::post('/acara/store',[AcaraController::class,'store'])->name('acara.store');
Route::post('/agenda/store',[AgendaController::class,'store'])->name('agenda.store');
Route::get('/acara/agenda/{acara_id}',[AgendaController::class,'index'])->name('acara.agenda');

Route::get('/checkin/{id_agenda}',[AgendaController::class,'checkin']);
Route::get('/checkout/{id_agenda}',[AgendaController::class,'checkout']);

// Route::get('/laporan', [LaporanController::class, 'index']);
