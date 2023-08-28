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

        /**::-webkit-scrollbar {
            display: none;
        }

        * {
            scrollbar-width: none;
        }

        * {
            -ms-overflow-style: none;
        }*/
    </style>


</head>
<body>
<input hidden name="url" class="currentUrl" value="{{$url}}">

<div class="layui-bg-gray" style="padding: 30px;">
    <div class="layui-card layer-crad" style="">
        <div class="layui-card-body layui-card-crad">
            @if(!isset($error))
                <p class="Lay-Title title">卡密查询</p>
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                    <span
                        style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为卡密查询必填信息</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if($request->input("user",0) == 0)
                                <input hidden name="type" value="0">
                            @else
                                <input hidden name="type" value="1">
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <p class="form-title form-title-des" style="height: 300px;">批量数据</p>
                        <textarea name="batch_card" style="height: 300px;" lay-verify="required" autocomplete="off"
                                  type="text" placeholder="批量卡密一行一个格式：xxxxxx"
                                  class="created-input created-input-des"></textarea>
                    </div>


                    <div class="layui-form-item">
                        <div>
                            <button type="submit" class="created-button layui-btn">提交查询</button>
                        </div>
                    </div>


                    </form>
                    <div class="layui-form-item">
                        <p class="form-title form-title-des" style="height: 300px;">查询结果</p>
                        <textarea name="batch_cards" style="height: 300px;" lay-verify="required" autocomplete="off"
                                  type="text" placeholder="批量查询结果一行一个"
                                  class="created-input created-input-des inquires"></textarea>
                    </div>
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

    function getId(id) {
        return document.getElementById(id);
    }

    layui.use(['element', 'layer', 'util', "form"], function () {
        var layer = layui.layer
            , util = layui.util
            , $ = layui.$;
        var currentUrl = $('.currentUrl').val();
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".inquire",
                data: formData,
                success: function (data) {
                    layer.msg(data.msg);
                    if (data.code == 200) {
                        $(".inquires").val(data.data);
                    }
                }
            });
        });
    });
</script>
</body>
</html>

