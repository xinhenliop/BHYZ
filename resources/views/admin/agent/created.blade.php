@php use App\Http\Controllers\Auth\AuthController; @endphp
@php use App\Models\App; @endphp
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
                @if(empty($User->user))
                    <p class="one-title title">代理创建</p>
                @else
                    <p class="one-title title">代理修改</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                        @if(empty($User->user))
                            <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为创建代理必填信息</span>
                        @else
                            <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为修改代理必填信息</span>
                        @endif
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(!empty($User->user))
                                <input type="hidden" name="uid" value="{{$User->uid}}">
                            @endif
                            <div class="layui-form-item">
                                <p class="form-title">名称</p>
                                <input type="text" name="name" lay-verify="required" value="{{$User->name}}"
                                       autocomplete="off" placeholder="请输入代理名称" class="created-input">
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">账号</p>
                                <input type="text" name="user" lay-verify="required" value="{{$User->user}}"
                                       autocomplete="off" placeholder="请输入代理账号" class="created-input">
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">密码</p>
                                <input type="text" style="width: 300px;" name="password" lay-verify="required" value=""
                                       autocomplete="off" placeholder="请输入代理密码" class="created-input">

                                @if(!empty($User->user))
                                    <label class="form-label">不修改密码请留空</label>
                                @endif

                            </div>
                            @if($User->balance == 0 || AuthController::isAdmin(AppModel))
                                <div class="layui-form-item">
                                    <p class="form-title">余额</p>
                                    <input type="number" style="width: 300px;" name="balance" value="{{$User->balance}}"
                                           lay-verify="required" autocomplete="off" value="0" placeholder="请输入代理余额"
                                           class="created-input">
                                    @if(empty($User->user))
                                        <label class="form-label">余额单位. 元</label>
                                    @else
                                        <label class="form-label">直接修改余额无法纳入账单。</label>
                                    @endif
                                </div>
                            @endif
                            @if(AuthController::isAdmin(AppModel))
                                <div class="layui-form-item">
                                    <p class="form-title">折扣</p>
                                    <input type="number" style="width: 300px;" name="discount" lay-verify="required"
                                           value="{{$User->discount}}" autocomplete="off" value="100"
                                           placeholder="请输入代理折扣" class="created-input">
                                    <label class="form-label">代理折扣为百分比, 100=1,80=0.8,90=0.9</label>
                                </div>
                            @else
                                <input type="number" hidden name="discount" lay-verify="required" value="100">
                            @endif

                            <div class="layui-form-item">
                                <p class="form-title">QQ</p>
                                <input type="number" name="qq" lay-verify="required" autocomplete="off"
                                       value="{{$User->qq}}" placeholder="请输入代理QQ" class="created-input">
                                {{--<label class="form-label">代理折扣为百分比, 100=1,80=0.8,90=0.9</label>--}}
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">邮箱</p>
                                <input type="text" name="email" lay-verify="required" autocomplete="off"
                                       value="{{$User->email}}" placeholder="请输入代理邮箱" class="created-input">
                                {{--<label class="form-label">代理折扣为百分比, 100=1,80=0.8,90=0.9</label>--}}
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">程序分配</p>
                                <div>
                                    @foreach(App::where("app_status",1)->get() as $value)
                                        <input type="checkbox" @if(strpos($User->app_list,$value->uid)>0) checked
                                               @endif name="app_list[]" value="{{$value->uid}}|{{$value->app_name}}"
                                               lay-skin="tag">
                                        <div lay-checkbox>
                                            {{$value->app_name}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title form-title-des">代理备注</p>
                                <textarea name="remark" lay-verify="required" autocomplete="off" type="text"
                                          placeholder="代理备注信息"
                                          class="created-input created-input-des">{{$User->remark}}</textarea>
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
            if (formData.indexOf("user=&") > 0) {
                layer.alert('账号不可为空');
                return;
            }
            if (formData.indexOf("name=&") > 0) {
                layer.alert('名称不可为空');
                return;
            }
            if (formData.indexOf("password=&") > 0) {
                formData = formData.replace("password=&", "");
            }
            @php
                $urls = empty($User->user) ? "created" : "edit";
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

