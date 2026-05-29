<?php

/*
|--------------------------------------------------------------------------
| Catálogo de Preguntas de Seguridad
|--------------------------------------------------------------------------
|
| Lista de preguntas predefinidas que el usuario puede elegir para
| configurar su recuperación de contraseña offline (sin email).
|
| El ID es la clave del array y se almacena en user_recovery_question.pregunta_id.
| Si se modifica una pregunta, el ID debe mantenerse para no romper
| las relaciones existentes.
|
*/

return [
    'questions' => [
        1  => '¿Cuál es el nombre de tu primera mascota?',
        2  => '¿En qué ciudad naciste?',
        3  => '¿Cuál es tu equipo deportivo favorito?',
        4  => '¿Cuál es el nombre de tu mejor amigo de la infancia?',
        5  => '¿En qué calle vivías cuando tenías 10 años?',
        6  => '¿Cuál es tu comida favorita?',
        7  => '¿Cuál es el nombre de tu profesor favorito?',
        8  => '¿Cuál fue tu primer trabajo?',
        9  => '¿Cuál es el segundo nombre de tu padre?',
        10 => '¿Cuál fue el modelo de tu primer vehículo?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Parámetros de seguridad del flujo de recuperación
    |--------------------------------------------------------------------------
    */

    // Cantidad de intentos fallidos antes de aplicar bloqueo temporal
    'max_attempts_soft_lock' => 5,

    // Duración del bloqueo temporal en minutos
    'soft_lock_minutes' => 15,

    // Cantidad de intentos fallidos para bloqueo total (requiere admin)
    'max_attempts_hard_lock' => 10,

    // Duración del token de sesión entre validar respuestas y reset (segundos)
    'reset_token_ttl' => 300, // 5 minutos
];
