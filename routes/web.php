<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;

Route::get('/', function () {
    return view('welcome');
});
// Route untuk menampilkan form unggah file
Route::get('/upload-file', [FileUploadController::class, 'showUploadForm'])->name('showUploadForm');

// Route untuk memproses unggahan file dan menyimpan data
Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('upload');
Route::get('/show-data', [FileUploadController::class, 'showData']);
Route::get('/upload', [FileUploadController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload.excel');
