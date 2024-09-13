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

