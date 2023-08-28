@php use App\Libs\Encrypt\Encrypt_index; @endphp
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
                    <p class="one-title title">加密配置</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                        <span
                            style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">程序加密配置选项</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(isset($App))
                                <input hidden name="uid" value="{{$App->uid}}">
                            @endif

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.encrypt_mode')</p>
                                <select lay-search="cs" name="encrypt_mode">
                                    <option @if($App->encrypt_mode == 0) selected @endif value="NO">不加密</option>
                                    @foreach(Encrypt_index::Encry_model() as $value)
                                        <option @if($App->encrypt_mode == $value) selected
                                                @endif value="{{$value}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                {{--<label>@lang("app.encrypt_mode_label")</label>--}}
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('app.encrypt_keys')</p>
                                <input type="text" name="encrypt_keys" lay-verify="required"
                                       value="{{$App->encrypt_keys}}" autocomplete="off" style="width: 150px;"
                                       placeholder="@lang('app.encrypt_keys_hint')" class="created-input encrypt_keys">
                                <button type="button" class="layui-btn layui-btn-normal getApiKey"
                                        style="margin-left: 10px;"><i class="layui-icon layui-icon-refresh-1"></i>
                                </button>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.out_format')</p>
                                <select lay-search="cs" name="out_format">
                                    <option @if($App->out_format == 0) selected @endif value="0">文本</option>
                                    <option @if($App->out_format == 1) selected @endif value="1">JSON</option>
                                    <option @if($App->out_format == 2) selected @endif value="2">XML</option>
                                </select>
                                {{--<label>@lang("app.out_format_label")</label>--}}
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.user_more')</p>
                                <select lay-search="cs" name="user_more">
                                    <option @if($App->user_more == 0) selected @endif value="0">提示已登录</option>
                                    <option @if($App->user_more == 1) selected @endif value="1">注销最早设备</option>
                                </select>
                                {{--<label>@lang("app.user_more_label")</label>--}}
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
            if (formData.indexOf("version=&") > 0) {
                layer.alert('版本不可为空');
                return;
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

