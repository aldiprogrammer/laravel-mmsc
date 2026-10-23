<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'tbl_team';

    public $timestamps = false;

    protected $fillable = [
        'kode_team',
        'team',
        'status',
    ];
}
