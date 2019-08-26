@extends('admin.layouts.main')

@section('content')
    <div class="layui-card layadmin-header">
        <div class="layui-breadcrumb" lay-filter="breadcrumb">
            <a lay-href="">主页</a>
            <a><cite>主页二</cite></a>
        </div>
    </div>

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        访问量
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">周</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">9,999,666</p>
                        <p>
                            总计访问量
                            <span class="layuiadmin-span-color">88万 <i class="layui-icon">&#xe66c;</i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>
                            新下载
                            <span class="layuiadmin-span-color">10% <i class="layui-icon">&#xe650;</i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        收入
                        <span class="layui-badge layui-bg-green layuiadmin-badge">年</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">

                        <p class="layuiadmin-big-font">999,666</p>
                        <p>
                            总收入
                            <span class="layuiadmin-span-color">*** <i class="layui-icon">&#xe659;</i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        活跃用户
                        <span class="layui-badge layui-bg-orange layuiadmin-badge">月</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">

                        <p class="layuiadmin-big-font">66,666</p>
                        <p>
                            最近一个月
                            <span class="layuiadmin-span-color">15% <i class="layui-icon">&#xe770;</i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        访问量
                        <div class="layui-btn-group layuiadmin-btn-group">
                            <button class="layui-btn layui-btn-primary layui-btn-xs">去年</button>
                            <button class="layui-btn layui-btn-primary layui-btn-xs">今年</button>
                        </div>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-sm4">
                                <div class="layuiadmin-card-list">
                                    <p class="layuiadmin-normal-font">月访问数</p>
                                    <span>同上期增长</span>
                                    <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                                        <div class="layui-progress-bar" lay-percent="30%"></div>
                                    </div>
                                </div>
                                <div class="layuiadmin-card-list">
                                    <p class="layuiadmin-normal-font">月下载数</p>
                                    <span>同上期增长</span>
                                    <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                                        <div class="layui-progress-bar" lay-percent="20%"></div>
                                    </div>
                                </div>
                                <div class="layuiadmin-card-list">
                                    <p class="layuiadmin-normal-font">月收入</p>
                                    <span>同上期增长</span>
                                    <div class="layui-progress layui-progress-big" lay-showPercent="yes">
                                        <div class="layui-progress-bar" lay-percent="25%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection