@extends('admin.panel')

@section('title', 'Administrador - Editar sede')

@section('panel-content')
    {{-- Encabezado de la página con el nombre de la sede que se edita --}}
    <div class="page-header">
        <div>
            {{-- Nombre de la sede que se está editando --}}
            <h1>{{ $sede->nombre }}</h1>
            {{-- Subtítulo con el NIT de la sede --}}
            <p class="subtitle">NIT {{ $sede->Nit }}</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para regresar al listado sin guardar --}}
            <a href="{{ route('admin.sedes.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de edición --}}
    <div class="panel form-panel">
        {{-- Formulario que envía los datos al controlador para actualizar la sede --}}
        <form method="POST" action="{{ route('admin.sedes.update', $sede) }}">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Método HTTP PUT simulado --}}
            @method('PUT')

            {{-- Incluye los campos compartidos con el formulario de alta --}}
            @include('admin.sedes._form')

            {{-- Acciones del formulario --}}
            <div class="page-header-actions" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('admin.sedes.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
