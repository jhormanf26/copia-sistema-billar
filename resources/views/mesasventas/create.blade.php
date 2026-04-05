@extends('adminlte::page')

@section('title', 'Nueva Mesa')

@section('content_header')
    <h1><i class="fas fa-plus-circle"></i> Agregar Nueva Mesa</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('mesas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="numeromesa">Número de Mesa</label>
            <input type="text" class="form-control" name="numeromesa" required>
        </div>

        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select class="form-control" name="tipo" required>
                <option value="pool">Pool</option>
                <option value="tresbandas">Tres Bandas</option>
                <option value="libre">Libre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="ocupada">Ocupada</option>
                <option value="reservada">Reservada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar Mesa
        </button>
        <a href="{{ route('mesas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@stop
