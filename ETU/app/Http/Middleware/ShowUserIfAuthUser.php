<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowUserIfAuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $requestedUserId = $request->route('id');

        if ($user->id != $requestedUserId) {
            abort(403, 'Accès refusé : ce n’est pas votre profil.');
        }

        return $next($request);
    }
}
