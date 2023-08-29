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
        return \App\Libs\models\Statistics::chart($this, false, true);
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
        return \App\Libs\models\Statistics::chart($this, true);
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
        return \App\Libs\models\Statistics::home_total($this);
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
        return \App\Libs\models\Statistics::chart($this, false);
    }

    /**
     * card_chart
     * card page statistics total
     * @return JsonResponse
     */
    public function card_total()
    {
        if (!$this->input("today", false)) return response()->json(["code" => "201", "msg" => "参数不对！"]);
        return \App\Libs\models\Statistics::total($this, false, false);
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
        return \App\Libs\models\Statistics::type($this, false, false);
    }

    /**
     * card_app
     * card page statistics total
     * @return JsonResponse
     */
    public function card_app()
    {
        //取卡类
        return \App\Libs\models\Statistics::app($this, false, false);
    }

    /**
     * card_status
     * card page statistics total
     * @return JsonResponse
     */
    public function card_status()
    {
        //取卡类
        return \App\Libs\models\Statistics::status($this, false, false);
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
        return \App\Libs\models\Statistics::total($this, true, false);
    }

    /**
     * user_status
     * card page statistics total
     * @return JsonResponse
     */
    public function user_status()
    {
        //取卡类
        return \App\Libs\models\Statistics::status($this, true, false);
    }

    /**
     * card_app
     * card page statistics total
     * @return JsonResponse
     */
    public function user_app()
    {
        //取卡类

        return \App\Libs\models\Statistics::app($this, true, false);
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
