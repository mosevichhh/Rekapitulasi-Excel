<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekapitulasi extends Model
{
    use HasFactory;

    // Jika nama tabel di database tidak sesuai dengan konvensi penamaan Laravel,
    // Anda bisa menyebutkan nama tabel di sini.
    protected $table = 'rekapitulasi';

    // Sebutkan atribut yang dapat diisi (fillable)
    protected $fillable = [
        'success',
        'failed',
        'gmv',
        'profit',
        'babe',
        'net_profit',
        'tanggal',
    ];
}
