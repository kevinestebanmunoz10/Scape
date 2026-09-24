@extends('admin.panel')

@section('title', 'Administrador - Permisos')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Permisos</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Registra los permisos de salida de los estudiantes.</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para administrar los tipos de permiso --}}
            <a href="{{ route('admin.permisos.tipos') }}" class="btn btn-ghost">
                {{-- Ícono de etiqueta/catálogo --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 12 22 2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82Z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                Tipos de permiso
            </a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel con el listado de permisos registrados --}}
    <section class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Permisos registrados</h2>
                {{-- Descripción breve del listado --}}
                <span class="date-range">Permisos de salida autorizados</span>
            </div>
            {{-- Total de permisos encontrados --}}
            <div class="link">{{ $permisos->total() }} permiso(s)</div>
        </div>

        {{-- Tabla de permisos registrados --}}
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Documento</th>
                        <th>Acudiente</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Descripci&oacute;n</th>
                        <th>Autorizado por</th>
                        <th class="cell-actions">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de permisos --}}
                <tbody>
                    @forelse ($permisos as $permiso)
                        <tr>
                            {{-- Nombre del estudiante (o guion si está vacío) --}}
                            <td>{{ $permiso->estudiante ?? '—' }}</td>
                            {{-- Documento del estudiante (o guion si está vacío) --}}
                            <td>{{ $permiso->documento_estudiante ?? '—' }}</td>
                            {{-- Nombre del acudiente (o guion si está vacío) --}}
                            <td>{{ $permiso->acudiente ?? '—' }}</td>
                            {{-- Tipo de permiso --}}
                            <td><span class="tipo-pill">{{ $permiso->tipo }}</span></td>
                            {{-- Fecha del permiso formateada --}}
                            <td>{{ $permiso->fecha ? \Carbon\Carbon::parse($permiso->fecha)->format('d M, Y') : '—' }}</td>
                            {{-- Descripción del permiso (o guion si está vacía) --}}
                            <td class="desc-cell">{{ $permiso->descripcion ?? '—' }}</td>
                            {{-- Nombre de quien autorizó o estado pendiente --}}
                            <td>
                                @if ($permiso->autorizado_por)
                                    {{ $permiso->autorizado_por }}
                                @else
                                    <span class="estado-pill estado-pendiente">Pendiente</span>
                                @endif
                            </td>
                            <td class="cell-actions">
                                <div class="table-actions">
                                    {{-- Enlace para editar el permiso --}}
                                    <a href="{{ route('admin.permisos.edit', $permiso->id_autorizacion) }}" class="icon-btn" title="Editar permiso">
                                        {{-- Ícono de lápiz --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    {{-- Formulario que elimina el permiso con confirmación previa --}}
                                    <form method="POST" action="{{ route('admin.permisos.destroy', $permiso->id_autorizacion) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este permiso?');">
                                        {{-- Token CSRF de Laravel --}}
                                        @csrf
                                        {{-- Método HTTP DELETE simulado --}}
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Eliminar permiso">
                                            {{-- Ícono de papelera --}}
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay permisos registrados --}}
                        <tr>
                            <td colspan="8" class="empty-cell">No hay permisos de salida registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación del listado (solo si hay más de una página) --}}
        @if ($permisos->hasPages())
            <div class="pagination-wrap">
                {{-- Renderiza los enlaces de paginación con el estilo de Bootstrap 5 --}}
                {{ $permisos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection