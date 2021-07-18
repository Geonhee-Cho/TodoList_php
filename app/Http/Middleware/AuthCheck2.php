<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $check = $request->session()->get('id');

        if (!empty($check)) {
            return redirect('/?alert_message=이미 로그인 하셨습니다.');
        }
        return $next($request);
    }
}
