<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelunasanBooking extends Model
{
    protected $table = 'tbl_pelunasan_booking';

    public $timestamps = false;

    protected $fillable = [
        'kode_booking',
        'id_user',
        'atas_nama',
        'bank',
        'bukti',
        'sisa_pembayaran',
        'status',
    ];
}
