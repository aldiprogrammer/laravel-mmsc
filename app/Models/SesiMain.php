<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiMain extends Model
{
    protected $table = 'tbl_sesi_main';

    public $timestamps = false;

    protected $fillable = [
        'kode_main',
        'tgl_main',
        'sesi',
        'team',
        'lawan',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];
}
