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
                @if(($Type->type != null))
                    <p class="Lay-Title title">{{$Type->type}}</p>
                @else
                    <p class="Lay-Title title">分类创建</p>
                @endif
                <div class="layer-body" style="padding: 10px;">
                    <div class="layer-body">
                    <span
                        style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;">以下内容为创建分类必填信息</span>
                        <span style="font-size: 14px;color:#666666;margin-left: 10px;margin-top: -10px;"> </span>
                        <form class="layui-form" id="example" onsubmit="return false" style="margin-top: 10px;">
                            @if(($Type->uid != null))
                                <input hidden name="uid" value="{{$Type->uid}}">
                            @endif
                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('type.status')</p>
                                <input type="checkbox" name="status" class="lay-checkbox" @if($Type->status==1) checked
                                       @endif lay-skin="switch" lay-text="1|0" title="@lang("app.app_status_hint")">
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.type')</p>
                                <input type="text" name="type" lay-verify="required" value="{{$Type->type}}"
                                       autocomplete="off" placeholder="@lang('type.type_hint')" class="created-input">
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title" style="margin-top: 8px;">@lang('type.app')</p>
                                <select id="app_uid" lay-search="cs" name="app_uid">
                                    @foreach((App::where("app_status",1)->get()) as $value)
                                        <option @if($Type->app_uid == $value->uid) selected
                                                @endif value="{{$value->uid}}">{{$value->app_name}}</option>
                                    @endforeach
                                </select>
                                <label>@lang("type.app_label")</label>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.price')</p>
                                <input style="width: 200px" type="number" step="0.01" name="price"
                                       lay-verify="required|number" autocomplete="off" value="{{$Type->price}}"
                                       placeholder="@lang('type.price_hint')" class="created-input">
                                <label>分类生成卡密价格！</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.time')</p>
                                <input style="width: 150px;margin-right: 10px;" type="number" name="time"
                                       lay-verify="required" autocomplete="off" value="{{$Type->time}}"
                                       placeholder="@lang('type.time_hint')" class="created-input">
                                <select style="width: 140px" id="type_time" lay-search="cs" name="type_time">
                                    @foreach((config("system")['Times']) as $key=>$value)
                                        <option @if($Type->type_time == $key) selected
                                                @endif value="{{$key}}">{{$value[0]}}</option>
                                    @endforeach
                                </select>
                                <label>分类生成卡密时间</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.length')</p>
                                <input style="width: 200px" type="number" name="length" lay-verify="required"
                                       autocomplete="off" value="{{$Type->length}}"
                                       placeholder="@lang('type.length_hint')" class="created-input">
                                <label>分类生成卡密长度！最大允许卡密长度100</label>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.prefix')</p>
                                <input style="width: 200px" type="text" name="prefix" lay-verify="required"
                                       autocomplete="off" value="{{$Type->prefix}}"
                                       placeholder="@lang('type.prefix_hint')" class="created-input">
                                <label>分类生成卡密前缀！</label>
                            </div>

                            <div class="layui-form-item">
                                <p class="form-title">@lang('type.suffix')</p>
                                <input style="width: 200px" type="text" name="suffix" lay-verify="required"
                                       autocomplete="off" value="{{$Type->suffix}}"
                                       placeholder="@lang('type.suffix_hint')" class="created-input">
                                <label>分类生成卡密后缀！</label>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title form-title-des">字符集</p>
                                <textarea name="card_str" style="width: 300px" lay-verify="required" autocomplete="off"
                                          type="text" placeholder="随机种子文本"
                                          class="created-input created-input-des">{{$Type->card_str}}</textarea>
                                <label>分类生成卡密需要的字符集！</label>
                            </div>


                            <div class="layui-form-item">
                                <p class="form-title form-title-des">@lang('type.remark')</p>
                                <textarea name="remark" lay-verify="required" autocomplete="off" type="text"
                                          placeholder="@lang('type.remark_hint')"
                                          class="created-input created-input-des">{{$Type->remark}}</textarea>

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

    layui.use(['element', 'layer', 'util', "form"], function () {
        var layer = layui.layer
            , util = layui.util
            , $ = layui.$;
        var currentUrl = $('.currentUrl').val();
        $(".created-button").on("click", function () {
            var formData = $("#example").serialize();
            if (formData.indexOf("price=&") > 0) {
                layer.alert('价格不可为空');
                return;
            }
            if (formData.indexOf("type=&") > 0) {
                layer.alert('名称不可为空');
                return;
            }
            if (formData.indexOf("app=&") > 0) {
                layer.alert('名称不可为空');
                return;
            }
            if (formData.indexOf("time=&") > 0) {
                layer.alert('名称不可为空');
                return;
            }
            if (formData.indexOf("status=") == -1) {
                formData += "status=0&";
            } else {
                formData = formData.replace("status=on", "status=1");
            }
            formData += "&app=" + getId("app_uid").selectedOptions[0].innerText;
            @php
                $urls = empty($Type->uid) ? "created" : "edit";
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

