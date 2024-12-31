<?php

use App\Libraries\CommonFunction;

$user_name = CommonFunction::getUserFullName();
?>
    <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="box-shadow: 0px 1px #dee2e6;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    @if (Auth::user()->user_type == '5x505')
        <strong style="float: left; font-size: 18px"
                class="title">{{ Session::get('associated_company_name') }}</strong>
    @endif


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Navbar Search -->
        <li class="nav-item">
{{--            <a class="nav-link" data-widget="navbar-search" href="#" role="button">--}}
{{--                <i class="fas fa-search"></i>--}}
{{--            </a>--}}
            <div class="navbar-search-block">
                {!! Form::open(['url' => '/process/list', 'method' => 'POST', 'class' => 'form-inline',
                'id' => 'global-search', 'role' => 'form']) !!}
                <div class="input-group input-group-sm">
                    <input name="search_by_keyword" class="form-control form-control-navbar" type="search"
                           placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
{{--            <a class="nav-link" data-toggle="dropdown" href="#" id="loadNotifications">--}}
{{--                <i class="far fa-bell"></i>--}}
{{--                <span class="badge badge-success" id="notificationCount"></span>--}}
{{--                <span class="badge badge-warning navbar-badge countPendingNotification"></span>--}}
{{--            </a>--}}

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification"  style="overflow: auto; height: 300px">
                <span class="dropdown-item dropdown-header ">
                    <span class="countPendingNotification"></span> new
            notifications
                </span>
                <div class="dropdown-divider"></div>

                <!-- Inner Menu: contains the notifications -->
                <ul class="menu" id="notification">
                    <li class="text-center" id="notificationLoading">
                        <i class="fa fa-spinner fa-pulse fa-3x"></i>
                    </li>
                </ul>

                <a href="#" class="dropdown-item" id="notificationLoading">
                </a>
                <div class="dropdown-divider"></div>
                <a href="/notification-all" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>



        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img style="margin-top:-5px;" alt='...' width="30px" height="30px"
                     src="{{ isset(Auth::user()->user_pic) && Auth::user()->user_pic != '' ?  url('uploads/users/profile-pic/' . Auth::user()->user_pic) : '/assets/images/demo-user.jpg'}}"
                     {{--                             src="/assets/images/a2i.jpg"--}}
                     {{--                             src="{{ isset(Auth::user()->user_pic) && Auth::user()->user_pic != '' ? '/' . Auth::user()->user_pic : '' . $userPic }}"--}}
                     class="user-image img-circle">
                <span class="hidden-xs  float-right text-right">

                       <span class="hidden-xs">&nbsp;{{ $user_name }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <div class="row text-center mx-auto d-block p-2" style="background: silver">
                    <img style="width: 20%" alt='...'
                         src="{{ isset(Auth::user()->user_pic) && Auth::user()->user_pic != '' ?  url('uploads/users/profile-pic/' . Auth::user()->user_pic) : '/assets/images/demo-user.jpg'}}"
                         class="mx-auto d-block img-circle">

                    {{ $user_name }} - {!! Auth::user()->designation !!} <br>
                    <small>Last login: {{ Session::get('last_login_time') }}</small>
                    <br>
                    <a href="/users/profileinfo#tab_5" class="btn btn-xs bg-navy"><i
                            class="fa fa-unlock-alt"></i> &nbsp; Access log</a>
                </div>

                <div class="dropdown-divider"></div>
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ url('users/profileinfo') }}" class="btn btn-default">Profile</a>
                    </div>
                    <div>
                        <a href="{{ url('osspid/logout') }}" class="btn btn-default ">Sign out</a>
                    </div>
                </div>
            </div>

        </li>
        {{--        <li class="nav-item">--}}
        {{--            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">--}}
        {{--                <i class="fas fa-th-large"></i>--}}
        {{--            </a>--}}
        {{--        </li>--}}
    </ul>
</nav>

<br>
<!-- /.navbar -->
