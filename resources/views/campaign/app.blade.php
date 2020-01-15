<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPCAT官网</title>
    <!-- Bootstrap -->
    <link href="{{url('css/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{url('css/campaign/carousel.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('css/campaign/common.css')}}"/>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="static/jquery-1.11.1/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- <script src="static/bootstrap-3.3.0/js/bootstrap.min.js"></script> -->
    <script src="{{url('/js/jquery/jquery-2.1.1.min.js')}}"></script>
    <script src="{{url('/js/bootstrap/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/common.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/plugins/layer/layer.js')}}"></script>
    {{--<script src="{{url('/js/campaign/main.js')}}" type="text/javascript" charset="utf-8"></script>--}}
    <script src="{{url('/js/campaign/count.js')}}"></script>
</head>

<body>
<nav class="navbar navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=""><img src="{{url('images/campaign/logo.png')}}"/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav  navbar-right col-md-3">
                @if(!Auth::check())
                    <li><a href="javascript:void(0);" id="btnLogin">登录</a></li>
                    <li><a href="{{env('APP_PATH', '')}}/register" target="_blank">注册</a></li>
                @else
                    <li><a href="{{env('APP_PATH', '')}}/desktop" target="_blank">我的控制台</a></li>
                    <li class="my-control"><a href="/logout">退出</a></li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right  col-md-7">
                <li class="active"><a href="{{env('APP_PATH', '')}}/">首页</a></li>
                <li id="webtest"><a href="{{env('APP_PATH', '')}}/camp/webtest">Web测试</a></li>
                <li id="apptest"><a href="{{env('APP_PATH', '')}}/camp/apptest">App测试</a></li>
                @if(Auth::check())
					<li id="blog"><a href="{{env('APP_PATH', '')}}/posts" target="_blank">测试文档</a></li>
                    <li id="u8"><a href="{{env('APP_PATH', '')}}/camp/u8" target="_blank">U8专区</a></li>
                    <li id="ys"><a href="{{env('APP_PATH', '')}}/camp/ys/ysIndex" target="_blank">YS专区</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@yield('content')
@include('layout.footer')
<script type="text/javascript">
    var start = location.href.lastIndexOf("/") + 1;
    var dhId = location.href.substr(start);
    if ($("#" + dhId).length > 0) {
        $("#" + dhId).siblings().removeClass("active");
        $("#" + dhId).addClass("active");
    }
    $("#btnLogin").on("click", function () {
        layer.open({
            type: 2,
            title: "登录",
            skin: '', // 加上边框
            area: ["600px", "450px"], // 宽高
            scrollbar: false,
            maxmin: false,
            content: CommonUtil.getRootPath() + "/login"
        });
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 600) {
            $(".value").numberRock({
                speed: 40,
                count: 100
            })
            $(".value2").numberRock({
                speed: 30,
                count: 500
            })
            $(".value3").numberRock({
                speed: 20,
                count: 5000
            })
        }
    })


</script>
</body>

</html>
