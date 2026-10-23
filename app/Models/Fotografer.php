<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fotografer extends Model
{
    protected $table = 'tbl_potografer';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nohp',
        'harga',
        'harga2',
        'foto',
        'status',
        'role',
        'jml_jam',
        'keterangan',
        'tgl_tutup_mulai',
        'tgl_tutup_selesai',
        'jam_tutup_mulai',
        'jam_tutup_selesai',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
