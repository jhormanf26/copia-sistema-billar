@extends('adminlte::page')

@section('title', 'Gestión de Mesas')

@section('content_header')
    <h1><i class="fas fa-table"></i> Gestión de Mesas y Mesas de Consumo</h1>
@stop

@section('content')
<div class="container-fluid">

    {{-- 🔙 Botón volver al Inicio --}}
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Inicio
        </a>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Volver a inventario</a>

        {{-- ✅ Botones para crear nuevas mesas --}}
        <div>
            <a href="{{ route('mesas.create') }}" class="btn btn-success mx-1">
                <i class="fas fa-plus"></i> Nueva Mesa 
            </a>

            
        </div>
    </div>

    <div class="row">

        {{-- 🟢 MESAS  --}}
        @foreach($mesas as $mesa)
            <div class="col-md-4">
                <div class="card {{ $mesa->estado == 'ocupada' ? 'card-danger' : ($mesa->estado == 'reservada' ? 'card-info' : 'card-success') }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Mesa #{{ $mesa->numeromesa }}</h3>
                        <span class="badge bg-secondary">{{ ucfirst($mesa->tipo) }}</span>
                    </div>
                    <div class="card-body text-center">

                        {{-- Imagen --}}
                        <img src="{{ asset('img/mesas/' . $mesa->tipo . '.png') }}" 
                             alt="{{ $mesa->tipo }}" 
                             class="img-fluid mb-3" 
                             style="height: 150px; object-fit: contain;">

                        {{-- ⭐ BOTONES DEBAJO DE LA IMAGEN --}}
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('mesas.edit', $mesa->idmesa) }}" class="btn btn-warning btn-sm mx-1">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('mesas.destroy', $mesa->idmesa) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta mesa?');" class="mx-1">
                                @csrf
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

        

    </div>
</div>
@stop
