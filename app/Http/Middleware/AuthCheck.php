<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
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

        if (empty($check)) {
            return redirect('/user/signin-page?alert_message=로그인이 필요합니다.');
        }
        return $next($request);
    }
}
