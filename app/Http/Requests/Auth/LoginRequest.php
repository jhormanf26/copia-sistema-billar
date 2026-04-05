<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string', 'email'],
            'numerodocumento' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => 'El correo debe ser un email válido.',
            'password.required' => 'La contraseña es requerida.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Validar que al menos uno de los dos campos esté presente
        if (!$this->input('email') && !$this->input('numerodocumento')) {
            throw ValidationException::withMessages([
                'email' => 'Debes ingresar un correo o número de documento.',
            ]);
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Determinar el método de autenticación (email o numerodocumento)
        $credentials = ['password' => $this->input('password')];
        
        if ($this->input('email')) {
            $credentials['email'] = $this->input('email');
            $loginField = 'email';
        } else {
            $credentials['numerodocumento'] = $this->input('numerodocumento');
            $loginField = 'numerodocumento';
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $loginField => trans('auth.failed'),
            ]);
        }

        // Verificar si el usuario está activo
        $user = Auth::user();
        if ($user && $user->estado === 'inactivo') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                $loginField => 'Tu cuenta está inactiva. Contacta al administrador para activarla.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // Usar email o numerodocumento como base para el throttle key
        $identifier = $this->input('email') ?? $this->input('numerodocumento');
        return Str::transliterate(Str::lower($identifier).'|'.$this->ip());
    }
}
