@extends('profesor.panel')

@section('title', 'Profesor - Dashboard')

@section('panel-content')
    {{-- Encabezado con el rango de fechas del mes en curso --}}
    <div class="header-row">
        <div class="date-filter">
            Showing data:
            <div class="date-box">
                {{-- Muestra el periodo actual: desde el primer día del mes hasta hoy --}}
                {{ now()->startOfMonth()->format('d M, Y') }} - {{ now()->format('d M, Y') }}
                {{-- Ícono decorativo de calendario --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            </div>
        </div>
    </div>

    {{-- Cuadrícula de tarjetas con estadísticas de ejemplo --}}
    <section class="stats-grid">
        {{-- Tarjeta: asistencia global --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Asistencia global</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">94%</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">Promedio del mes</div>
        </div>
        {{-- Tarjeta: inasistencias del día --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Inasistencias del d&iacute;a</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">12</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">4 sin justificar</div>
        </div>
        {{-- Tarjeta: justificantes --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Justificantes</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">8</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">3 pendientes de revisi&oacute;n</div>
        </div>
        {{-- Tarjeta: puntualidad por bloques --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Puntualidad por bloques</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">87%</div>
            {{-- Subtítulo vacío para mantener la altura consistente --}}
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    {{-- Cuadrícula inferior con paneles informativos --}}
    <section class="bottom-grid">
        {{-- Panel: puntualidad por bloques --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <div>
                    {{-- Título del panel --}}
                    <h2>Puntualidad por bloques</h2>
                    {{-- Rango de fechas del mes en curso --}}
                    <span class="date-range">From {{ now()->startOfMonth()->format('d') }}-{{ now()->format('d') }} {{ now()->format('M, Y') }}</span>
                </div>
                {{-- Enlace "Ver detalle" (aún sin destino definido) --}}
                <div class="link">Ver detalle</div>
            </div>
            <div class="bloques-list">
                {{-- Bloque PHP: define los datos de ejemplo de los bloques horarios --}}
                @php
                    $bloques = [
                        ['name' => 'Bloque 1', 'time' => '07:00 - 09:00', 'value' => 92],
                        ['name' => 'Bloque 2', 'time' => '09:10 - 11:10', 'value' => 85],
                        ['name' => 'Bloque 3', 'time' => '11:20 - 13:20', 'value' => 78],
                        ['name' => 'Bloque 4', 'time' => '14:00 - 16:00', 'value' => 90],
                    ];
                @endphp
                {{-- Recorre los bloques y dibuja su barra de puntualidad --}}
                @foreach ($bloques as $bloque)
                    {{-- Elemento de bloque con nombre, horario y porcentaje --}}
                    <div class="bloque-item">
                        <div class="bloque-head">
                            {{-- Nombre del bloque y horario asociado --}}
                            <span>{{ $bloque['name'] }} <span class="bloque-time">{{ $bloque['time'] }}</span></span>
                            {{-- Porcentaje de puntualidad; resaltado en rojo si es inferior a 80 --}}
                            <span class="{{ $bloque['value'] < 80 ? 'bloque-value bad' : 'bloque-value' }}">{{ $bloque['value'] }}%</span>
                        </div>
                        {{-- Barra de progreso que refleja el porcentaje del bloque --}}
                        <div class="bloque-bar">
                            <div class="bloque-fill {{ $bloque['value'] < 80 ? 'fill-bad' : '' }}" style="width: {{ $bloque['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Panel: inasistencias por grupo (datos de ejemplo) --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <h2>Inasistencias por grupo</h2>
            </div>
            {{-- Lista de grupos con sus inasistencias --}}
            <div class="prestamos-list">
                {{-- Grupo con estado malo --}}
                <div class="prestamo-item">
                    <span>Grado 6&ordm; A &mdash; 4 inasistencias</span>
                    {{-- Ícono de estado incorrecto (equis) --}}
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                {{-- Grupo con estado malo --}}
                <div class="prestamo-item">
                    <span>Grado 7&ordm; B &mdash; 3 inasistencias</span>
                    {{-- Ícono de estado incorrecto (equis) --}}
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                {{-- Grupo con estado correcto --}}
                <div class="prestamo-item">
                    <span>Grado 8&ordm; A &mdash; 2 inasistencias</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                {{-- Grupo con estado correcto --}}
                <div class="prestamo-item">
                    <span>Grado 9&ordm; A &mdash; 2 inasistencias</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                {{-- Grupo con estado correcto --}}
                <div class="prestamo-item">
                    <span>Grado 10&ordm; B &mdash; 1 inasistencia</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection