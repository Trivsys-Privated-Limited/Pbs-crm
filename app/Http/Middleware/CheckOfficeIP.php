<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOfficeIP
{
    public function handle(Request $request, Closure $next)
    {
        $userIp = $request->ip();

        if (strpos($userIp, ':') !== false) {
            return redirect()->back()->with(['error' => 'Access Denied.']);
        }

        $allowedIps = explode(',', env('ALLOWED_IPS'));

        if (! in_array($userIp, $allowedIps)) {
            return redirect()->back()->with(['error' => 'Access Denied.']);
        }
        
        return $next($request);
    }
}
