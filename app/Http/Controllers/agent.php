<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\Libs\HttpInput;
use App\Models\Logs;
use App\Models\System;
use Illuminate\Http\Request;

define("AppModel", "agent");


function log(string $type, string $message)
{
    Logs::createds($type, $message, AuthController::user(AppModel)->user);
}

class agent extends Controller
{
    protected $redirectTo = '/Agent';
    private string $group;

    function __construct()
    {
        $this->redirectTo = System::getSystem("system", "agent_url", "/Agent");
        $this->group = "agent";
        $this->middleware(AppModel, ['except' => 'logout']);
    }

    function index(Request $request)
    {
        if (!AuthController::check($this->group)) {
            return redirect($this->redirectTo . "/login");
        }
        return (new HttpInput)->call_($request, 'agent');
    }

    protected function guard()
    {
        return auth()->guard($this->group);
    }

}
