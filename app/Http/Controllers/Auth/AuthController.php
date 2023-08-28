<?php

namespace App\Http\Controllers\Auth;

use App\Models\Logs;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public static function check($guard): bool
    {
        return AuthController::guard($guard)->check();
    }

    protected static function guard($guard)
    {
        return auth()->guard($guard);
    }

    public static function login(Request $request, string $group, string $redirectTo)
    {
        if ($request->isMethod("GET")) {
            return view("auth.login");
        }
        $validator = Validator::make($request->input(),
            ['user' => 'required', 'password' => 'required']
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $config = System::getSystem("captcha", "web", true);
        if ($config && $request->input("code") != $request->session()->get('captcha', "0000")) {
            return view("auth.login")->with('msg', "验证码错误！");
        }
        $request->session()->put('captcha', "");
        if (AuthController::attempt($group, ['user' => $request->input("user"), "password" => $request->input("password")], true)) {
            $UserManager = AuthController::user($group);
            if ($UserManager->status == 0) {
                AuthController::logouts($group, $request);
                return view("auth.login")->with('msg', "账号已被禁止登录！");
            }
            $UserManager->last_login_ip = $request->getClientIp();
            $UserManager->last_login_time = Date::now();
            $UserManager->save();
            $request->session()->regenerate();
            Logs::createds(__("Logs.login"), __("login.login_success"), $request->input("user"));
            return redirect()->intended($redirectTo);
        } else {
            Logs::createds(__("Logs.login"), __("login.login_error"), $request->input("user"));
            return view("auth.login")->with('msg', "账号密码错误！");
        }
    }

    public static function attempt($guard, $params, $paramss = false)
    {
        return AuthController::guard($guard)->attempt($params, $paramss);
    }

    public static function user($guard)
    {
        return AuthController::guard($guard)->user();
    }

    public static function logouts($guard, $request): void
    {
        /*Logs::createds(__("Logs.login"),__("login.logout"),AuthController::user($guard)->user);*/
        AuthController::logout($guard);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public static function logout($guard)
    {
        AuthController::guard($guard)->logout();
    }

    public static function isAgents($group)
    {
        if (empty(($Users = AuthController::user($group))))
            return true;
        if (!AuthController::isAdmin($group) && $Users->level > 1)
            return true;
        return false;
    }

    public static function isAdmin($group)
    {
        return isset(AuthController::guard($group)->user()->admin_system);
    }

    public static function isAgent(string $AppModel)
    {
        if (empty((AuthController::user($AppModel))))
            return true;
        return false;
    }

}
