<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfTokenFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestToken = $request->header('csfr-token');

        $envToken = env('CSFR_TOKEN');

        if (!$requestToken || $requestToken !== $envToken) {
            return response()->json([
                'statusCode' => 402,
                'message' => 'Something went wrong.',
            ], 403);
        }

        return $next($request);
    }
}
