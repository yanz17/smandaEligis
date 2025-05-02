<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eligible extends Model
{
    protected $table = 'eligibles';

    protected $fillable = [
        'siswa_id',
        'hasil_akhir',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(related: Siswa::class, foreignKey: 'siswa_id');
    }
}
