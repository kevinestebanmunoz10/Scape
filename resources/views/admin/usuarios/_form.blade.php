@php($usuario = $usuario ?? null)

<div class="form-grid">
    <div class="form-field">
        <label for="Documento">Documento</label>
        <input type="text" inputmode="numeric" id="Documento" name="Documento"
            value="{{ old('Documento', $usuario?->Documento) }}" maxlength="20" @disabled($usuario) @required(! $usuario)>
        @error('Documento')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="Nom_usua">Nombre completo</label>
        <input type="text" id="Nom_usua" name="Nom_usua" value="{{ old('Nom_usua', $usuario?->Nom_usua) }}" maxlength="100" required>
        @error('Nom_usua')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="email">Correo electr&oacute;nico</label>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario?->email) }}" maxlength="100" required>
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="Telefono">Tel&eacute;fono</label>
        <input type="text" id="Telefono" name="Telefono" value="{{ old('Telefono', $usuario?->Telefono) }}" maxlength="20" required>
        @error('Telefono')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="id_rol">Rol</label>
        <select id="id_rol" name="id_rol" required>
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol->id_rol }}" @selected((string) old('id_rol', $usuario?->id_rol) === (string) $rol->id_rol)>{{ $rol->rol }}</option>
            @endforeach
        </select>
        @error('id_rol')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="id_Estado">Estado</label>
        <select id="id_Estado" name="id_Estado" required>
            <option value="">Selecciona un estado</option>
            @foreach ($estados as $estado)
                <option value="{{ $estado->id_estado }}" @selected((string) old('id_Estado', $usuario?->id_Estado) === (string) $estado->id_estado)>{{ $estado->estado ? 'Activo' : 'Inactivo' }}</option>
            @endforeach
        </select>
        @error('id_Estado')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="cod_postal">Ciudad</label>
        <select id="cod_postal" name="cod_postal" required>
            <option value="">Selecciona una ciudad</option>
            @foreach ($ciudades as $ciudad)
                <option value="{{ $ciudad->cod_Postal }}" @selected((string) old('cod_postal', $usuario?->cod_postal) === (string) $ciudad->cod_Postal)>{{ $ciudad->Ciudad }}</option>
            @endforeach
        </select>
        @error('cod_postal')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="QR">C&oacute;digo QR</label>
        <input type="text" id="QR" name="QR" value="{{ old('QR', $usuario?->QR) }}" maxlength="255">
        <span class="form-hint">Opcional. Si lo dejas vac&iacute;o se genera autom&aacute;ticamente.</span>
        @error('QR')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field full">
        <label for="Contrasena">Contrase&ntilde;a</label>
        <input type="password" id="Contrasena" name="Contrasena" autocomplete="new-password" @required(! $usuario)>
        <span class="form-hint">{{ $usuario ? 'D&eacute;jala en blanco para conservar la contrase&ntilde;a actual.' : 'M&iacute;nimo 6 caracteres.' }}</span>
        @error('Contrasena')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>
