<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if ($role === 'admin' && $user->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
        
        if ($role === 'teacher' && $user->role !== 'teacher') {
            abort(403, 'Unauthorized. Teacher access required.');
        }
        
        return $next($request);
    }
}
