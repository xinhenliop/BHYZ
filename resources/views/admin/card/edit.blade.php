@php use Illuminate\Support\Facades\Date; @endphp
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
                @if($request->input("user",0) == 0)
                    <p class="Lay-Title title">卡密修改</p>
                @else
                    <p class="Lay-Title title">用户修改</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            <input hidden name="uid" value="{{$Card->uid}}">
                            <div class="layui-form-item">
                                @if($request->input("user",0) == 0)
                                    <p class="form-title">当前卡密：</p>
                                @else
                                    <p class="form-title">当前用户：</p>
                                @endif

                                <p class="form-title" style="">{{$Card->user}}</p>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">到期时间：</p>
                                @if($Card->end_time>0)
                                    <input type="text" class="layui-input layer-input orderNumber"
                                           id="ID-laydate-type-datetime-1" name="end_time" placeholder="检索信息"
                                           value="{{ Date::createFromTimestamp($Card->end_time)->format('Y-m-d H:i:s')}}"
                                           style="margin-left: 10px;min-width: 60px;width: 200px;">
                                @else
                                    <p class="form-title" style="font-size: 15px;"> @if($Card->status==1)
                                            冻结
                                        @else
                                            未激活
                                        @endif </p>
                                @endif

                            </div>

                            @if($request->input("user",0) == 1)
                                <div class="layui-form-item">
                                    <p class="form-title">用户密码：</p>
                                    <input style="width: 200px" type="text" name="features" lay-verify="required"
                                           autocomplete="off" value="{{$Card->features}}" class="created-input">
                                    <label>不修改密码请留空！</label>
                                </div>
                            @endif


                            <div class="layui-form-item">
                                <p class="form-title">绑定设备码：</p>
                                <input style="width: 200px" type="text" name="features" lay-verify="required"
                                       autocomplete="off" value="{{$Card->features}}" class="created-input">
                                <label>卡密绑定设备码！</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">绑定IP地址：</p>
                                <input style="width: 200px" type="text" name="ip" lay-verify="required"
                                       autocomplete="off" value="{{$Card->ip}}" class="created-input">
                                <label>卡密绑定IP地址！</label>
                            </div>

                            @if($request->input("user",0) == 1)
                                <div class="layui-form-item">
                                    <p class="form-title" style="margin-top: 8px;">程序更改</p>
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
                                            <option @if($Card->app_uid == $value->uid) selected
                                                    @endif value="{{$value->uid}}">{{$value->app_name}}</option>
                                        @endforeach
                                    </select>
                                    <label>程序选择</label>
                                </div>
                            @endif


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
            , $ = layui.$
            , laydate = layui.laydate;

        var currentUrl = $('.currentUrl').val();
        laydate.render({
            elem: '#ID-laydate-type-datetime-1',
            type: 'datetime',
            fullPanel: true // 2.8+
        });
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            @if($request->input("user",0) == 1)
                formData += "&app_name=" + getId("app_uid").selectedOptions[0].innerText;
            @endif
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".edit",
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

