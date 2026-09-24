@extends('admin.panel')

@section('title', 'Administrador - Detalle de equipo')

@section('panel-content')
    {{-- Encabezado de la página con el serial del equipo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Serial del equipo --}}
            <h1>{{ $equipo->serial_equi }}</h1>
            {{-- Subtítulo con tipo y marca del equipo --}}
            <p class="subtitle">{{ $equipo->tipo?->tipo ?? 'Equipo' }} - {{ $equipo->marca?->marca ?? '—' }}</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para editar el equipo --}}
            <a href="{{ route('admin.equipos.edit', $equipo) }}" class="btn btn-primary">Editar</a>
            {{-- Enlace para regresar al listado --}}
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel principal que resume los datos del equipo --}}
    <div class="panel form-panel">
        <div class="prestamos-list">
            {{-- Fila: serial --}}
            <div class="prestamo-item">
                <span>Serial</span>
                {{-- Serial del equipo --}}
                <span class="profile-value">{{ $equipo->serial_equi }}</span>
            </div>
            {{-- Fila: tipo --}}
            <div class="prestamo-item">
                <span>Tipo</span>
                {{-- Tipo del equipo (o guion medio si no tiene) --}}
                <span class="profile-value">{{ $equipo->tipo?->tipo ?? '—' }}</span>
            </div>
            {{-- Fila: marca --}}
            <div class="prestamo-item">
                <span>Marca</span>
                {{-- Marca del equipo (o guion medio si no tiene) --}}
                <span class="profile-value">{{ $equipo->marca?->marca ?? '—' }}</span>
            </div>
            {{-- Fila: color --}}
            <div class="prestamo-item">
                <span>Color</span>
                {{-- Color del equipo --}}
                <span class="profile-value">{{ $equipo->Color ?? '—' }}</span>
            </div>
            {{-- Fila: imagen --}}
            <div class="prestamo-item">
                <span>Imagen</span>
                {{-- Muestra la imagen del equipo si existe; si no, un guion medio --}}
                @if ($equipo->imagen)
                    {{-- Construye la URL de la imagen: externa o desde el storage local --}}
                    <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                        alt="Imagen del equipo" class="equipo-img-show">
                @else
                    <span class="profile-value">—</span>
                @endif
            </div>
            {{-- Fila: usuario asignado --}}
            <div class="prestamo-item">
                <span>Usuario asignado</span>
                {{-- Nombre del usuario asignado (o guion medio si no tiene) --}}
                <span class="profile-value">{{ $equipo->usuario?->Nom_usua ?? '—' }}</span>
            </div>
        </div>

        {{-- Formulario que elimina el equipo con confirmación previa --}}
        <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}"
            class="delete-block" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este equipo?');">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Método HTTP DELETE simulado --}}
            @method('DELETE')
            {{-- Botón para eliminar el equipo --}}
            <button type="submit" class="btn btn-danger">Eliminar equipo</button>
        </form>
    </div>
@endsection