@extends('admin.panel')

@section('title', 'Administrador - Detalle de equipo')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>{{ $equipo->serial_equi }}</h1>
            <p class="subtitle">{{ $equipo->tipo?->tipo ?? 'Equipo' }} - {{ $equipo->marca?->marca ?? '—' }}</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.equipos.edit', $equipo) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <div class="prestamos-list">
            <div class="prestamo-item">
                <span>Serial</span>
                <span class="profile-value">{{ $equipo->serial_equi }}</span>
            </div>
            <div class="prestamo-item">
                <span>Tipo</span>
                <span class="profile-value">{{ $equipo->tipo?->tipo ?? '—' }}</span>
            </div>
            <div class="prestamo-item">
                <span>Marca</span>
                <span class="profile-value">{{ $equipo->marca?->marca ?? '—' }}</span>
            </div>
            <div class="prestamo-item">
                <span>Color</span>
                <span class="profile-value">{{ $equipo->Color ?? '—' }}</span>
            </div>
            <div class="prestamo-item">
                <span>Imagen</span>
                @if ($equipo->imagen)
                    <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                        alt="Imagen del equipo" class="equipo-img-show">
                @else
                    <span class="profile-value">—</span>
                @endif
            </div>
            <div class="prestamo-item">
                <span>Usuario asignado</span>
                <span class="profile-value">{{ $equipo->usuario?->Nom_usua ?? '—' }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}"
            class="delete-block" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este equipo?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar equipo</button>
        </form>
    </div>
@endsection