<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class Authentication
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userToken = $request->header('Authorization');

        if ($userToken == null) {
            return response()->json([
                'code' => '401',
                'status' => 'Unauthorized',
                'message' => 'token di butuhkan'
            ],401,[
                'Content-Type' => 'application/json'
            ]);
        }

        if (
            User::where('token')->get()->count() < 1
        ) {
            return response()->json([
                'code' => '401',
                'status' => 'Unauthorized',
                'message' => 'token tidak valid'
            ],401,[
                'Content-Type' => 'application/json'
            ]);
        }

        return $next($request);
    }
}
