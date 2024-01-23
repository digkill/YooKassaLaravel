<?php

namespace Digkill\YooKassaLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class IpAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $ips = config('yookassa')['ip_allow'];
        if (app()->isProduction()) {

            $result = false;
            foreach ($ips as $ip) {
                if ($this->ipInRange($request->ip(), $ip)) {
                    $result = true;
                    break;
                }
            }
            abort_if(!$result, 404, 'Not found!');
        }

        return $next($request);
    }

    private function ipInRange($ip, $range): bool
    {
        if (!str_contains($range, '/')) {
            $range .= '/32';
        }
        list($range, $netmask) = explode('/', $range, 2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($ip);
        $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
        $netmask_decimal = ~$wildcard_decimal;

        return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
    }
}
