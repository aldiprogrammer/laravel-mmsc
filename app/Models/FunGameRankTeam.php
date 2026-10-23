<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunGameRankTeam extends Model
{
    protected $table = 'tbl_fungame_rankteam';

    public $timestamps = false;

    protected $fillable = [
        'kode_main',
        'tgl_main',
        'jam_main',
        'jam_mulai',
        'jam_selesai',
        'jml_team',
        'lapangan',
        'id_lapangan',
        'harga',
        'harga2',
        'open_sesi',
        'status',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
