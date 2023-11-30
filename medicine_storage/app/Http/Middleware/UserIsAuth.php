<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = User::where($request->email);
        $phone = User::where($request->phone);
        if ($email !== null || $phone !== null) {
        return response()->json(['message' => 'please register ']);
        }
        return $next($request);
    }

}
