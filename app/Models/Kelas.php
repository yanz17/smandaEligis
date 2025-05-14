<?php

namespace App\Models;
use App\Models\Siswa;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'jurusan' , 'user_id'];

    public function user()
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }

    public function siswas()
    {
        return $this->hasMany(related: Siswa::class);
    }
}
