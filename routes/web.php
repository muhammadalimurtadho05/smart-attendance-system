<?php

use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('absensi.dashboard');
});

Route::get('/mahasiswa',[MahasiswaController::class, 'index']);

Route::get('/acara', function () {
    return view('absensi.acara');
});

Route::get('/absensi', function () {
    return view('absensi.absensi');
});

Route::get('/laporan', function () {
    return view('absensi.laporan');
});
