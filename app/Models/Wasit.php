<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wasit extends Model
{
    protected $table = 'tbl_wasit';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nohp',
        'jk',
        'alamat',
        'harga',
        'status',
        'jml_jam',
        'lisensi',
        'foto',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
