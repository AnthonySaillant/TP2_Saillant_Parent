<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OneCriticPerFilmPerUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $filmId = $request->input('film_id');

        $exists = Critic::where('user_id', $user->id)
                          ->where('film_id', $filmId)
                          ->exists();

        if ($exists) {
            abort(403, 'Vous avez déjà critiqué ce film.');
        }
        
        return $next($request);
    }
}
