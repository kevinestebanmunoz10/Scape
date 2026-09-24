@extends('layouts.app')

@section('panel-navbar', true)

@section('title', 'Profesor - SCAPE')

@push('styles')
    {{-- Carga la hoja de estilos del panel cuando se solicita la pila 'styles' --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    {{-- Contenedor principal del panel con distribución de dashboard (sidebar + contenido) --}}
    <div class="dashboard-shell">
        {{-- Barra lateral de navegación del profesor --}}
        <aside class="sidebar">
            {{-- Etiqueta de la sección de cuenta del usuario autenticado --}}
            <div class="cuenta-label">USUARIO</div>
            {{-- Menú superior con acciones de la cuenta --}}
            <nav class="menu sidebar-account">
                {{-- Enlace al perfil del profesor; se marca como activo según la ruta actual --}}
                <a href="{{ route('profesor.perfil') }}" class="{{ request()->routeIs('profesor.perfil') ? 'active' : '' }}">
                    {{-- Ícono de usuario de los enlaces del menú --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg>
                    Mi perfil
                </a>
                {{-- Enlace que cierra la sesión previniendo el comportamiento por defecto del enlace --}}
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    {{-- Ícono de salida (logout) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Logout
                </a>
                {{-- Formulario oculto que envía el POST real de logout (contiene el token CSRF) --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </nav>

            {{-- Menú principal de navegación del panel del profesor --}}
            <nav class="menu sidebar-main">
                {{-- Acceso al dashboard; resalta el enlace si estamos en la ruta correspondiente --}}
                <a href="{{ route('profesor.dashboard') }}" class="{{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}">
                    {{-- Ícono de inicio (dashboard) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 4l9 6.5"/><path d="M5 9.5V20h14V9.5"/></svg>
                    Dashboard
                </a>
                {{-- Acceso a la toma de lista; activo solo en la ruta profesor.toma-lista --}}
                <a href="{{ route('profesor.toma-lista') }}" class="{{ request()->routeIs('profesor.toma-lista') ? 'active' : '' }}">
                    {{-- Ícono de lista de verificación --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="5" rx="1"/><path d="M9 4H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-4"/><path d="m9 11 2 2 4-4"/></svg>
                    Toma de lista
                </a>
                {{-- Acceso a fichas y grupos; activo solo en la ruta profesor.fichas-grupos --}}
                <a href="{{ route('profesor.fichas-grupos') }}" class="{{ request()->routeIs('profesor.fichas-grupos') ? 'active' : '' }}">
                    {{-- Ícono de grupos de personas --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Fichas y grupos
                </a>
                {{-- Acceso a reportes y alertas; activo solo en la ruta profesor.reportes-alertas --}}
                <a href="{{ route('profesor.reportes-alertas') }}" class="{{ request()->routeIs('profesor.reportes-alertas') ? 'active' : '' }}">
                    {{-- Ícono de campana de alertas --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    Reportes y alertas
                </a>
                {{-- Acceso a exportar; activo solo en la ruta profesor.exportar --}}
                <a href="{{ route('profesor.exportar') }}" class="{{ request()->routeIs('profesor.exportar') ? 'active' : '' }}">
                    {{-- Ícono de descarga/exportación --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                    Exportar
                </a>
            </nav>
        </aside>

        {{-- Área principal donde se renderiza el contenido específico de cada sección --}}
        <main class="main">
            {{-- Incluye el botón para mostrar/ocultar la barra lateral en vistas móviles --}}
            @include('partials.sidebar-toggle')

            {{-- Renderiza el contenido definido en la sección 'panel-content' de cada vista hija --}}
            @yield('panel-content')
        </main>
    </div>
@endsection