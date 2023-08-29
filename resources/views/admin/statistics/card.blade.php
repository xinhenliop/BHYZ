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
    <div class="tongji-bg-gray panel">
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
        <div class="table-data" style="margin-top: 10px;">
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
                    <img src="/image/chart-bar.png" class="main-icon"></img>
                    <p class="table-statistics-text">卡密</p>
                </div>
                <div class="line">
                    <p class="table-statistics-number card">0</p>
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
                    <img src="/image/hard-drive.png" class="main-icon"></img>
                    <p class="table-statistics-text">卡类</p>
                </div>
                <div class="line">
                    <p class="table-statistics-number card_type">0</p>
                </div>
            </div>
        </div>
    </div>


    <div class="tongji-bg-gray panel">

        <div class="divcontainer" style="margin-top: 30px;display: grid;grid-template-columns: 1fr 1fr 1fr;">
            <div id="bing"></div>
            <div id="bing1"></div>
            <div id="bing2"></div>
        </div>

        <div class="layui-form today-form">
            <p class="form-label title one-title" style="margin-top: 30px;margin-bottom: 10px;">基础流量统计</p>
            <select lay-filter="select-filter-type" class="form-control tu-today">
                <option value="1">昨日</option>
                <option value="7">本周</option>
                <option value="30">本月</option>
                <option value="90">本季</option>
                <option value="9999">所有</option>
            </select>
        </div>
        <div class="divcontainer" style="display: grid;">
            <div id="container"></div>
        </div>
    </div>
</div>
<script src="../model/layui/layui.js"></script>
<script>
    Highcharts.getOptions().plotOptions.pie.colors = (function () {
        var colors = [],
            base = Highcharts.getOptions().colors[0],
            i;
        for (i = 0; i < 10; i += 1) {
            // Start out with a darkened base color (negative brighten), and end
            // up with a much brighter color
            colors.push(new Highcharts.Color(base).brighten((i - 3) / 7).get());
        }
        return colors;
    }());
    var myLineChart = null;

    function charts(csv, container) {
        Highcharts.chart(container, {
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

    function charts_bing(csv, bings) {
        Highcharts.chart(bings, {
            title: {
                text: csv.title
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '数量占比',
                data: csv.data
            }]
        });

    }

    layui.use(['element', 'layer', "form", "laydate"], function () {
        var element = layui.element
            , layer = layui.layer
            , $ = layui.$
            , form = layui.form;

        function tu_today() {
            $.get("?m=statistics.card_chart&today=" + $(".tu-today").val(), function (response) {
                var data = response;
                if (data.code === 200) {
                    charts(data, "container");
                    return;
                }
                layer.msg(data.msg);
            });
        }

        function to_today() {
            $.get("?m=statistics.card_total&today=" + $(".today").val(), function (response) {
                if (response.code === 200) {
                    var data = response.data;
                    $(".activation").text(data.activation);
                    $(".expired").text(data.expired);
                    $(".card").text(data.card);
                    $(".card_type").text(data.card_type);
                    return;
                }
                layer.msg(response.msg);
            });
        }

        function bing_today(bing, bings) {
            $.get("?m=statistics." + bing, function (response) {
                if (response.code === 200) {
                    charts_bing(response, bings);
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
        bing_today("card_app", "bing");
        bing_today("card_type", "bing1");
        bing_today("card_status", "bing2");
        tu_today();
        to_today();
    });
    //头部事件
    //JS
</script>
</body>
</html>
