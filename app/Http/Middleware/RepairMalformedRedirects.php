<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RepairMalformedRedirects
{
    /**
     * Remove stale malformed redirect targets left in older browser sessions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasSession()) {
            $intended = $request->session()->get('url.intended');

            if (is_string($intended) && str_contains($intended, 'https:/dashboard')) {
                $request->session()->forget('url.intended');
            }
        }

        if ($request->path() === 'https:/dashboard') {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
