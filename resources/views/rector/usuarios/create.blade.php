@extends('rector.panel')

@section('title', 'Rector - Nuevo usuario')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Nuevo usuario</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Registra una nueva cuenta en el sistema.</p>
        </div>
        {{-- Enlace para regresar al listado de usuarios --}}
        <a href="{{ route('rector.usuarios.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de alta --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST a la ruta de almacenamiento --}}
        <form method="POST" action="{{ route('rector.usuarios.store') }}">
            {{-- Token CSRF de Laravel --}}
            @csrf

            {{-- Reutiliza el formulario de campos de usuario del módulo admin --}}
            @include('admin.usuarios._form')

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('rector.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para crear el usuario --}}
                <button type="submit" class="btn btn-primary">Guardar usuario</button>
            </div>
        </form>
    </div>
@endsection