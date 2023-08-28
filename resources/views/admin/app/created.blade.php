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
                @if(isset($App))
                    <p class="one-title title">{{$App->app_name}}</p>
                @else
                    <p class="one-title title">程序创建</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                    <span
                        style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为创建程序必填信息</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(isset($App))
                                <input hidden name="uid" value="{{$App->uid}}">
                            @endif

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('app.app_status')</p>
                                <input type="checkbox" name="app_status" class="lay-checkbox" lay-skin="switch"
                                       lay-text="1|0" title="@lang("app.app_status_hint")">

                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">@lang('app.app_name')</p>
                                <input type="text" name="app_name" lay-verify="required" autocomplete="off"
                                       placeholder="@lang('app.app_name_hint')" class="created-input">
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('app.version')</p>
                                <input type="text" name="version" lay-verify="required" autocomplete="off"
                                       placeholder="@lang('app.version_hint')" class="created-input">
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title form-title-des">@lang('app.description')</p>
                                <textarea name="description" lay-verify="required" autocomplete="off" type="text"
                                          placeholder="@lang('app.description_hint')"
                                          class="created-input created-input-des"></textarea>

                            </div>

                            <div class="layui-form-item">
                                <div>
                                    <button type="submit"
                                            class="created-button layui-btn">@lang('app.created_submit')</button>
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
            if (formData.indexOf("version=&") > 0) {
                layer.alert('版本不可为空');
                return;
            }
            if (formData.indexOf("app_name=&") > 0) {
                layer.alert('名称不可为空');
                return;
            }
            if (formData.indexOf("_status") == -1) {
                formData += "app_status=0&";
            } else {
                formData = formData.replace("_status=on", "_status=1");
            }
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".created",
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

