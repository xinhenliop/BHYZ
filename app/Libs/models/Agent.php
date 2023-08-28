<?php

namespace App\Libs\models;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\Logs;
use App\Models\System;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class Agent
{
    public static function typeToTime($type, $time)
    {
        return System::getSystem("Times", $type, 0)[1] * $time;
    }

    public static function CreateAgent($arrays)
    {
        if (AuthController::isAgents(AppModel))
            return response::message(400, "权限不足！");
        if (!AuthController::isAdmin(AppModel)) {
            $arrays['level'] = 2;
            $arrays['Inviter_uid'] = AuthController::user(AppModel)->uid;
            if (AuthController::user(AppModel)->balance < $arrays['balance'])
                return response::message(400, "余额不足！");
        }
        $arrays['password'] = Hash::make($arrays['password']);
        if (User::where("user", $arrays['user'])->first() != null)
            return response::message(400, "账号已存在！");
        $arrays['app_list'] = json_encode($arrays['app_list']);
        if (User::createds($arrays)) {
            (!AuthController::isAdmin(AppModel)) && AuthController::user(AppModel)->decrement("balance", $arrays['balance']);
            Logs::createds(__("Logs.app"), __("app.edit_app_success", ["uid" => $arrays['user']]), AuthController::user(AppModel)->user);
            return response::message(200, "创建成功！");
        }
        return response::message(400, "创建失败！");
    }

    public static function addBalance(array $array_pop_key)
    {
        if (AuthController::isAgents(AppModel))
            return response::message(400, "权限不足！");

        if (!AuthController::isAdmin(AppModel)) {
            if ((AuthController::user(AppModel)->balance - $array_pop_key['balance']) < 0)
                return response::message(400, "您的可用余额不足，请充值后再试！");
        }
        $Card = strpos($array_pop_key['uid'], "]") > 0 ? User::whereIn("uid", json_decode($array_pop_key['uid'], true)) : User::where("uid", $array_pop_key['uid']);
        if ($Card->increment("balance", $array_pop_key['balance'])) {
            (!AuthController::isAdmin(AppModel)) && AuthController::user(AppModel)->decrement("balance", $array_pop_key['balance']);
            return response::message(200, "充值成功！");
        }
        return response::message(400, "充值失败！");

    }

    public static function delete($array_pop_key)
    {
        if (AuthController::isAgents(AppModel))
            return response::message(400, "权限不足！");
        if (User::whereIn("uid", json_decode($array_pop_key["uid"], true))->delete()) {
            Logs::createds(__("Logs.app"), __("app.del_success", ["uids" => $array_pop_key["uid"]]), AuthController::user(AppModel)->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public static function Agent_List(array $array_pop_key = [])
    {
        if (AuthController::isAgents(AppModel))
            return response::message(400, "权限不足！");
        $array_pop_key["limit"] = !isset($array_pop_key["limit"]) ? 10 : $array_pop_key["limit"];
        $array_pop_key["page"] = !isset($array_pop_key["page"]) ? 1 : $array_pop_key["page"];
        $apps = isset($array_pop_key["user"]) ? User::where("user", 'like', '%' . $array_pop_key["user"] . "%") : User::where("Id", ">", 0);
        !AuthController::isAdmin(AppModel) && ($apps = $apps->where("Inviter_uid", AuthController::user(AppModel)->uid));
        $appList = $apps->paginate($array_pop_key["limit"], ['uid', 'name', 'status', 'user', "app_list", "crad_list", "qq", "email", "spread_number", "Inviter", "reg_ip", "login_count", "discount", "balance", "last_login_ip", "level", "last_login_time", "created_at"], "bh_app", $array_pop_key["page"]);
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = $value['status'] == 0 ? "禁用" : "正常";
            $appList[$key]['discount'] = $value['discount'] . "%";
            $apps = "";
            foreach (json_decode($value['app_list'], true) as $values) {
                $apps .= sprintf('<span class="layui-badge layui-bg-green">%s</span>', substr($values, strpos($values, "|") + 1, 9999));
            }
            $appList[$key]['app_list'] = $apps;
        }
        return $appList;
    }

    public static function EditAgent(array $array_pop_key)
    {
        if (AuthController::isAgents(AppModel))
            return response::message(400, "权限不足！");

        if (isset($array_pop_key["password"])) {
            if (!empty($array_pop_key["password"])) {
                $array_pop_key['password'] = Hash::make($array_pop_key["password"]);
            } else {
                $array_pop_key = Utils::array_pop_key($array_pop_key, "password");
            }
        }
        if ((isset($array_pop_key['level']) || isset($array_pop_key['balance']) && !AuthController::isAdmin(AppModel))) return response::message(400, "不支持此操作！");
        if (User::updateds($array_pop_key)) {
            Logs::createds(__("Logs.app"), __("app.edit_app_success", ["uid" => $array_pop_key["uid"]]), AuthController::user(AppModel)->user);
            return response::message(200, "修改成功！");
        }
        Logs::createds(__("Logs.app"), __("app.edit_app_filed", ["uid" => $array_pop_key["uid"]]), AuthController::user(AppModel)->user);
        return response::message(401, "修改失败！");
    }

    /**
     * count
     * Agent statistics count
     * @return int
     */
    public static function count()
    {
        return User::count();
    }


    /**
     * totalBalance
     * Agent statistics Balanc
     * @return float
     */
    public static function totalBalance()
    {
        $balance = 0;
        try {
            $array = User::select("balance")->get();
            if ($array == null || count($array) == 0) return $balance;
            foreach ($array as $value) {
                $balance += $value->balance;
            }
        } catch (Exception $e) {
        }
        return $balance;
    }


}
