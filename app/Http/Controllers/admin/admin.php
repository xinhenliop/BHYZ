<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\System;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class admin extends Auths
{

    protected $url = "admin";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public static function ad()
    {
        Utils::Http_Post("https://");
    }

    public function index()
    {
        //$this->request->input();
        return view('admin.index')->with($this->agvs);
    }

    public function home()
    {
        //$this->request->input();
        return view('admin.home')->with($this->agvs);
    }

    public function system()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['SYSTEM'] = config("system")['system'];
            return view('admin.system.system')->with($this->agvs);
        }
        $form = Validator::make($this->all(), ["name" => "required", "description" => "required", "time_zone" => "required", "status" => "required|int", "close_toast" => "required", "admin_url" => "required", "agent" => "required|int", "agent_close_toast" => "required", "ip_whitelist" => "", "sql" => "required|int", "agent_url" => "required", "sql_argv" => "",
        ]);
        if ($form->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $agent = $form->getData();
        $agent['status'] = $agent['status'] == '1';
        $agent['agent'] = $agent['agent'] == '1';
        $agent['sql'] = $agent['sql'] == '1';
        $agent["ver"] = config("system")['system']['ver'];
        System::setSystem("system", Utils::array_pop_key($agent, "m"));
        return response::message(200, "修改成功！");
    }

    public function captcha()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['SYSTEM'] = config("system")['captcha'];
            return view('admin.system.captcha')->with($this->agvs);
        }
        $form = Validator::make($this->all(), ["curve" => "required|int", "line" => "required", "snowflake" => "required|int", "height" => "required|int", "width" => "required|int", "count" => "required|int", "fonts" => "required", "web" => "required|int",
        ]);
        if ($form->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $agent = $form->getData();
        $agent['web'] = $agent['web'] == '1';
        $agent['curve'] = $agent['curve'] == '1';
        $agent['line'] = $agent['line'] == '1';
        $agent['snowflake'] = $agent['snowflake'] == '1';
        $agent['height'] = (int)$agent['height'];
        $agent['width'] = (int)$agent['width'];
        $agent['count'] = (int)$agent['count'];
        System::setSystem("captcha", Utils::array_pop_key($agent, "m"));
        return response::message(200, "修改成功！");
    }

    public function email()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['SYSTEM'] = config("system")['smtp'];
            return view('admin.system.email')->with($this->agvs);
        }
        $form = Validator::make($this->all(), ["host" => "required|int", "port" => "required", "username" => "", "password" => "", "SSL" => "required|int", "email" => "", "status" => "required|int"]);
        if ($form->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $agent = $form->getData();
        $agent['status'] = $agent['status'] == '1';
        $agent['SSL'] = $agent['SSL'] == '1';
        $agent['port'] = (int)$agent['height'];
        System::setSystem("smtp", Utils::array_pop_key($agent, "m"));
        return response::message(200, "修改成功！");
    }

    public function administrator()
    {
        if ($this->request->isMethod("GET")) {
            return view('admin.system.user')->with($this->agvs);
        }
    }

    public function created()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['SYSTEM'] = new \App\Models\Admin();
            return view('admin.system.created')->with($this->agvs);
        }
        return \App\Libs\models\Admin::register($this->all());
    }

    public function get()
    {
        $from = $this->validator([
            "uid" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $this->agvs['SYSTEM'] = \App\Models\Admin::where("uid", $this->input("uid"))->first();
        return view('admin.system.created')->with($this->agvs);

    }

    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Admin::delete($this->all());
    }

    public function edit()
    {
        $from = $this->validator([
            "uid" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Admin::updated($this->all());
    }

    public function adminList()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("user", "0") == "0" && ($this->validator(["user" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Admin::adminList($this->all());
    }

    /**
     * show
     * Agent statistics Balanc
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|View
     */
    public function show()
    {
        return view("admin.show")->with($this->agvs);
    }

    /**
     * show
     * Agent statistics Balanc
     * @return Application|Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|JsonResponse|View
     */
    public function reset()
    {
        if (($this->validator([
            "password" => "required",
            "oldPassword" => "required",
            "cmpassword" => "required",
        ]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if ($this->input("password") != $this->input("cmpassword")) return response::message(500, "修改密码不一致！");
        $UserManager = AuthController::user(AppModel);
        if (auth()->guard(AppModel)->attempt(["user" => $UserManager->user, "password" => $this->input("oldPassword")])) {
            $UserManager->password = Hash::make($this->input("cmpassword"));
            $UserManager->save();
            log(__("Logs.operate"), "管理员密码修改: " . $this->input("cmpassword"));
            AuthController::logouts(AppModel, $this->request);
        }
        return response::message(500, "旧密码错误！");
    }
}
