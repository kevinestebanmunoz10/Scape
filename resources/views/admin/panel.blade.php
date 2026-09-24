@extends('layouts.app')

@section('panel-navbar', true)

@section('title', 'Administrador - SCAPE')

@push('styles')
    {{-- Carga la hoja de estilos del panel cuando se solicita la pila 'styles' --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    {{-- Contenedor principal del panel con distribución de dashboard (sidebar + contenido) --}}
    <div class="dashboard-shell">
        {{-- Barra lateral de navegación del administrador --}}
        <aside class="sidebar">
            {{-- Etiqueta de la sección de cuenta del usuario autenticado --}}
            <div class="cuenta-label">USUARIO</div>
            {{-- Menú superior con acciones de la cuenta --}}
            <nav class="menu sidebar-account">
                {{-- Enlace al perfil del administrador; se marca como activo según la ruta actual --}}
                <a href="{{ route('admin.perfil') }}" class="{{ request()->routeIs('admin.perfil') ? 'active' : '' }}">
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

            {{-- Menú principal de navegación del panel de administración --}}
            <nav class="menu sidebar-main">
                {{-- Acceso al dashboard; resalta el enlace si estamos en la ruta correspondiente --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    {{-- Ícono de inicio (dashboard) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 4l9 6.5"/><path d="M5 9.5V20h14V9.5"/></svg>
                    Dashboard
                </a>
                {{-- Acceso a la gestión de usuarios; activo en cualquier ruta admin.usuarios.* --}}
                <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                    {{-- Ícono de usuarios --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg>
                    Usuarios
                </a>
                {{-- Acceso al módulo de accesos; activo solo en la ruta admin.accesos --}}
                <a href="{{ route('admin.accesos') }}" class="{{ request()->routeIs('admin.accesos*') ? 'active' : '' }}">
                    {{-- Ícono de registros de acceso --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="4" rx="1"/><rect x="3" y="14" width="18" height="4" rx="1"/></svg>
                    Accesos
                </a>
                {{-- Acceso a la gestión de equipos; activo en cualquier ruta admin.equipos.* --}}
                <a href="{{ route('admin.equipos.index') }}" class="{{ request()->routeIs('admin.equipos.*') ? 'active' : '' }}">
                    {{-- Ícono de equipos (dispositivo) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="12" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    Equipos
                </a>
                {{-- Enlace de permisos de salida; activo en cualquier ruta admin.permisos.* --}}
                <a href="{{ route('admin.permisos') }}" class="{{ request()->routeIs('admin.permisos*') ? 'active' : '' }}">
                    {{-- Ícono de permisos --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 7a3 3 0 1 1 0 6"/><path d="M15 11a3 3 0 1 1 0 6"/><path d="M6 20l12-12"/></svg>
                    Permisos
                </a>
                {{-- Enlace de gestión (aún sin destino definido) --}}
                <a href="#">
                    {{-- Ícono de útiles de gestión --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L4 17v3h3l5.3-5.3a4 4 0 0 1 5.4-5.4l-3 3-2-2z"/></svg>
                    Gesti&oacute;n
                </a>
                {{-- Enlace de reportes (aún sin destino definido) --}}
                <a href="#">
                    {{-- Ícono de documento/reportes --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M9 13h6M9 17h6"/></svg>
                    Reportes
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