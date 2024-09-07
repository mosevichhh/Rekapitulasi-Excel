<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileData extends Model
{
    use HasFactory;

    protected $fillable = [
        'success',
        'failed',
        'gmv',
        'profit',
        'babe',
        'net_profit',
    ];
}
