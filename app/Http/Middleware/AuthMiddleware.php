<?php

namespace App\Http\Middleware;

use App\Models\Driver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Remove the "Bearer " part from the token
        $token = str_replace('Bearer ', '', $token);

        $driver = Driver::where('auth_token', $token)->first();

        if (!$driver) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $request->driver = $driver; // Agrega el conductor a la solicitud para su posterior uso

        return $next($request); // Permite el acceso a la ruta protegida
    }

}