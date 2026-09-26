@extends('admin.panel')

@section('title', 'Administrador - Matrículas')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acciones --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Matr&iacute;culas</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Administra las matrículas de los estudiantes y el salón que ocupan.</p>
        </div>
        <div class="page-header-actions">
            {{-- Formulario de búsqueda y filtrado de matrículas --}}
            <form class="search-form" method="GET" action="{{ route('admin.matriculas.index') }}">
                {{-- Ícono de lupa --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                {{-- Entrada de texto con el término de búsqueda actual --}}
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por grado, salón o estudiante..." autocomplete="off">
                {{-- Si hay búsqueda activa, muestra el enlace para limpiarla --}}
                @if ($buscar !== '')
                    <a href="{{ route('admin.matriculas.index') }}" class="search-clear" title="Limpiar filtros">&times;</a>
                @endif
                {{-- Botón que aplica la búsqueda --}}
                <button type="submit" class="date-apply">Buscar</button>
            </form>
            {{-- Enlace para crear una nueva matrícula --}}
            <a href="{{ route('admin.matriculas.create') }}" class="btn btn-primary">
                {{-- Ícono de signo más --}}
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nueva matrícula
            </a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene la tabla de matrículas --}}
    <div class="panel">
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Estudiante</th>
                        <th>Grado</th>
                        <th>Jornada</th>
                        <th>Sal&oacute;n</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de matrículas --}}
                <tbody>
                    @forelse ($matriculas as $matricula)
                        <tr>
                            {{-- Número de la matrícula --}}
                            <td>{{ $matricula->numero_matri }}</td>
                            {{-- Estudiante matriculado (o guion medio si no tiene) --}}
                            <td>{{ $matricula->estudiante?->Nom_usua ?? '—' }}</td>
                            {{-- Grado del estudiante --}}
                            <td>{{ $matricula->grado }}</td>
                            {{-- Jornada escolar (o guion medio si no tiene) --}}
                            <td>{{ $matricula->Jornada ?? '—' }}</td>
                            {{-- Salón asignado con su capacidad (o guion medio si no tiene) --}}
                            <td>{{ $matricula->salon ? $matricula->salon->codigo.' — capacidad '.$matricula->salon->capacidad : '—' }}</td>
                            {{-- Fecha de la matrícula --}}
                            <td>{{ $matricula->fecha_matri?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay resultados que mostrar --}}
                        <tr>
                            <td colspan="6" class="empty-cell">
                                {{-- Distingue si no hay matrículas o si el filtro no arrojó resultados --}}
                                @if ($buscar !== '')
                                    No se encontraron matrículas con los filtros aplicados.
                                @else
                                    No hay matrículas registradas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación del listado (solo si hay más de una página) --}}
        @if ($matriculas->hasPages())
            <div class="table-pagination">{{ $matriculas->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
