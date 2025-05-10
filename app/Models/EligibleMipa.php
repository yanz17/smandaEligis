<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibleMipa extends Model
{
    protected $table = 'eligibles_mipa';
    protected $fillable = ['siswa_id', 'hasil_akhir'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
