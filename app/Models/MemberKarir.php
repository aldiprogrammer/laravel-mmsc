<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberKarir extends Model
{
    protected $table = 'tbl_member_karir';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'kode_main',
        'id_user',
        'nama',
        'posisi',
        'waktu_main',
        'sesi',
        'jam_mulai',
        'jam_selesai',
        'tgl_main',
        'team',
        'lawan',
        'status_pembayaran',
        'bukti',
        'atasnama',
        'bank',
        'hadir',
        'kapten',
    ];
}
