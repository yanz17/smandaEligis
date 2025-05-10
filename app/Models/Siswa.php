<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswas';
    protected $fillable = ['id','nama', 'tanggal_lahir' ,'kelas_id'];

    public function kelas()
    {
        return $this->belongsTo(related: Kelas::class);
    }

    public function nilai()
    {
        return $this->hasOne(related: Nilai::class);
    }
}
