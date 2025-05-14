<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'password', 'role'];

    protected $hidden = ['password'];

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'user_id'); // sesuaikan foreign key jika perlu
    }

    public function isWaliKelas(): bool
    {
        return $this->role === 'wakel';
    }

    public function isGuruBK(): bool
    {
        return $this->role === 'gurubk';
    }
}
