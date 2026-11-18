<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\TopsisCalculationController;

// Halaman Utama & Tampil Daftar Alternatif, Kriteria, serta Log Riwayat
Route::get('/', [TopsisCalculationController::class, 'index'])->name('topsis.index');

// Route untuk Kriteria (CRUD)
Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
Route::get('/kriteria/{id}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');
Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

// Route untuk Alternatif & Penilaian Nilai Kriteria Siswa (CRUD)
Route::post('/alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
Route::get('/alternatif/{id}/edit', [AlternatifController::class, 'edit'])->name('alternatif.edit');
Route::put('/alternatif/{id}', [AlternatifController::class, 'update'])->name('alternatif.update');
Route::delete('/alternatif/{id}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');

// Route untuk Proses Kalkulasi Metode TOPSIS & Menyimpan Log Riwayat
Route::post('/hitung-topsis', [TopsisCalculationController::class, 'hitung'])->name('topsis.hitung');

// Route untuk Menampilkan Detail Riwayat Langkah-Langkah Perhitungan TOPSIS & Hapus Riwayat
Route::get('/riwayat-perhitungan/{id}', [TopsisCalculationController::class, 'show'])->name('topsis.show');
Route::delete('/riwayat-perhitungan/{id}', [TopsisCalculationController::class, 'destroy'])->name('topsis.destroy');