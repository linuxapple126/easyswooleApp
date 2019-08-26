@extends('admin.auth.base')

@section('content')
    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <form action="" method="post">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                <input type="text" name="username" value="" id="username" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="password" id="password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" type="button" onclick="login()">登 入</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="application/javascript">
        $(document).keyup(function (event) {
            if (event.keyCode === 13) {
                login();
            }
        });

        function login() {
            let username = $("#username").val();
            if (!username) {
                layer.msg("用户名不能为空", {icon: 5, time: 2000});
                return false;
            }
            let password = $("#password").val();
            if (!username) {
                layer.msg("密码不能为空", {icon: 5, time: 2000});
                return false;
            }
            let data = {'username': username, 'password': password};
            ajaxLogin(data);
        }
    </script>
@endsection
