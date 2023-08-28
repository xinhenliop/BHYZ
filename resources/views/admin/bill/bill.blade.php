<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> {{--{{ $user['name'] }}--}} 控制台</title>
    <link rel="stylesheet" href="../model/layui/css/layui.css">
    <link rel="stylesheet" href="../model/app.css">
    <link rel="stylesheet" href="../css/app.css">
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


<div class="layui-bg-gray body-p" style="padding: 30px;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card layer-crad" style="">
                <div class="layui-card-body layui-card-crad">
                    {{--<p class="one-title title">账户信息</p>--}}
                    <div class="layer-body" style="padding-top: 1px;padding-left: 15px;">
                        <p>
                            <i class="layui-icon @lang("log.header_icon")" style="font-size: 18px;"></i><span
                                style="font-size: 14px;color:#666666;margin-left: 10px;">@lang("log.header")</span>
                        </p>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space12">
        <div class="layui-col-md12">
            <div class="layui-card layer-crad" style="">
                <div class="layui-card-body layui-card-crad">
                    <div class="layer-body" style="padding: 10px;">
                        <div class="layer-body">
                            <p class="one-title title">订单管理</p>
                            <hr style="margin-top: 20px;">
                            <div class="table-a-td demoTable">
                                <input type="text" class="layui-input layer-input orderNumber" name="orderNumber"
                                       placeholder="检索信息" value="" style="width: 300px;">
                                <input hidden name="orderState" class="orderState" value="log_users">
                                <button class="layui-btn layui-btn-primary demo1"
                                        style="width: 150px;height: 40px;border-radius: 5px;display: flex;margin-left: 10px;">
                                    <p class="demo2 lay-left">创建用户</p>
                                    <i class="layui-icon layui-icon-down layui-font-12 lay-rigth"></i>
                                </button>
                                <button type="button"
                                        style="margin-left: 10px;width: 100px;border-radius: 5px; height: 40px;"
                                        class="layui-btn layui-btn-normal layui-btns"
                                        lay-events="reload">搜索
                                </button>
                            </div>
                            <div>
                                <table class="layui-hide" lay-filter="test" id="test"></table>
                                <script type="text/html" id="barDemo">
                                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                                </script>
                                <script type="text/html" id="toolbar">
                                    <button type="button" class="layui-btns" lay-event="check_del">删除选中</button>
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
        var wheres = {
            page: {
                curr: 1 //重新从第 1 页开始
            }, where: {}
        }
        var dorder = "user";


        dropdown.render({
            elem: '.demo1'
            , data: [{
                title: '创建用户'
                , va: 'user'
            }, {
                title: '订单编号'
                , va: 'order_number'
            }
            ]
            , click: function (obj) {
                $('.demo2').text(obj.title);
                dorder = obj.va;
            }
        });

        table.render({
            elem: '#test'
            , url: "?m=" + currentUrl + ".bills"
            , toolbar: '#toolbar'
            , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                layout: ['count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                , groups: 2 //只显示 1 个连续页码

            }
            , id: 'testReload'
            , cols: [[
                {type: 'checkbox', fixed: 'left'},
                , {field: 'uid', width: 100, title: 'UID'}
                , {field: 'order_number', width: 200, title: '编号'}
                , {
                    field: 'status', width: 200, title: '订单状态', templet: function (d) {
                        return d.status === 0 ? "未支付" : d.status === 1 ? "已过期" : d.status === 2 ? "已支付" : "未知状态";
                    }
                }
                , {field: 'user', width: 200, title: '发起用户'}
                , {field: 'name', width: 200, title: '订单名称'}
                , {field: 'price', width: 200, title: '订单价格'}
                , {field: 'remark', width: 200, title: '订单备注'}
                , {field: 'created_at', width: 200, title: '创建时间'}
                , {fixed: 'right', title: '操作', width: 200, toolbar: '#barDemo'},
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
                table.reload('testReload', wheres);
            }
        };

        table.on('tool(test)', function (obj) { // 双击 toolDouble
            var data = obj.data;
            if (obj.event === 'del') {
                $.get({
                    url: '?m=' + currentUrl + '.del&Id=' + data.uid,
                    success: function (data) {
                        layer.msg(data.msg, {icon: 4});
                        active.reload();
                    }, error: function (data) {
                        layer.msg("失败", {icon: 4});
                    }
                });
                //post删除
            }
        });

        table.on('toolbar(test)', function (obj) {
            var id = obj.config.id;
            var checkStatus = table.checkStatus(id);
            switch (obj.event) {
                case 'check_del':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    var ids = [];
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    $.get({
                        url: '?m=' + currentUrl + '.del&uid=' + JSON.stringify(ids),
                        success: function (data) {
                            layer.msg(data.msg, {icon: 4});
                            active.reload();
                        }, error: function (data) {
                            layer.msg("失败", {icon: 4});
                        }
                    });
                    break;
                case 'getData':
                    break;
            }

        });
        util.event('lay-events', {
            //左侧菜单事件
            to_url: function () {
                //document.getElementById("body_url").setAttribute('src',"?m="+this.getAttribute('v-url'));
                //console.log(body_to.src);
                window.location.href = "?m=" + this.getAttribute('v-url');
            }, reload: function () {
                (LogData.val().length > 0) ? (wheres.where[dorder] = LogData.val()) : delete (wheres.where[dorder]);
                active.reload();
            }
        });

    });
    //头部事件
    //JS
</script>
</body>
</html>
