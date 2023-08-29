<?php

namespace App\Libs\models;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Json\response;
use App\Libs\Encrypt\Encrypt_index;
use App\Models\Logs;

class App
{
    public static function CreatedApp($array)
    {
        if (!AuthController::isAdmin(AppModel)) return response::message(200, "权限不足！");
        if (\App\Models\App::createds($array)) {
            Logs::createds(__("Logs.app"), __("app.created_app_success", ["name" => $array["app_name"]]), AuthController::user(AppModel)->user);
            return response::message(200, "创建成功！");
        }
        Logs::createds(__("Logs.app"), __("app.created_app_filed", ["name" => $array["app_name"]]), AuthController::user(AppModel)->user);
        return response::message(401, "创建失败！");
    }

    public static function EditApp($array)
    {
        if (!AuthController::isAdmin(AppModel)) return response::message(200, "权限不足！");
        if (\App\Models\App::updateds($array)) {
            Logs::createds(__("Logs.app"), __("app.edit_app_success", ["uid" => $array["uid"]]), AuthController::user(AppModel)->user);
            return response::message(200, "修改成功！");
        }
        Logs::createds(__("Logs.app"), __("app.edit_app_filed", ["uid" => $array["uid"]]), AuthController::user(AppModel)->user);
        return response::message(401, "修改失败！");
    }

    public static function DelApp($array)
    {
        if (!AuthController::isAdmin(AppModel)) return response::message(200, "权限不足！");
        if (\App\Models\App::whereIn("uid", json_decode($array["uid"], true))->delete()) {
            Logs::createds(__("Logs.app"), __("app.del_success", ["uids" => $array["uid"]]), AuthController::user(AppModel)->user);
            return response::message(200, __("message.success"));
        }
        return response::message(400, __("message.filed"));
    }

    public static function AppList($array)
    {
        !isset($array['limit']) && $array['limit'] = 10;
        !isset($array['page']) && $array['page'] = 1;
        $apps = isset($array["app_name"]) ? \App\Models\App::where("app_name", 'like', '%' . $array["app_name"] . "%") : \App\Models\App::where("Id", ">", 0);
        $appList = $apps->paginate($array["limit"], ['uid', 'app_name', 'app_status', 'user_count', "km_count", "version", "encrypt_mode", "validate_app_md5", "transmission", "app_url", "app_data", "description", "socket_encrypt", "created_at"], "bh_app", $array["page"]);
        foreach ($appList as $key => $value) {
            $appList[$key]['app_status'] = $value['app_status'] == 0 ? "停用" : "启用";
            $appList[$key]['encrypt_mode'] = $value['encrypt_mode'];
            $appList[$key]['socket_encrypt'] = $value['socket_encrypt'];
            $appList[$key]['transmission'] = Encrypt_index::transmission($value['transmission']);
        }
        return $appList;
    }

    /**
     * count
     * Home page statistics count
     * @param string|null $lable string
     * @param string|null $value string
     * @return int
     */
    public static function count(string $lable = null, string $value = null): int
    {
        return \App\Models\App::count();
    }
}
