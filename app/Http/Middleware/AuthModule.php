<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;

class AuthModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module_id, $is_ajax = 0)
    {
        $check_module = DB::table("rel_user_module")->where([
            ["user_id", "=", session("user_id")],
            ["module_id", "=", $module_id]
        ])->first();

        if ($check_module->is_allow == 1) {
            return $next($request);
        } else {
            if ($is_ajax == 1) {
                $response = [
                    "success" => 403,
                    "link" => url("/403-page")
                ];

                return \response()->json($response);
            } else {
                return redirect("/403-page");
            }
        }
    }
}
