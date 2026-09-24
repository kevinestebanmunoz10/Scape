@extends('admin.panel')

@section('title', 'Administrador - Accesos')

@section('panel-content')
    {{-- Encabezado con la barra de filtros del módulo de accesos --}}
    <div class="header-row">
        {{-- Formulario GET que filtra los accesos por fechas, tipo y texto de búsqueda --}}
        <form class="filter-bar" method="GET" action="{{ route('admin.accesos') }}">
            {{-- Rango de fechas para filtrar los accesos --}}
            <div class="date-box">
                {{-- Fecha inicial del rango --}}
                <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" max="{{ $hasta->format('Y-m-d') }}">
                {{-- Separador visual entre fechas --}}
                <span class="date-sep">&mdash;</span>
                {{-- Fecha final del rango --}}
                <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" min="{{ $desde->format('Y-m-d') }}">
            </div>

            {{-- Selector de tipo de acceso (todos, usuarios, estudiantes o visitantes) --}}
            <select name="tipo" class="filter-select">
                <option value="todos" @selected($tipo === 'todos')>Todos</option>
                <option value="usuario" @selected($tipo === 'usuario')>Usuarios</option>
                <option value="estudiante" @selected($tipo === 'estudiante')>Estudiantes</option>
                <option value="visitante" @selected($tipo === 'visitante')>Visitantes</option>
            </select>

            {{-- Campo de búsqueda por nombre o documento; conserva el valor actual --}}
            <input type="search" name="buscar" class="filter-input" placeholder="Buscar nombre o documento" value="{{ $buscar }}">

            {{-- Botón que aplica los filtros --}}
            <button type="submit" class="date-apply">Filtrar</button>
            {{-- Enlace limpio que restablece el listado sin filtros --}}
            <a href="{{ route('admin.accesos') }}" class="filter-clear">Limpiar</a>
        </form>
    </div>

    {{-- Cuadrícula de tarjetas con estadísticas de accesos --}}
    <section class="stats-grid">
        {{-- Tarjeta: total de accesos en el rango --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Total accesos</h3>
            {{-- Valor principal: total de accesos --}}
            <div class="value">{{ $totalAccesos }}</div>
            {{-- Subtítulo: rango de fechas consultado --}}
            <div class="sub">{{ $desde->format('d M, Y') }} - {{ $hasta->format('d M, Y') }}</div>
        </div>
        {{-- Tarjeta: personas que siguen dentro --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Dentro</h3>
            {{-- Valor principal: accesos de entrada --}}
            <div class="value">{{ $entradas }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">Sin salida registrada</div>
        </div>
        {{-- Tarjeta: personas que ya salieron --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Salidas</h3>
            {{-- Valor principal: accesos de salida --}}
            <div class="value">{{ $salidas }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">Con salida registrada</div>
        </div>
        {{-- Tarjeta: visitantes en el rango --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Visitantes</h3>
            {{-- Valor principal: cantidad de visitantes --}}
            <div class="value">{{ $visitantes }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">En el rango</div>
        </div>
    </section>

    {{-- Panel con el registro tabular de los accesos --}}
    <section class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Registro de accesos</h2>
                {{-- Rango de fechas de los datos mostrados --}}
                <span class="date-range">Del {{ $desde->format('d M, Y') }} al {{ $hasta->format('d M, Y') }}</span>
            </div>
            {{-- Total de resultados encontrados --}}
            <div class="link">{{ $totalAccesos }} resultado(s)</div>
        </div>

        {{-- Tabla de accesos --}}
        <table>
            {{-- Encabezados de columna --}}
            <thead>
                <tr>
                    <th>Persona</th>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Ingreso</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            {{-- Cuerpo: recorre la lista de accesos --}}
            <tbody>
                @forelse ($accesos as $acceso)
                    <tr>
                        {{-- Nombre de la persona (o guion medio si está vacío) --}}
                        <td>{{ $acceso->nombre ?? '—' }}</td>
                        {{-- Documento de la persona (o guion medio si está vacío) --}}
                        <td>{{ $acceso->documento ?? '—' }}</td>
                        {{-- Tipo de acceso mostrado como una píldora de color --}}
                        <td><span class="tipo-pill">{{ $acceso->tipo }}</span></td>
                        {{-- Fecha de ingreso formateada --}}
                        <td>{{ \Carbon\Carbon::parse($acceso->f_ingreso)->format('d M, Y') }}</td>
                        {{-- Hora de ingreso formateada --}}
                        <td>{{ \Carbon\Carbon::parse($acceso->f_ingreso)->format('h:i a') }}</td>
                        {{-- Hora de salida, o guion si no hay salida registrada --}}
                        <td>{{ $acceso->f_salida ? \Carbon\Carbon::parse($acceso->f_salida)->format('h:i a') : '—' }}</td>
                        <td>
                            {{-- Si hay fecha de salida muestra "Fuera", en caso contrario "Dentro" --}}
                            @if ($acceso->f_salida)
                                <span class="estado-pill estado-fuera">Fuera</span>
                            @else
                                <span class="estado-pill estado-dentro">Dentro</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    {{-- Mensaje cuando no hay accesos en el rango --}}
                    <tr>
                        <td colspan="7">No hay accesos registrados en este rango</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación del listado de accesos (solo si hay más de una página) --}}
        @if ($accesos->hasPages())
            <div class="pagination-wrap">
                {{-- Renderiza los enlaces de paginación con el estilo de Bootstrap 5 --}}
                {{ $accesos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection