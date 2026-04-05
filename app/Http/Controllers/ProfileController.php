<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validatedData = $request->validate([
    'name' => [
        'required',
        'string',
        'max:100',
        'regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/'
    ],
    'apellidos' => [
        'required',
        'string',
        'max:255',
        'regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/'
    ],
    'email' => [
        'required',
        'string',
        'email',
        'max:255',
        Rule::unique('users')->ignore($user->id)
    ],
    'numerodocumento' => [
        'required',
        'regex:/^[0-9]+$/',
        'max:20',
        Rule::unique('users')->ignore($user->id)
    ],
    'tipodocumento' => [
        'nullable',  // ⛔ YA NO ES REQUIRED
        'string',
        'in:CC,CE,PA,NIT'
    ],
]);

// Si no enviaron un tipo de documento, conservar el que había
if (!$request->filled('tipodocumento')) {
    $validatedData['tipodocumento'] = $user->tipodocumento;
}

$user->update($validatedData);


        if (empty($validatedData['tipodocumento'])) {
            $validatedData['tipodocumento'] = $user->tipodocumento;
        }

        $user->update($validatedData);

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado con éxito.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Contraseña actualizada correctamente.');
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

    /**
     * Update the user's avatar color.
     */
    public function updateAvatarColor(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_color' => ['required', 'string', 'in:FF6B6B,4ECDC4,45B7D1,96CEB4,FFEAA7,DDA15E,BC6C25,9D84B7'],
        ]);

        $request->user()->update([
            'avatar_color' => $request->avatar_color,
        ]);

        return redirect()->route('profile.show')->with('success', 'Avatar actualizado correctamente.');
    }

    /**
     * Upload a custom avatar image.
     */
    public function uploadAvatarImage(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
        ]);

        $user = $request->user();

        // Eliminar avatar anterior si existe
        if ($user->avatar_image && Storage::disk('public')->exists('avatars/' . $user->avatar_image)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar_image);
        }

        // Guardar nuevo avatar
        $filename = 'avatar_' . $user->id . '_' . time() . '.' . $request->file('avatar_file')->getClientOriginalExtension();
        $request->file('avatar_file')->storeAs('avatars', $filename, 'public');

        $user->update([
            'avatar_image' => $filename,
        ]);

        return redirect()->route('profile.show')->with('success', 'Imagen de avatar subida correctamente.');
    }

    /**
     * Delete the user's custom avatar image.
     */
    public function deleteAvatarImage(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_image && Storage::disk('public')->exists('avatars/' . $user->avatar_image)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar_image);
        }

        $user->update([
            'avatar_image' => null,
        ]);

        return redirect()->route('profile.show')->with('success', 'Imagen de avatar eliminada. Ahora se mostrará el avatar generado.');
    }
}