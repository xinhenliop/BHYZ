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

/**
 * admin Base class controller
 */
class admin extends Controller
{

    /**
     * @var mixed|string
     */
    protected $redirectTo = '/admin';

    function __construct()
    {
        $this->redirectTo = System::getSystem("system", "admin_url", "/admin");
        $this->middleware(AppModel, ['except' => 'logout']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed|string
     */
    function index(Request $request)
    {
        if (!AuthController::check(AppModel)) {
            return redirect($this->redirectTo . "/login");
        }
        return (new HttpInput)->call_($request, 'admin');
    }

}
