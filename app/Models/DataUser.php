<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataUser extends Model
{
    protected $table = 'tbl_user';

    public $timestamps = false;

    public function isAktif(): bool
    {
        return (int) $this->status === 1;
    }
}
