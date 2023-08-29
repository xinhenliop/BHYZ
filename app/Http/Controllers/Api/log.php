<?php

namespace App\Http\Controllers\Api;

use App\Models\Logs;

class log extends ApiController
{
    public function __construct($appid = null, $request = null)
    {
        parent::__construct($appid, $request);
    }

    public function message()
    {
        $message = $this->params("message", false);
        $user = $this->params("user", false);
        $device = $this->params("device", false);
        if (!$message || !$user) return $this->response(403, "参数不可为空！");
        Logs::createds("客户端消息", $user, !$device ? $message . "\r\nAPP: " . $this->App->app_name . "\r\nDEVICE: " . $device : $message);
        return $this->response(200, "OK");
    }

    public function log()
    {
        $log = $this->params("log", false);
        $logContext = $this->params("logContext", false);
        $device = $this->params("device", false);
        if (!$log || !$logContext) return $this->response(403, "参数不可为空！");
        Logs::createds($log, "客户端Log", !$device ? $logContext . "\r\nAPP: " . $this->App->app_name . "\r\nDEVICE: " . $device : $logContext);
        return $this->response(200, "OK");
    }
}
