<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\Auths;
use App\Http\Controllers\Json\response;
use App\Libs\models\Card;
use App\Libs\Utils;
use Illuminate\Http\Request;

class batch extends Auths
{
    protected $url = "batch";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    public function add()
    {
        if ($this->request->isMethod("GET")) {
            return view('admin.batch.add')->with($this->agvs);
        }
        if ($this->input("type", 0) == 0) {
            $from = $this->validator([
                "type" => "required|int",
                "type_uid" => "required|int",
                "batch_card" => "required",
            ]);
        } else {
            $from = $this->validator([
                "type" => "required|int",
                "app_uid" => "required|int",
                "batch_card" => "required",
            ]);
        }
        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::CreateCard(Utils::array_pop_key($from->getData(), "m"));
    }

    public function inquire()
    {
        if ($this->request->isMethod("GET")) {
            return view('admin.batch.inquire')->with($this->agvs);
        }

        $from = $this->validator([
            "batch_card" => "required",
        ]);

        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::CardInquire(Utils::array_pop_key($from->getData(), "m"));
    }

    public function maintenance()
    {
        if ($this->request->isMethod("GET")) {
            return view('admin.batch.maintenance')->with($this->agvs);
        }

        $from = $this->validator([
            "app_uid" => "required",
        ]);

        if ($from->fails()) {
            return response::message(500, __("message.argv_error"));
        }
        return Card::CardMaintenance(Utils::array_pop_key($from->getData(), "m"));
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
}
