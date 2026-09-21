<?php

namespace App\Http\Controllers\Rector;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Estado;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UsuariosController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $usuarios = User::with(['rol', 'estado'])
            ->when($buscar !== '', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%'))
            ->orderByDesc('Documento')
            ->paginate(10)
            ->withQueryString();

        return view('rector.usuarios.index', compact('usuarios', 'buscar'));
    }

    public function create(): View
    {
        return view('rector.usuarios.create', $this->formData());
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['QR'] = $data['QR'] ?? 'QR-'.$data['Documento'];

        User::create($data);

        return redirect()
            ->route('rector.usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function show(User $usuario): View
    {
        $usuario->load(['rol', 'estado']);

        return view('rector.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario): View
    {
        return view('rector.usuarios.edit', array_merge(
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
            ->route('rector.usuarios.index')
            ->with('status', 'Usuario actualizado correctamente.');
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
