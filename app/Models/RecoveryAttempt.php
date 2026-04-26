<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoveryAttempt extends Model
{
    protected $table = 'recovery_attempt';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email',
        'ip',
        'user_agent',
        'tipo',
        'resultado',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
