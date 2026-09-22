@extends('admin.panel')

@section('title', 'Administrador - Usuarios')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Usuarios</h1>
            <p class="subtitle">Administra las cuentas registradas en el sistema.</p>
        </div>
        <div class="page-header-actions">
            <form class="search-form" method="GET" action="{{ route('admin.usuarios.index') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre..." autocomplete="off">
                @if ($buscar !== '')
                    <a href="{{ route('admin.usuarios.index') }}" class="search-clear" title="Limpiar búsqueda">&times;</a>
                @endif
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nuevo usuario
            </a>
        </div>
    </div>

    @include('partials.alertas')

    <div class="panel">
        <div class="table-wrap">
            <table>
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
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->Documento }}</td>
                            <td>{{ $usuario->Nom_usua }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->rol?->rol ?? '&mdash;' }}</td>
                            <td>
                                @if ((int) $usuario->estado?->estado === 1)
                                    <span class="badge badge-active">Activo</span>
                                @else
                                    <span class="badge badge-inactive">Inactivo</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    <a href="{{ route('admin.usuarios.show', $usuario) }}" class="icon-btn" title="Ver usuario">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="icon-btn" title="Editar usuario">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    @if ((int) $usuario->estado?->estado === 1)
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" onsubmit="return confirm('&iquest;Seguro que deseas desactivar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-btn danger" title="Desactivar usuario">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.usuarios.activar', $usuario) }}">
                                            @csrf
                                            <button type="submit" class="icon-btn success" title="Activar usuario">
                                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-cell">
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

        @if ($usuarios->hasPages())
            <div class="table-pagination">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
