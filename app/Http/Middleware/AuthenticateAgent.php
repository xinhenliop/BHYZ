<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\AuthController;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //当 auth 中间件判定某个用户未认证，会返回一个 JSON 401 响应，或者，如果是 Ajax 请求的话，将用户重定向到 login 命名路由（也就是登录页面）。
        if (Auth::guard($guard)->guest() || (User::where("uid", Auth::guard($guard)->user()->uid)->first())->status == 0) {
            AuthController::logouts($guard, $request);
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(config('system')['system']['agent_url'] . "/login");
            }
        }
        return $next($request);
    }
}
