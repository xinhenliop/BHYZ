<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\Libs\HttpInput;
use App\Models\Logs;
use App\Models\System;
use Illuminate\Http\Request;

define("AppModel", "admin");

function log(string $type, string $message)
{
    Logs::createds($type, $message, AuthController::user(AppModel)->user);
}

class admin extends Controller
{

    protected $redirectTo = '/admin';

    function __construct()
    {
        $this->redirectTo = System::getSystem("system", "admin_url", "/admin");
        $this->middleware(AppModel, ['except' => 'logout']);
    }

    function index(Request $request)
    {
        if (!AuthController::check(AppModel)) {
            return redirect($this->redirectTo . "/login");
        }
        return (new HttpInput)->call_($request, 'admin');
    }

}
