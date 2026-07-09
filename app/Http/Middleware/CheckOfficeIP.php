<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOfficeIP
{
    public function handle(Request $request, Closure $next)
    {
        $userIp = $request->ip();
        \Log::info('User IP: ' . $userIp);

        if (strpos($userIp, ':') !== false) {
            abort(403, 'Access Denied.');
        }

        $allowedIps = explode(',', env('ALLOWED_IPS'));

        if (! in_array($userIp, $allowedIps)) {
            abort(403, 'Access Denied.');
        }
        
        return $next($request);
    }
}