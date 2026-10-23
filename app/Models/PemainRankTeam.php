<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemainRankTeam extends Model
{
    protected $table = 'tbl_pemainrankteam';

    public $timestamps = false;

    protected $fillable = [
        'kode_team',
        'nama',
        'team',
        'posisi',
        'nik',
        'no_punggung',
        'nickname',
        'idrow',
        'foto',
        'ktp',
        'baru',
    ];
}
