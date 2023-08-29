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
</head>
<body>
<input hidden name="url" class="currentUrl" value="{{$url}}">
<div class="layui-bg-gray body-p" style="padding: 30px;">
    {{--<div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card layer-crad" style="">
                <div class="layui-card-body layui-card-crad">
                    --}}{{--<p class="one-title title">账户信息</p>--}}{{--
                    <div class="layer-body" style="padding-top: 1px;padding-left: 15px;">
                        <p>
                            <i class="layui-icon @lang("app.header_icon")" style="font-size: 18px;"></i><span style="font-size: 14px;color:#666666;margin-left: 10px;">@lang("app.header")</span>
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </div>--}}

    <div class="layui-row layui-col-space12">
        <div class="layui-col-md12">
            <div class="layui-card layer-crad" style="">
                <div class="layui-card-body layui-card-crad">
                    {{--<p class="one-title title">账户信息</p>--}}
                    <div class="layer-body" style="padding: 10px;">
                        <div class="layer-body">
                            <p class="one-title title">分类管理</p>
                            <hr style="margin-top: 20px;">
                            <div class="table-a-td demoTable">
                                <input type="text" class="layui-input layer-input orderNumber" name="orderNumber"
                                       placeholder="检索信息" value="" style="min-width: 60px;width: 10%;">
                                <button type="button"
                                        style="margin-left: 10px;width: 100px;border-radius: 5px; height: 40px;"
                                        class="layui-btns layui-btn"
                                        lay-header-event="reload" style=";">搜索
                                </button>
                                <button type="button"
                                        style="margin-left: 10px;width: 100px;border-radius: 5px; height: 40px;"
                                        class="layui-btns layui-btn "
                                        lay-header-event="CreCombo" style=";">创建分类
                                </button>

                            </div>
                            <div>
                                <table class="layui-hide" lay-filter="test" id="test"></table>
                                <script type="text/html" id="barDemo">
                                    <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="setState">启（停）用</a>
                                    <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="edit">编辑</a>
                                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                                </script>

                                <script type="text/html" id="toolbar">
                                    <button type="button" class="layui-btns" lay-event="check_del">删除选中</button>
                                    <button type="button" class="layui-btns" lay-event="check_stop">停用选中</button>
                                    <button type="button" class="layui-btns" lay-event="check_start">启用选中</button>

                                </script>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<script src="../model/layui/layui.js"></script>
<script>

    layui.use(['element', 'dropdown', 'layer', 'util', 'table'], function () {
        var element = layui.element
            , layer = layui.layer
            , util = layui.util
            , $ = layui.$
            , dropdown = layui.dropdown
            , table = layui.table;
        var currentUrl = $('.currentUrl').val();
        var LogData = $('.orderNumber');

        table.render({
            elem: '#test'
            , url: "?m=" + currentUrl + ".types&t=page"
            , toolbar: '#toolbar'
            , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                layout: ['count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                , groups: 2 //只显示 1 个连续页码

            }
            , id: 'testReload'
            , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'uid', width: 80, title: 'UID'}
                , {field: 'type', width: 200, title: '名称'}
                , {field: 'status', width: 200, title: '状态'}
                , {field: 'length', width: 200, title: '长度'}
                , {field: 'app', width: 300, title: '程序'}
                , {field: 'app_uid', width: 200, title: '程序UID'}
                , {field: 'time', width: 200, title: '时长'}
                , {field: 'type_time', width: 200, title: '时长单位'}
                , {field: 'price', width: 200, title: '价格'}
                , {field: 'prefix', width: 200, title: '前缀'}
                , {field: 'suffix', width: 200, title: '后缀'}
                , {fixed: 'right', title: '操作', width: 300, toolbar: '#barDemo'},
            ]],
            parseData: function (res) { //res 即为原始返回的数据
                return {
                    "code": 0, //解析接口状态
                    "msg": res.from, //解析提示文本
                    "count": res.total, //解析数据长度
                    "data": res.data //解析数据列表
                };
            }

        });
        var active = {
            reload: function () {
                //执行重载
                table.reload('testReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }, where: {
                        type: LogData.val()
                    }
                });
            }
        };

        table.on('toolbar(test)', function (obj) {
            var id = obj.config.id;
            var checkStatus = table.checkStatus(id);
            var ids = [];
            var urls = "";
            switch (obj.event) {
                case 'check_del':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    var ids = [];
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    urls = '?m=' + currentUrl + '.del&uid=' + JSON.stringify(ids);
                    break;
                case 'check_stop':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    urls = '?m=' + currentUrl + '.edit&uid=' + JSON.stringify(ids) + "&status=0";
                    break;
                case 'check_start':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    urls = '?m=' + currentUrl + '.edit&uid=' + JSON.stringify(ids) + "&status=1";
                    break;
            }
            if (ids.length > 0) {
                $.get({
                    url: urls,
                    success: function (data) {
                        layer.msg(data.msg, {icon: 4});
                        active.reload();
                    }, error: function (data) {
                        layer.msg("失败", {icon: 4});
                    }
                });
            }
        });
        table.on('tool(test)', function (obj) { // 双击 toolDouble
            var data = obj.data;
            if (obj.event === 'del') {
                $.get({
                    url: '?m=' + currentUrl + '.del&uid=[' + data.uid + "]",
                    success: function (data) {
                        layer.msg(data.msg, {icon: 4});
                        active.reload();
                    }, error: function (data) {
                        layer.msg("失败", {icon: 4});
                    }
                });
                //post删除
            } else if (obj.event === 'setState') {
                state = data.status == '启用' ? 0 : 1;
                $.post({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '?m=' + currentUrl + ".edit",
                    data: 'uid=' + data.uid + "&status=" + state,
                    success: function (data) {
                        layer.msg(data.msg, {icon: 4});
                        active.reload();
                    }, error: function (data) {
                        layer.msg("失败", {icon: 4});
                    }
                });
            } else if (obj.event === 'edit') {
                var index = layer.open({
                    title: '编辑卡密',
                    type: 2,
                    area: ['50%', '70%'],
                    maxmin: true,
                    content: '?m=' + currentUrl + ".get&c=get&uid=" + data.uid
                });
                //layer.full(index);
            }
        });

        util.event('lay-header-event', {
            //左侧菜单事件
            to_url: function () {
                //document.getElementById("body_url").setAttribute('src',"?m="+this.getAttribute('v-url'));
                //console.log(body_to.src);
                window.location.href = "?m=" + this.getAttribute('v-url');
            }, reload: function () {
                active.reload();
            }, CreCombo: function () {
                var index = layer.open({
                    title: '卡密制作',
                    type: 2,
                    maxmin: true,
                    area: ['800px', '70%'],
                    content: ['?m=' + currentUrl + ".created"]
                });
                //layer.full(index);
            }
        });

    });
    //头部事件
    //JS
</script>
</body>
</html>
