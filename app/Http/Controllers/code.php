<?php

namespace App\Http\Controllers;

use App\Libs\captcha\CaptchaBuilder;
use App\Libs\models\Card;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

/*
 *
 * 验证码生产
*/

class code
{
    function __invoke(Request $request, Response $response)
    {
        $config = config('system')['captcha'];
        $captcha = new CaptchaBuilder();
        $captcha->initialize($config);
        $captcha->create();
        $request->session()->put('captcha', $captcha->getText());
        header('Content-Type: image/jpeg');
        return (new Response($captcha->output(), 200))->header('Content-Type', 'image/jpeg');
    }

    public function card(Request $request)
    {
        if ($request->isMethod("GET")) {
            return view("code");
        }
        if (!isset($_POST['user'])) return \response()->json(["code" => 200, "msg" => "参数不能为空！"]);
        $config = System::getSystem("captcha", "web", true);
        if ($config && $request->input("code") != $request->session()->get('captcha', "0000")) {
            return \response()->json(["code" => 200, "msg" => "验证码错误！"]);
        }
        $request->session()->put('captcha', "");
        $card = Card::getCard($_POST['user']);
        $response = ["code" => 200, "msg" => "   卡密: " . $card->user . "  类型：" . System::getSystem("Times", $card->type_time, 0)[0] . "卡  状态：" . Card::Status($card->status)];
        $card->end_time > 0 && $response['msg'] .= " 到期时间：" . Date::createFromTimestamp($card->end_time)->format('Y-m-d H:i:s');
        return \response()->json($response);
    }
}
