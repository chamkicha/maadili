<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log request details
        $data = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'ip' => $request->ip(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        // Log::info('Request Log:', $data);
        Log::channel('requests')->info('Request Log:', $data);

        // Proceed with the request
        return $next($request);
    }
}
