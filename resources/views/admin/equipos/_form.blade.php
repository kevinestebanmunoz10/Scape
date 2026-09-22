@php($equipo = $equipo ?? null)

<div class="form-grid">
    <div class="form-field">
        <label for="serial_equi">Serial</label>
        <input type="text" id="serial_equi" name="serial_equi"
            value="{{ old('serial_equi', $equipo?->serial_equi) }}" maxlength="50" @disabled($equipo) @required(! $equipo)>
        <span class="form-hint">{{ $equipo ? 'El serial no se puede modificar.' : 'Identificador &uacute;nico del equipo.' }}</span>
        @error('serial_equi')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="id_t_equip">Tipo de equipo</label>
        <select id="id_t_equip" name="id_t_equip" required>
            <option value="">Selecciona un tipo</option>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->id_t_equip }}" @selected((string) old('id_t_equip', $equipo?->id_t_equip) === (string) $tipo->id_t_equip)>{{ $tipo->tipo }}</option>
            @endforeach
        </select>
        @error('id_t_equip')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="id_Marca">Marca</label>
        <select id="id_Marca" name="id_Marca" required>
            <option value="">Selecciona una marca</option>
            @foreach ($marcas as $marca)
                <option value="{{ $marca->id_marca }}" @selected((string) old('id_Marca', $equipo?->id_Marca) === (string) $marca->id_marca)>{{ $marca->marca }}</option>
            @endforeach
        </select>
        @error('id_Marca')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="Color">Color</label>
        <input type="text" id="Color" name="Color" value="{{ old('Color', $equipo?->Color) }}" maxlength="30">
        @error('Color')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-field">
        <label for="imagen">Imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">
        <span class="form-hint">Opcional. JPG, PNG o WEBP de hasta 2&nbsp;MB.</span>
        @error('imagen')<span class="form-error">{{ $message }}</span>@enderror
        @if ($equipo?->imagen)
            <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                alt="Imagen del equipo" class="equipo-img-preview">
        @endif
    </div>

    <div class="form-field full">
        <label for="Documento">Usuario asignado</label>
        <input type="text" id="usuarioBusqueda" class="select-search" placeholder="Filtrar usuario por nombre o documento..." autocomplete="off">
        <select id="Documento" name="Documento" class="select-filter">
            <option value="">Sin asignar</option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->Documento }}" @selected((string) old('Documento', $equipo?->Documento) === (string) $usuario->Documento)>
                    {{ $usuario->Nom_usua }} &mdash; {{ $usuario->Documento }}
                </option>
            @endforeach
        </select>
        <span class="form-hint">Opcional. Usuario al que se le asigna este equipo.</span>
        @error('Documento')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            var input = document.getElementById('usuarioBusqueda');
            var select = document.getElementById('Documento');

            if (!input || !select) {
                return;
            }

            input.addEventListener('input', function() {
                var query = input.value.trim().toLowerCase();

                Array.prototype.forEach.call(select.options, function(option) {
                    if (option.value === '') {
                        option.hidden = false;
                        return;
                    }

                    option.hidden = query !== '' && option.text.toLowerCase().indexOf(query) === -1;
                });
            });
        })();
    </script>
@endpush