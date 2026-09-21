@extends('rector.panel')

@section('title', 'Rector - Dashboard')

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
            <h3>Usuarios activos</h3>
            <div class="value">438</div>
            <div class="sub">12 nuevos esta semana</div>
        </div>
        <div class="stat-card">
            <h3>Accesos hoy</h3>
            <div class="value">312</div>
            <div class="sub">198 entradas | 114 salidas</div>
        </div>
        <div class="stat-card">
            <h3>Pr&eacute;stamos activos</h3>
            <div class="value">27</div>
            <div class="sub">4 Vencidos</div>
        </div>
        <div class="stat-card">
            <h3>Visitantes hoy</h3>
            <div class="value">9</div>
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    <section class="bottom-grid">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2>&Uacute;ltimos accesos</h2>
                    <span class="date-range">From {{ now()->startOfMonth()->format('d') }}-{{ now()->format('d') }} {{ now()->format('M, Y') }}</span>
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
                    <tr>
                        <td>Juan Duarte</td>
                        <td>10000000001</td>
                        <td>09:02 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                    <tr>
                        <td>Sara Lopez</td>
                        <td>10000000002</td>
                        <td>09:01 a.m</td>
                        <td>Visitante</td>
                    </tr>
                    <tr>
                        <td>Mario Vargas</td>
                        <td>10000000003</td>
                        <td>09:01 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                    <tr>
                        <td>Camila Ruiz</td>
                        <td>10000000004</td>
                        <td>09:00 a.m</td>
                        <td>Visitante</td>
                    </tr>
                    <tr>
                        <td>Felipe Bravo</td>
                        <td>10000000005</td>
                        <td>09:00 a.m</td>
                        <td>Estudiante</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Pr&eacute;stamos por devolver</h2>
            </div>
            <div class="prestamos-list">
                <div class="prestamo-item">
                    <span>Port&aacute;til DELL - SN - 4032</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Port&aacute;til APPLE - SN - 9065</span>
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Port&aacute;til LENOVO - SN - 2206</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Port&aacute;til ACER - SN - 5096</span>
                    <span class="status-icon status-ok">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                </div>
                <div class="prestamo-item">
                    <span>Port&aacute;til ASUS - SN - 2394</span>
                    <span class="status-icon status-bad">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection
