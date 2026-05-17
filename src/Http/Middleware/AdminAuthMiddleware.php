<?php

namespace Ssh521\KoreanBbs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $prefix = config('korean-bbs.prefix.admin');

        if (!session('bbs_admin_authenticated')) {
            return redirect()->route('bbs.admin.login');
        }

        return $next($request);
    }
}
