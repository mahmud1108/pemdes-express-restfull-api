<?php

namespace App\Http\Middleware;

use App\Models\Bumdes;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BumdesMiddleware
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

        $bumdes = Bumdes::where('token', $token)->first();
        if (!$bumdes) {
            $auth = false;
        } else {
            Auth::login($bumdes);
        }

        if ($auth) {
            return $next($request);
        } else {
            return response()->json([
                'errors' => [
                    'message' => [
                        'Unauthorize'
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
