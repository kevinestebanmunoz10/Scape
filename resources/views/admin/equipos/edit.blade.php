@extends('admin.panel')

@section('title', 'Administrador - Editar equipo')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Editar equipo</h1>
            <p class="subtitle">Actualiza la informaci&oacute;n del equipo {{ $equipo->serial_equi }}.</p>
        </div>
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <form method="POST" action="{{ route('admin.equipos.update', $equipo) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.equipos._form')

            <div class="form-actions">
                <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection