<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string  $role
    * @return mixed
    */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if ($role === 'student' && !$user->student) {
            return response()->json(['error' => 'Access denied. Only students can access this route.'], 403);
        }

        if ($role === 'lecturer' && !$user->lecturer) {
            return response()->json(['error' => 'Access denied. Only lecturers can access this route.'], 403);
        }

        return $next($request);
    }
}
