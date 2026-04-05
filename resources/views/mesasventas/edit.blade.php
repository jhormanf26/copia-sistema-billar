@extends('adminlte::page')

@section('title', 'Editar Mesa')

@section('content_header')
    <h1><i class="fas fa-edit"></i> Editar Mesa #{{ $mesa->numeromesa }}</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('mesas.update', $mesa->idmesa) }}" method="POST">
        @csrf
    
        <div class="form-group">
            <label for="numeromesa">Número de Mesa</label>
            <input type="text" class="form-control" name="numeromesa" value="{{ $mesa->numeromesa }}" required>
        </div>

        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select class="form-control" name="tipo" required>
                <option value="pool" {{ $mesa->tipo == 'pool' ? 'selected' : '' }}>Pool</option>
                <option value="tresbandas" {{ $mesa->tipo == 'tresbandas' ? 'selected' : '' }}>Tres Bandas</option>
                <option value="libre" {{ $mesa->tipo == 'libre' ? 'selected' : '' }}>Libre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" name="estado" required>
                <option value="disponible" {{ $mesa->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="ocupada" {{ $mesa->estado == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                <option value="reservada" {{ $mesa->estado == 'reservada' ? 'selected' : '' }}>Reservada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Actualizar
        </button>
        <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@stop
