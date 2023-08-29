<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\models\Card;
use App\Libs\Utils;
use App\Models\AppUsers;
use App\Models\Kami;
use Illuminate\Http\Request;

class cards extends Auths
{
    protected $url = "cards";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function index()
    {
        return view("admin.card.card")->with($this->agvs);
    }

    public function user()
    {
        return view("admin.card.user")->with($this->agvs);
    }

    public function cards()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("where", "0") == "0" && ($this->validator(["where" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::CardList(Utils::array_pop_key($this->all(), "m"));
    }

    public function users()
    {
        if (($this->validator(["page" => "required|int", "limit" => "required|int"]))->fails() && ($this->input("where", "0") == "0" && ($this->validator(["where" => "required"]))->fails())) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::UserList(Utils::array_pop_key($this->all(), "m"));
    }

    public function created()
    {
        if ($this->request->isMethod("GET")) {
            $this->agvs['Type'] = new Kami();
            return view("admin.card.created")->with($this->agvs);
        }
        if ($this->input("type", 0) == 0) {
            $from = $this->validator([
                "type" => "required|int",
                "prefix" => "",
                "type_uid" => "required|int",
                "count" => "required|int",
            ]);
        } else {
            $from = $this->validator([
                "type" => "required|int",
                "app_uid" => "required|int",
                "user" => "required",
                "password" => "required",
            ]);
        }
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::CreateCard(Utils::array_pop_key($from->getData(), "m"));
    }


    public function edit()
    {
        $from = $this->validator([
            "uid" => "required",
        ]);
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::EditCard(Utils::array_pop_key($from->getData(), "m"));
    }

    public function addTime()
    {
        if (($this->validator(["uid" => "required", "time" => "required|int"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::AddTime(Utils::array_pop_key($this->all(), "m"));
    }

    public function del()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::DeleteCard(Utils::array_pop_key($this->all(), "m"));
    }

    public function get()
    {
        if (($this->validator(["uid" => "required"]))->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        $this->agvs['Card'] = ($App = AppUsers::where("uid", "=", $this->input("uid"))->first()) != null ? $App : null;
        return view("admin.card.edit")->with($this->agvs);
    }
}
