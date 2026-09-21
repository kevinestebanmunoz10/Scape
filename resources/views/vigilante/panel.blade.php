@extends('layouts.app')

@section('panel-navbar', true)

@section('title', 'Vigilante - SCAPE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="cuenta-label">USUARIO</div>
            <nav class="menu sidebar-account">
                <a href="{{ route('vigilante.perfil') }}" class="{{ request()->routeIs('vigilante.perfil') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-4 3-6 7-6s7 2 7 6"/></svg>
                    Mi perfil
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </nav>
        </aside>

        <main class="main">
            @include('partials.sidebar-toggle')

            @yield('panel-content')
        </main>
    </div>
@endsection
