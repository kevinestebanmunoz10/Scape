@extends('admin.panel')

@section('title', 'Administrador - Sedes')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Sedes</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Administra las sedes de la instituci&oacute;n y la ciudad donde se encuentran.</p>
        </div>
        <div class="page-header-actions">
            {{-- Formulario de búsqueda y filtrado de sedes --}}
            <form class="search-form" method="GET" action="{{ route('admin.sedes.index') }}">
                {{-- Ícono de lupa --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                {{-- Entrada de texto con el término de búsqueda actual --}}
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre o NIT..." autocomplete="off">
                {{-- Selector de estado; se envía junto con la búsqueda en la misma URL --}}
                <select name="estado" class="filter-select">
                    {{-- Opción que muestra todas las sedes sin importar su estado --}}
                    <option value="">Todas</option>
                    {{-- Marca la opción que corresponde al filtro aplicado --}}
                    <option value="1" @selected($estado === '1')>Activas</option>
                    <option value="2" @selected($estado === '2')>Inactivas</option>
                </select>
                {{-- Si hay filtros activos, muestra el enlace para limpiarlos --}}
                @if ($buscar !== '' || $estado !== '')
                    <a href="{{ route('admin.sedes.index') }}" class="search-clear" title="Limpiar filtros">&times;</a>
                @endif
                {{-- Botón que aplica la búsqueda --}}
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            {{-- Enlace para crear una nueva sede --}}
            <a href="{{ route('admin.sedes.create') }}" class="btn btn-primary">
                {{-- Ícono de signo más --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nueva sede
            </a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene la tabla de sedes --}}
    <div class="panel">
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>NIT</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Matr&iacute;culas</th>
                        <th>Estado</th>
                        <th class="cell-actions">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de sedes --}}
                <tbody>
                    @forelse ($sedes as $sede)
                        <tr>
                            {{-- NIT que identifica a la sede --}}
                            <td>{{ $sede->Nit }}</td>
                            {{-- Nombre de la sede --}}
                            <td>{{ $sede->nombre }}</td>
                            {{-- Ciudad donde está ubicada la sede --}}
                            <td>{{ $sede->ciudad ? $sede->ciudad->Ciudad.' — '.$sede->ciudad->cod_Postal : '—' }}</td>
                            {{-- Cantidad de matrículas registradas en la sede --}}
                            <td>{{ $sede->matriculas_count }}</td>
                            <td>
                                {{-- Según el estado numérico (1 = activa) muestra una insignia u otra --}}
                                @if ($sede->esta_activa)
                                    <span class="badge badge-active">Activa</span>
                                @else
                                    <span class="badge badge-inactive">Inactiva</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    {{-- Enlace para ver el detalle de la sede --}}
                                    <a href="{{ route('admin.sedes.show', $sede) }}" class="icon-btn" title="Ver sede">
                                        {{-- Ícono de ojo --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    {{-- Enlace para editar la sede --}}
                                    <a href="{{ route('admin.sedes.edit', $sede) }}" class="icon-btn" title="Editar sede">
                                        {{-- Ícono de lápiz --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    {{-- Si la sede está activa, ofrece desactivarla; si no, reactivarla --}}
                                    @if ($sede->esta_activa)
                                        {{-- Formulario que desactiva la sede (envío DELETE) con confirmación previa --}}
                                        <form method="POST" action="{{ route('admin.sedes.destroy', $sede) }}" onsubmit="return confirm('&iquest;Seguro que deseas desactivar esta sede?');">
                                            {{-- Token CSRF de Laravel --}}
                                            @csrf
                                            {{-- Método HTTP DELETE simulado --}}
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn danger" title="Desactivar sede">
                                                {{-- Ícono de papelera --}}
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Formulario que reactiva una sede inactiva --}}
                                        <form method="POST" action="{{ route('admin.sedes.activar', $sede) }}">
                                            {{-- Token CSRF de Laravel --}}
                                            @csrf
                                            <button type="submit" class="icon-btn success" title="Activar sede">
                                                {{-- Ícono de edificio con check --}}
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V5l7-3 7 3v16"/><path d="m9 21 3-3 3 3"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay resultados que mostrar --}}
                        <tr>
                            <td colspan="6" class="empty-cell">
                                {{-- Distingue si no hay sedes o si el filtro no arrojó resultados --}}
                                @if ($buscar !== '' || $estado !== '')
                                    No se encontraron sedes con los filtros aplicados.
                                @else
                                    No hay sedes registradas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación del listado (solo si hay más de una página) --}}
        @if ($sedes->hasPages())
            <div class="table-pagination">{{ $sedes->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
