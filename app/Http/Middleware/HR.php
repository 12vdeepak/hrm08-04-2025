<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HR
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent');
        if ($this->isMobileDevice($userAgent)) {
            Auth::logout();
            return redirect()->route('hr_login_view')->with('error', 'CRM cannot be accessed with mobile');
        }
        if (!auth()->check()) {
            Auth::logout();
            return redirect()->route('hr_login_view');
        }
        if (auth()->user()->role_id != 2) {
            Auth::logout();
            return redirect()->route('hr_login_view');
        }
        return $next($request);
    }

    private function isMobileDevice($userAgent)
    {
        // Regular expression to detect common mobile user agents
        $pattern = "/(android|bb\d+|blackberry|iphone|ipod|windows phone)/i";
        return preg_match($pattern, $userAgent);
    }
}
