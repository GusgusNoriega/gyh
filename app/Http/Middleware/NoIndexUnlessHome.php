<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoIndexUnlessHome
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $route = $request->route();
        $routeName = $route?->getName();
        $path = '/'.ltrim($request->path(), '/');

        // Permitir indexar únicamente la home pública (por ahora)
        if ($routeName === 'home' || $path === '/') {
            $response->headers->set('X-Robots-Tag', 'index, follow');
            return $response;
        }

        // Recursos SEO (accesibles, pero no se recomienda indexarlos)
        if ($path === '/sitemap.xml' || $path === '/robots.txt') {
            $response->headers->set('X-Robots-Tag', 'noindex, follow');
            return $response;
        }

        // Todo lo demás: no indexar ni seguir
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}

