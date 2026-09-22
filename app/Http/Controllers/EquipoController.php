<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\TipoEquipo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $asignacion = (string) $request->query('asignacion', '');

        $equipos = Equipo::with(['tipo', 'marca', 'usuario'])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where('serial_equi', 'like', '%'.$buscar.'%')
                    ->orWhere('Color', 'like', '%'.$buscar.'%')
                    ->orWhereHas('usuario', function ($query) use ($buscar) {
                        $query->where('Nom_usua', 'like', '%'.$buscar.'%')
                            ->orWhere('Documento', 'like', '%'.$buscar.'%');
                    });
            })
            ->when($asignacion === 'sin', fn ($query) => $query->whereNull('Documento'))
            ->when($asignacion === 'con', fn ($query) => $query->whereNotNull('Documento'))
            ->orderBy('serial_equi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.equipos.index', compact('equipos', 'buscar', 'asignacion'));
    }

    public function create(): View
    {
        return view('admin.equipos.create', $this->formData());
    }

    public function store(StoreEquipoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('equipos', 'public');
        }

        Equipo::create($data);

        return redirect()
            ->route('admin.equipos.index')
            ->with('status', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo): View
    {
        $equipo->load(['tipo', 'marca', 'usuario']);

        return view('admin.equipos.show', compact('equipo'));
    }

    public function edit(Equipo $equipo): View
    {
        return view('admin.equipos.edit', array_merge(
            $this->formData(),
            ['equipo' => $equipo],
        ));
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('equipos', 'public');
        } else {
            unset($data['imagen']);
        }

        $equipo->update($data);

        return redirect()
            ->route('admin.equipos.index')
            ->with('status', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo): RedirectResponse
    {
        if ($equipo->prestamos()->exists()) {
            return redirect()
                ->route('admin.equipos.index')
                ->with('error', 'No puedes eliminar un equipo con pr&eacute;stamos registrados.');
        }

        $equipo->delete();

        return redirect()
            ->route('admin.equipos.index')
            ->with('status', 'Equipo eliminado correctamente.');
    }

    private function formData(): array
    {
        return [
            'marcas' => Marca::orderBy('id_marca')->get(),
            'tipos' => TipoEquipo::orderBy('id_t_equip')->get(),
            'usuarios' => User::orderBy('Nom_usua')->get(),
        ];
    }
}
