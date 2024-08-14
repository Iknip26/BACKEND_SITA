<?php

namespace App\Http\Middleware;

use App\Models\Lecturer;
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
        // dd($user);
        if ($role === 'student' && (!$user->role == "Mahasiswa" ||!$user->role == "Kaprodi")) {
            return response()->json(['error' => 'Access denied. Only students can access this route.'], 403);
        }

        if ($role === 'lecturer' && (!$user->role == "Dosen" || !$user->role == "Kaprodi")) {
            return response()->json(['error' => 'Access denied. Only lecturers can access this route.'], 403);
        }

        if ($role === 'kaprodi' && !$user->role == "Dosen") {
            $dosen = Lecturer::where('user_id',$user->id);
            if($dosen->isKaprodi == false){
                return response()->json(['error' => 'Access denied. Only Akademik can access this route.'], 403);
            }
        }

        return $next($request);
    }
}
