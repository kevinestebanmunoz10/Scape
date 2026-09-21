@extends('admin.panel')

@section('title', 'Administrador - Accesos')

@section('panel-content')
    <div class="header-row">
        <form class="filter-bar" method="GET" action="{{ route('admin.accesos') }}">
            <div class="date-box">
                <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" max="{{ $hasta->format('Y-m-d') }}">
                <span class="date-sep">&mdash;</span>
                <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" min="{{ $desde->format('Y-m-d') }}">
            </div>

            <select name="tipo" class="filter-select">
                <option value="todos" @selected($tipo === 'todos')>Todos</option>
                <option value="usuario" @selected($tipo === 'usuario')>Usuarios</option>
                <option value="estudiante" @selected($tipo === 'estudiante')>Estudiantes</option>
                <option value="visitante" @selected($tipo === 'visitante')>Visitantes</option>
            </select>

            <input type="search" name="buscar" class="filter-input" placeholder="Buscar nombre o documento" value="{{ $buscar }}">

            <button type="submit" class="date-apply">Filtrar</button>
            <a href="{{ route('admin.accesos') }}" class="filter-clear">Limpiar</a>
        </form>
    </div>

    <section class="stats-grid">
        <div class="stat-card">
            <h3>Total accesos</h3>
            <div class="value">{{ $totalAccesos }}</div>
            <div class="sub">{{ $desde->format('d M, Y') }} - {{ $hasta->format('d M, Y') }}</div>
        </div>
        <div class="stat-card">
            <h3>Dentro</h3>
            <div class="value">{{ $entradas }}</div>
            <div class="sub">Sin salida registrada</div>
        </div>
        <div class="stat-card">
            <h3>Salidas</h3>
            <div class="value">{{ $salidas }}</div>
            <div class="sub">Con salida registrada</div>
        </div>
        <div class="stat-card">
            <h3>Visitantes</h3>
            <div class="value">{{ $visitantes }}</div>
            <div class="sub">En el rango</div>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>Registro de accesos</h2>
                <span class="date-range">Del {{ $desde->format('d M, Y') }} al {{ $hasta->format('d M, Y') }}</span>
            </div>
            <div class="link">{{ $totalAccesos }} resultado(s)</div>
        </div>

        <table>
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
            <tbody>
                @forelse ($accesos as $acceso)
                    <tr>
                        <td>{{ $acceso->nombre ?? '—' }}</td>
                        <td>{{ $acceso->documento ?? '—' }}</td>
                        <td><span class="tipo-pill">{{ $acceso->tipo }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($acceso->f_ingreso)->format('d M, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($acceso->f_ingreso)->format('h:i a') }}</td>
                        <td>{{ $acceso->f_salida ? \Carbon\Carbon::parse($acceso->f_salida)->format('h:i a') : '—' }}</td>
                        <td>
                            @if ($acceso->f_salida)
                                <span class="estado-pill estado-fuera">Fuera</span>
                            @else
                                <span class="estado-pill estado-dentro">Dentro</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay accesos registrados en este rango</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($accesos->hasPages())
            <div class="pagination-wrap">
                {{ $accesos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection
