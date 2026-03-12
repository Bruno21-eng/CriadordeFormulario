<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        $path = $request->path();

        if ($user->role !== 'admin' && $request->is('admin*')) {
            return redirect('/user');
        }

        if ($user->role === 'admin' && $request->is('user*')) {
            return redirect('/admin');
        }


        return $next($request);
    }
}
