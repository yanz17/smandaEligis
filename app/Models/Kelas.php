<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'jurusan' , 'user_id'];

    public function user()
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }

    public function siswa()
    {
        return $this->hasMany(related: Siswa::class);
    }
}
