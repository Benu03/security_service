<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class ReverseProxyPath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Tambahkan path reverse proxy hanya jika environment bukan 'local'
        if (config('static.app_env') !== 'local') {

            if (config('static.reverse_proxy') == true) {
                $reverseProxyPath = '/3/';
            }
            else
            {
                $reverseProxyPath = '/';
            }
            $baseUrl = $request->getSchemeAndHttpHost() . $reverseProxyPath;

            // Ubah URL root aplikasi
            URL::forceRootUrl($baseUrl);
        }

        return $next($request);
    }
}