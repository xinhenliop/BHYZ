<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\Auths;
use App\Libs\models\Card;
use App\Models\Kami;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\View;

class statistics extends Auths
{
    protected $url = "statistics";

    function __construct(Request $request)
    {
        parent::__construct($request, $this->url);
    }

    /**
     * index_chart
     * Home page statistics chart
     * @return JsonResponse
     */
    public function index_chart()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);

        //统计时间计算
        $date = $this->extractDate(false, false);
        #获取统计数据
        $card = Card::user_chart($date);
        $user = Card::user_chart($this->extractDate(true, false));
        $data["Date"] = Card::getDate($card['data'], $user['data']);
        $card_end = Card::user_chart($this->extractDate(false, true));
        $user_end = Card::user_chart($this->extractDate(true, true));
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
            "title" => $this->getDay($date['today']) . "数据统计",
            "subtitle" => "",
            "xAxis" => $data["Date"],
            "data" => $counts,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * @param bool $user
     * @param bool $end
     * @return array
     */
    public function extractDate(bool $user, bool $end): array
    {
        $data['day'] = (int)$this->input("today");
        $date = [date("Y"), date("m"), date("d")];
        $data['date_date'] = $data['day'] == 999 ? time() : strtotime($date[0] . '-' . $date[1] . '-' . $date[2] . " 00:00:00");
        $data['today'] = $data['day'] * 24 * 3600;
        $data['day'] = $data['day'] > 1 ? "%m-%d" : "%H";
        $user && $data['user'] = true;
        $end && $data['end'] = true;
        return $data;
    }

    /**
     * user_chart
     * Home page statistics chart
     * @return JsonResponse
     */
    public function user_chart()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);
        //统计时间计算
        #获取统计数据
        $date = $this->extractDate(true, false);
        $card = Card::user_chart($date);
        $date['end'] = true;
        $card_end = Card::user_chart($date);
        return $this->extracted($card_end, $card, $date['today']);
    }

    /**
     * @param array $card_end
     * @param array $card
     * @param float|int $today
     * @return JsonResponse
     */
    public function extracted(array $card_end, array $card, float|int $today): JsonResponse
    {
        $data["Date"] = Card::getDate($card_end['data'], $card_end['data']);
        $counts = [
            ["name" => $card['name']]
            , ["name" => $card_end['name']]];
        foreach ($data["Date"] as $key => $value) {
            $counts[0]["data"][$key] = 0;
            $counts[1]["data"][$key] = 0;
            foreach ($card['data'] as $values) {
                if ($values->Date == $value) $counts[0]["data"][$key] = $values->Count;
            }

            foreach ($card_end['data'] as $values) {
                if ($values->Date == $value) $counts[1]["data"][$key] = $values->Count;
            }
        }
        $result = [
            "code" => 200,
            "title" => $this->getDay($today) . "数据统计",
            "subtitle" => "",
            "xAxis" => $data["Date"],
            "data" => $counts,
            "msg" => "",
        ];
        return response()->json($result);
    }

    public function getDay($day)
    {
        switch ($day) {
            case 1:
                return "昨天";
            case 7:
                return "本周";
            case 30:
                return "本月";
            case 90:
                return "本季";
            case 9999:
                return "所有";
        }
    }

    /**
     * index_total
     * Home page statistics total
     * @return JsonResponse
     */
    public function index_total()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);
        //统计时间计算
        $date = $this->extractDate(false, false);
        #获取统计数据
        $data['card'] = Card::count($date);
        $data['user'] = Card::count($this->extractDate(true, false));
        $data['app'] = \App\Libs\models\App::count();
        $data['activation'] = Card::user_count($date);
        $data['expired'] = Card::user_count($this->extractDate(false, true));
        $data['registrations'] = Card::user_count($this->extractDate(true, false));
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
     * card_chart
     * Home page statistics chart
     * @return JsonResponse
     */
    public function card_chart()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);
        //统计时间计算
        #获取统计数据
        $date = $this->extractDate(false, false);
        $card = Card::user_chart($date);
        $card_end = Card::user_chart($this->extractDate(false, true));
        return $this->extracted($card_end, $card, $date['today']);
    }

    /**
     * card_chart
     * card page statistics total
     * @return JsonResponse
     */
    public function card_total()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);

        //统计时间计算
        $date = $this->extractDate(false, false);
        #获取统计数据
        $data['card'] = Card::count($date);
        $data['activation'] = Card::user_count($date);
        $data['expired'] = Card::user_count($this->extractDate(false, true));
        $data['card_type'] = AuthController::isAdmin(AppModel) ? Kami::count() : count(json_decode(AuthController::user(AppModel)->app_list, true));
        $result = [
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * index_total
     * Home page statistics total
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function user()
    {
        return view("admin.statistics.user")->with($this->agvs);
    }

    /**
     * card_type
     * card page statistics total
     * @return JsonResponse
     */
    public function card_type()
    {
        //取卡类
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

    /**
     * card_app
     * card page statistics total
     * @return JsonResponse
     */
    public function card_app()
    {
        //取卡类
        if (AuthController::isAdmin(AppModel)) {
            $App = \App\Models\App::where("Id", ">", 1);
        } else {
            $uid = [];
            foreach (json_decode(AuthController::user(AppModel)->app_list, true) as $value) $uid[] = substr($value, 0, strpos($value, "|"));
            $App = \App\Models\App::where("app_status", 1)->whereIn("uid", $uid);
        }
        $data = [];
        foreach (($App->get()) as $value) {
            $data[] = [$value->type, Card::count(["where" => [["app_uid", "=", $value->uid]]])];
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

    /**
     * card_status
     * card page statistics total
     * @return JsonResponse
     */
    public function card_status()
    {
        //取卡类
        $data = [];
        $data[] = ["已过期", Card::count(["where" => [["end_time", "<", time()], ["status", 0]]])];
        $data[] = ["未激活", Card::count(["where" => [["status", 2]]])];
        $data[] = ["冻结", Card::count(["where" => [["status", 1]]])];
        $data[] = ["已激活", Card::count(["where" => [["status", 3], ["end_time", ">", time()]]])];
        #获取统计数据
        $result = [
            "title" => "状态分类",
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * card_chart
     * card page statistics total
     * @return JsonResponse
     */
    public function user_total()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);
        //统计时间计算
        $date = $this->extractDate(true, false);
        #获取统计数据
        $data['user'] = Card::count($date);
        $data['activation'] = Card::user_count($this->extractDate(true, true));
        $data['app'] = AuthController::isAdmin(AppModel) ? \App\Models\App::count() : count(json_decode(AuthController::user(AppModel)->app_list, true));
        $result = [
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * user_status
     * card page statistics total
     * @return JsonResponse
     */
    public function user_status()
    {
        //取卡类
        $data = [];
        $data[] = ["已过期", Card::count(["user" => true, "where" => [["end_time", "<", time()]]])];
        $data[] = ["未过期", Card::count(["user" => true, "where" => [["end_time", ">", time()]]])];
        $data[] = ["冻结", Card::count(["user" => true, "where" => [["status", 1]]])];
        #获取统计数据
        $result = [
            "title" => "状态分类",
            "code" => 200,
            "data" => $data,
            "msg" => "",
        ];
        return response()->json($result);
    }

    /**
     * card_app
     * card page statistics total
     * @return JsonResponse
     */
    public function user_app()
    {
        //取卡类
        if (AuthController::isAdmin(AppModel)) {
            $App = \App\Models\App::where("Id", ">", 1);
        } else {
            $uid = [];
            foreach (json_decode(AuthController::user(AppModel)->app_list, true) as $value) $uid[] = substr($value, 0, strpos($value, "|"));
            $App = \App\Models\App::where("app_status", 1)->whereIn("uid", $uid);
        }
        $data = [];
        foreach (($App->get()) as $value) {
            $data[] = [$value->type, Card::count(["user" => true, "where" => [["app_uid", "=", $value->uid]]])];
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

    /**
     * index_total
     * Home page statistics total
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function card()
    {
        return view("admin.statistics.card")->with($this->agvs);
    }
}
