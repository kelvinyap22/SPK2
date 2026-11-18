<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    protected $table = 'perhitungans';

    protected $fillable = [
        'nama_sesi', 
        'detail_proses'
    ];

    /**
     * Casting otomatis kolom JSON dari database ke array PHP, 
     * dan sebaliknya.
     */
    protected $casts = [
        'detail_proses' => 'array',
    ];
}