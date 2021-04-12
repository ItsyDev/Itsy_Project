<?php

namespace App\Http\Middleware;
use Closure;

class AuthLogin
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
        if (session()->has("login")) {
            return $next($request);
        }

        return redirect("login")->with("message", "<script>sweet('error', 'Failed', 'You must login!')</script>");
    }
}
