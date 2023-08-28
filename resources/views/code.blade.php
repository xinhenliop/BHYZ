<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>卡密查询</title>
    <link rel="stylesheet" href="../model/layui/css/layui.css">
    <link rel="stylesheet" href="../css/app.css">
    <style>
        html {
            position: absolute;
            height: 100%;
            width: 100%;
        }

        body {
            width: 100%;
            height: 100%;
        }

        .body-p {
            width: 100%;
            height: 100%;
            align-items: center;
            display: flex !important;
            flex-direction: column;
            justify-content: center;
            background-color: #fefefe;
            background-image: url("../image/login_bg.jpg")
        }

        p {
            width: 100%;
            color: #0095ef;
            font-weight: bold;
        }

        .layui-input-wrap {
            width: 100%;
        }

        .code_panel {
            width: 600px;
            height: 400px;
            align-items: center;
            display: flex !important;
            flex-direction: column;
            justify-content: start;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

<div class="body-p">
    <div class="layui-panel panel code_panel">
        <p style="margin-top: 10px;"></p>
        <a href="/"><p class="Name_blue">{{config("system")['system']['name']}}</p></a>
        <p style="margin-top: 30px;"></p>
        <div class="layui-form">
            <form class="layui-form  layui-form-pane" id="example" onsubmit="return false" style="width: auto;">
                {{csrf_field()}}
                <p class="title_blue">输入查询卡密</p>
                <div class="layui-form-item">
                    <input type="text" style="width: 320px;" name="user" lay-verify="required" autocomplete="off"
                           placeholder="" class="created-input">
                    <label class="form-label">需要查询的卡密信息</label>
                </div>
                @if(config('system')['captcha']['web'])
                    <p class="title_blue">@lang('login.code')</p>
                    <div class="layui-form-item">
                        <div class="layui-input-wrap">
                            <input type="text" name="code" lay-verify="required|code" autocomplete="off"
                                   placeholder="@lang('login.code_hint')" class="input yzm-input">
                            <img src="/code" class="image-code" id="code_re">
                        </div>
                    </div>
                @endif
                <div>
                    <button id="login" class="login-button" style="width: 120px;">查询卡密</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../model/layui/layui.js"></script>
<script>
    //JS
    layui.use(['form', 'element', 'layer', 'util'], function () {
        var element = layui.element
            , layer = layui.layer
            , $ = layui.$;
        var form = layui.form;
        $("#code_re").on('click', function () {
            this.src = "/code?t=" + (Date.now());
        });
        form.verify({
            user: function (value) {
            }
            @if(config('system')['captcha']['web'])
            , code: function (value) {
                if (value.length < 2) {
                    return '验证码至少2位';
                }
            }
            @endif
        });
        $(".login-button").on("click", function () {
            var formData = $("#example").serialize();
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/card',
                data: formData,
                success: function (data) {
                    layer.alert(data.msg);
                }
            });
        });
    });
</script>
</body>
</html>
