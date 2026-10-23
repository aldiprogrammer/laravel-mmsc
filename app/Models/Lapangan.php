<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'tbl_lapangan';

    public $timestamps = false;

    protected $fillable = [
        'lapangan',
        'slug',
        'pasilitas',
        'harga_perjam',
        'status_booking',
        'gambar',
    ];
}
