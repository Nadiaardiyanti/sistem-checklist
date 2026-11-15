<?php

namespace App\Http\Middleware;

use App\Models\SecurityEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * KSI - Otorisasi: batasi route berdasarkan peran. Pemakaian: ->middleware('role:admin')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            SecurityEvent::record(
                'access_denied',
                $user?->id,
                'Mencoba membuka ' . $request->method() . ' /' . $request->path(),
                $request
            );

            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
