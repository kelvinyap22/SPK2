<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaians';

    protected $fillable = [
        'alternatif_id', 
        'kriteria_id', 
        'nilai'
    ];

    /**
     * Relasi ke tabel alternatif.
     */
    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    /**
     * Relasi ke tabel kriteria.
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}