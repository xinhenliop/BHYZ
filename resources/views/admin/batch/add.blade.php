@php use App\Models\Kami; @endphp
@php use App\Models\App; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> {{--{{ $user['name'] }}--}} 控制台</title>
    <link rel="stylesheet" href="../model/layui/css/layui.css">
    <link rel="stylesheet" href="../model/app.css">
    <link rel="stylesheet" href="../css/app.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html {
            position: absolute;
            height: 100%;
            width: 100%;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        * {
            scrollbar-width: none;
        }

        * {
            -ms-overflow-style: none;
        }
    </style>


</head>
<body>
<input hidden name="url" class="currentUrl" value="{{$url}}">

{{--<div class="header">
    <p class="layui-breadcrumb" id="body-" style="height: 50px;padding-top: 15px;">
        <span style="background-color: #666666;width: 1px;height: 100%;color: #666666;">|</span>

        @foreach(\App\Http\Controllers\Menc::getMenuName($url,\App\Http\Controllers\Menc::getUserMenu()) as $key=>$ite)
            @if(isset($ite['name']))
                <a v-url="{{$ite['url']}}" class="header-url" lay-header-event="to_url" href="javascript:;"
                   href="javascript:;">{{$ite['name']}}</a>
            @endif
        @endforeach
    </p>
</div>--}}


<div class="layui-bg-gray" style="padding: 30px;">
    <div class="layui-card layer-crad" style="">
        <div class="layui-card-body layui-card-crad">
            @if(!isset($error))
                <p class="Lay-Title title">卡密批量创建</p>
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                    <span
                        style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为创建卡密必填信息</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if($request->input("user",0) == 0)
                                <input hidden name="type" value="0">
                            @else
                                <input hidden name="type" value="1">
                            @endif

                            @if(isset($user->balance))
                                <div class="layui-form-item">
                                    <p class="form-title form-title-des created-input">
                                        您的当前可用余额：{{$user->balance}}元</p>
                                </div>
                            @endif

                            @if($request->input("user",0) == 0)
                                <div class="layui-form-item">
                                    <p class="form-title" style="margin-top: 8px;">@lang('type.app')</p>
                                    <select id="app_uid" style="width: 150px;" lay-search="cs" name="type_uid">
                                        @php
                                            if (isset($user->admin_system)){
                                                $Kami = Kami::where("status",1);
                                            }else{
                                                $uid = [];
                                                foreach (json_decode($user->app_list,true) as $value) $uid[]=substr($value,0,strpos($value,"|"));
                                                $Kami = Kami::where("status",1)->whereIn("app_uid",$uid);
                                            }
                                        @endphp
                                        @foreach(($Kami->get()) as $value)
                                            <option
                                                value="{{$value->uid}}">{{$value->app . "[" .$value->type . "]" . $value->price ."元"}}</option>
                                        @endforeach
                                    </select>
                                    <label>选择分类</label>
                                    @else
                                        <div class="layui-form-item">
                                            <p class="form-title" style="margin-top: 8px;">@lang('type.app')</p>
                                            <select id="app_uid" style="width: 150px;" lay-search="cs" name="app_uid">
                                                @php
                                                    if (isset($user->admin_system)){
                                                        $Kami = App::where("app_status",1);
                                                    }else{
                                                        $uid = [];
                                                        foreach (json_decode($user->app_list,true) as $value) $uid[]=substr($value,0,strpos($value,"|"));
                                                        $Kami = App::where("app_status",1)->whereIn("uid",$uid);
                                                    }
                                                @endphp
                                                @foreach(($Kami->get()) as $value)
                                                    <option value="{{$value->uid}}">{{$value->app_name}}</option>
                                                @endforeach
                                            </select>
                                            <label>程序选择</label>
                                            @endif

                                        </div>
                                        <div class="layui-form-item">
                                            <p class="form-title form-title-des" style="height: 300px;">批量数据</p>
                                            <textarea name="batch_card" style="height: 300px;" lay-verify="required"
                                                      autocomplete="off" type="text"
                                                      placeholder="批量卡密一行一个格式：xxxxxx，批量用户一行一个格式： 账号----密码"
                                                      class="created-input created-input-des"></textarea>
                                        </div>
                                        <div class="layui-form-item">
                                            <div>
                                                <button type="submit"
                                                        class="created-button layui-btn">@lang('type.created_submit')</button>
                                            </div>
                                        </div>
                        </form>
                    </div>
                </div>
            @else
                <p class="one-title title">{{$error}}</p>
            @endif
        </div>
    </div>
</div>
<script src="../model/layui/layui.js"></script>
<script>

    function getRandom(len) {
        var ret = '';
        var string = '123456789ABCDEFGHIJKLMIOPQRSTUVWSYZ';
        var strlen = string.length - 1;
        for (var i = 0; i < len; i++) {
            ret += string.charAt(Math.round(Math.random() * strlen));
        }
        return ret;
    }

    function getId(id) {
        return document.getElementById(id);
    }

    layui.use(['element', 'layer', 'util', "form"], function () {
        var layer = layui.layer
            , util = layui.util
            , $ = layui.$;
        var currentUrl = $('.currentUrl').val();
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".add",
                data: formData,
                success: function (data) {
                    layer.msg(data.msg, function () {
                        if (data.code == 200) {
                            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index);
                        }
                    });
                }
            });
        });
    });
</script>
</body>
</html>

