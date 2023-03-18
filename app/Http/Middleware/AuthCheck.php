<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(
                [
                    'error' => [
                        'code' => 401,
                        'message' => 'Unauthorized'
                    ]
                ], 401
            );
        }

        $user = User::firstWhere('api_token', $token);

        if (!$user) {
            return response()->json(
                [
                    'error' => [
                        'code' => 401,
                        'message' => 'Unauthorized'
                    ]
                ], 401
            );
        }

        Auth::login($user);

        return $next($request);
    }
}
