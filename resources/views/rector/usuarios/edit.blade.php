@extends('rector.panel')

@section('title', 'Rector - Editar usuario')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>Editar usuario</h1>
            <p class="subtitle">Actualiza la informaci&oacute;n de {{ $usuario->Nom_usua }}.</p>
        </div>
        <a href="{{ route('rector.usuarios.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <form method="POST" action="{{ route('rector.usuarios.update', $usuario) }}">
            @csrf
            @method('PUT')

            @include('admin.usuarios._form')

            <div class="form-actions">
                <a href="{{ route('rector.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection