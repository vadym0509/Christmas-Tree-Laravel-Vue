<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Purcell\Hone\Models\IpAddress;

class IpCheck
{
    /**
     * If the users current IP address is not recognised, send them to a blocker page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $check = IpAddress::where('ip_address', $request->ip())->get();

        if ($check->isEmpty()) {

            // log out the user
            Auth::logout();

            // send to the blocker!
            return redirect()->route('admin.blocker');
        }

        return $next($request);
    }
}
