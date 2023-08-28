<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class bill extends Auths
{
    protected $url = "bill";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.bill.bill")->with($this->agvs);
    }

    public function bills()
    {
        if ((Validator::make($this->all(), ["page" => "required|int", "limit" => "required|int"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $limit = $this->input("limit", 10);
        $page = $this->input("page", 1);
        Utils::array_pop_key($this->all(), "m");
        $Bill = \App\Models\Bill::where("Id", ">", 0);
        foreach (Utils::array_pop_key(Utils::array_pop_key(Utils::array_pop_key($this->all(), "m"), "limit"), "page") as $keys => $Orderz) ($Bill = $Bill->where($keys, 'like', '%' . $Orderz . "%"));
        return $Bill->orderBy("created_at", "desc")->paginate($this->input("limit", 10), ['uid', 'type', 'name', 'price', "user", "message", "created_at"], "bh_logs", $this->input("page", 0));
    }

    public function del()
    {
        if ((Validator::make($this->all(), ["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (\App\Models\Bill::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.log"), __("log.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }
}
