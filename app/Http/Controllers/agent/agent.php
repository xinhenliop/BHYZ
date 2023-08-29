<?php

namespace App\Http\Controllers\agent;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use Illuminate\Http\Request;

class agent extends Auths
{
    protected $url = "agent";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.agent.agent")->with($this->agvs);
    }

    public function agents()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("user", "0") == "0" && ($this->validator(["user" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Agent::Agent_List(Utils::array_pop_key($this->all(), "m"));
    }

    public function created()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['User'] = new \App\Models\User();
            return view("admin.agent.created")->with($this->agvs);
        }
        $from = $this->validator([
            "name" => "required",
            "balance" => "required",
            "user" => "required",
            "password" => "required",
            "qq" => "",
            "discount" => "required|int",
            "email" => "",
            "app_list" => ""
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Agent::CreateAgent(Utils::array_pop_key($from->getData(), "m"));
    }


    public function edit()
    {
        $from = $this->validator([
            "uid" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Agent::EditAgent(Utils::array_pop_key($from->getData(), "m"));
    }

    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Agent::delete($this->all());
    }

    public function get()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $this->agvs['User'] = ($App = \App\Models\User::where("uid", "=", $this->input("uid"))->first()) != null ? $App : null;
        return view("admin.agent.created")->with($this->agvs);
    }

    public function addBalance()
    {
        if (($this->validator(["uid" => "required", "balance" => "required|int"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return \App\Libs\models\Agent::addBalance($this->all());
    }

}
