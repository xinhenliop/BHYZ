<!DOCTYPE html>
<html>
<head>
    <title>{{$err['title']}}</title>
    <link href="./css/app.css" rel="stylesheet/css">
    <style>
        body {
            width: 100%;
            height: 100%;
            background-color: #fffdf8;
        }

        div {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        p {
            color: #01aaed;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body style="">
<div id="divs">
    <p>{{$err['context']}}</p>
</div>
</body>
</html>
