<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::exists("authorization") && Session::exists("token_expired") && Session::exists("level")) {
            return $next($request);
        }else {
            return redirect()->route("auth.login")->with("error", "You must login first!");
        }
    }
}
