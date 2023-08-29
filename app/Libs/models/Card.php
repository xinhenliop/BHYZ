<?php

namespace App\Libs\models;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Json\response;
use App\Libs\Utils;
use App\Models\AppUsers;
use App\Models\Kami;
use App\Models\Logs;
use App\Models\System;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Card
{


    public static function CreateCard($arrays, $App = null, $Type = null)
    {
        if (empty(($Users = AuthController::user(AppModel))))
            return response::message(400, "权限不足！");
        $arrays['users'] = AuthController::user(AppModel)->user;
        $arrays['user_uid'] = AuthController::user(AppModel)->uid;
        $arrays['created_at'] = Date::now();

        $arrays['uid'] = rand(111111, 99999999);
        $cards = "";
        if ($arrays['type'] == 0) {
            $Cards = [];
            $batch = isset($arrays['batch_card']) ? explode("\r\n", $arrays['batch_card']) : [];
            $generate_count = count($batch) > 0 ? count($batch) : $arrays['count'];
            if (($Type = Kami::where("uid", $arrays['type_uid'])->first()) == null)
                return response::message(400, "分类错误");
            if (($App = \App\Models\App::where("uid", $Type->app_uid)->first()) == null)
                return response::message(400, "程序错误！");
            $suffix = $Type->suffix;
            $prefix = empty($arrays['prefix']) ? $Type->prefix : $arrays['prefix'];
            $arrays = Utils::array_pop_key(Utils::array_pop_key(Utils::array_pop_key($arrays, 'count'), "prefix"), "batch_card");
            if (!AuthController::isAdmin(AppModel)) {
                //不是管理员余额更改
                if (($balance = AuthController::user(AppModel)->balance - ($Type->price * $generate_count * (AuthController::user(AppModel)->discount / 100))) < 0)
                    return response::message(401, "制卡失败，余额不足！");
                //更新余额
                AuthController::user(AppModel)->update(["balance" => $balance]);
            }
            $arrays['name'] = $Type->type;
            $arrays['app_uid'] = $App->uid;
            $arrays['app_name'] = $App->app_name;
            $arrays['unbind_count'] = $App->unbind_count;
            $arrays['type_time'] = $Type->type_time;
            $arrays['time'] = Card::typeToTime($Type->type_time, $Type->time);
            $length = $Type->length - strlen($prefix) - strlen($suffix);
            for ($i = 0; $i < $generate_count; $i++) {
                $arrays['user'] = count($batch) > 0 ? trim($batch[$i]) : ($prefix . Utils::randomString($length, $Type->card_str) . $suffix);
                count($batch) == 0 && ($cards .= $arrays['user'] . "\n");
                $arrays['uid'] = rand(111111, 99999999);
                $Cards[] = $arrays;
                if (($i % 1000) == 0) {
                    AppUsers::createds($Cards);
                    $Cards = [];
                }
            }
            $arrays = $Cards;
            $App->km_count += $generate_count;
        } else {
            $Users = [];
            if (($App = \App\Models\App::where("uid", $arrays['app_uid'])->first()) == null)
                return response::message(400, "用户已存在！");
            $arrays['app_name'] = $App->app_name;
            $arrays['unbind_count'] = $App->unbind_count;
            $batch = isset($arrays['batch_card']) ? explode("\r\n", $arrays['batch_card']) : [];
            $arrays = Utils::array_pop_key($arrays, 'batch_card');
            $generate_count = count($batch) > 0 ? count($batch) : 1;
            $arrays['activate_time'] = time();
            $arrays['activate_date'] = Date::now();
            $i = 0;
            foreach ($batch as $value) {
                $usert = explode("----", $value);
                $arrays['name'] = "用户" . rand(1111, 99999);
                $arrays['user'] = $usert[0];
                $arrays['password'] = Hash::make($usert[1]);
                $Users[] = $arrays;
                if (($i % 1000) == 0) {
                    AppUsers::createds($Users);
                    $Users = [];
                }
                $i++;
            }
            $App->user_count += $generate_count;
        }

        $App->save();
        if (AppUsers::createds($arrays))
            return response::message(200, "创建成功！", $cards);
        return response::message(400, "创建失败！", $cards);
    }

    public static function typeToTime($type, $time)
    {
        return System::getSystem("Times", $type, 0)[1] * $time;
    }

    public static function AddTime(array $array_pop_key)
    {
        if (!AuthController::isAdmin(AppModel))
            return response::message(400, "权限不足！");
        $Card = strpos($array_pop_key['uid'], "]") > 0 ? AppUsers::whereIn("uid", json_decode($array_pop_key['uid'], true)) : AppUsers::where("uid", $array_pop_key['uid']);
        if ($Card->where("end_time", 0)->increment("time", $array_pop_key['time'] * 3600)) {
            $Card->where("end_time", ">", 0)->increment("end_time", $array_pop_key['time'] * 3600);
            return response::message(200, "加时成功！");
        }
        return response::message(400, "加时失败！");

    }

    public static function CardList(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");
        $page = $array_pop_key["page"];
        $limit = $array_pop_key["limit"];
        $all = Utils::array_pop_key(Utils::array_pop_key(Utils::array_pop_key($array_pop_key, "page"), "limit"), "m");
        $Kami = AppUsers::where("type", 0);
        foreach ($all as $keys => $value) {
            if ($keys == "users" or $keys == "user") {
                $Kami = $Kami->where($keys, "like", '%' . $value . '%');
            } else if ($keys == "activate_time") {
                $Kami = $Kami->where($keys, substr($value, 0, 1), substr($value, 1, strlen($value)));
            } else {
                $Kami = $value == 99 ? $Kami->where("end_time", ">", 0) : $Kami->where($keys, $value);
            }
        }
        !AuthController::isAdmin(AppModel) && ($Kami = $Kami->where("user_uid", AuthController::user(AppModel)->uid));
        $appList = $Kami->paginate($limit, ['uid', 'users', 'status', "user", "app_name", 'name', "time", "activate_time", "activate_date", "end_time", "login_count", "unbind_count", "features", "ip", "last_time", "last_ip", "created_at"], "bh_app_users", $page);
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = Card::Status($value['status']);
            if ($value['end_time'] > 0) {
                $appList[$key]['time'] = Card::Time(($value['activate_time'] + $value['time']) - time());
                $appList[$key]['end_time'] = Date::createFromTimestamp($value['end_time'])->format('Y-m-d H:i:s');
            } else {
                $appList[$key]['time'] = Card::Time(($value['time']));
            }
        }
        return $appList;
    }

    public static function Status($status)
    {
        switch ($status) {
            case 0:
                return "已过期";
            case 1:
                return "冻结";
            case 2:
                return "正常";
            case 3:
                return "已激活";
            default:
                return "未知状态";
        }
    }

    public static function Time($times)
    {
        if ($times < 60) {
            return $times . "秒";
        } else if ($times <= (3600)) {
            return (int)($times / 60) . "分钟";
        } else if ($times <= (3600 * 24)) {
            return (int)($times / 3600) . "小时";
        } else if ($times >= (3600 * 24)) {
            return (int)($times / (3600 * 24)) . "天";
        }
        return "未知天数";
    }

    public static function UserList(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");

        $page = $array_pop_key["page"];
        $limit = $array_pop_key["limit"];
        $all = Utils::array_pop_key(Utils::array_pop_key($array_pop_key, "page"), "limit");
        $Kami = AppUsers::where("type", 1);
        foreach ($all as $keys => $value) {
            if ($keys == "users" or $keys == "user") {
                $Kami = $Kami->where($keys, "like", '%' . $value . '%');
            } else if ($keys == "activate_time") {
                $Kami = $Kami->where($keys, substr($value, 0, 1), substr($value, 1, strlen($value)));
            } else {
                $Kami = $value == 99 ? $Kami->where("end_time", ">", 0) : $Kami->where($keys, $value);

            }
        }
        !AuthController::isAdmin(AppModel) && ($Kami = $Kami->where("user_uid", AuthController::user(AppModel)->uid));
        $appList = $Kami->paginate($limit, ['uid', 'status', "user", "app_name", "time", "activate_time", "end_time", "activate_date", "login_count", "unbind_count", "features", "ip", "last_time", "last_ip", "created_at"], "bh_app_users", $page);
        foreach ($appList as $key => $value) {
            $appList[$key]['status'] = Card::Status($value['status']);
            if ($value['end_time'] > 0) {
                $appList[$key]['time'] = Card::Time(($value['activate_time'] + $value['time']) - time());
                $appList[$key]['end_time'] = Date::createFromTimestamp($value['end_time'])->format('Y-m-d H:i:s');
            } else {
                $appList[$key]['time'] = Card::Time(($value['time']));
            }
        }
        return $appList;
    }

    public static function EditCard(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");

        if (isset($array_pop_key["password"])) {
            if (!empty($array_pop_key["password"])) {
                $array_pop_key['password'] = Hash::make($array_pop_key["password"]);
            } else {
                $array_pop_key = Utils::array_pop_key($array_pop_key, "password");
            }
        }

        if (isset($array_pop_key["end_time"])) {
            if (!empty($array_pop_key["end_time"])) {
                $array_pop_key['end_time'] = strtotime($array_pop_key["end_time"]);
                $array_pop_key['end_time'] > time() && $array_pop_key['status'] = 3;
            } else {
                $array_pop_key = Utils::array_pop_key($array_pop_key, "end_time");
            }
        }
        if ((isset($array_pop_key['time']) || isset($array_pop_key['end_time']) && !AuthController::isAdmin(AppModel))) return response::message(400, "不支持此操作！");
        if (AppUsers::updateds($array_pop_key)) {
            Logs::createds(__("Logs.card"), __("type.edit_card_success", ["uid" => $array_pop_key["uid"]]), AuthController::user(AppModel)->user);
            return response::message(200, "修改成功！");
        }
        Logs::createds(__("Logs.card"), __("type.edit_card_filed", ["uid" => $array_pop_key["uid"]]), AuthController::user(AppModel)->user);
        return response::message(401, "修改失败！");
    }


    public static function DeleteCard(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");
        $Card = AppUsers::whereIn("uid", json_decode($array_pop_key["uid"], true));
        !AuthController::isAdmin(AppModel) && ($Card = $Card->where("user_uid", AuthController::user(AppModel)->uid));
        if ($Card->delete()) {
            //Logs::createds(__("Logs.card"),__("type.edit_card_success",["uid"=>$array_pop_key["uid"]]),AuthController::user(AppModel)->user);
            return response::message(200, "删除成功！");
        }
        //Logs::createds(__("Logs.card"),__("type.edit_card_filed",["uid"=>$array_pop_key["uid"]]),AuthController::user(AppModel)->user);
        return response::message(401, "删除失败！");
    }

    public static function CardInquire(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");
        $Card = explode("\r\n", $array_pop_key['batch_card']);
        if (count($Card) <= 0) {
            return response::message(401, "查询卡密为空！");
        }
        $returns = "";
        foreach (AppUsers::whereIn("user", $Card)->get() as $value) {
            $returns .= $value->user . "  状态：" . Card::Status($value->status);
            if ($value->status == 3) {
                $returns .= " 到期时间：" . date("Y-m-d H:i:s", $value->end_time);
            }
            $returns .= "\n";
        }
        return response::message(200, "查询成功！", $returns);
    }

    public static function CardMaintenance(array $array_pop_key)
    {
        if (AuthController::isAgent(AppModel))
            return response::message(400, "权限不足！");
        ($array_pop_key['app_uid'] == "ALL") && $array_pop_key = Utils::array_pop_key($array_pop_key, "app_uid");
        $Cards = null;
        switch ($array_pop_key['type']) {
            case 'ID':
                $Ids = explode(",", $array_pop_key['wheres']);
                if (count($Ids) == 0) return response::message(400, "列表不能为空！");
                $Cards = AppUsers::whereIn('Id', $Ids);
                break;
            case 'CARD':
                $Ids = explode("\r\n", $array_pop_key['wheres']);
                if (count($Ids) == 0) return response::message(400, "列表不能为空！");
                $Cards = AppUsers::whereIn('user', $Ids);
                break;
            case 'STATUS':
                if ($array_pop_key['status'] == -1) return response::message(400, "参数错误！");
                $Cards = AppUsers::where('status', $array_pop_key['status']);
                break;
            case 'ATIME':
                if (empty($array_pop_key['start']) || empty($array_pop_key['end'])) return response::message(400, "时间不能为空！");
                $Cards = AppUsers::where('activate_time', ">", $array_pop_key['start'])->where('activate_time', '<', $array_pop_key['end']);
                break;
            case 'ETIME':
                if (empty($array_pop_key['start']) || empty($array_pop_key['end'])) return response::message(400, "时间不能为空！");
                $Cards = AppUsers::where('end_time', ">", strtotime($array_pop_key['start']))->where('end_time', '<', strtotime($array_pop_key['end']));
                break;
            case 'USER':
                if (empty($array_pop_key['user'])) return response::message(400, "时间不能为空！");
                $Cards = AppUsers::where('user', $array_pop_key['user']);
                break;
        }
        $Cards = (AuthController::isAdmin(AppModel)) ? $Cards : $Cards->where('user_uid', AuthController::user(AppModel)->uid);

        switch ($array_pop_key['towhere']) {
            case 'status=2':
                $Cards->update(['activate_time' => 0, 'activate_date' => null, "end_time" => 0, "status" => 2, "features" => "", "ip" => ""]);
                break;
            case 'status=1':
                $Cards->update(["status" => 1]);
                break;
            case 'status=3':
                $Cards->where("end_time", ">", 0)->update(["status" => 3]);
                $Cards->where("end_time", 0)->update(["status" => 2]);
                break;
            case 'add':
                $Cards->where("end_time", ">", 0)->increment(["time" => ((int)$array_pop_key['wheretwo']) * 3600, "end_time" => ((int)$array_pop_key['wheretwo']) * 3600]);
                $Cards->where("end_time", 0)->increment(["time" => ((int)$array_pop_key['wheretwo']) * 3600]);
                break;
            case 'time':
                $Cards->where("end_time", ">", 0)->update(["time" => ((int)$array_pop_key['wheretwo']) * 3600, "end_time" => ((int)$array_pop_key['wheretwo']) * 3600]);
                $Cards->where("end_time", 0)->update(["time" => ((int)$array_pop_key['wheretwo']) * 3600]);
                break;
            case 'del':
                $Cards->delete();
                break;
        }
        return response::message(200, "维护成功！");
    }

    public static function register(mixed $user, mixed $password, mixed $email, mixed $appid)
    {
        if (self::getCard($user) != null) {
            return ["code" => "407", "msg" => "账号已存在！"];
        }
        $Users = new AppUsers();
        $Users->type = 1;
        $Users->user = $user;
        $Users->password = md5(trim($password));
        $Users->email = $email;
        $Users->uid = rand(11111, 99999999);
        $Users->app_uid = $appid;
        $Users->activate_time = time();
        $Users->activate_date = Date::now();

        if ($Users->save()) return ["code" => "200", "msg" => "注册成功！. " . $password];
        return ["code" => "408", "msg" => "注册失败！"];
    }

    public static function getCard($user, $field = "user")
    {
        return AppUsers::where($field, $user)->first();
    }

    /**
     * getDate
     * get Models Date
     * @param $arr array
     * @param $arr1 array
     * @return array
     */
    public static function getDate($arr, $arr1)
    {
        $A1 = array_column($arr, 'Date');
        $A2 = array_column($arr1, 'Date');
        return array_unique(array_merge($A1, $A2));
    }

    /**
     * user_chart
     * Home page statistics chart
     * @param $array array
     * @return array
     */
    public static function user_chart(array $array): array
    {
        $labal = isset($array['end']) ? "end_time" : "activate_time";
        $user = isset($array['user']) ? 1 : 0;
        $wheres = !AuthController::isAdmin(AppModel) ? "" : "`user_uid`=" . AuthController::user(AppModel)->uid . " AND ";
        $card = DB::select("SELECT from_unixtime(`$labal`, '" . $array['day'] . "') as Date,COUNT(`Id`) as Count FROM `bh_app_users` WHERE $wheres`type`=$user AND `$labal`<" . $array['date_date'] . " AND `$labal`>" . $array['date_date'] - $array['today'] . " GROUP BY Date;");
        $data['name'] = (isset($array['user']) ? "用户" : "卡密") . (isset($array['end']) ? "到期" : "激活");
        $data['data'] = $card;
        return $data;
    }

    /**
     * user_count
     * Home page statistics count
     * @param $array array
     * @return int
     */
    public static function user_count(array $array): int
    {
        $labal = isset($array['end']) ? "end_time" : "activate_time";
        $user = isset($array['user']) && $array['user'] ? 1 : 0;
        $Users = AppUsers::where("type", $user);
        !AuthController::isAdmin(AppModel) && $Users = $Users->where("user_uid", AuthController::user(AppModel)->uid);
        return $Users->where($labal, "<", $array['date_date'])->where($labal, ">", $array['date_date'] - $array['today'])->count();
    }

    /**
     * count
     * Home page statistics count
     * @param $array array
     * @return int
     */
    public static function count(array $array): int
    {
        $user = isset($array['user']) && $array['user'] ? 1 : 0;
        $Users = AppUsers::where("type", $user);
        isset($array['where']) && count($array['where']) > 0 && $Users = $Users->where($array['where']);
        !AuthController::isAdmin(AppModel) && $Users = $Users->where("user_uid", AuthController::user(AppModel)->uid);
        return $Users->count();
    }

    public static function getType($type_time)
    {

    }

}
