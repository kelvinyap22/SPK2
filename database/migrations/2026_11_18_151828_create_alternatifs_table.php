<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatifs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alternatif', 20)->unique(); // NIS atau Kode Alternatif
            $table->string('nama');
            $table->string('kelas', 20)->nullable();
            $table->string('jurusan', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatifs');
    }
};