@extends('profesor.panel')

@section('title', 'Profesor - Dashboard')

@section('panel-content')
    <div class="header-row">
        <div class="date-filter">
            Showing data:
            <div class="date-box">
                {{ now()->startOfMonth()->format('d M, Y') }} - {{ now()->format('d M, Y') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            </div>
        </div>
    </div>

    <section class="stats-grid">
        <div class="stat-card">
            <h3>Asistencia global</h3>
            <div class="value">94%</div>
            <div class="sub">Promedio del mes</div>
        </div>
        <div class="stat-card">
            <h3>Inasistencias del d&iacute;a</h3>
            <div class="value">12</div>
            <div class="sub">4 sin justificar</div>
        </div>
        <div class="stat-card">
            <h3>Justificantes</h3>
            <div class="value">8</div>
            <div class="sub">3 pendientes de revisi&oacute;n</div>
        </div>
        <div class="stat-card">
            <h3>Puntualidad por bloques</h3>
            <div class="value">87%</div>
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    <section class="bottom-grid">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2>Puntualidad por bloques</h2>
                    <span class="date-range">From {{ now()->startOfMonth()->format('d') }}-{{ now()->format('d') }} {{ now()->format('M, Y') }}</span>
                </div>
                <div class="link">Ver detalle</div>
            </div>
            <div class="bloques-list">
                @php
                    $bloques = [
                        ['name' => 'Bloque 1', 'time' => '07:00 - 09:00', 'value' => 92],
                        ['name' => 'Bloque 2', 'time' => '09:10 - 11:10', 'value' => 85],
                        ['name' => 'Bloque 3', 'time' => '11:20 - 13:20', 'value' => 78],
                        ['name' => 'Bloque 4', 'time' => '14:00 - 16:00', 'value' => 90],
                    ];
                @endphp
                @foreach ($bloques as $bloque)
                    <div class="bloque-item">
                        <div class="bloque-head">
                            <span>{{ $bloque['name'] }} <span class="bloque-time">{{ $bloque['time'] }}</span></span>
                            <span class="{{ $bloque['value'] < 80 ? 'bloque-value bad' : 'bloque-value' }}">{{ $bloque['value'] }}%</span>
                        </div>
                        <div class="bloque-bar">
                            <div class="bloque-fill {{ $bloque['value'] < 80 ? 'fill-bad' : '' }}" style="width: {{ $bloque['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Inasistencias por grupo</h2>
            </div>
            <div class="prestamos-list">
                <div class="prestamo-item">
                    <span>Grado 6&ordm; A &mdash; 4 inasistencias</span>
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Grado 7&ordm; B &mdash; 3 inasistencias</span>
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Grado 8&ordm; A &mdash; 2 inasistencias</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Grado 9&ordm; A &mdash; 2 inasistencias</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Grado 10&ordm; B &mdash; 1 inasistencia</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection