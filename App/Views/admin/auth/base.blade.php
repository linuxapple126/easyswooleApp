<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layuiAdmin</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('static/admin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('static/admin/style/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('static/admin/style/login.css')}}" media="all">
    <style>
        body {
            background-image: url({{asset('static/admin/images/bj.jpg')}});
        }
    </style>
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>layuiAdmin</h2>
            <p>layui 官方出品的单页面后台管理模板系统</p>
        </div>
        @yield('content')
    </div>
</div>

<script src="https://lib.baomitu.com/jquery/3.4.1/jquery.js"></script>
<script src="{{asset('static/admin/jquery.cookie.js')}}"></script>
<script src="{{asset('static/admin/common.js')}}"></script>
<script src="{{asset('static/admin/layui/layui.js')}}"></script>
<script>
    layui.config({
        base: "{{asset('static/backend/layuiadmin/')}}" //静态资源所在路径
    }).use(['layer'], function () {
    })
</script>

@yield('script')
</body>
</html>