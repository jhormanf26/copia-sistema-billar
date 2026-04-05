<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'numerodocumento' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:admin,empleado'], // Validar que sea un rol válido
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'numerodocumento' => $request->numerodocumento,
            'tipo' => $request->tipo, // Guardar el rol
            'estado' => 'inactivo', // Por defecto, nuevo usuario activo
        ]);

        event(new Registered($user));

       // Auth::login($user);

       // return redirect(route('welcome', absolute: false));
        return redirect()
            ->route('login')
            ->with('status', 'Tu cuenta se ha creado correctamente. Espera a que un administrador la active.');
    }

}
