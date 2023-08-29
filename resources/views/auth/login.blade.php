@php use App\Models\System; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>用户登录</title>
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
    </style>
</head>
<body>

<div class="body-p">
    <div class="layui-panel panel login_panel">
        <div class="layui-card-body">
            <p style="margin-top: 10px;"></p>
            <a href="/"><p class="Name_blue">{{config("system")['system']['name']}}</p></a>
            <p style="margin-top: 30px;"></p>
            <div class="layui-form">
                <form class="layui-form  layui-form-pane" method="POST" action="?" style="width: auto;">
                    {{csrf_field()}}
                    <p class="title_blue">@lang('login.user')</p>
                    <div class="layui-form-item">
                        <div class="layui-input-wrap">
                            <input name="user" id="uid" lay-verify="required|name" autocomplete="off" type="text"
                                   placeholder="@lang('login.user')" class="input">
                        </div>
                    </div>

                    <p class="title_blue">@lang('login.password')</p>
                    <div class="layui-form-item">
                        <div class="layui-input-wrap">
                            <input type="password" name="password" id="tokenc" lay-verify="required|password"
                                   autocomplete="off" placeholder="@lang('login.password_hint')" class="input"></div>
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
                        <button id="login" class="login-button">@lang('login.submit')</button>
                    </div>

                    <div style="width: 100%;text-align: center;font-size: 12px;">
                        @if(System::getSystem("system","users",false))
                            <a href="/reg"><p>@lang('login.to_register')</p></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../model/layui/layui.js"></script>
<script>
    //JS
    layui.use(['form', 'element', 'layer', 'util'], function () {
        var element = layui.element
            , layer = layui.layer
            , util = layui.util
            , $ = layui.$;
        var form = layui.form;
        $("#code_re").on('click', function () {
            this.src = "/code?t=" + (Date.now());
        });
        @if(isset($msg))
        layer.msg('{{$msg}}');
        @endif

        form.verify({
            name: function (value) {
                if (value.length < 2) {
                    return '账号或邮箱至少8位';
                }
            }
            , password: function (value) {
                if (value.length < 2) {
                    return '密码至少8位';
                }
            }
            @if(config('system')['captcha']['web'])
            , code: function (value) {
                if (value.length < 2) {
                    return '验证码至少8位';
                }
            }
            @endif
        });
        //头部事件

    });
</script>
</body>
</html>
