<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriterias';

    protected $fillable = [
        'kode', 
        'nama', 
        'sifat', 
        'bobot'
    ];

    /**
     * Relasi ke tabel penilaian.
     */
    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }
}