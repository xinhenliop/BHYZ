@php use App\Models\App; @endphp
@php use App\Models\Kami; @endphp
@php use App\Libs\models\Agent; @endphp
@php use App\Libs\models\Admin; @endphp
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
                <p class="Lay-Title title">卡密维护</p>
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                    <span
                        style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为卡密必填信息</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">

                            <div class="layui-form-item App">
                                <p class="form-title" style="margin-top: 8px;">@lang('type.app')</p>
                                <select lay-filter="select-filter-app" lay-search="cs" name="app_uid">

                                    @if (isset($user->admin_system))
                                        @foreach((App::where("app_status",1)->get()) as $value)
                                            <option value="{{$value->uid}}">{{$value->app_name}}</option>
                                        @endforeach
                                    @else
                                        @php
                                            if (isset($user->admin_system)){
                                                $Kami = Kami::where("status",1);
                                            }else{
                                                $uid = [];
                                                foreach (json_decode($user->app_list,true) as $value) $uid[]= explode("|",$value);
                                            }
                                        @endphp
                                        @foreach($uid as $value)
                                            <option value="{{$value[0]}}">{{$value[1]}}</option>
                                        @endforeach
                                    @endif
                                    <option value="ALL">全部软件</option>
                                </select>
                                <label>指定软件</label>
                            </div>

                            <div class="layui-form-item Select">
                                <p class="form-title" style="margin-top: 8px;">选择方式</p>
                                <select lay-filter="select-filter-type" lay-search="cs" name="type">
                                    <option value="ID">ID范围</option>
                                    <option value="CARD">指定卡密</option>
                                    <option value="STATUS">卡密状态</option>
                                    <option value="ATIME">激活时间</option>
                                    <option value="ETIME">到期时间</option>
                                    @if(isset($user->admin_system))
                                        <option value="USER">制卡人员</option>
                                    @endif
                                </select>
                                <label>范围范围选取方式</label>
                            </div>

                            <div class="layui-form-item TYPES ATIME ETIME" style="display: none;">
                                <p class="form-title">@lang('type.type')</p>
                                <label class="form-label">开始时间: </label>
                                <input type="text" style="width: 200px;" id="ID-laydate-type-datetime-1" name="start"
                                       lay-verify="required" value="" autocomplete="off" placeholder=""
                                       class="created-input">
                                <label class="form-label">结束时间: </label>
                                <input type="text" style="width: 200px;" id="ID-laydate-type-datetime-2" name="end"
                                       lay-verify="required" value="" autocomplete="off" placeholder=""
                                       class="created-input">
                            </div>
                            @if(isset($user->admin_system))
                                <div class="layui-form-item TYPES USER" style="display: none;">
                                    <p class="form-title">制卡者</p>
                                    <select lay-filter="" class="user" lay-search="cs" name="user">
                                        @foreach(Agent::Agent_List([]) as $agent)
                                            <option value="{{$agent->uid}}">代理: {{$agent->user}}</option>
                                        @endforeach

                                        @foreach(Admin::adminList([]) as $agent)
                                            <option value="{{$agent->uid}}">管理员: {{$agent->user}}</option>
                                        @endforeach

                                        <option value="-1">未选择</option>
                                    </select>
                                    <label>制卡范围选取方式</label>
                                </div>
                            @endif


                            <div class="layui-form-item TYPES STATUS" style="display: none;">
                                <p class="form-title">卡密状态</p>
                                <select lay-filter="" lay-search="cs" class="status" name="status">
                                    <option value="1">冻结</option>
                                    <option value="0">已过期</option>
                                    <option value="2">未使用</option>
                                    <option value="3">已激活</option>
                                    <option value="-1">未选择</option>
                                </select>
                                <label>卡密范围选取方式</label>
                            </div>


                            <div class="layui-form-item TYPES CARD ID" style="">
                                <p class="form-title form-title-des">卡密内容</p>
                                <textarea name="wheres" lay-verify="required" autocomplete="off" type="text"
                                          placeholder="指定卡密内容"
                                          class="created-input created-input-des wheres"></textarea>
                            </div>


                            <div class="layui-form-item Select">
                                <p class="form-title" style="margin-top: 8px;">选择维护功能</p>
                                <select lay-filter="select-filter-weihu" lay-search="cs" name="towhere">
                                    <option value="status=2">设置未激活</option>
                                    <option value="status=1">冻结</option>
                                    <option value="status=2">解冻</option>
                                    @if(isset($user->admin_system))
                                        <option value="add">加时(小时数)</option>
                                        <option value="time">设置卡密时间(小时数)</option>
                                    @endif
                                    <option value="del">删除</option>
                                </select>
                                <label>范围范围选取方式</label>
                            </div>

                            <div class="layui-form-item STIME" style="display: none;">
                                <p class="form-title">设置时间</p>
                                <input type="number" name="wheretwo" lay-verify="required" value="" autocomplete="off"
                                       placeholder="设置时间" class="created-input wheretwo">
                            </div>


                            <div class="layui-form-item">
                                <div>
                                    <button type="submit"
                                            class="created-button layui-btn">@lang('type.created_submit')</button>
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

    function getId(id) {
        return document.getElementById(id);
    }

    layui.use(['element', 'layer', 'util', "form", "laydate"], function () {
        var layer = layui.layer
            , util = layui.util
            , $ = layui.$
            , form = layui.form
            , laydate = layui.laydate;
        var currentUrl = $('.currentUrl').val();
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            $.post({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '?m=' + currentUrl + ".maintenance",
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
        laydate.render({
            elem: '#ID-laydate-type-datetime-1',
            type: 'datetime',
            fullPanel: true // 2.8+
        });
        laydate.render({
            elem: '#ID-laydate-type-datetime-2',
            type: 'datetime',
            fullPanel: true // 2.8+
        });
        form.on('select(select-filter-type)', function (data) {
            $(".TYPES").hide();
            $("." + data.value).toggle();
            $(".created-input").val("");
            $(".user").val(-1);
            $(".status").val(-1);
        });
        form.on('select(select-filter-weihu)', function (data) {
            var value = data.value; // 获得被选中的值
            $(".STIME").hide();
            if (value == "add" || value == "time") {
                $(".STIME").show();
            } else {
                $(".wheretwo").val("");
            }
        });

    });

</script>


</body>
</html>

