<?php

namespace App\Libs\models;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Kami;
use App\Models\Plugin;

/**
 *
 */
class Statistics
{
    /**
     * @param $class
     * @param $user
     * @param $end
     * @return \Illuminate\Http\JsonResponse
     */
    public static function total($class, $user, $end)
    {
        #获取统计数据
        $card = Card::count($class->extractDate($user, false));
        $data['activation'] = Card::user_count($class->extractDate($user, false));
        if ($user) {
            $data['user'] = $card;
            $data['app'] = AuthController::isAdmin(AppModel) ? \App\Models\App::count() : count(json_decode(AuthController::user(AppModel)->app_list, true));
        } else {
            $data['card'] = $card;
            $data['expired'] = Card::user_count($class->extractDate($user, true));
            $data['card_type'] = AuthController::isAdmin(AppModel) ? Kami::count() : count(json_decode(AuthController::user(AppModel)->app_list, true));
        }
        $result = [
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }


    public static function home_total($class)
    {
        $date = $class->extractDate(false, false);
        $data['card'] = Card::count($date);
        $data['user'] = Card::count($class->extractDate(true, false));
        $data['app'] = \App\Libs\models\App::count();
        $data['activation'] = Card::user_count($date);
        $data['expired'] = Card::user_count($class->extractDate(false, true));
        $data['registrations'] = Card::user_count($class->extractDate(true, false));
        $data['water'] = \App\Libs\models\Bill::bill_count($date);
        $data['agent'] = \App\Libs\models\Agent::count();
        $data['total'] = \App\Libs\models\Agent::totalBalance();
        $result = [
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * @param $class
     * @param $user
     * @param $end
     * @return \Illuminate\Http\JsonResponse
     */
    public static function status($class, $user, $end)
    {
        $data = [];
        $where = [["end_time", "<", time()], ["status", 0]];
        $where1 = [["end_time", ">", time()]];
        if (!$user) {
            $where1[] = ["status", 3];
            $data[] = ["已激活", Card::count(["user" => $user, "where" => $where1])];
        }
        $data[] = ["已过期", Card::count(["user" => $user, "where" => $where])];
        $data[] = ["未激活", Card::count(["user" => $user, "where" => [["status", 2], ["end_time", 0]]])];
        $data[] = ["冻结", Card::count(["user" => $user, "where" => [["status", 1]]])];

        #获取统计数据
        $result = [
            "title" => "状态分类",
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    public static function app($class, $user, $end)
    {
        if (AuthController::isAdmin(AppModel)) {
            $App = \App\Models\App::where("Id", ">", 1);
        } else {
            $uid = [];
            foreach (json_decode(AuthController::user(AppModel)->app_list, true) as $value) $uid[] = substr($value, 0, strpos($value, "|"));
            $App = \App\Models\App::where("app_status", 1)->whereIn("uid", $uid);
        }
        $data = [];
        foreach (($App->get()) as $value) {
            $data[] = [$value->type, Card::count(["user" => $user, "where" => [["app_uid", "=", $value->uid]]])];
        }
        #获取统计数据
        $result = [
            "title" => "APP分类",
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }


    public static function type($class, $user, $end)
    {
        if (AuthController::isAdmin(AppModel)) {
            $Kami = Kami::where("Id", ">", 1);
        } else {
            $uid = [];
            foreach (json_decode(AuthController::user(AppModel)->app_list, true) as $value) $uid[] = substr($value, 0, strpos($value, "|"));
            $Kami = Kami::where("status", 1)->whereIn("app_uid", $uid);
        }
        $data = [];
        foreach (($Kami->get()) as $value) {
            $data[] = [$value->type, Card::count(["where" => [["type_uid", "=", $value->uid]]])];
        }
        #获取统计数据
        $result = [
            "title" => "卡类分类",
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }


    public static function chart($class, $user, $home = false)
    {
        if ($home) {
            $date = $class->extractDate(false, false);
            #获取统计数据
            $card = Card::user_chart($date);
            $user = Card::user_chart($class->extractDate(true, false));
            $data["Date"] = Card::getDate($card['data'], $user['data']);
            $card_end = Card::user_chart($class->extractDate(false, true));
            $user_end = Card::user_chart($class->extractDate(true, true));
            $data["Date"] = array_values(array_unique(array_merge($data["Date"], Card::getDate($card_end['data'], $user_end['data']))));
            $counts = [
                ["name" => $card['name']]
                , ["name" => $card_end['name']]
                , ["name" => $user['name']]
                , ["name" => $user_end['name']]];
            foreach ($data["Date"] as $key => $value) {
                $counts[0]["data"][$key] = 0;
                $counts[1]["data"][$key] = 0;
                $counts[2]["data"][$key] = 0;
                $counts[3]["data"][$key] = 0;
                foreach ($card['data'] as $values) {
                    if ($values->Date == $value) $counts[0]["data"][$key] = $values->Count;
                }
                foreach ($card_end['data'] as $values) {
                    if ($values->Date == $value) $counts[1]["data"][$key] = $values->Count;
                }
                foreach ($user['data'] as $values) {
                    if ($values->Date == $value) $counts[2]["data"][$key] = $values->Count;
                }
                foreach ($user_end['data'] as $values) {
                    if ($values->Date == $value) $counts[3]["data"][$key] = $values->Count;
                }
            }
            $result = [
                "code" => 200,
                "title" => $class->getDay($date['today']) . "数据统计",
                "subtitle" => "",
                "xAxis" => $data["Date"],
                "data" => $counts,
                "msg" => "",
            ];
            return response()->json($result);
        }
        $date = $class->extractDate($user, false);
        $card = Card::user_chart($date);
        $card_end = Card::user_chart($class->extractDate($user, true));
        return $class->extracted($card_end, $card, $date['today']);
    }

}
