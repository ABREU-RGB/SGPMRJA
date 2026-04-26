<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RecoveryQuestionController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserRecoveryQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('recoveryQuestions');

        return view('profile.edit', [
            'user'              => $user,
            'recoveryQuestions' => config('recovery_questions.questions', []),
        ]);
    }

    /**
     * Guarda o actualiza las 3 preguntas de seguridad del usuario.
     */
    public function updateRecoveryQuestions(Request $request): RedirectResponse
    {
        $catalog = config('recovery_questions.questions', []);
        $validIds = array_keys($catalog);

        $data = $request->validate([
            'preguntas'      => ['required', 'array', 'size:3'],
            'preguntas.*'    => ['required', 'integer', 'in:' . implode(',', $validIds)],
            'respuestas'     => ['required', 'array', 'size:3'],
            'respuestas.*'   => ['required', 'string', 'min:3', 'max:255'],
        ], [
            'preguntas.size'   => 'Debes seleccionar 3 preguntas.',
            'respuestas.size'  => 'Debes responder las 3 preguntas.',
            'respuestas.*.min' => 'La respuesta debe tener al menos 3 caracteres.',
        ]);

        // Verificar que las 3 preguntas son distintas
        if (count(array_unique($data['preguntas'])) !== 3) {
            throw ValidationException::withMessages([
                'preguntas' => 'No puedes repetir la misma pregunta.',
            ]);
        }

        // Verificar que las 3 respuestas (normalizadas) son distintas entre sí.
        // Si todas son iguales el atacante solo necesitaría adivinar una.
        $respuestasNormalizadas = array_map(
            fn($r) => RecoveryQuestionController::normalizeAnswer($r),
            $data['respuestas']
        );
        if (count(array_unique($respuestasNormalizadas)) !== 3) {
            throw ValidationException::withMessages([
                'respuestas' => 'Las 3 respuestas deben ser distintas entre sí. No puedes usar la misma respuesta para dos preguntas.',
            ]);
        }

        $user = $request->user();

        DB::transaction(function () use ($user, $data) {
            $user->recoveryQuestions()->delete();

            foreach ($data['preguntas'] as $index => $preguntaId) {
                $orden = $index + 1;
                $respuesta = RecoveryQuestionController::normalizeAnswer($data['respuestas'][$index]);

                UserRecoveryQuestion::create([
                    'user_id'     => $user->id,
                    'pregunta_id' => $preguntaId,
                    'respuesta'   => Hash::make($respuesta),
                    'orden'       => $orden,
                ]);
            }

            // Si venía de una recuperación, limpiar la marca
            if ($user->recovery_must_reset_questions) {
                $user->update(['recovery_must_reset_questions' => false]);
            }
        });

        return Redirect::route('profile.edit')
            ->with('status', 'recovery-questions-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
