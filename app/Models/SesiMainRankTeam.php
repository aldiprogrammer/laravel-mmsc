<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiMainRankTeam extends Model
{
    protected $table = 'tbl_sesi_main_rankteam';

    public $timestamps = false;

    protected $fillable = [
        'kode_main',
        'sesi',
        'jam_main',
        'lapangan',
        'harga',
        'status',
    ];
}
