<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunGame extends Model
{
    protected $table = 'tbl_fungame';

    public $timestamps = false;

    protected $fillable = [
        'kode_main',
        'tgl_main',
        'jam_mulai',
        'jam_selesai',
        'jam_main',
        'jml_team',
        'jml_pemain',
        'harga',
        'harga_gk',
        'id_lapangan',
        'lapangan',
        'status',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
