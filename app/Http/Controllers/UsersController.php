<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::paginate(15);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'numerodocumento' => ['required', 'string', 'max:255', 'unique:users,numerodocumento'],
            'tipodocumento' => ['required', 'string', 'in:cc,ce,pa,nit,ti'],
            'tipo' => ['required', 'string', 'in:admin,empleado'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'numerodocumento' => $request->numerodocumento,
            'tipodocumento' => $request->tipodocumento,
            'tipo' => $request->tipo,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'numerodocumento' => ['required', 'string', 'max:255', 'unique:users,numerodocumento,' . $user->id],
            'tipodocumento' => ['required', 'string', 'in:cc,ce,pa,nit,ti'],
            'tipo' => ['required', 'string', 'in:admin,empleado'],
            'estado' => ['required', 'string', 'in:activo,inactivo'],
        ]);

        $user->update([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'numerodocumento' => $request->numerodocumento,
            'tipodocumento' => $request->tipodocumento,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'estado' => $user->estado === 'activo' ? 'inactivo' : 'activo',
        ]);

        return redirect()->route('users.index')->with('success', 'Estado del usuario actualizado.');
    }

    /**
     * Update user tipo (role) - admin only
     */
    public function updateTipo(Request $request, User $user)
    {
        // No permitir cambiar el tipo de otro admin
        if ($user->tipo === 'admin' && $user->id !== auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes cambiar el tipo de otro administrador.');
        }

        $request->validate([
            'tipo' => ['required', 'in:admin,empleado'],
        ]);

        $user->update([
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('users.index')
            ->with('success', "Tipo de usuario actualizado a {$request->tipo}.");
    }

    /**
     * Update user estado (active/inactive) - admin only
     */
    public function updateEstado(Request $request, User $user)
    {
        // No permitir desactivar al único admin
        if ($user->id === auth()->id() && $request->estado === 'inactivo') {
            return redirect()->route('users.index')
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $request->validate([
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $user->update([
            'estado' => $request->estado,
        ]);

        return redirect()->route('users.index')
            ->with('success', "Estado de usuario actualizado a {$request->estado}.");
    }

    /**
     * Display admin users management view.
     */
    public function adminManagement()
    {
        $users = User::paginate(15);

        return view('admin.users-management', [
            'users' => $users,
        ]);
    }

    /**
     * Get users for AJAX/API endpoint (used by sedevar).
     */
    public function getUsers()
    {
        $users = User::select('id', 'name', 'apellidos', 'email', 'numerodocumento', 'tipo', 'estado', 'created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Sync users from external sedevar source.
     * This method would connect to sedevar API/database to sync users.
     */
    public function syncFromSedevar()
    {
        try {
            // TODO: Implement integration with sedevar
            // This would fetch users from sedevar and sync them

            return redirect()->route('users.index')->with('success', 'Usuarios sincronizados desde Sedevar correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Error al sincronizar usuarios: ' . $e->getMessage());
        }
    }
}
