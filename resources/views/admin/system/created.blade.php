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

                        <p class="one-title title model-title">{{empty($SYSTEM->user) ? "新建管理员" : "编辑管理员"}}</p>
                        <hr style="margin-top: 20px;">
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(isset($SYSTEM->user))
                                <input type="hidden" name="uid" value="{{$SYSTEM->uid}}">
                            @endif
                            <div class="layui-form-item">
                                <p class="form-title">账号</p>
                                <input type="text" name="user" value="{{$SYSTEM['user']}}" lay-verify="required"
                                       autocomplete="off" class="created-input systems">
                                <label class="form-label">管理员账号</label>
                            </div>
                            <div class="layui-form-item">
                                <p class="form-title">密码</p>
                                <input type="text" name="password" value="" lay-verify="required" autocomplete="off"
                                       class="created-input systems">
                                <label class="form-label">管理员密码@if(!empty($SYSTEM->user))
                                        ,不修改请留空！
                                    @endif</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">权限分配</p>
                                <div>
                                    @php
                                        $admin_system = json_decode($SYSTEM->admin_system,true);
                                    @endphp
                                    {{--{{$SYSTEM->admin_system}}--}}
                                    @foreach((config("system")['author']) as $keys=>$value)
                                        <input type="checkbox" @if(isset($admin_system[$keys])) checked
                                               @endif name="admin_system[{{$keys}}]" value="1" lay-skin="tag">
                                        <div lay-checkbox>
                                            {{$value}}
                                        </div>
                                    @endforeach
                                </div>
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
            if (formData.indexOf("&password=&")) {
            }
            @php
                $urls = empty($SYSTEM->user) ? "created" : "edit";
            @endphp
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".{{$urls}}",
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

