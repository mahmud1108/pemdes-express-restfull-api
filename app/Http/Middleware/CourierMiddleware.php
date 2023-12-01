<?php

namespace App\Http\Middleware;

use App\Models\Courier;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CourierMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $auth = true;

        if (!$token) {
            $auth = false;
        }

        $courier = Courier::where('token', $token)->first();
        if (!$courier) {
            $auth = false;
        } else {
            Auth::login($courier);
        }

        if ($auth) {
            return $next($request);
        } else {
            return   response()->json([
                'errors' => [
                    'message' => [
                        'Unauthorized'
                    ]
                ]
            ], 401);
        }
    }
}
