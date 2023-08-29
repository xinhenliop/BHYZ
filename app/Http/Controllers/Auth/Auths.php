<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Auths
{
    public function __construct(Request $request, $url)
    {
        $this->request = $request;
        $this->user = AuthController::user(AppModel);
        $this->agvs = ["user" => $this->user, "request" => $request, "web" => AppModel, "url" => $url];
    }

    public function input(string $name, $default = null)
    {
        return $this->request->input($name, $default);
    }

    public function session($name, $default = null)
    {
        return $this->request->session()->get($name, $default);

    }

    public function validator($validator)
    {
        return Validator::make($this->all(), $validator);
    }

    public function all()
    {
        return $this->request->all();
    }

    public function isAdmin()
    {
        return isset($this->user->admin_system);
    }
}
