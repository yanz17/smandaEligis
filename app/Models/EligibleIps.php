<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibleIps extends Model
{
    protected $table = 'eligibles_ips';
    protected $fillable = ['siswa_id', 'hasil_akhir'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
