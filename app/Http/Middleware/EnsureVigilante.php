<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVigilante
{
    /**
     * Allow the request only for guard users (id_rol = 4).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ((int) $user->id_rol === 4) {
            return $next($request);
        }

        if (in_array((int) $user->id_rol, [1, 2, 3], true)) {
            return redirect()->route($user->panelRoute());
        }

        abort(403);
    }
}
