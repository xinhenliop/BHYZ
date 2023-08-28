<?php


namespace App\Libs;

use Illuminate\Http\Request;
use Psy\Util\Json;


class HttpInput
{
    public function __construct()
    {
        $this->name = 'App\\Http\\Controllers\\';
        $this->AppModelsPath = "../app/Http/Controllers/";
        $this->med = '';
        $this->req = [];
    }

    public static function stringToBool($value)
    {
        return $value == "1";
    }

    public function call_(Request $request, $T)
    {
        $pa = '';
        $this->type = $T;
        $this->req = $request->all();
        $this->med = $request->method();
        if ($request->method() == 'GET') {
            if ($request->input("m", '1') == '1') {
                $this->req['m'] = "admin.home";
            }
        }

        return $this->call_function($request);
    }

    function call_function(Request $request)
    {
        $php = $this->AppModelsPath;//app_path() .
        if (($this->type == '1' or !is_dir($php . "/" . $this->type))) {
            return $this->ReturnHtml(array("title" => "No API!", "context" => "No API!1"));
        }

        $path = explode(".", $this->get_parms('m', ''));
        if (count($path) == 0)
            return $this->ReturnHtml(array("title" => "No API!", "context" => "No API!2"));
        //模块函数拼接
        //模块拼接
        $path_num = count($path);
        if (count($path) == 1) {
            $functon = "index";
            $class = $this->name . $this->type . "\\" . $path[0];
        } else { //多级目录的情况
            $functon = $path[$path_num - 1] and array_pop($path);
            $class = $this->name . $this->type . "\\" . $path[$path_num - 2];
        }
        if (class_exists($class)) {
            $cl = new  $class($request);
            if (method_exists($cl, $functon)) {
                return $cl->$functon();//call_user_func_array(array($class,$functon),array($request));
            }
            return $this->ReturnHtml(array("title" => "No API!", "context" => "No API!"));
        }
        //装载模块
        return $this->ReturnHtml(array("title" => "No API!", "context" => "No API!"));

    }

    public function ReturnHtml($ar)
    {
        $ar['context'] = $ar['context'] . ' | ' . $this->get_parms('m', "1") . '';
        if ($this->med == 'GET') {
            return view("err.no_c")->with(['err' => $ar, "request" => $this->req]);
        }
        return $this->ReturnJson($ar);
    }

    function get_parms($key, $def)
    {
        if (isset($this->req[$key])) {
            return $this->req[$key];
        }
        return $def;
    }

    //不适用命名空间方法

    function ReturnJson($json)
    {
        return Json::encode($json);
    }

}
