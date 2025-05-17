<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    protected $fillable = [
        'user_id', 'model_type', 'model_id', 'action', 'data', 'status', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'data' => 'array',
        'approved_at' => 'datetime',
    ];

    // relasi user yang request
    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi user yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
