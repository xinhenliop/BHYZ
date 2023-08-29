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
                            <p class="one-title title">用户管理</p>
                            <hr style="margin-top: 20px;">
                            <div class="table-a-td demoTable">
                                <form id="secan" class="table-secan layui-form">
                                    <select lay-search="cs" class="layui-select app_uid" name="app_uid">
                                        <option selected value="0">软件选择</option>
                                        @foreach(App::all() as $value)
                                            <option value="{{$value->uid}}">{{$value->app_name}}</option>
                                        @endforeach
                                    </select>

                                    <input hidden name="orderState" class="orderState" value="users">
                                    <button class="layui-btn layui-btn-primary demo1"
                                            style="width: 150px;height: 40px;border-radius: 5px;display: flex;margin-left: 10px;">
                                        <p class="demo2 lay-left">用户</p>
                                        <i class="layui-icon layui-icon-down layui-font-12 lay-rigth"></i>
                                    </button>

                                    <input type="text" class="layui-input layer-input orderNumber" name="orderNumber"
                                           placeholder="检索信息" value=""
                                           style="margin-left: 10px;min-width: 60px;width: 200px;">
                                    <button class="layui-btn layui-btn-primary demo5"
                                            style="width: 150px;height: 40px;border-radius: 5px;display: flex;margin-left: 10px;">
                                        <p class="demo6 lay-left">用户状态</p>
                                        <i class="layui-icon layui-icon-down layui-font-12 lay-rigth"></i>
                                    </button>

                                    <button type="button"
                                            style="margin-left: 10px;width: 100px;border-radius: 5px; height: 40px;"
                                            class="layui-btns layui-btn"
                                            lay-header-event="reload" style=";">搜索
                                    </button>
                                </form>
                                <button type="button"
                                        style="margin-left: 10px;width: 100px;border-radius: 5px; height: 40px;"
                                        class="layui-btns layui-btn "
                                        lay-header-event="CreCombo" style=";">添加用户
                                </button>

                            </div>
                            <div>
                                <table class="layui-hide" lay-filter="test" id="test"></table>
                                <script type="text/html" id="barDemo">
                                    @if(isset($user->admin_system))
                                        <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="addTime">加时</a>
                                    @endif
                                    <a class="layui-btn layui-btn-green layui-btn-xs" lay-event="edit">编辑</a>
                                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                                </script>

                                <script type="text/html" id="toolbar">
                                    @if(isset($user->admin_system))
                                        <button type="button" class="layui-btns" lay-event="addTime">选中加时</button>
                                    @endif
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

    layui.use(['element', 'dropdown', 'layer', 'util', 'table', "laydate"], function () {
        var element = layui.element
            , layer = layui.layer
            , util = layui.util
            , $ = layui.$
            , dropdown = layui.dropdown
            , table = layui.table
            , laydate = layui.laydate;
        var currentUrl = $('.currentUrl').val();
        var LogData = $('.orderNumber');
        var wheres = {
            page: {
                curr: 1 //重新从第 1 页开始
            }, where: {}
        };


        function addTime(uids) {
            layer.prompt({title: '增加时间单位/时'}, function (value, index, elem) {
                if (value === '') return elem.focus();
                layer.close(index);
                $.post({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '?m=' + currentUrl + ".addTime",
                    data: 'uid=' + uids + "&time=" + value,
                    success: function (data) {
                        layer.msg(data.msg, {icon: 4});
                        active.reload();
                    }, error: function (data) {
                        layer.msg("失败", {icon: 4});
                    }
                });
            });
        }

        dropdown.render({
            elem: '.demo1'
            , data: [{
                title: '选择条件'
                , va: '0'
            }, {
                title: '账号'
                , va: 'user'
            }, {
                title: '激活时间大于'
                , va: '>activate_date'
            }, {
                title: '激活时间小于'
                , va: '<activate_date'
            }
            ]
            , click: function (obj) {
                $('.orderState').val(obj.va);
                $('.demo2').text(obj.title);
                if (obj.va == "0") {
                    delete (wheres.where['user']);
                    delete (wheres.where['users']);
                    delete (wheres.where['activate_date']);
                    return;
                }
                if (obj.title.startsWith("激活时间")) {
                    LogData.attr("id", "ID-laydate-type-datetime-1");
                    LogData.attr("placeholder", "yyyy-MM-dd HH:mm:ss");
                    laydate.render({
                        elem: '#ID-laydate-type-datetime-1',
                        type: 'datetime',
                        fullPanel: true // 2.8+
                    });
                } else {
                    LogData.attr("id", "000");
                    LogData.attr("placeholder", "检索信息");
                }
            }
        });

        dropdown.render({
            elem: '.demo5'
            , data: [{
                title: '过期'
                , va: '0'
            }, {
                title: '冻结'
                , va: '1'
            }, {
                title: '正常'
                , va: '2'
            }, {
                title: '已激活'
                , va: '99'
            }, {
                title: '选择状态'
                , va: '-1'
            }
            ]
            , click: function (obj) {
                if (obj.va >= 0) {
                    wheres.where.status = obj.va;
                } else {
                    delete (wheres.where.status);
                }
                $('.demo6').text(obj.title);

            }
        });
        table.render({
            elem: '#test'
            , url: "?m=cards.users"
            , toolbar: '#toolbar'
            , page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                layout: ['count', 'prev', 'page', 'next', 'skip', "filter", 'limit'] //自定义分页布局
                , groups: 3 //只显示 1 个连续页码

            }
            , id: 'testReload'
            , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'uid', width: 80, title: 'UID'}
                , {field: 'user', width: 200, title: '账号'}
                , {field: 'app_name', width: 150, title: '软件'}
                , {field: 'status', width: 80, title: '状态'}
                , {field: 'activate_date', width: 250, title: '激活时间'}
                , {field: 'end_time', width: 250, title: '到期时间'}
                , {field: 'login_count', width: 100, title: '登录次数'}
                , {field: 'unbind_count', width: 100, title: '解绑次数'}
                , {field: 'time', width: 100, title: '剩余时长'}
                , {field: 'features', width: 150, title: '绑定设备码'}
                , {field: 'ip', width: 150, title: '绑定IP'}
                , {field: 'last_time', width: 150, title: '最后登录时间'}
                , {field: 'last_ip', width: 150, title: '最后登录IP'}
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
                table.reload('testReload', wheres);
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
                    urls = '?m=' + currentUrl + '.edit&uid=' + JSON.stringify(ids) + "&status=1";
                    break;
                case 'check_start':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    urls = '?m=' + currentUrl + '.edit&uid=' + JSON.stringify(ids) + "&status=2";
                    break;
                case 'addTime':
                    var data = checkStatus.data.length === 0 ? (layer.msg("未选择数据")) & 0 : checkStatus.data;
                    if (data == 0) break;
                    for (var i = 0; i < data.length; i++) {
                        ids[i] = data[i].uid;
                    }
                    addTime(JSON.stringify(ids));
                    return;
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
            } else if (obj.event === 'addTime') {
                addTime(data.uid);
            } else if (obj.event === 'edit') {
                var index = layer.open({
                    title: '用户修改',
                    type: 2,
                    area: ['800px', '500px'],
                    maxmin: true,
                    content: '?m=' + currentUrl + ".get&user=1&uid=" + data.uid
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
                var app_uid = $(".app_uid");
                if (app_uid.val() != 0) {
                    wheres.where.app_uid = app_uid.val();
                } else {
                    delete (wheres.where.app_uid);
                }
                var where = $('.orderState').val();
                if (LogData.val().length > 1 && where !== "0") {
                    if (where.indexOf("activate_time") > 0) {
                        wheres.where[where.substring(1,)] = where.substring(0, 1) + LogData.val();
                    } else {
                        wheres.where[$('.orderState').val()] = LogData.val();
                    }

                }
                active.reload();
            }, CreCombo: function () {
                var index = layer.open({
                    title: '添加用户',
                    type: 2,
                    maxmin: true,
                    area: ['800px', '500px'],
                    content: ['?m=' + currentUrl + ".created&user=1"]
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
