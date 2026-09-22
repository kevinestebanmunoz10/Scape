<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Estado;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    //funcion para mostrar lols registros 
    public function index(Request $request): View
    {
        //funcion $buscar para que funcione la barra de busqueda
        $buscar = trim((string) $request->query('buscar', ''));

        //donde se define qué se va a buscar y  en qué orden se va a mostrar
        $usuarios = User::with(['rol', 'estado'])
            ->when($buscar !== '', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%'))
            ->orderByDesc('Documento')
            ->paginate(10)
            ->withQueryString();

        //donde se dice cual vista es la que se va a mostrar despues de  la consulta
        return view('admin.usuarios.index', compact('usuarios', 'buscar'));
    }

    //funcion para mostrar el formulario de creacion
    public function create(): View
    {
        return view('admin.usuarios.create', $this->formData());
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['QR'] = $data['QR'] ?? 'QR-'.$data['Documento'];

        User::create($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function show(User $usuario): View
    {
        $usuario->load(['rol', 'estado']);

        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario): View
    {
        return view('admin.usuarios.edit', array_merge(
            $this->formData(),
            ['usuario' => $usuario],
        ));
    }

    public function update(UpdateUserRequest $request, User $usuario): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['Contrasena'])) {
            unset($data['Contrasena']);
        }

        if (empty($data['QR'])) {
            $data['QR'] = 'QR-'.$usuario->Documento;
        }

        $usuario->update($data);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->is(auth()->user())) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $inactivo = Estado::where('estado', 0)->value('id_estado');

        $usuario->update(['id_Estado' => $inactivo]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('status', 'Usuario desactivado correctamente.');
    }

    public function activar(User $usuario): RedirectResponse
    {
        $activo = Estado::where('estado', 1)->value('id_estado');

        $usuario->update(['id_Estado' => $activo]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('status', 'Usuario activado correctamente.');
    }

    private function formData(): array
    {
        return [
            'roles' => Rol::orderBy('id_rol')->get(),
            'estados' => Estado::orderBy('id_estado')->get(),
            'ciudades' => DB::table('ciudad')->orderBy('Ciudad')->get(),
        ];
    }
}
