<?php

namespace App\Models;

use App\Libs\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";
    protected $table = "bh_app";
    protected $primaryKey = "Id";
    protected $fillable = [
        "user_count",     //账号用户
        "km_count",       //卡密用户
        "uid",
        "app_name",       //软件名称
        "description",    //软件介绍
        "app_data",       //软件数据
        "app_status",     //软件状态
        "close_toast",    //软件关闭提示
        "app_notice",     //公告
        "bind_device",    //是否绑定设备
        "bind_ip",        //是否绑定IP
        "version",        //版本
        "unbind_time",    //解绑扣时
        "app_url",
        "out_format",     //输出格式
        "validate_data",  //验证数据
        "socket_encrypt",    //传输编码
        "encrypt_mode",   //加密方式
        "transmission",   //传输加密
        "validate_data_time", //数据包效验时间
        "validate_sign",  //数据包签名
        "encrypt_keys",   //
        "validate_app_md5",  //软件MD5
        "user_more",      //多登录模式
        "unbind_count",     //最大解绑次数
        "token_validate"   //心跳时间
    ];
    protected $hidden = [
    ];

    public static function createds($arrays)
    {
        $thiss = new App();
        $thiss->uid = rand(111111, 99999999);
        foreach ($arrays as $key => $value) {
            $thiss->{$key} = $value;
        }
        return $thiss->save();
    }

    public static function updateds(array $array_pop_key)
    {
        $App = strpos($array_pop_key['uid'], "]") > 0 ? App::whereIn("uid", json_decode($array_pop_key['uid'], true)) : App::where("uid", "=", $array_pop_key['uid']);
        return $App->update(Utils::array_pop_key($array_pop_key, "uid"));
    }

    public function show()
    {
        $show = [];
        $label = $this->fillable;
        array_push($label, "created_at", "updated_at");
        foreach (array_diff($label, $this->hidden) as $column => $value) $show[$value] = $this->$value;
        return $show;
    }
}
