<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\Kami;
use App\Models\Logs;
use App\Models\System;
use Illuminate\Http\Request;

class type extends Auths
{
    protected $url = "type";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.type.type")->with($this->agvs);
    }

    public function types()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("type", "0") == "0" && ($this->validator(["type" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        $apps = $this->input("type", false) ? Kami::where("type", 'like', '%' . $this->input("type",) . "%") : Kami::where("Id", ">", 0);
        $appList = $apps->paginate($this->input("limit", 10), ['uid', 'type', 'status', "type_time", 'price', "time", "app_uid", "app", "remark", "prefix", "suffix", "length", "created_at"], "bh_km_type", $this->input("page", 0));
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = $value['status'] == 0 ? "停用" : "启用";
            $appList[$key]['type_time'] = System::getSystem("Times", $value['type_time'], "天");
        }
        return $appList;
    }

    public function created()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['Type'] = new Kami();
            return view("admin.type.created")->with($this->agvs);
        }
        $from = $this->validator([
            "type" => "required",
            "app_uid" => "required|int",
            "status" => "required|int",
            "time" => "required|int",
            "type_time" => "required|int",
            "length" => "required|int",
            "price" => "required",
            "app" => "required",
            "card_str" => "",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }

        if (Kami::createds(Utils::array_pop_key($from->getData(), "m"))) {
            Logs::createds(__("Logs.type"), __("type.created_type_success", ["name" => $this->input("type")]), $this->user->user);
            return response::message(200, "创建成功！");
        }
        Logs::createds(__("Logs.type"), __("type.created_type_filed", ["name" => $this->input("type")]), $this->user->user);
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
        if (Kami::updateds(Utils::array_pop_key($from->getData(), "m"))) {
            Logs::createds(__("Logs.type"), __("type.edit_type_success", ["uid" => $this->input("uid")]), $this->user->user);
            return response::message(200, "修改成功！");
        }
        Logs::createds(__("Logs.type"), __("type.edit_type_filed", ["uid" => $this->input("uid")]), $this->user->user);
        return response::message(401, "修改失败！");
    }


    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (Kami::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.type"), __("type.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public function get()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $this->agvs['Type'] = ($App = Kami::where("uid", "=", $this->input("uid"))->first()) != null ? $App : null;
        return view("admin.type.created")->with($this->agvs);
    }
}
