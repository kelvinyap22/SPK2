<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
    protected $table = 'alternatifs';

    protected $fillable = [
        'kode_alternatif', 
        'nama', 
        'kelas', 
        'jurusan'
    ];

    /**
     * Relasi ke tabel penilaian.
     */
    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }
}