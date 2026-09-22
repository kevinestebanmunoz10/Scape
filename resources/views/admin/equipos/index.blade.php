@extends('admin.panel')

@section('title', 'Administrador - Equipos')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Equipos</h1>
            <p class="subtitle">Administra los equipos registrados en el sistema.</p>
        </div>
        <div class="page-header-actions">
            <form class="search-form" method="GET" action="{{ route('admin.equipos.index') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por serial, color o usuario..." autocomplete="off">
                @if ($buscar !== '' || $asignacion !== '')
                    <a href="{{ route('admin.equipos.index') }}" class="search-clear" title="Limpiar filtros">&times;</a>
                @endif
                <select name="asignacion" class="filter-select" aria-label="Filtro de asignaci&oacute;n">
                    <option value="">Todas las asignaciones</option>
                    <option value="sin" @selected($asignacion === 'sin')>Solo sin asignar</option>
                    <option value="con" @selected($asignacion === 'con')>Solo asignados</option>
                </select>
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            <a href="{{ route('admin.equipos.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nuevo equipo
            </a>
        </div>
    </div>

    @include('partials.alertas')

    <div class="panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Usuario</th>
                        <th>Imagen</th>
                        <th class="cell-actions">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->serial_equi }}</td>
                            <td>{{ $equipo->tipo?->tipo ?? '—' }}</td>
                            <td>{{ $equipo->marca?->marca ?? '—' }}</td>
                            <td>{{ $equipo->Color ?? '—' }}</td>
                            <td>{{ $equipo->usuario?->Nom_usua ?? '—' }}</td>
                            <td>
                                @if ($equipo->imagen)
                                    <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                                        alt="Imagen del equipo" class="equipo-img-thumb">
                                @else
                                    <span class="profile-value">—</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    <a href="{{ route('admin.equipos.show', $equipo) }}" class="icon-btn" title="Ver equipo">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.equipos.edit', $equipo) }}" class="icon-btn" title="Editar equipo">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este equipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Eliminar equipo">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-cell">
                                @if ($buscar !== '' || $asignacion !== '')
                                    No se encontraron equipos con los filtros aplicados.
                                @else
                                    No hay equipos registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($equipos->hasPages())
            <div class="table-pagination">{{ $equipos->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection