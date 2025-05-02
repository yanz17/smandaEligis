<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilais';

    protected $fillable = [
        'siswa_id',
        'sem_1',
        'sem_2',
        'sem_3',
        'sem_4',
        'sem_5',
        'prestasi',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(related: Siswa::class, foreignKey:'siswa_id');
    }
}
