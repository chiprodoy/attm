<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class LogIClockRequest
{

    private function appLog(){
        return Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/iclock_request.log'),
                'permission' => 0775,
        ]);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     /* Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Log request details before processing
        $this->appLog()->info('Incoming Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'headers' => $request->headers->all(),
            'body' => $request->all(), // Be cautious with sensitive data here
        ]);

        $response = $next($request);

        // Log response details after processing (optional)
        $this->appLog()->info('Outgoing Response', [
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            // 'body' => $response->getContent(), // Uncomment if you need to log response body
        ]);

        return $response;
    }
}
