@extends('admin.panel')

@section('title', 'Administrador - Dashboard')

@section('panel-content')
    <div class="header-row">
        <form class="date-filter" method="GET" action="{{ route('dashboard') }}">
            <label for="desde">Mostrando datos:</label>
            <div class="date-box">
                <input type="date" id="desde" name="desde" value="{{ $desde->format('Y-m-d') }}" max="{{ $hasta->format('Y-m-d') }}">
                <span class="date-sep">&mdash;</span>
                <input type="date" id="hasta" name="hasta" value="{{ $hasta->format('Y-m-d') }}" min="{{ $desde->format('Y-m-d') }}">
                <button type="submit" class="date-apply">Filtrar</button>
            </div>
        </form>
    </div>

    <section class="stats-grid">
        <div class="stat-card">
            <h3>Usuarios activos</h3>
            <div class="value">{{ $usuariosActivos }}</div>
            <div class="sub">{{ $usuariosInactivos }} inactivos</div>
        </div>
        <div class="stat-card">
            <h3>Accesos</h3>
            <div class="value">{{ $accesosRango }}</div>
            <div class="sub">{{ $entradas }} entradas | {{ $salidas }} salidas</div>
        </div>
        <div class="stat-card">
            <h3>Pr&eacute;stamos activos</h3>
            <div class="value">{{ $prestamosActivos }}</div>
            <div class="sub">{{ $prestamosVencidos }} Vencidos</div>
        </div>
        <div class="stat-card">
            <h3>Visitantes</h3>
            <div class="value">{{ $visitantesRango }}</div>
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    <section class="bottom-grid">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2>&Uacute;ltimos accesos</h2>
                    <span class="date-range">Del {{ $desde->format('d M, Y') }} al {{ $hasta->format('d M, Y') }}</span>
                </div>
                <div class="link">Historial completo</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Documento</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimosAccesos as $acceso)
                        <tr>
                            <td>{{ $acceso->nombre ?? '—' }}</td>
                            <td>{{ $acceso->documento ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($acceso->fecha)->format('h:i a') }}</td>
                            <td>{{ $acceso->tipo }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay accesos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Pr&eacute;stamos por devolver</h2>
            </div>
            <div class="prestamos-list">
                @forelse ($prestamosPendientes as $prestamo)
                    @php
                        $vencido = $prestamo->f_Prestamo < today();
                    @endphp
                    <div class="prestamo-item">
                        <span>{{ $prestamo->equipo?->tipo?->tipo ?? 'Equipo' }} {{ $prestamo->equipo?->marca?->marca ?? '' }} - {{ $prestamo->equipo?->serial_equi ?? 'SN' }}</span>
                        <span class="status-icon {{ $vencido ? 'status-bad' : 'status-ok' }}">
                            @if ($vencido)
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="prestamo-item">
                        <span>No hay pr&eacute;stamos por devolver</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
