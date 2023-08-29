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

<div class="layui-bg-gray" style="padding: 30px;">
    <div class="layui-card layer-crad" style="">
        <div class="layui-card-body layui-card-crad">
            @if(!isset($error))
                @if(isset($App->app_name))
                    <p class="one-title title">{{$App->app_name}}</p>
                @else
                    <p class="one-title title">传输配置</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                        <span
                            style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">程序数据传输配置选项</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(isset($App))
                                <input hidden name="uid" value="{{$App->uid}}">
                            @endif

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.transmission')</p>
                                <select lay-search="cs" name="transmission">
                                    <option @if($App->transmission == 0) selected @endif value="0">明文传输</option>
                                    <option @if($App->transmission == 1) selected @endif value="1">GET加密</option>
                                    <option @if($App->transmission == 2) selected @endif value="2">POST加密</option>
                                    <option @if($App->transmission == 3) selected @endif value="3">自动识别</option>
                                </select>
                                <label>@lang("app.transmission_label")</label>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.socket_encrypt')</p>
                                <select lay-search="cs" name="socket_encrypt">
                                    <option @if($App->socket_encrypt == "NO") selected @endif value="NO">不编码</option>
                                    <option @if($App->socket_encrypt == "HEX") selected @endif value="HEX">HEX</option>
                                    <option @if($App->socket_encrypt == "Base64") selected @endif value="Base64">
                                        Base64
                                    </option>
                                </select>
                                <label>@lang("app.socket_encrypt_label")</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.validate_sign')</p>
                                <input type="checkbox" name="validate_sign" class="lay-checkbox"
                                       @if($App->validate_sign==1) checked @endif lay-skin="switch" lay-text="1|0"
                                       title="@lang("app.app_status_hint")">
                                <label>@lang("app.validate_sign_label")</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title form-title-des">@lang('app.validate_app_md5')</p>
                                <textarea name="validate_app_md5" lay-verify="required" autocomplete="off" type="text"
                                          placeholder="@lang('app.validate_app_md5_hint')"
                                          class="created-input created-input-des">{{$App->validate_app_md5}}</textarea>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('app.validate_data_time')</p>
                                <input type="text" style="width: 200px;" name="validate_data_time" lay-verify="required"
                                       autocomplete="off" value="{{$App->validate_data_time}}"
                                       placeholder="@lang('app.validate_data_time_hint')" class="created-input">
                                <label>@lang("app.validate_data_time_hint")</label>

                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">@lang('app.token_validate')</p>
                                <input type="text" style="width: 200px;" name="token_validate" lay-verify="required"
                                       autocomplete="off" value="{{$App->token_validate}}"
                                       placeholder="@lang('app.token_validate_hint')" class="created-input">
                                <label>@lang("app.token_validate_hint")</label>
                            </div>
                            <div class="layui-form-item">
                                <div>
                                    <button type="submit"
                                            class="created-button layui-btn">@lang('app.edit_submit')</button>
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


        $(".getApiKey").click(function () {
            $(".encrypt_keys").val(getRandom(32));
        });
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            if (formData.indexOf("validate_sign=on") > 0) {
                formData = formData.replace("validate_sign=on", "validate_sign=1");
            } else {
                formData += "&validate_sign=0";
            }
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".edit&uid={{$App->uid}}",
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

