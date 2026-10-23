<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamRankTeam extends Model
{
    protected $table = 'tbl_teamrankteam';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'id_user',
        'nama_team',
        'logo_team',
        'status',
        'pool',
        'tambah_pemain',
    ];

    /**
     * @return HasMany<PemainRankTeam, $this>
     */
    public function pemain(): HasMany
    {
        return $this->hasMany(PemainRankTeam::class, 'kode_team', 'kode');
    }

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
