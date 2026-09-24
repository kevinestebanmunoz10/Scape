<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Middleware;

// Import de la clase Closure de PHP
use Closure;
// Import de la clase Request de Laravel
use Illuminate\Http\Request;
// Import de la clase Response de Symfony
use Symfony\Component\HttpFoundation\Response;

// Middleware que restringe el acceso exclusivamente a los vigilantes (id_rol = 4)
class EnsureVigilante
{
    /**
     * Allow the request only for guard users (id_rol = 4).
     *
     * @param  Closure(Request): (Response)  $next
     */
    // Método que procesa la solicitud entrante
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // Obtiene el usuario autenticado de la solicitud

        if (! $user) { // Verifica si no hay usuario autenticado
            return redirect()->guest(route('login')); // Redirige al usuario no autenticado a la página de login
        }

        if ((int) $user->id_rol === 4) { // Verifica si el rol del usuario es vigilante
            return $next($request); // Permite continuar con el siguiente middleware o controlador
        }

        if (in_array((int) $user->id_rol, [1, 2, 3], true)) { // Verifica si el rol del usuario es admin, profesor o rector
            return redirect()->route($user->panelRoute()); // Redirige al panel correspondiente a su rol
        }

        abort(403); // Responde con error de acceso prohibido para roles no reconocidos
    }
}
