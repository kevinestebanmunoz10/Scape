@extends('admin.panel')

@section('title', 'Administrador - Nuevo equipo')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Nuevo equipo</h1>
            <p class="subtitle">Registra un nuevo equipo en el sistema.</p>
        </div>
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <form method="POST" action="{{ route('admin.equipos.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.equipos._form')

            <div class="form-actions">
                <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar equipo</button>
            </div>
        </form>
    </div>
@endsection