<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemain extends Model
{
    protected $table = 'tbl_pemain';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tgl_lahir',
        'posisi',
        'no_punggung',
        'team',
        'foto',
    ];
}
