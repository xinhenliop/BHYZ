<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api;
use App\Libs\Utils;
use Exception;
use Illuminate\Http\Request;

class ApiController
{
    //Appid
    protected $appid = null;

    protected $App = null;

    //参数储存数组
    protected $params = [];

    //Api编码类型
    protected $bind_ip = false;

    //Api加密类型
    protected $bind_device = false;

    //Api传输加密方式
    protected $clinet_address = null;

    //Api加密Keys
    protected $parser = null;
    protected $Card = null;
    private $socket_encrypt = 1;
    private $encrypt_mode = 1;
    private $transmission = 1;
    private $encrypt_keys = null;
    //加密库命名空间地址
    private $Encrypted = null;

    //加密库文件地址
    private $SEncrypted = null;
    private $EnUse = "App\\Libs\\Encrypt\\";
    private $enPath = "../app/Libs/Encrypt/";

    //动态卡类
    private $response = ["code" => 200, "msg" => "", "data" => []];

    function __construct($appid = null, Request $request = null)
    {
        $this->clinet_address = $request->getClientIp();
        //获取所有参数
        $this->params = $this->all($request);
        //取Appid
        $this->appid = $appid == null ? !$this->params("appid", false) ? null : $this->params("appid") : $appid;
        //取程序实列
        ($this->appid != null) && ($this->App = \App\Models\App::where("uid", $this->appid)->first()) && $this->App();
        !isset($this->App->uid) && $this->ExitApi(400, "App not found");
        //解密加密数据
        $this->Decrypt($this->params("m", ""), $request) && $this->ExitApi(400, "data decrypt failed");
        //效验数据签名
        $this->App->validate_sign == 1 && $this->validate() && $this->ExitApi(400, "validate sign failed");
        //效验APP Md5数据
        if (!empty($this->App->validate_app_md5)) {
            !isset($this->params['md5']) && $this->ExitApi(400, "app md5 not found");
            trim($this->App->validate_app_md5) != $this->params['md5'] && $this->ExitApi(400, "validate md5 failed");
        }
        //效验数据包时间
        if ($this->App->validate_data_time != 0) {
            !isset($this->params['time']) && $this->ExitApi(400, "time not found");
            ($this->App->validate_data_time + (int)$this->params['time']) < time() && $this->ExitApi(400, "validate data time failed");
        }
    }

    /**
     * all
     * request params to array
     * @param string $m
     * @param Request|null $request
     * @return array|string
     */
    private function all(?Request $request)
    {
        $query = file_get_contents('php://input');
        switch ($request->header("Content-Type", false)) {
            case 'application/json':
                return json_decode($query, true);
            case 'application/xml':
                $this->parser = xml_parser_create();
                xml_parse($this->parser, $query, true);
                return $this->parser;
            case 'application/x-www.form-urlencoded' || 'multipart/form-data':
                return $request->all();
            default:
                return $query;
        }
    }

    /**
     * params
     * request params
     * @param string $key
     * @param $default
     * @return void*
     */
    function params($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    function App(): void
    {
        $this->bind_device = $this->App->bind_device;
        $this->bind_ip = $this->App->bind_ip;
        $this->encrypt_keys = $this->App->encrypt_keys;
        $this->socket_encrypt = $this->App->socket_encrypt;
        $this->encrypt_mode = $this->App->encrypt_mode;
        $this->transmission = $this->App->transmission;
        $this->Encrypted = file_exists($this->enPath . $this->encrypt_mode . ".php") ? $this->EnUse . $this->encrypt_mode : $this->EnUse . "NO";
        $this->SEncrypted = file_exists($this->enPath . $this->socket_encrypt . ".php") ? $this->EnUse . $this->socket_encrypt : $this->EnUse . "NO";
    }

    /**
     * ExitApi
     * exit api response error
     * @param int $status
     * @param string $msg
     * @param array $data
     * @return void
     */
    function ExitApi($status, $msg, $data = [])
    {
        $this->response['code'] = $status;
        $this->response['msg'] = $msg;
        $this->response['data'] = $data;
        exit(api::response($this->response, $this->App->out_format ?? 1));
    }

    /**
     * response
     * return api response
     * @param int $status
     * @param string $msg
     * @param array $data
     * @return string
     */
    function response($status, $msg, $data = [])
    {
        $this->response['code'] = $status;
        $this->response['msg'] = $msg;
        $this->response['data'] = $this->validate_Encrypt($data);
        return $this->Encrypt(api::response($this->response, $this->App->out_format ?? 1));
    }

    /**
     * validate_Encrypt
     * param md5 Signature
     * @param array $data
     * @return array
     */
    function validate_Encrypt($data)
    {
        if ($this->App->validate_sign != 1 || count($data) < 1) return $data;
        ksort($data);
        $arraystring = "";
        foreach ($data as $key => $value) {
            if (!str_contains(".appid.sign", $key)) {
                $arraystring .= "&" . $key . "=" . $value;
            }
        }
        $data['sign'] = md5(substr($arraystring, 1, strlen($arraystring)));
        return $data;
    }

    /**
     * Encrypt
     * encrypt Data
     * @param string $data
     * @return string
     */
    function Encrypt($data)
    {
        return $this->SEncrypted::encrypt($this->Encrypted::encrypt($data, $this->encrypt_keys));
    }

    /**
     * Decrypt
     * decrypt params from request
     * @param string $m
     * @param Request|null $request
     * @return bool
     */
    function Decrypt(mixed $m, ?Request $request): bool
    {
        //解密加密参数
        if ($this->transmission == 0) return false; //Transmission == 0 means
        if (empty($m)) return false;  //App未定义
        if (!isset($_POST['m']) && $this->transmission == 2) return true; //Transmission == 2 means
        if (!isset($_GETT['m']) && $this->transmission == 1) return true; //Transmission == 2 means
        $decoded = $this->Encrypted::decrypt($this->SEncrypted::decrypt($m), $this->encrypt_keys);
        $this->params = Utils::array_pop_key($this->params, "m");
        if (strpos($decoded, "&") !== false) {
            $decoded = explode("&", $decoded);
            if (count($decoded) == 0) return true; //参数错误
            foreach ($decoded as $value) $this->params[substr($value, 0, strpos($value, "="))] = substr($value, strpos($value, "=") + 1);
        } else if (strpos($decoded, "{\"") !== false) {
            foreach (json_decode($decoded, true) as $value) $this->params[] = $value;
        }
        return false;
    }

    /**
     * validate
     * validateSignature method
     * @return bool
     */
    function validate()
    {
        ksort($this->params);
        $arraystring = "appid=" . $this->params['appid'];
        try {
            foreach ($this->params as $key => $value) {
                if (!str_contains(".appid.sign", $key)) {
                    $arraystring .= "&" . $key . "=" . $value;
                }
            }
            return !(md5($arraystring) == $this->params['sign']);
        } catch (Exception $a) {
            return true;
        }

    }

    function __destruct()
    {
        //释放xml对象
        if ($this->parser != null) {
            xml_parser_free($this->parser);
            unset($this->parser);
        }
    }


    /**
     * cardCheck
     * @param string $statusCode
     * @return boolean
     */
    public function cardCheck($statusCode)
    {
        if (($this->Card = \App\Libs\models\Card::getCard(trim($statusCode), "token")) == null) return false;
        if (((time() - strtotime($this->Card->last_time)) < $this->App->token_validate) && !empty($this->Card->token)) return true;
        return false;
    }
}
