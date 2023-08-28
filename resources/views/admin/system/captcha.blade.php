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
                        <p class="one-title title model-title">网站设置</p>
                        <hr style="margin-top: 20px;">
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">

                            <div class="layui-form-item">
                                <p class="form-title">是否启用</p>
                                <input type="checkbox" name="web" class="lay-checkbox" @if($SYSTEM['web']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <label class="form-label">验证码打开验证码开关！</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">字体</p>
                                <select lay-search="cs" name="fonts">
                                    @php
                                        $fontDir = "./font/";
                                            $fonts = array_filter(array_slice(scandir($fontDir), 2), function ($file) use ($fontDir) {
                                                    return is_file($fontDir . $file) && strcasecmp(pathinfo($file, PATHINFO_EXTENSION), 'ttf') === 0;
                                            });
                                    @endphp
                                    @foreach($fonts as $value)
                                        <option @if($SYSTEM['fonts'] == $value) selected
                                                @endif value="{{$value}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                <label>生成验证码字体文件</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">长度</p>
                                <input type="number" name="count" value="{{$SYSTEM['count']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">生成验证码的长度</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">图片宽度</p>
                                <input type="number" name="width" value="{{$SYSTEM['width']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">生成验证码图片宽度</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">图片高度</p>
                                <input type="number" name="height" value="{{$SYSTEM['height']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">生成验证码图片高度</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">干扰雪花</p>
                                <input type="checkbox" name="snowflake" class="lay-checkbox"
                                       @if($SYSTEM['snowflake']) checked @endif lay-skin="switch" value="1"
                                       title="@lang("app.app_status_hint")">
                                <label class="form-label">验证码生成的干扰雪花</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">干扰线</p>
                                <input type="checkbox" name="line" class="lay-checkbox" @if($SYSTEM['line']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <label class="form-label">验证码生成的干扰线</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">干扰曲线</p>
                                <input type="checkbox" name="curve" class="lay-checkbox" @if($SYSTEM['curve']) checked
                                       @endif  value="1" lay-skin="switch" title="@lang("app.app_status_hint")">
                                <label class="form-label">验证码生成的干扰曲线</label>
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
            if (formData.indexOf("web=") === -1) {
                formData += "&web=0";
            }
            if (formData.indexOf("&curve=") === -1) {
                formData += "&curve=0";
            }
            if (formData.indexOf("&line=") === -1) {
                formData += "&line=0";
            }
            if (formData.indexOf("&snowflake=") === -1) {
                formData += "&snowflake=0";
            }
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".captcha",
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

