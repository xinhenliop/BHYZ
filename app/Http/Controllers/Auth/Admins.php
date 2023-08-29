<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Admins extends Controller
{
    //
    protected $redirectTo = '/admin';//登录成功跳转路径
    private string $group;

    public function __construct()
    {
        $this->redirectTo = System::getSystem("system", "admin_url", "/admin");
        $this->group = "admin";
        $this->middleware('guest')->except('logout');//添加guest中间件，除了logout方法
    }

    function login(Request $request)
    {
        if (AuthController::check($this->group))
            return redirect($this->redirectTo);
        return AuthController::login($request, $this->group, $this->redirectTo);
    }

    public function logout(Request $request)
    {
        AuthController::logouts($this->group, $request);
        return redirect($this->redirectTo . "/login");
    }

    protected function guard()//这个方法trait也有，但是如果我们用其他的guard,就要重写方法
    {
        return Auth::guard('admin');//你要使用的guard
    }
}
