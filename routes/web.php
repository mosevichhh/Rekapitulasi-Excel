<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\RekapitulasiController;


Route::get('/', function () {
    return view('welcome');
});
// Route untuk menampilkan form unggah file
Route::get('/upload-file', [FileUploadController::class, 'showUploadForm'])->name('showUploadForm');

// Route untuk memproses unggahan file dan menyimpan data
Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('upload');
Route::post('/rekapitulasi/store', [RekapitulasiController::class, 'store'])->name('rekapitulasi.store');
Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
Route::get('/rekapitulasi/chart-data', [RekapitulasiController::class, 'chartData']);
Route::get('/rekapitulasi/chart-data', [RekapitulasiController::class, 'getChartData'])->name('rekapitulasi.chart-data');
