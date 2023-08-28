<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class order extends Auths
{
    protected $url = "order";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.order.order")->with($this->agvs);
    }

    public function orders()
    {
        if ((Validator::make($this->all(), ["page" => "required|int", "limit" => "required|int"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $limit = $this->input("limit", 10);
        $page = $this->input("page", 1);
        Utils::array_pop_key($this->all(), "m");
        $Order = \App\Models\Order::where("Id", ">", 0);
        foreach (Utils::array_pop_key(Utils::array_pop_key(Utils::array_pop_key($this->all(), "m"), "limit"), "page") as $keys => $Orderz) ($Order = $Order->where($keys, 'like', '%' . $Orderz . "%"));
        return $Order->orderBy("created_at", "desc")->paginate($limit, ['uid', 'type', 'name', 'price', "user", "remark", "status", "order_number", "created_at"], "bh_logs", $page);
    }

    public function del()
    {
        if ((Validator::make($this->all(), ["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (Logs::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.log"), __("log.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public function get()
    {
        if ((Validator::make($this->all(), ["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        if (Logs::whereIn("uid", json_decode($this->input("uid"), true))->delete()) {
            Logs::createds(__("Logs.log"), __("log.del_success", ["uids" => $this->input("uid")], $this->session("locale", "zh")), $this->user->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }
}
