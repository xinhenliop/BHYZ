<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title> {{--{{ $user['name'] }}--}} 控制台</title>
    <link rel="stylesheet" href="../model/layui/css/layui.css">
    <link rel="stylesheet" href="../model/app.css">
    <link rel="stylesheet" href="../css/app.css">
    <script src="http://code.hcharts.cn/highcharts/10.0.0/highcharts.js"></script>
    <style type="text/css">
        html {
            position: absolute;
            height: 100%;
            width: 100%;
        }

        body {
            width: 100%;
            height: 100%;
        }

        .body-p {
            width: 100%;
            height: 100%;
            background-color: #fefefe;
            display: flex;
            flex-wrap: nowrap;
            padding-left: 20px;
            padding-right: 5%;
            padding-top: 20px;
        }

        p {
            width: 100%;
            color: #0095ef;
            font-weight: bold;
        }

        .body {
            width: 100%;
            height: 100%;
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<input hidden name="url" class="currentUrl" value="{{$url}}">
<div class="layui-bg-gray body-p">
    <div class="container">
        <div class="notice panel">
            <p class="one-title title">公告管理<img style="margin-left: 5px;width: 20px;height: 20px;"
                                                    src="/image/bell.png"></p>
            <hr style="margin-top: 10px;">
            <div class="gg-list">

                <div class="gg divcontainer">
                    <div class="tips"></div>
                    <div class="text-lable">
                        <a href="javascript:"><span class="gg-title" style="color: #f69797;">欢迎使用BH，BH可以方便快捷为您搭建一套API认证系统，且完全免费、开源。可自定义开发，添加插件等功能。</span></a>
                        <label class="gg-time">2023-08-28 18:45:35</label>
                    </div>
                </div>

                <div class="gg divcontainer">
                    <div class="tips"></div>
                    <div class="text-lable">
                        <a href="javascript:"><span class="gg-title" style="color: #f8baba;">推荐安装php扩展：opcache，该扩展主要是为了加速和缓存php脚本，大幅度提升网站运行速度。宝塔用户可以在php-8.0管理中找到安装扩展选项进行安装。</span></a>
                        <label class="gg-time">2023-8-28 18:20:35</label>
                    </div>
                </div>

                <div class="gg divcontainer">
                    <div class="tips"></div>
                    <div class="text-lable">
                        <a href="https://github.com/xinhenliop/BHYZ" target="_blank"><span class="gg-title"
                                                                                           style="color: #7e52f8;">BH开源地址：https://github.com/xinhenliop/BHYZ</span></a>
                        <label class="gg-time">2023-08-28 18:15:35</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="data panel">
            {{-- <p class="one-title title"></p>
             <hr style="margin-top: 10px;">--}}
            <div class="layui-form today-form">
                <select lay-filter="select-filter-to" class="form-control today">
                    <option value="1">昨日</option>
                    <option value="7">七天</option>
                    <option value="30">本月</option>
                    <option value="90">本季</option>
                    <option value="9999">所有</option>
                </select>
            </div>
            <div class="table-data">
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/chess-queen.png" class="main-icon"></img>
                        <p class="table-statistics-text">激活</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number activation">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/chess-knight.png" class="main-icon"></img>
                        <p class="table-statistics-text ">注册</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number registrations">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/calendar-xmark.png" class="main-icon"></img>
                        <p class="table-statistics-text">过期</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number expired">0</p>
                    </div>
                </div>

                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/chart-bar.png" class="main-icon"></img>
                        <p class="table-statistics-text">卡密</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number card">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/address-card.png" class="main-icon"></img>
                        <p class="table-statistics-text">用户</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number user">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/border-all.png" class="main-icon"></img>
                        <p class="table-statistics-text">程序</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number app">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/circle-nodes.png" class="main-icon"></img>
                        <p class="table-statistics-text ">流水</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number water">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/bag-shopping.png" class="main-icon"></img>
                        <p class="table-statistics-text ">代理</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number agent">0</p>
                    </div>
                </div>
                <div class="panel table-statistics">
                    <div class="line line1 divcontainer">
                        <img src="/image/baht-sign.png" class="main-icon"></img>
                        <p class="table-statistics-text ">代理总额</p>
                    </div>
                    <div class="line">
                        <p class="table-statistics-number total">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tongji-bg-gray panel">
        <div class="layui-form today-form">
            <select lay-filter="select-filter-type" class="form-control tu-today">
                <option value="1">昨日</option>
                <option value="7">本周</option>
                <option value="30">本月</option>
                <option value="90">本季</option>
                <option value="9999">所有</option>
            </select>
        </div>
        <div id="container"></div>
    </div>
</div>
<script src="../model/layui/layui.js"></script>
<script>
    var myLineChart = null;

    function charts(csv) {
        Highcharts.chart('container', {
            title: {
                text: csv.title
            },
            subtitle: {
                text: csv.subtitle
            },
            yAxis: {
                title: {
                    text: '数量'
                }
            },
            xAxis: {
                categories: csv.xAxis
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        // 开启数据标签
                        enabled: true
                    },
                    // 关闭鼠标跟踪，对应的提示框、点击事件会失效
                    enableMouseTracking: false
                }
            },
            series: csv.data,
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    }

    layui.use(['element', 'layer', "form", "laydate"], function () {
        var element = layui.element
            , layer = layui.layer
            , $ = layui.$
            , form = layui.form;

        function tu_today() {
            $.get("?m=statistics.index_chart&today=" + $(".tu-today").val(), function (response) {
                var data = response;
                if (data.code === 200) {
                    charts(data);
                    return;
                }
                layer.msg(data.msg);
            });
        }

        function to_today() {
            $.get("?m=statistics.index_total&today=" + $(".today").val(), function (response) {
                if (response.code === 200) {
                    var data = response.data;
                    $(".activation").text(data.activation);
                    $(".expired").text(data.expired);
                    $(".registrations").text(data.registrations);
                    $(".water").text("￥" + data.water);
                    $(".agent").text(data.agent);
                    $(".total").text("￥" + data.total);
                    $(".card").text(data.card);
                    $(".user").text(data.user);
                    $(".app").text(data.app);
                    return;
                }
                layer.msg(response.msg);
            });
        }

        form.on("select(select-filter-type)", function (data) {
            tu_today();
        });
        form.on("select(select-filter-to)", function (data) {
            to_today();
        });

        tu_today();
        to_today();
    });
    //头部事件
    //JS
</script>
</body>
</html>
