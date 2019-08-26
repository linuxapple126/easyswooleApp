/**
 * 设置cookie
 * @param key
 * @param data
 * @param time
 * @param domain
 * @param secure
 */
function setCookie(key, data, time, domain, secure = false) {
    $.cookie(key, data, {
        expires: time,
        path: '/',
        domain: domain,
        secure: secure,
    });
}

/**
 * 获取cookie
 * @param key
 */
function getCookie(key) {
    $.cookie(key);
}

/**
 * 删除cookie
 * @param key
 */
function delCookie(key) {
    $.removeCookie(key, {path: '/', expires: -1});
}

/**
 * 获取全局token
 */
let authorization = getCookie('token');

/**
 * 登录调用
 * @param data
 */
function ajaxLogin(data) {
    $.ajax({
        async: true,
        url: '/admin/auth/login',
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            console.log(res);
            if (res.code !== 200) {
                layer.msg(res.message, {icon: 5, time: 1000});
                return false;
            }
            layer.msg('登录成功,请稍等...', {icon: 6, time: 1000}, function () {
                setCookie('token', res.data.token, 7);
                window.location.href = '/admin/index';
            });
        }
    });
}

/**
 * 管理员退出
 */
function logout() {
    $.ajax({
        async: true,
        url: '/auth/logout',
        type: 'post',
        dataType: 'json',
        success: function (res) {
            console.log(res);
            if (res.code !== 200) {
                layer.msg(res.message, {icon: 5, time: 1000});
                return false;
            }
            layer.msg('已安全退出,为您跳转登录...', {icon: 6, time: 1000}, function () {
                delCookie('token');
                window.location.href = '/auth/login';
            });
        }
    });
}


/**
 * 全局ajax调用
 * @param url
 * @param data
 * @param endUrl
 */
function ajaxGet(url, data, endUrl) {
    $.ajax({
        async: false,
        url: url,
        data: data,
        type: "GET",
        dataType: 'json',
        success: function (res) {
            if (res.code !== 0) {
                layer.msg(res.message, {icon: 5, time: 1000});
                return false;
            }
        }
    });
}

/**
 * 全局ajax调用
 * @param url
 * @param data
 * @param endUrl
 */
function ajaxPost(url, data, endUrl) {
    $.ajax({
        async: false,
        url: url,
        data: data,
        type: "POST",
        dataType: 'json',
        success: function (res) {
            if (res.code !== 200) {
                layer.msg(res.message, {icon: 5, time: 1000});
                return false;
            }
            layer.msg(res.message, {icon: 6, time: 1000}, function () {
                window.location.href = endUrl;
            });
        }
    });
}
