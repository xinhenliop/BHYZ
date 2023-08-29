<?php

namespace App\Libs\models;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Admin
{
    public static function delete($array_pop_key)
    {
        if (empty(($Users = AuthController::user(AppModel))) && $Users->root != 1)
            return response::message(400, "权限不足！");
        if (\App\Models\Admin::whereIn("uid", json_decode($array_pop_key["uid"], true))->delete()) {
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public static function updated(array $all)
    {
        if (empty(($Users = AuthController::user(AppModel))) && $Users->root != 1)
            return response::message(400, "权限不足！");

        if (isset($all["password"])) {
            if (!empty($all["password"])) {
                $all['password'] = Hash::make($all["password"]);
            } else {
                $all = Utils::array_pop_key($all, "password");
            }
        }
        if (isset($all["admin_system"])) {
            $all["admin_system"] = json_encode($all["admin_system"]);
        }
        if (\App\Models\Admin::updateds(Utils::array_pop_key($all, "m"))) {
            log(__("Logs.operate"), "修改管理员：" . $all["user"]);
            return response::message(200, "修改成功！");
        }
        return response::message(401, "修改失败！");
    }

    public static function register($all)
    {
        if (($User = AuthController::user(AppModel)) == null || $User->root != 1) {
            return response::message(401, "权限不足！");
        }
        $validator = Validator::make($all,
            ['user' => 'required', 'password' => 'required', "admin_system" => "required"]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $users = new \App\Models\Admin();
        $users->user = $all["user"];
        $users->password = Hash::make($all["password"]);
        $users->uid = rand(111111, 99999999);
        $users->admin_system = json_encode($all["admin_system"]);
        $users->status = 1;
        if ($users->save()) {
            log(__("Logs.operate"), "创建管理员：" . $all["user"]);
            return response::message(200, "创建成功！");
        }

        return response::message(400, "创建失败！");
    }

    public static function adminList($all)
    {
        if (($User = AuthController::user(AppModel)) == null || $User->root != 1) {
            return response::message(401, "权限不足！");
        }
        $all["limit"] = !isset($all["limit"]) ? 10 : $all["limit"];
        $all["page"] = !isset($all["page"]) ? 1 : $all["page"];
        $apps = isset($all["user"]) ? \App\Models\Admin::where("user", 'like', '%' . $all["user"] . "%") : \App\Models\Admin::where("Id", ">", 0);
        $appList = $apps->paginate($all["limit"], ['uid', 'user', 'root', 'status', "login_count", "last_login_time", "last_login_ip", "created_at"], "bh_app", $all["page"]);
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = $value['status'] == 0 ? "禁用" : "启用";
            $appList[$key]['root'] = $value['root'] == 0 ? "普通管理员" : "超级管理员";
        }
        return $appList;
    }
}
