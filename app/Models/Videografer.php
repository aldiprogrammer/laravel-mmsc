<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videografer extends Model
{
    protected $table = 'tbl_videograper';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nowa',
        'alamat',
        'harga',
        'harga2',
        'foto',
        'status',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
