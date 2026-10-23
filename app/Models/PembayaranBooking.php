<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranBooking extends Model
{
    protected $table = 'tbl_pembayaranbooking';

    public $timestamps = false;

    protected $fillable = [
        'kode_booking',
        'norek',
        'atasnama',
        'harga',
        'wasit',
        'bola',
        'rompi',
        'poto',
        'status_poto_duajam',
        'video',
        'status_pembayaran',
        'pembayaran',
        'total_harga',
        'bukti',
        'status',
        'wa',
    ];
}
