@php($usuario = $usuario ?? null)

{{-- Cuadrícula de campos del formulario de usuario (alta y edición) --}}
<div class="form-grid">
    {{-- Campo: documento de identidad --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Documento">Documento</label>
        {{-- En edición el documento está deshabilitado; en alta es obligatorio y conserva el valor previo --}}
        <input type="text" inputmode="numeric" id="Documento" name="Documento"
            value="{{ old('Documento', $usuario?->Documento) }}" maxlength="20" @disabled($usuario) @required(! $usuario)>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Documento')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: nombre completo --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Nom_usua">Nombre completo</label>
        {{-- Entrada de texto obligatoria que conserva el valor previo --}}
        <input type="text" id="Nom_usua" name="Nom_usua" value="{{ old('Nom_usua', $usuario?->Nom_usua) }}" maxlength="100" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Nom_usua')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: correo electrónico --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="email">Correo electr&oacute;nico</label>
        {{-- Entrada de correo obligatoria que conserva el valor previo --}}
        <input type="email" id="email" name="email" value="{{ old('email', $usuario?->email) }}" maxlength="100" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: teléfono --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Telefono">Tel&eacute;fono</label>
        {{-- Entrada de texto obligatoria que conserva el valor previo --}}
        <input type="text" id="Telefono" name="Telefono" value="{{ old('Telefono', $usuario?->Telefono) }}" maxlength="20" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Telefono')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: rol del usuario --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_rol">Rol</label>
        {{-- Selector de rol obligatorio --}}
        <select id="id_rol" name="id_rol" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un rol</option>
            {{-- Recorre los roles disponibles y marca el que corresponde al usuario --}}
            @foreach ($roles as $rol)
                <option value="{{ $rol->id_rol }}" @selected((string) old('id_rol', $usuario?->id_rol) === (string) $rol->id_rol)>{{ $rol->rol }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_rol')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: estado del usuario --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_Estado">Estado</label>
        {{-- Selector de estado obligatorio --}}
        <select id="id_Estado" name="id_Estado" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un estado</option>
            {{-- Recorre los estados y marca el correspondiente; muestra "Activo" o "Inactivo" --}}
            @foreach ($estados as $estado)
                <option value="{{ $estado->id_estado }}" @selected((string) old('id_Estado', $usuario?->id_Estado) === (string) $estado->id_estado)>{{ $estado->estado ? 'Activo' : 'Inactivo' }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_Estado')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: ciudad del usuario --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="cod_postal">Ciudad</label>
        {{-- Selector de ciudad obligatorio --}}
        <select id="cod_postal" name="cod_postal" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona una ciudad</option>
            {{-- Recorre las ciudades y marca la correspondiente al usuario --}}
            @foreach ($ciudades as $ciudad)
                <option value="{{ $ciudad->cod_Postal }}" @selected((string) old('cod_postal', $usuario?->cod_postal) === (string) $ciudad->cod_Postal)>{{ $ciudad->Ciudad }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('cod_postal')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: código QR (opcional) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="QR">C&oacute;digo QR</label>
        {{-- Entrada de texto opcional que conserva el valor previo --}}
        <input type="text" id="QR" name="QR" value="{{ old('QR', $usuario?->QR) }}" maxlength="255">
        {{-- Ayuda: si se deja vacío se genera automáticamente --}}
        <span class="form-hint">Opcional. Si lo dejas vac&iacute;o se genera autom&aacute;ticamente.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('QR')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: contraseña (ocupa el ancho completo de la cuadrícula) --}}
    <div class="form-field full">
        {{-- Etiqueta del campo --}}
        <label for="Contrasena">Contrase&ntilde;a</label>
        {{-- Solo es obligatoria en alta; en edición se puede dejar en blanco --}}
        <input type="password" id="Contrasena" name="Contrasena" autocomplete="new-password" @required(! $usuario)>
        {{-- Ayuda contextual según se trate de alta o de edición --}}
        <span class="form-hint">{{ $usuario ? 'D&eacute;jala en blanco para conservar la contrase&ntilde;a actual.' : 'M&iacute;nimo 6 caracteres.' }}</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Contrasena')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>