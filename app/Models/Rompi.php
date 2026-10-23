<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rompi extends Model
{
    protected $table = 'tbl_rompi';

    public $timestamps = false;

    protected $fillable = [
        'rompi',
        'warna',
        'grade',
        'harga',
        'foto',
        'status',
        'jml_jam',
    ];

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
