<?php

namespace App\Http\Controllers\Api;

class app extends ApiController
{
    /**
     * @var mixed|null
     */
    public function __construct($appid = null, $request = null)
    {
        parent::__construct($appid, $request);
    }

    public function data()
    {
        if (!$this->cardCheck($this->params("statusCode", ""))) return $this->response(420, "请登录后获取！");
        return $this->response(200, "获取成功！", ['app_data' => $this->App->app_data]);
    }

    public function des()
    {
        return $this->response(200, "获取成功！", ['description' => $this->App->description]);
    }

    public function version()
    {
        return $this->response(200, "获取成功！", ['version' => $this->App->version]);
    }

    public function validates()
    {
        if (!$this->cardCheck($this->params("statusCode", ""))) return $this->response(420, "请登录后获取！");
        return $this->response(200, "获取成功！", ['validate' => $this->App->validate_data]);
    }

    public function appurl()
    {
        return $this->response(200, "获取成功！", ['app_url' => $this->App->app_url]);
    }

    public function notice()
    {
        return $this->response(200, "获取成功！", ['app_notice' => $this->App->app_notice]);
    }
}
