<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class user extends Auths
{
    protected $url = "user";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    function edit()
    {

    }

    function register()
    {
        $validator = Validator::make($this->request->input(),
            ['user' => 'required', 'password' => 'required', "system" => "required", "status" => "required"]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $users = new Admin();
        $users->user = $this->request->input("user");
        $users->password = Hash::make($this->request->input("password"));
        $users->uid = rand(111111, 99999999);
        $users->admin_system = json_encode(array("user" => true, "agent" => true, "app" => true, "card" => true, "system" => true, "log" => true, "plugin" => true, "tools" => true));
        $users->status = 1;
        $users->save();
        return response()->json($users);
    }

    public function adminList()
    {
        if (($User = AuthController::user(AppModel)) == null || $User->root != 1) {
            return response::message(401, "权限不足！");
        }
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("user", "0") == "0" && ($this->validator(["user" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        $apps = $this->input("user", false) ? Admin::where("user", 'like', '%' . $this->input("user",) . "%") : Admin::where("Id", ">", 0);
        $appList = $apps->paginate($this->input("limit", 10), ['uid', 'user', 'root', 'status', "login_count", "last_login_time", "last_login_ip", "created_at"], "bh_app", $this->input("page", 0));
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = $value['app_status'] == 0 ? "禁用" : "启用";
            $appList[$key]['root'] = $value['root'] == 0 ? "普通管理员" : "超级管理员";
        }
        return $appList;
    }

    public function username()
    {
        return "超级管理员" . $this->user->Id;
    }

    public function show()
    {
        return $this->user->show();
    }

}
