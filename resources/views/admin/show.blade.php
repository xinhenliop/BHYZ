@php use App\Models\System; @endphp
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

        .body-p {
            width: 100%;
            height: 100%;
            align-items: center;
            display: flex !important;
            flex-direction: column;
            margin-top: 10px;
            justify-content: start;
            background-color: #fefefe;
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


<div class="layui-bg-gray body-p">
    <form class="layui-form lay-centers" id="example" onsubmit="return false" style="margin-top: 10px;">

        <div style="width: 100%;margin-bottom: 50px;margin-left: 100px;">
            <img src="/favicon.ico" class="iconsn">
            <p class="title p-title">超级管理员{{$user->user}}</p>
            <span style="color: #5fb878;font-size: 12px;font-weight: bold;margin-top: 10px;">登录IP: <span class=""
                                                                                                           style="color: #5fb878;font-size: 12px;">{{$request->getClientIp()}}</span><span
                    style="color: #fc63cb;font-size: 12px;">--{{$user->last_login_ip}}({{$user->last_login_time}})</span></span>
        </div>
        <form style="width: 100%;margin-top: 50px;margin-bottom: 10px;text-align: center;">
            <div class="layui-form-item">
                <p class="form-title">旧密码</p>
                <input style="width: 300px" type="text" name="oldPassword" lay-verify="required" autocomplete="off"
                       value=""
                       class="created-input lins">
                <label>旧密码</label></div>

            <div class="layui-form-item">
                <p class="form-title">新密码</p>
                <input style="width: 300px" type="text" name="password" lay-verify="required" autocomplete="off"
                       value=""
                       class="created-input lins">
                <label>新密码</label></div>

            <div class="layui-form-item">
                <p class="form-title">确认密码</p>
                <input style="width: 300px" type="text" name="cmpassword" lay-verify="required" autocomplete="off"
                       value=""
                       class="created-input lins">
                <label>确认密码</label></div>
        </form>
        <div class="layui-form-item">
            <div class="divcontainer" style="width: 100%;text-align: center;">
                <button type="submit" class="created-button edit layui-btn">提交修改</button>
                <div style="margin-left: 20px;"></div>
                <button type="submit" class="created-button logout layui-btn">注销登录</button>
            </div>
        </div>
    </form>
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
        $(".edit").on("click", function () {
            var formData = $("#example").serialize();
            if (formData.indexOf("password=&") > 0) {
                layer.alert('密码不可为空！');
                return;
            }
            if (formData.indexOf("oldPassword=&") > 0) {
                layer.alert('旧密码不可为空');
                return;
            }

            if ((formData.indexOf("cmpassword=") + 12) > formData.length) {
                layer.alert('请输入确认密码！');
                return;
            }
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".reset",
                data: formData,
                success: function (data) {
                    layer.msg(data.msg, function () {
                        if (data.code == 200) {
                            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                            parent.layer.close(index);
                            @if($request->input("user",0) == 0)
                            parent.layer.open({
                                type: 1,
                                area: ['800px', '600px'],
                                content: `<textarea name="remark" style="width: 100%;height: 100%;" lay-verify="required" autocomplete="off" type="text" class="created-input created-input-des">` + data.data + `</textarea>`
                            });
                            @endif
                        }
                    });
                }
            });
        });
        $(".logout").on("click", function () {
            window.location.href = "{{System::getSystem("system","admin_url","/admin")}}/logout";
        });
    });

</script>
</body>
</html>

