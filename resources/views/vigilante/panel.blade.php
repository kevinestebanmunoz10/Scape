@extends('layouts.app')

@section('panel-navbar', true)

@section('title', 'Vigilante - SCAPE')

@push('styles')
    {{-- Carga la hoja de estilos del panel cuando se solicita la pila 'styles' --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    {{-- Contenedor principal del panel con distribución de dashboard (sidebar + contenido) --}}
    <div class="dashboard-shell">
        {{-- Barra lateral de navegación del vigilante --}}
        <aside class="sidebar">
            {{-- Etiqueta de la sección de cuenta del usuario autenticado --}}
            <div class="cuenta-label">USUARIO</div>
            {{-- Menú superior con acciones de la cuenta --}}
            <nav class="menu sidebar-account">
                {{-- Enlace al perfil del vigilante; se marca como activo según la ruta actual --}}
                <a href="{{ route('vigilante.perfil') }}" class="{{ request()->routeIs('vigilante.perfil') ? 'active' : '' }}">
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