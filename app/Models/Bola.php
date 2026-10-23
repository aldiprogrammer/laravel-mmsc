<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bola extends Model
{
    protected $table = 'tbl_bola';

    public $timestamps = false;

    protected $fillable = [
        'bola',
        'grade',
        'status',
        'foto',
        'harga',
        'jml_jam',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
