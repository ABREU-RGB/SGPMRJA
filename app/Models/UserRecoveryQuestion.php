<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecoveryQuestion extends Model
{
    protected $table = 'user_recovery_question';

    protected $fillable = [
        'user_id',
        'pregunta_id',
        'respuesta',
        'orden',
    ];

    protected $hidden = [
        'respuesta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Devuelve el texto de la pregunta desde el catálogo en config.
     */
    public function getPreguntaTextoAttribute(): string
    {
        return config('recovery_questions.questions.' . $this->pregunta_id, '— Pregunta no disponible —');
    }
}
