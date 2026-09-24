@extends('rector.panel')

@section('title', 'Rector - Dashboard')

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
        {{-- Tarjeta: usuarios activos --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Usuarios activos</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">438</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">12 nuevos esta semana</div>
        </div>
        {{-- Tarjeta: accesos del día --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Accesos hoy</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">312</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">198 entradas | 114 salidas</div>
        </div>
        {{-- Tarjeta: préstamos activos --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Pr&eacute;stamos activos</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">27</div>
            {{-- Subtítulo de ejemplo --}}
            <div class="sub">4 Vencidos</div>
        </div>
        {{-- Tarjeta: visitantes del día --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Visitantes hoy</h3>
            {{-- Valor de ejemplo --}}
            <div class="value">9</div>
            {{-- Subtítulo vacío para mantener la altura consistente --}}
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    {{-- Cuadrícula inferior con paneles informativos de ejemplo --}}
    <section class="bottom-grid">
        {{-- Panel: últimos accesos (datos de ejemplo) --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <div>
                    {{-- Título del panel --}}
                    <h2>&Uacute;ltimos accesos</h2>
                    {{-- Range de fechas del mes en curso --}}
                    <span class="date-range">From {{ now()->startOfMonth()->format('d') }}-{{ now()->format('d') }} {{ now()->format('M, Y') }}</span>
                </div>
                {{-- Enlace al historial completo (aún sin destino definido) --}}
                <div class="link">Historial completo</div>
            </div>
            {{-- Tabla con accesos de ejemplo --}}
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Documento</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                {{-- Cuerpo: filas estáticas de ejemplo --}}
                <tbody>
                    {{-- Primer registro de ejemplo --}}
                    <tr>
                        <td>Juan Duarte</td>
                        <td>10000000001</td>
                        <td>09:02 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                    {{-- Segundo registro de ejemplo --}}
                    <tr>
                        <td>Sara Lopez</td>
                        <td>10000000002</td>
                        <td>09:01 a.m</td>
                        <td>Visitante</td>
                    </tr>
                    {{-- Tercer registro de ejemplo --}}
                    <tr>
                        <td>Mario Vargas</td>
                        <td>10000000003</td>
                        <td>09:01 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                    {{-- Cuarto registro de ejemplo --}}
                    <tr>
                        <td>Camila Ruiz</td>
                        <td>10000000004</td>
                        <td>09:00 a.m</td>
                        <td>Visitante</td>
                    </tr>
                    {{-- Quinto registro de ejemplo --}}
                    <tr>
                        <td>Felipe Bravo</td>
                        <td>10000000005</td>
                        <td>09:00 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Panel: préstamos por devolver (datos de ejemplo) --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <h2>Pr&eacute;stamos por devolver</h2>
            </div>
            {{-- Lista de préstamos de ejemplo --}}
            <div class="prestamos-list">
                {{-- Préstamo en plazo --}}
                <div class="prestamo-item">
                    <span>Port&aacute;til DELL - SN - 4032</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                {{-- Préstamo vencido --}}
                <div class="prestamo-item">
                    <span>Port&aacute;til APPLE - SN - 9065</span>
                    {{-- Ícono de estado incorrecto (equis) --}}
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                {{-- Préstamo en plazo --}}
                <div class="prestamo-item">
                    <span>Port&aacute;til LENOVO - SN - 2206</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                {{-- Préstamo en plazo --}}
                <div class="prestamo-item">
                    <span>Port&aacute;til ACER - SN - 5096</span>
                    {{-- Ícono de estado correcto (check) --}}
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                {{-- Préstamo vencido --}}
                <div class="prestamo-item">
                    <span>Port&aacute;til ASUS - SN - 2394</span>
                    {{-- Ícono de estado incorrecto (equis) --}}
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection