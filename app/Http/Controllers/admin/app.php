<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Encrypt\Encrypt_index;
use App\Libs\Utils;
use App\Models\Logs;
use Illuminate\Http\Request;

class app extends Auths
{
    protected $url = "app";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.app.app")->with($this->agvs);
    }

    public function apps()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("app_name", "0") == "0" && ($this->validator(["app_name" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        $apps = $this->input("app_name", false) ? \App\Models\App::where("app_name", 'like', '%' . $this->input("app_name",) . "%") : \App\Models\App::where("Id", ">", 0);
        $appList = $apps->paginate($this->input("limit", 10), ['uid', 'app_name', 'app_status', 'user_count', "km_count", "version", "encrypt_mode", "validate_app_md5", "transmission", "app_url", "app_data", "description", "socket_encrypt", "created_at"], "bh_app", $this->input("page", 0));
        foreach ($appList as $key => $value) {
            $appList[$key]['app_status'] = $value['app_status'] == 0 ? "停用" : "启用";
            $appList[$key]['encrypt_mode'] = $value['encrypt_mode'];
            $appList[$key]['socket_encrypt'] = Encrypt_index::socket_encrypt($value['socket_encrypt']);
            $appList[$key]['transmission'] = Encrypt_index::transmission($value['transmission']);
        }
        return $appList;
    }

    public function created()
    {
        if ($this->request->isMethod("GET")) {
            return view("admin.app.created")->with($this->agvs);
        }
        $from = $this->validator([
            "app_name" => "required",
            "description" => "",
            "app_status" => "required|int",
            "version" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (\App\Models\App::createds(Utils::array_pop_key($from->getData(), "m"))) {
            Logs::createds(__("Logs.app"), __("app.created_app_success", ["name" => $this->input("app_name")]), $this->user->user);
            return response::message(200, "创建成功！");
        }
        Logs::createds(__("Logs.app"), __("app.created_app_filed", ["name" => $this->input("app_name")]), $this->user->user);
        return response::message(401, "创建失败！");
    }


    public function edit()
    {
        $from = $this->validator([
            "uid" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (\App\Models\App::updateds(Utils::array_pop_key($from->getData(), "m"))) {
            Logs::createds(__("Logs.app"), __("app.edit_app_success", ["uid" => $this->input("uid")]), $this->user->user);
            return response::message(200, "修改成功！");
        }
        Logs::createds(__("Logs.app"), __("app.edit_app_filed", ["uid" => $this->input("uid")]), $this->user->user);
        return response::message(401, "修改失败！");
    }


    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (\App\Models\App::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.app"), __("app.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public function get()
    {
        if (($this->validator(["uid" => "required", "c" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $parg = $this->input("c");
        $this->agvs['App'] = ($App = \App\Models\App::where("uid", "=", $this->input("uid"))->first()) != null ? $App : null;
        return view("admin.app." . $parg)->with($this->agvs);
    }

}
