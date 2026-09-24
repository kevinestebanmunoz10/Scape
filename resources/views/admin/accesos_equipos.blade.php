@extends('admin.panel')

@section('title', 'Administrador - Accesos de equipos')

@section('panel-content')
    {{-- Pestañas para alternar entre las vistas de accesos de personas y equipos --}}
    <nav class="access-tabs">
        {{-- Pestaña inactiva: accesos de personas --}}
        <a href="{{ route('admin.accesos') }}" class="{{ request()->routeIs('admin.accesos') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Accesos de personas
        </a>
        {{-- Pestaña activa: accesos de equipos --}}
        <a href="{{ route('admin.accesos.equipos') }}" class="{{ request()->routeIs('admin.accesos.equipos') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="12" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Accesos de equipos
        </a>
    </nav>

    {{-- Encabezado con la barra de filtros del módulo de accesos de equipos --}}
    <div class="header-row">
        {{-- Formulario GET que filtra los accesos de equipos por fechas y serial --}}
        <form class="filter-bar" method="GET" action="{{ route('admin.accesos.equipos') }}">
            {{-- Rango de fechas para filtrar los accesos --}}
            <div class="date-box">
                {{-- Fecha inicial del rango --}}
                <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" max="{{ $hasta->format('Y-m-d') }}">
                {{-- Separador visual entre fechas --}}
                <span class="date-sep">&mdash;</span>
                {{-- Fecha final del rango --}}
                <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" min="{{ $desde->format('Y-m-d') }}">
            </div>

            {{-- Campo de búsqueda por serial; conserva el valor actual --}}
            <input type="search" name="buscar" class="filter-input" placeholder="Buscar serial del equipo" value="{{ $buscar }}">

            {{-- Botón que aplica los filtros --}}
            <button type="submit" class="date-apply">Filtrar</button>
            {{-- Enlace limpio que restablece el listado sin filtros --}}
            <a href="{{ route('admin.accesos.equipos') }}" class="filter-clear">Limpiar</a>
        </form>
    </div>

    {{-- Cuadrícula de tarjetas con estadísticas de accesos de equipos --}}
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
        {{-- Tarjeta: equipos que siguen dentro --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Dentro</h3>
            {{-- Valor principal: accesos de entrada --}}
            <div class="value">{{ $dentro }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">Sin salida registrada</div>
        </div>
        {{-- Tarjeta: equipos que ya salieron --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Salidas</h3>
            {{-- Valor principal: accesos de salida --}}
            <div class="value">{{ $salidas }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">Con salida registrada</div>
        </div>
        {{-- Tarjeta: equipos distintos que ingresaron --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Equipos únicos</h3>
            {{-- Valor principal: equipos distintos --}}
            <div class="value">{{ $equiposUnicos }}</div>
            {{-- Subtítulo descriptivo --}}
            <div class="sub">Seriales distintos</div>
        </div>
    </section>

    {{-- Panel con el registro tabular de los accesos de equipos --}}
    <section class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Registro de accesos de equipos</h2>
                {{-- Rango de fechas de los datos mostrados --}}
                <span class="date-range">Del {{ $desde->format('d M, Y') }} al {{ $hasta->format('d M, Y') }}</span>
            </div>
            {{-- Total de resultados encontrados --}}
            <div class="link">{{ $totalAccesos }} resultado(s)</div>
        </div>

        {{-- Tabla de accesos de equipos --}}
        <table>
            {{-- Encabezados de columna --}}
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            {{-- Cuerpo: recorre la lista de accesos de equipos --}}
            <tbody>
                @forelse ($equipos as $acceso)
                    <tr>
                        {{-- Serial del equipo --}}
                        <td>{{ $acceso->serial }}</td>
                        {{-- Tipo de equipo, o guion medio si está vacío --}}
                        <td>{{ $acceso->tipo ?? '—' }}</td>
                        {{-- Marca del equipo, o guion medio si está vacía --}}
                        <td>{{ $acceso->marca ?? '—' }}</td>
                        {{-- Color del equipo, o guion medio si está vacío --}}
                        <td>{{ $acceso->color ?? '—' }}</td>
                        {{-- Responsable del acceso (usuario), o guion medio si está vacío --}}
                        <td>{{ $acceso->nombre ?? '—' }}</td>
                        {{-- Fecha de entrada formateada --}}
                        <td>{{ \Carbon\Carbon::parse($acceso->f_entrada)->format('d M, Y') }}</td>
                        {{-- Hora de entrada formateada --}}
                        <td>{{ \Carbon\Carbon::parse($acceso->f_entrada)->format('h:i a') }}</td>
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
                    {{-- Mensaje cuando no hay accesos de equipos en el rango --}}
                    <tr>
                        <td colspan="9">No hay accesos de equipos registrados en este rango</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación del listado de accesos (solo si hay más de una página) --}}
        @if ($equipos->hasPages())
            <div class="pagination-wrap">
                {{-- Renderiza los enlaces de paginación con el estilo de Bootstrap 5 --}}
                {{ $equipos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection