@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Gestión de Usuarios</h1>
        <p class="text-gray-600">Administra tipos de usuario y estados</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header tabla -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <h2 class="text-white text-lg font-semibold">Usuarios del Sistema</h2>
        </div>

        <!-- Tabla responsiva -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Usuario</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Documento</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rol Actual</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <!-- Usuario -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">{{ $user->name }} {{ $user->apellidos ?? '' }}</p>
                                    <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700">{{ $user->email }}</p>
                        </td>

                        <!-- Documento -->
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700">{{ $user->numerodocumento }}</p>
                        </td>

                        <!-- Rol Actual -->
                        <td class="px-6 py-4">
                            @if($user->tipo === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    🔐 Administrador
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    👤 Empleado
                                </span>
                            @endif
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            @if($user->estado === 'activo')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    ✓ Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                    ✗ Inactivo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <!-- Botón Editar Rol -->
                                <button onclick="openRoleModal({{ $user->id }}, '{{ $user->tipo }}')" 
                                        class="px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition"
                                        @if(auth()->user()->id !== $user->id) title="Cambiar rol" @endif>
                                    🔄 Rol
                                </button>

                                <!-- Botón Cambiar Estado -->
                                <form action="{{ route('users.update-estado', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="estado" value="{{ $user->estado === 'activo' ? 'inactivo' : 'activo' }}">
                                    <button type="submit" 
                                            class="px-3 py-2 @if($user->estado === 'activo') bg-orange-500 hover:bg-orange-600 @else bg-green-500 hover:bg-green-600 @endif text-white text-sm rounded-lg transition"
                                            @if(auth()->user()->id === $user->id) disabled title="No puedes desactivar tu propia cuenta" @endif>
                                        @if($user->estado === 'activo')
                                            ⏹️ Desactivar
                                        @else
                                            ▶️ Activar
                                        @endif
                                    </button>
                                </form>

                                <!-- Botón Ver Perfil -->
                                <a href="{{ route('users.show', $user) }}" 
                                   class="px-3 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition">
                                    👁️ Ver
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-lg">No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($users->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Usuarios</p>
            <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Administradores</p>
            <p class="text-3xl font-bold text-red-600">{{ \App\Models\User::where('tipo', 'admin')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Empleados</p>
            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::where('tipo', 'empleado')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Usuarios Activos</p>
            <p class="text-3xl font-bold text-green-600">{{ \App\Models\User::where('estado', 'activo')->count() }}</p>
        </div>
    </div>
</div>

<!-- Modal para cambiar Rol -->
<div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Cambiar Rol de Usuario</h3>
        
        <form id="roleForm" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="userId">

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nuevo Rol</label>
                <select name="tipo" id="roleSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">-- Selecciona un rol --</option>
                    <option value="empleado">👤 Empleado (Acceso: Mesas/Ventas)</option>
                    <option value="admin">🔐 Administrador (Acceso: Total)</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRoleModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-900 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Cambiar Rol
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRoleModal(userId, currentRole) {
    document.getElementById('userId').value = userId;
    document.getElementById('roleSelect').value = currentRole;
    document.getElementById('roleModal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const userId = document.getElementById('userId').value;
    const form = this;
    form.action = `/users/${userId}/update-tipo`;
    form.submit();
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRoleModal();
    }
});
</script>

@endsection
