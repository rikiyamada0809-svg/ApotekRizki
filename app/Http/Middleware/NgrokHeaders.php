<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NgrokHeaders
{
    /**
     * Handle an incoming request.
     * Adds the ngrok-skip-browser-warning header to all responses
     * so that ngrok's interstitial page does not block CSS/JS/font assets.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('ngrok-skip-browser-warning', 'true');
        return $response;
    }
}
