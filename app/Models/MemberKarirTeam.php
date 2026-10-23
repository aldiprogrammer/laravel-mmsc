<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberKarirTeam extends Model
{
    protected $table = 'tbl_member_karir_team';

    public $timestamps = false;

    protected $fillable = [
        'kode_main',
        'id_user',
        'kode_team',
        'team',
        'lawan',
        'tgl_main',
        'jam_mulai',
        'sesi',
        'atas_nama',
        'bank',
        'harga',
        'total_harga',
        'foto',
        'lapangan',
        'status_pembayaran',
        'bukti',
    ];
}
