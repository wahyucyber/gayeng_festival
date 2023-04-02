<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::exists("authorization") && Session::exists("token_expired") && Session::exists("level")) {
            if (Session::get("level") == "Admin") {
                return redirect()->route("admin.dashboard");
            }
        }else {
            return $next($request);
        }
    }
}
