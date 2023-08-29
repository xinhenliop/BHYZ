<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>代理中心</title>
    <link rel="stylesheet" href="../model/layui/css/layui.css">
    <link rel="stylesheet" href="../css/app.css">
    <style>
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

        .table-header {
            margin-left: 2px;
            width: 100%;
            height: 60px;
            border: 0px;
            background-color: #ffffff;
            padding-left: 10px;
            box-shadow: 1px 1px 3px 1px #cbcbcb;
        }
    </style>
    <script src="../js/jquery.min.js"></script>
</head>
<body>

<div class="body-p">
    @include("agent.menu",['request'=>$request,"user"=>$user,"web"=>$web])
    <div class="body">
        @include("components.header",['request'=>$request,"user"=>$user,"web"=>$web])
        <iframe src="?m={{$request->input("m","admin")}}" frameborder="0" id="Frame"
                style="margin-left: 5px;margin-top: 5px;width: 100%;height: 93%;"></iframe>
    </div>
</div>

<script src="../model/layer/layer.js"></script>
<script>
    var last_em = null;

    function getLabelOffest(id) {
        var label = document.getElementById(id);
        var labelTop = label.offsetTop;//绝对位置 left 的距离
        var clientHeight = label.clientHeight;
        var labelleft = label.offsetLeft;//绝对位置距离页面的 top 的高
        while (label = label.offsetParent) {
            labelTop += label.offsetTop;
            labelleft += label.offsetLeft;
        }
        return {
            "labelTop": labelTop + clientHeight + 6,
            "labelLeft": labelleft - 5
        };
    }

    //JS
    @if(isset($msg))
    layer.msg('{{$msg}}');
    @endif
    function meun() {
        var winth = ($(".bh-menu").css("width"));
        winth = winth.substring(0, winth.length - 2);
        if (parseInt(winth) > 45) {
            $(".bh-menu").css("width", "40px");
            $(".bh-menu").css("padding-left", "0px");
            $(".bh-menu-sw").css("transform", "rotate(180deg)");
            $(".web-titles").css("display", "flex");
        } else {
            $(".web-titles").css("display", "none");
            $(".bh-menu").css("width", "250px");
            $(".bh-menu").css("padding-left", "20px");
            $(".bh-menu-sw").css("transform", "");
        }

        $(".menu-title").toggle();
        $(".web-title").toggle();
        $(".bh-menu-title").toggle();
        $(".items-version").toggle();
        $(".items-line").toggle();
    }

    if (window.screen.width < 400) {
        meun();
    }
    $(".bh-menu-sw").on('click', function () {
        meun();
    });

    $("a.items").on('click', function () {
        $(this.querySelector(".icon")).css("fill", "blue");
        $(this.querySelector(".menu-title")).css("color", "#4b4bea");
        if (this.parentElement.querySelector(".two-menu") != null) {
            thiss = this.parentElement;
            $(thiss.querySelector(".two-menu")).toggle();
            if ($(thiss.querySelector(".bh-menu-two")).css("transform") == 'none') {
                $(thiss.querySelector(".bh-menu-two")).css("transform", "rotate(-90deg)");
            } else {
                $(thiss.querySelector(".bh-menu-two")).css("transform", "")
            }
        } else {
            $("#Frame").attr("src", this.getAttribute("to-url"));
        }
        last_em != null ? ($(last_em.querySelector(".icon")).css("fill", "black") && $(last_em.querySelector(".menu-title")).css("color", "#424242")) : false;
        (last_em = this);

    });


</script>
</body>
</html>
