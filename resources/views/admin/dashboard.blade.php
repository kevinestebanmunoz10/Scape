@extends('layouts.app')

@section('title', 'SCAPE - Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-shell">
        <aside class="sidebar">


            <div class="cuenta-label">USUARIO</div>
            <nav class="menu sidebar-account">
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg>
                    Perfil
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </nav>

            <nav class="menu sidebar-main">
                <a href="{{ route('dashboard') }}" class="active">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 4l9 6.5"/><path d="M5 9.5V20h14V9.5"/></svg>
                    Dashboard
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg>
                    Usuarios
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="4" rx="1"/><rect x="3" y="14" width="18" height="4" rx="1"/></svg>
                    Accesos
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="12" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    Equipos
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7a3 3 0 1 1 0 6"/><path d="M15 11a3 3 0 1 1 0 6"/><path d="M6 20l12-12"/></svg>
                    Permisos
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L4 17v3h3l5.3-5.3a4 4 0 0 1 5.4-5.4l-3 3-2-2z"/></svg>
                    Gesti&oacute;n
                </a>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M9 13h6M9 17h6"/></svg>
                    Reportes
                </a>
            </nav>

            </aside>

        <main class="main">


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
        </main>
    </div>
@endsection