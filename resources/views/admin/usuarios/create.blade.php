@extends('admin.panel')

@section('title', 'Administrador - Nuevo usuario')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Nuevo usuario</h1>
            <p class="subtitle">Registra una nueva cuenta en el sistema.</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf

            @include('admin.usuarios._form')

            <div class="form-actions">
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar usuario</button>
            </div>
        </form>
    </div>
@endsection
