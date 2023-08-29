@php use App\Libs\models\timeZone; @endphp
@php use App\Libs\models\language; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> {{$SYSTEM['name']}}控制台</title>
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
                                <p class="form-title">标题</p>
                                <input type="text" name="name" value="{{$SYSTEM['name']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">网站显示标题</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">副标题</p>
                                <input type="text" name="description" lay-verify="required" autocomplete="off"
                                       value="{{$SYSTEM['description']}}" class="created-input systems">
                                <label class="form-label">网站显示副标题</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">后台地址</p>
                                <input type="text" name="admin_url" lay-verify="required" autocomplete="off"
                                       value="{{$SYSTEM['admin_url']}}" class="created-input systems">
                                <label class="form-label">管理员后台地址设置</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">后台地址</p>
                                <input type="text" name="agent_url" lay-verify="required" autocomplete="off"
                                       value="{{$SYSTEM['agent_url']}}" class="created-input systems">
                                <label class="form-label">代理后台地址设置</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">时区设置</p>
                                <select lay-search="cs" name="time_zone">
                                    @foreach(timeZone::timeZoneList() as $keys=>$value)
                                        <option @if($SYSTEM['time_zone'] == $keys) selected
                                                @endif value="{{$keys}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                <label>服务器时区设置！不同时区差异化巨大请仔细了解后设置。</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">语言设置</p>
                                <select lay-search="cs" name="language">
                                    @foreach(language::localeList() as $keys=>$value)
                                        <option @if($SYSTEM['time_zone'] == $keys) selected
                                                @endif value="{{$keys}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                <label>服务器语言设置！</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">网站状态</p>
                                <input type="checkbox" name="status" class="lay-checkbox" @if($SYSTEM['status']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <input type="text" style="margin-left: 10px;" name="close_toast" lay-verify="required"
                                       value="{{$SYSTEM['close_toast']}}" autocomplete="off" placeholder=""
                                       class="created-input systems">
                                <label class="form-label">网站状态设置，网站关闭提示</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">代理中心</p>
                                <input type="checkbox" name="agent" class="lay-checkbox" @if($SYSTEM['agent']) checked
                                       @endif lay-skin="switch" value="1" title="@lang("app.app_status_hint")">
                                <input type="text" style="margin-left: 10px;" name="agent_close_toast"
                                       lay-verify="required" value="{{$SYSTEM['agent_close_toast']}}" autocomplete="off"
                                       placeholder="" class="created-input systems">
                                <label class="form-label">网站状态设置，网站关闭提示</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">防火墙</p>
                                <input type="checkbox" name="sql" class="lay-checkbox" @if($SYSTEM['sql']) checked
                                       @endif  value="1" lay-skin="switch" title="@lang("app.app_status_hint")">
                                <label class="form-label">系统防火墙开关</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title form-title-des">自定义规则</p>
                                <textarea name="sql_argv" lay-verify="required" autocomplete="off" type="text"
                                          class="created-input created-input-des systems">{{$SYSTEM['sql_argv']}}</textarea>
                                <label class="form-label">自定义防火墙过滤规则(仅支持正则)</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title form-title-des">后台白名单</p>
                                <textarea name="ip_whitelist" lay-verify="required" autocomplete="off" type="text"
                                          class="created-input created-input-des systems">{{$SYSTEM['ip_whitelist']}}</textarea>
                                <label class="form-label">网站后台白名单列表多条使用|分割，不懂请谨慎开启</label>
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

            if (formData.indexOf("&sql=") === -1) {
                formData += "&sql=0";
            }
            if (formData.indexOf("&agent=") === -1) {
                formData += "&agent=0";
            }
            if (formData.indexOf("&status=") === -1) {
                formData += "&status=0";
            }
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".system",
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

