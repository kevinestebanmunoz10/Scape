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
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));

        $usuarios = User::with(['rol', 'estado'])
            ->when($buscar !== '', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%'))
            ->orderByDesc('Documento')
            ->paginate(10)
            ->withQueryString();

        return view('admin.usuarios.index', compact('usuarios', 'buscar'));
    }

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
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('status', 'Usuario eliminado correctamente.');
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
