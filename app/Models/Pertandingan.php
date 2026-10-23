<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertandingan extends Model
{
    protected $table = 'tbl_pertandingan_rankteam';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'kode_main',
        'team',
        'lawan',
        'score_team',
        'score_lawan',
        'video',
        'session',
    ];
}
