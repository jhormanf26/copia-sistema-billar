@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="text-dark mb-2 font-weight-bold">
                    <i class="fas fa-users mr-2 text-primary"></i> Gestión de Usuarios
                </h1>
                <p class="text-muted mb-0">Total de usuarios registrados: <strong>{{ $users->total() }}</strong></p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-user-plus mr-2"></i> Crear Usuario
                </a>
                <button class="btn btn-info btn-lg rounded-pill shadow-sm" data-toggle="modal" data-target="#syncSedevarModal">
                    <i class="fas fa-sync mr-2"></i> Sincronizar Sedevar
                </button>
            </div>
        </div>
        <hr class="divider">
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- Mostrar mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Mostrar errores --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i> Listado de Usuarios
                    </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" 
                                   name="table_search" 
                                   class="form-control float-right" 
                                   id="searchUsers"
                                   placeholder="Buscar usuario...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="usersTable">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">
                                    <i class="fas fa-user mr-2"></i> Nombre
                                </th>
                                <th style="width: 15%">
                                    <i class="fas fa-envelope mr-2"></i> Email
                                </th>
                                <th style="width: 15%">
                                    <i class="fas fa-id-card mr-2"></i> Documento
                                </th>
                                <th style="width: 10%">
                                    <i class="fas fa-briefcase mr-2"></i> Tipo
                                </th>
                                <th style="width: 10%">
                                    <i class="fas fa-check-circle mr-2"></i> Estado
                                </th>
                                <th style="width: 15%">
                                    <i class="fas fa-calendar mr-2"></i> Creado
                                </th>
                                <th style="width: 15%">
                                    <i class="fas fa-cogs mr-2"></i> Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-circle text-info mr-2"></i>
                                        <strong>{{ $user->name }} {{ $user->apellidos }}</strong>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}" class="text-muted">
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->numerodocumento }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($user->tipo === 'admin')
                                            <span class="badge badge-danger">
                                                <i class="fas fa-crown mr-1"></i> Administrador
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-briefcase mr-1"></i> Empleado
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->estado === 'activo')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i> Activo
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle mr-1"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted" title="{{ $user->created_at->format('d/m/Y H:i') }}">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('users.show', $user) }}" 
                                               class="btn btn-info" 
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="btn btn-primary" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" 
                                                  method="POST" 
                                                  style="display:inline;" 
                                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2">No hay usuarios registrados</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} usuarios
                            </p>
                        </div>
                        <div class="col-md-6">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Usuarios</span>
                    <span class="info-box-number">{{ $users->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Usuarios Activos</span>
                    <span class="info-box-number">{{ \App\Models\User::where('estado', 'activo')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-crown"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Administradores</span>
                    <span class="info-box-number">{{ \App\Models\User::where('tipo', 'admin')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-briefcase"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Empleados</span>
                    <span class="info-box-number">{{ \App\Models\User::where('tipo', 'empleado')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para sincronizar con Sedevar -->
<div class="modal fade" id="syncSedevarModal" tabindex="-1" role="dialog" aria-labelledby="syncSedevarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('users.syncFromSedevar') }}" method="POST">
                @csrf
                
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="syncSedevarModalLabel">
                        <i class="fas fa-sync mr-2"></i> Sincronizar Usuarios desde Sedevar
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Información:</strong> Esta acción sincronizará todos los usuarios registrados en Sedevar con esta aplicación.
                    </div>

                    <p>
                        Se realizarán las siguientes operaciones:
                    </p>
                    <ul>
                        <li>Se crearán nuevos usuarios que existan en Sedevar</li>
                        <li>Se actualizará la información de usuarios existentes</li>
                        <li>Se mantendrá la integridad de los datos locales</li>
                    </ul>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Nota:</strong> Este proceso puede tomar algunos minutos dependiendo de la cantidad de usuarios.
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-sync mr-2"></i> Sincronizar Ahora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .divider {
        border: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, #007bff, transparent);
        margin: 1.5rem 0;
    }

    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }

    .badge {
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .info-box {
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .info-box-icon {
        border-radius: 0.5rem 0 0 0.5rem;
    }
</style>
@stop

@section('js')
<script>
    // Búsqueda en tiempo real
    document.getElementById('searchUsers').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let table = document.getElementById('usersTable');
        let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@stop
