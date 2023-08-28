@php use App\Models\System; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> {{System::getSystem("system","name","")}}控制台</title>
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

        .systems {
            width: 300px !important;
        }
    </style>


</head>
<body>
<input hidden name="url" class="currentUrl" value="{{$url}}">

<div class="layui-bg-gray" style="padding: 30px;">
    <div class="layui-card layer-crad" style="">
        <div class="layui-card-body layui-card-crad">
            @if(!isset($error))
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                        <p class="one-title title model-title">SMTP</p>
                        <hr style="margin-top: 20px;">
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">

                            <div class="layui-form-item">
                                <p class="form-title">是否启用</p>
                                <input type="checkbox" name="status" class="lay-checkbox" @if($SYSTEM['status']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <label class="form-label">邮箱开关！</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">SMTP地址</p>
                                <input type="text" name="host" value="{{$SYSTEM['host']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">邮箱服务器SMTP地址</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">端口</p>
                                <input type="number" name="port" value="{{$SYSTEM['port']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">邮箱服务器SMTP端口</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">账号</p>
                                <input type="text" name="username" value="{{$SYSTEM['username']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">邮箱服务器SMTP账号</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">密码</p>
                                <input type="text" name="password" value="{{$SYSTEM['password']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">邮箱服务器SMTP密码</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">发信名称</p>
                                <input type="text" name="email" value="{{$SYSTEM['email']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">邮箱发信昵称</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">SSL</p>
                                <input type="checkbox" name="SSL" class="lay-checkbox" @if($SYSTEM['SSL']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <label class="form-label">邮箱SSL</label>
                            </div>

                            <div class="layui-form-item">
                                <div>
                                    <button type="submit" class="created-button layui-btn">提交保存</button>
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

    layui.use(['element', 'layer', 'util', "form"], function () {
        var layer = layui.layer
            , util = layui.util
            , $ = layui.$;
        var currentUrl = $('.currentUrl').val();
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            if (formData.indexOf("status=") === -1) {
                formData += "&status=0";
            }
            if (formData.indexOf("&SSL=") === -1) {
                formData += "&SSL=0";
            }

            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".email",
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

