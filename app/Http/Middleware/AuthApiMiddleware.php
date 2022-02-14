<?php

namespace App\Http\Middleware;

use App\GeneralSettings;
use Closure;
use Illuminate\Http\Request;

class AuthApiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $api_key = app(GeneralSettings::class)->api_key;

        if($api_key != $request->header('X-API-KEY')) {

            return response()->json([
                'message' => 'Security Auth Error.'
            ], 401);

        }

        return $next($request);
    }
}
