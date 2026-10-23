<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $table = 'tbl_booking';

    public $timestamps = false;

    protected $fillable = [
        'kode_booking',
        'iduser',
        'user',
        'jam_booking',
        'jam_mulai',
        'jam_selesai',
        'tgl',
        'team',
        'lapangan',
        'id_lapangan',
        'status',
        'sistem_pembayaran',
        'status_pembayaran',
        'status_hapus',
        'status_pelunasan',
        'booking',
        'status_jam',
        'parents',
        'tgl_booking',
        'voucher',
        'status_jam_booking',
        'status_update',
        'kode_book2',
        'jmljam',
        'rankteam',
        'status_foto',
    ];

    public function pembayaran(): HasOne
    {
        return $this->hasOne(PembayaranBooking::class, 'kode_booking', 'kode_booking')->latestOfMany();
    }

    public function pelunasan(): HasOne
    {
        return $this->hasOne(PelunasanBooking::class, 'kode_booking', 'kode_booking')->latestOfMany();
    }

    public function getStatusLabelAttribute(): string
    {
        return match (mb_strtolower((string) $this->status)) {
            'selesai' => 'Selesai',
            'menunggu' => 'Menunggu',
            'dibatalkan' => 'Dibatalkan',
            'pending' => 'Pending',
            default => $this->status ?: 'Tidak diketahui',
        };
    }

    public function getStatusToneAttribute(): string
    {
        return match (mb_strtolower((string) $this->status)) {
            'selesai' => 'emerald',
            'menunggu' => 'amber',
            'dibatalkan' => 'rose',
            'pending' => 'blue',
            default => 'slate',
        };
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        if ((string) $this->status_pembayaran === '200' && (string) $this->status_pelunasan === '1') {
            return 'Selesai';
        }

        if ((string) $this->status_pembayaran === '200') {
            return 'Disetujui';
        }

        if ((string) $this->status_pembayaran === '400') {
            return 'Ditolak';
        }

        return 'Menunggu';
    }

    public function getStatusPembayaranToneAttribute(): string
    {
        return match ($this->status_pembayaran_label) {
            'Selesai' => 'emerald',
            'Disetujui' => 'blue',
            'Ditolak' => 'rose',
            default => 'amber',
        };
    }
}
