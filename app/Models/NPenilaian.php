<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPenilaian extends Model
{
    use HasFactory;

    protected $table = 'n_penilaian';
    protected $fillable = [
        'id',
        'periode',
        'id_karyawan',
        'id_kriteria',
        'nilai'
    ];
}
