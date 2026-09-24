@extends('rector.panel')

@section('title', 'Rector - Usuarios')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Usuarios</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Consulta y gestiona las cuentas registradas en el sistema.</p>
        </div>
        <div class="page-header-actions">
            {{-- Formulario de búsqueda por nombre de usuario --}}
            <form class="search-form" method="GET" action="{{ route('rector.usuarios.index') }}">
                {{-- Ícono de lupa --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                {{-- Entrada de texto con el término de búsqueda actual --}}
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre..." autocomplete="off">
                {{-- Si hay búsqueda activa, muestra el enlace para limpiarla --}}
                @if ($buscar !== '')
                    <a href="{{ route('rector.usuarios.index') }}" class="search-clear" title="Limpiar búsqueda">&times;</a>
                @endif
                {{-- Botón que ejecuta la búsqueda --}}
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            {{-- Enlace para crear un nuevo usuario --}}
            <a href="{{ route('rector.usuarios.create') }}" class="btn btn-primary">
                {{-- Ícono de signo más --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nuevo usuario
            </a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene la tabla de usuarios --}}
    <div class="panel">
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="cell-actions">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de usuarios --}}
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            {{-- Documento del usuario --}}
                            <td>{{ $usuario->Documento }}</td>
                            {{-- Nombre del usuario --}}
                            <td>{{ $usuario->Nom_usua }}</td>
                            {{-- Correo del usuario --}}
                            <td>{{ $usuario->email }}</td>
                            {{-- Rol del usuario (o guion medio si no tiene) --}}
                            <td>{{ $usuario->rol?->rol ?? '—' }}</td>
                            <td>
                                {{-- Según el estado numérico (1 = activo) muestra una insignia u otra --}}
                                @if ((int) $usuario->estado?->estado === 1)
                                    <span class="badge badge-active">Activo</span>
                                @else
                                    <span class="badge badge-inactive">Inactivo</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    {{-- Enlace para ver el detalle del usuario --}}
                                    <a href="{{ route('rector.usuarios.show', $usuario) }}" class="icon-btn" title="Ver usuario">
                                        {{-- Ícono de ojo --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    {{-- Enlace para editar el usuario --}}
                                    <a href="{{ route('rector.usuarios.edit', $usuario) }}" class="icon-btn" title="Editar usuario">
                                        {{-- Ícono de lápiz --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay resultados que mostrar --}}
                        <tr>
                            <td colspan="6" class="empty-cell">
                                {{-- Distingue si no hay usuarios o si la búsqueda no arrojó resultados --}}
                                @if ($buscar !== '')
                                    No se encontraron usuarios con el nombre &laquo;{{ $buscar }}&raquo;.
                                @else
                                    No hay usuarios registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación del listado (solo si hay más de una página) --}}
        @if ($usuarios->hasPages())
            <div class="table-pagination">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection