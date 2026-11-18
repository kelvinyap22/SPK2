<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\TopsisCalculationController;



// Halaman Utama & Tampil Daftar Alternatif, Kriteria, serta Log Riwayat
Route::get('/', [TopsisCalculationController::class, 'index'])->name('topsis.index');

// Route untuk Kriteria
Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');

// Route untuk Alternatif & Penilaian Nilai Kriteria Siswa
Route::post('/alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');

// Route untuk Proses Kalkulasi Metode TOPSIS & Menyimpan Log Riwayat
Route::post('/hitung-topsis', [TopsisCalculationController::class, 'hitung'])->name('topsis.hitung');

// Route untuk Menampilkan Detail Riwayat Langkah-Langkah Perhitungan TOPSIS
Route::get('/riwayat-perhitungan/{id}', [TopsisCalculationController::class, 'show'])->name('topsis.show');