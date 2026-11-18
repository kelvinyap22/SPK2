<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perhitungans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi'); // Nama sesi perhitungan (cth: Seleksi Beasiswa 2026)
            $table->json('detail_proses'); // Menyimpan rekam jejak matriks (R, Y, Solusi Ideal, Jarak, Ranking) dalam format JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perhitungans');
    }
};