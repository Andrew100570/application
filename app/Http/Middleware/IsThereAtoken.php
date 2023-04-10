<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IsThereAtoken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('x-auth-token');

        if (isset($token) and $token ==='dplp31qppIkvoxr3lIqsX77BrUrhDhsg9GFk9atO') {
                return $next($request);
        } else {
            return response()->json('{"response":"Error"}', 403, ['Content-Type' => 'application/json; charset=UTF-8']);
        }
    }
}
