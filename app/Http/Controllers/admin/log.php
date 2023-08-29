<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class log extends Auths
{
    protected $url = "log";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.log.log")->with($this->agvs);
    }

    public function logs()
    {
        if ((Validator::make($this->all(), ["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("label", "0") == "0" && (Validator::make($this->all(), ["label" => "required", "where" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        $Log = $this->input("label", false) ? Logs::where($this->input("label",), 'like', '%' . $this->input("where",) . "%") : Logs::where("Id", ">", 0);
        return $Log->orderBy("created_at", "desc")->paginate($this->input("limit", 10), ['uid', 'log_level', 'log_msg', 'log_users', "created_at"], "bh_logs", $this->input("page", 0));
    }

    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (Logs::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.type"), __("type.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

}
