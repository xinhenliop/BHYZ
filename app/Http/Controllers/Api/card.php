<?php

namespace App\Http\Controllers\Api;

use App\Libs\models\Email;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class card extends ApiController
{
    public function __construct($appid = null, $request = null)
    {
        parent::__construct($appid, $request);
    }

    public function login()
    {
        $user = $this->params("user", false);
        $password = $this->params("password", "");
        $device = $this->params("device", false);

        // 数据效验
        if (!$user) return $this->response(401, "账号不可为空！");
        if (!$device) return $this->response(402, "设备码不可为空！");

        // 获取用户实列
        if (($Card = \App\Libs\models\Card::getCard(trim($user))) == null) return $this->response(403, "不存在此用户！");
        //用户实列登录
        if ($Card->type == 1 && $Card->password != md5(trim($password))) return $this->response(406, "密码错误！");


        //用户状态检测
        if ($Card->status == 1) return $this->response(412, "用户已被禁止登录！");
        if ($Card->status == 0 || ($Card->status == 3 && $Card->end_time < time()) || ($Card->type == 1 && time() > $Card->end_time)) {
            $Card->status = 0;
            $Card->save();
            return $this->response(411, "用户已到期！");
        }

        if (((time() - strtotime($Card->last_time)) < $this->App->token_validate) && !empty($Card->token) && $this->App->user_more == 0) return $this->response(200, "设备已登录！");
        //检测设备IP信息
        if (!empty($Card->features) && $this->App->bind_device == 1 && $Card->features != $device) return $this->response(404, "设备码不匹配！");
        if (!empty($Card->ip) && $this->App->bind_ip == 1 && $Card->ip != $this->clinet_address) return $this->response(405, "IP不匹配！");

        //检测是否已经登陆
        //是否第一次激活
        if ($Card->end_time == 0) {
            $Card->end_time = time() + $Card->time;
            $Card->activate_date = Date::now();
            $Card->activate_time = time();
            $Card->status = 3;
        }
        //状态更新
        $Card->login_count += 1;
        $Card->token = Str::random(32);
        $this->updateCard($Card);
        $return = [
            "token" => $Card->token,
            "user" => $user,
            "appid" => $this->appid,
            "data" => $this->App->app_data,
            "end_time" => $Card->end_time,
            "end_time_str" => Date::createFromTimestamp($Card->end_time)->format('Y-m-d H:i:s'),
            "ip" => $this->clinet_address,
            "device" => $device
        ];
        return $this->response(200, "登录成功！", $return);
    }

    public function updateCard($Card)
    {
        $Card->ip = $this->clinet_address;
        $Card->features = $this->params("device", $Card->features);
        $Card->last_time = Date::now();
        $Card->last_ip = $this->clinet_address;
        $Card->save();
    }

    public function register()
    {
        $user = $this->params("user", false);
        $password = $this->params("password", false);
        $email = $this->params("email", "");
        if (!$user || !$password) {
            return $this->response(406, "账号密码不可为空！");
        }
        $return = \App\Libs\models\Card::register($user, $password, $email, $this->appid);
        return $this->response($return['code'], $return['msg']);
    }

    public function ubind()
    {
        $user = $this->params("user", false);
        $device = $this->params("device", false);
        // 数据效验
        if (!$user) return $this->response(401, "账号不可为空！");
        if (!$device) return $this->response(402, "设备码不可为空！");
        // 获取用户实列
        if (($Card = \App\Libs\models\Card::getCard(trim($user))) == null) return $this->response(403, "不存在此用户！");
        //是否未激活
        if ($Card->end_time <= 0) return $this->response(409, "用户未激活！");
        //是否满足换绑次数
        if ($Card->unbind_count <= 0) return $this->response(410, "换绑次数不足！");
        //是否满足换绑时间
        if (($Card->end_time - $this->App->unbind_time - time()) <= 0) return $this->response(411, "换绑时间不足！");
        //更新用户信息
        $Card->unbind_count -= 1;
        $this->updateCard($Card);
        return $this->response(200, "解绑成功！");
    }

    public function check()
    {
        $user = $this->params("user", false);
        $device = $this->params("device", false);
        // 数据效验
        if (!$user) return $this->response(401, "账号不可为空！");
        if (!$device) return $this->response(402, "设备码不可为空！");
        // 获取用户实列
        if (($Card = \App\Libs\models\Card::getCard(trim($user), "user")) == null) return $this->response(403, "用户不存在！");
        //状态检测
        if (((time() - strtotime($Card->last_time)) < $this->App->token_validate) && !empty($Card->token)) return $this->response(200, "设备已登录！");
        return $this->response(400, "用户未登录！");
    }

    public function recharge()
    {
        $user = $this->params("user", false);
        $card = $this->params("card", false);
        // 数据效验
        if (!$user) return $this->response(401, "账号不可为空！");
        if (!$card) return $this->response(402, "卡密不可为空！");
        // 获取用户实列
        // 获取卡密实列
        if (($User = \App\Libs\models\Card::getCard(trim($user), "user")) == null) return $this->response(403, "用户不存在！");
        if (($Card = \App\Libs\models\Card::getCard(trim($card), "user")) == null) return $this->response(403, "卡密不存在！");
        //用户状态
        if ($User->status == 1 || $User->type == 0) return $this->response(412, "此用户禁止充值！");
        //用户类型
        if ($Card->status != 2 || $Card->type == 1) return $this->response(415, "此类型卡密不可充值！");
        //充值用户数据修改
        $User->end_time += $Card->time;
        $User->end_time > time() && $User->status = 2;
        $User->add_km += 1;
        $recharge = json_decode($User->recharge, true);
        $recharge[] = ['card' => $card, "time" => Date::now(), "duration" => $Card->time];
        $User->recharge = json_encode($recharge);
        //充值卡密数据修改
        $Card->status = 0;
        $Card->end_time = time();
        $Card->activate_time = time();
        $Card->activate_date = Date::now();
        if ($Card->save() && $User->save()) {
            return $this->response(200, "充值成功！");
        }
        return $this->response(418, "充值成功！");
    }

    public function retrieve()
    {
        $user = $this->params("user", false);
        $email = $this->params("email", false);
        if (!$user) return $this->response(401, "账号不可为空！");
        if (!$email) return $this->response(402, "邮箱不可为空！");
        if (($User = \App\Libs\models\Card::getCard(trim($user), "user")) == null) return $this->response(403, "用户不存在！");

        if ($email != $User->email || empty($User->email)) $this->response(420, "邮箱不匹配！");
        $password = Str::random(10);
        $User->password = Hash::make($password);
        $User->save();
        if ((new Email())->send(['title' => "密码找回", "body" => "你当前重置密码为：" . $password], $email)) return $this->response(200, "重置密码已发送邮箱！");
        return $this->response(200, "重置密码失败！");
    }

    public function expire()
    {
        $statusCode = $this->params("statusCode", false);
        if (!$this->cardCheck($statusCode)) return $this->response(420, "请登录后获取！");
        return $this->response(200, "获取成功！", ["expire" => Date::createFromTimestamp($this->Card->end_time)->format('Y-m-d H:i:s'), "expire_time" => $this->Card->end_time]);
    }

    public function heartbeat()
    {
        $statusCode = $this->params("statusCode", false);
        if (!$this->cardCheck($statusCode) || $this->Card->status < 2) return $this->response(420, "请登录后再试！");
        if ($this->Card->end_time < time()) {
            $this->Card->status = 0;
        }
        $this->updateCard($this->Card);
        return $this->response(200, "ok");
    }

    public function info()
    {
        $statusCode = $this->params("statusCode", false);
        if (!$this->cardCheck($statusCode)) return $this->response(420, "请登录后再试！");
        return $this->response(200, "ok", [
            "app_name" => $this->Card->app_name,
            "user" => $this->Card->user,
            "activate_time" => $this->Card->activate_time,
            "end_time" => Date::createFromTimestamp($this->Card->end_time)->format('Y-m-d H:i:s'),
            "status" => \App\Libs\models\Card::Status($this->Card->status),
            "recharge_count" => $this->Card->add_km,
            "recharge" => json_decode($this->Card->recharge, true),
            "features" => $this->Card->features,
            "ip" => $this->Card->ip,
        ]);
    }

    public function logout()
    {
        $statusCode = $this->params("statusCode", false);
        $device = $this->params("device", false);
        // 数据效验
        if (!$statusCode) return $this->response(413, "Token不可为空！");
        if (!$device) return $this->response(402, "设备码不可为空！");
        // 获取用户实列
        if (($Card = \App\Libs\models\Card::getCard(trim($statusCode), "token")) == null) return $this->response(403, "Token错误！");
        //是否第一次激活
        $Card->token = "";
        $this->updateCard($Card);
        return $this->response(200, "已注销登录！");
    }

    public function reset()
    {
        $user = $this->params("user", false);
        $oldPassword = $this->params("oldPassword", false);
        $password = $this->params("password", false);
        $device = $this->params("device", false);

        if (!$user || !$device || !$oldPassword || !$password) $this->response(400, "参数不为空!");
        // 获取用户实列
        if (($Card = \App\Libs\models\Card::getCard(trim($user))) == null) return $this->response(403, "用户不存在！");
        //旧密码判断
        if ($Card->password != Hash::make($oldPassword)) $this->response(403, "旧密码错误！");

        //密码更改
        $Card->password = Hash::make($password);
        $this->updateCard($Card);
        return $this->response(200, "密码修改");
    }

}
