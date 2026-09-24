@extends('admin.panel')

@section('title', 'Administrador - Equipos')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Equipos</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Administra los equipos registrados en el sistema.</p>
        </div>
        <div class="page-header-actions">
            {{-- Formulario de búsqueda y filtrado de equipos --}}
            <form class="search-form" method="GET" action="{{ route('admin.equipos.index') }}">
                {{-- Ícono de lupa --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                {{-- Entrada de texto con el término de búsqueda actual --}}
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por serial, color o usuario..." autocomplete="off">
                {{-- Si hay búsqueda o filtro de asignación activo, muestra el enlace para limpiarlos --}}
                @if ($buscar !== '' || $asignacion !== '')
                    <a href="{{ route('admin.equipos.index') }}" class="search-clear" title="Limpiar filtros">&times;</a>
                @endif
                {{-- Selector para filtrar equipos según su asignación --}}
                <select name="asignacion" class="filter-select" aria-label="Filtro de asignaci&oacute;n">
                    <option value="">Todas las asignaciones</option>
                    <option value="sin" @selected($asignacion === 'sin')>Solo sin asignar</option>
                    <option value="con" @selected($asignacion === 'con')>Solo asignados</option>
                </select>
                {{-- Botón que aplica la búsqueda --}}
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            {{-- Enlace para crear un nuevo equipo --}}
            <a href="{{ route('admin.equipos.create') }}" class="btn btn-primary">
                {{-- Ícono de signo más --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nuevo equipo
            </a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene la tabla de equipos --}}
    <div class="panel">
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
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
                {{-- Cuerpo: recorre la lista de equipos --}}
                <tbody>
                    @forelse ($equipos as $equipo)
                        <tr>
                            {{-- Serial del equipo --}}
                            <td>{{ $equipo->serial_equi }}</td>
                            {{-- Tipo del equipo (o guion medio si no tiene) --}}
                            <td>{{ $equipo->tipo?->tipo ?? '—' }}</td>
                            {{-- Marca del equipo (o guion medio si no tiene) --}}
                            <td>{{ $equipo->marca?->marca ?? '—' }}</td>
                            {{-- Color del equipo --}}
                            <td>{{ $equipo->Color ?? '—' }}</td>
                            {{-- Usuario asignado (o guion medio si no tiene) --}}
                            <td>{{ $equipo->usuario?->Nom_usua ?? '—' }}</td>
                            <td>
                                {{-- Muestra la imagen del equipo si existe; si no, un guion medio --}}
                                @if ($equipo->imagen)
                                    {{-- Construye la URL de la imagen: externa o desde el storage local --}}
                                    <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                                        alt="Imagen del equipo" class="equipo-img-thumb">
                                @else
                                    <span class="profile-value">—</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    {{-- Enlace para ver el detalle del equipo --}}
                                    <a href="{{ route('admin.equipos.show', $equipo) }}" class="icon-btn" title="Ver equipo">
                                        {{-- Ícono de ojo --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    {{-- Enlace para editar el equipo --}}
                                    <a href="{{ route('admin.equipos.edit', $equipo) }}" class="icon-btn" title="Editar equipo">
                                        {{-- Ícono de lápiz --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    {{-- Formulario que elimina el equipo con confirmación previa --}}
                                    <form method="POST" action="{{ route('admin.equipos.destroy', $equipo) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este equipo?');">
                                        {{-- Token CSRF de Laravel --}}
                                        @csrf
                                        {{-- Método HTTP DELETE simulado --}}
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Eliminar equipo">
                                            {{-- Ícono de papelera --}}
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay resultados que mostrar --}}
                        <tr>
                            <td colspan="8" class="empty-cell">
                                {{-- Distingue si no hay equipos o si los filtros no arrojaron resultados --}}
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

        {{-- Paginación del listado (solo si hay más de una página) --}}
        @if ($equipos->hasPages())
            <div class="table-pagination">{{ $equipos->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection