<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$Segment = Request::segment(3);
$is_eligibility = 0;
if ($user_type == '5x505') {
    $is_eligibility = \App\Libraries\CommonFunction::checkEligibility();
}
$prefix = '';
if ($type[0] == 5) {
    $prefix = 'client';
}
?>

<style>
.logo-card {
    height: 80px !important;
    overflow: hidden;
    background: #f3f3f3;
}

.logo_design {
    height: 120px !important;
    width: 160px !important;
    object-fit: contain;
    margin-top: -20px;
}
.shadow-none {
    box-shadow: none !important;
}
.active-menu-color{
    background-color: #218838;
}
.margin-extra {
    margin: 34px;
}

</style>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 shadow-none">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link"> --}}
    {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" --}}
    {{-- style="opacity: .8"> --}}
    {{-- <span class="brand-text font-weight-light">AdminLTE 3</span> --}}
    {{-- </a> --}}


    <div class="row logo-card">
        <div class="col">
            <div class="mx-auto w-50">
                <a href="{{ url('/dashboard') }}" class="brand-linkd ">
                    <span class="logo-lg  d-flex justify-content-center">{!! Html::image("assets/images/btrc_logo.png", 'logo', ['width' => 150, 'height' => 70, 'class' => 'logo_design']) !!}</span>
                </a>
            </div>
        </div>
    </div>

{{--    <hr>--}}

    <!-- Sidebar -->
    <div class="sidebar" style="padding-bottom: 50px;">
        <!-- Sidebar user panel (optional) -->
    {{--        <div class="user-panel mt-3 pb-3 mb-3 d-flex">--}}
    {{--            <div class="image">--}}
    {{--                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">--}}
    {{--            </div>--}}
    {{--            <div class="info">--}}
    {{--                <a href="#" class="d-block">Alexander Pierce</a>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    <!-- SidebarSearch Form -->
    {{-- <div class="form-inline"> --}}
    {{-- <div class="input-group" data-widget="sidebar-search"> --}}
    {{-- <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search"> --}}
    {{-- <div class="input-group-append"> --}}
    {{-- <button class="btn btn-sidebar"> --}}
    {{-- <i class="fas fa-search fa-fw"></i> --}}
    {{-- </button> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}

    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-legacy" data-widget="treeview" role="menu"
                data-accordion="false" style="margin-bottom: 40px;">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @if(!in_array($type[0], [11]))
                <li class="nav-item ">
                    <a href="{{ url('/dashboard') }}"
                       class="nav-link {{ Request::is('dashboard') || Request::is('dashboard/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @endif
                @if ($is_eligibility || in_array($type[0], [5]))
                    <li class="nav-item  ">
                        <a href="{{ url('client/company-profile/create') }}"
                           class="nav-link {{ Request::is('client/company-profile/create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                {!!trans('CompanyProfile::messages.company_profile')!!}
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item
                    {{
                    Request::is('*/isp-license-*/*') ||
                    Request::is('*/nix-license-*/*') ||
                    Request::is('*/vsat-license-*/*') ||
                    Request::is('*/iptsp-license-*/*') ||
                    Request::is('*/vts-license-*/*') ||
                    Request::is('*/nttn-license-*/*')
                    ? 'active menu-is-opening menu-open' : '' }}">
                    @if(!in_array($type[0], [11]))
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Open License
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/isp-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/isp-license-issue/list/'. Encryption::encodeId(1)) }}"
                                   class="nav-link {{ Request::is('*/isp-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>ISP License</p>
                                </a>
                            </li>
                        @endif


                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/nix-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/nix-license-issue/list/'. Encryption::encodeId(9)) }}"
                                   class="nav-link {{ Request::is('*/nix-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> NIX License</p>
                                </a>

                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/vsat-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/vsat-license-issue/list/'. Encryption::encodeId(13)) }}"
                                   class="nav-link {{ Request::is('*/vsat-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>VSAT License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/iptsp-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/iptsp-license-issue/list/'. Encryption::encodeId(21)) }}"
                                   class="nav-link {{ Request::is('*/iptsp-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> IPTSP License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/vts-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/vts-license-issue/list/'. Encryption::encodeId(29)) }}"
                                   class="nav-link {{ Request::is('*/vts-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> VTS License</p>
                                </a>
                            </li>
                        @endif

                            @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                                <li class="nav-item {{ Request::is('*/nttn-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                    <a href="{{ url($prefix.'/nttn-license-issue/list/'. Encryption::encodeId(50)) }}"
                                       class="nav-link {{ Request::is('*/nttn-license-issue/list/*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> NTTN License</p>
                                    </a>
                                </li>
                            @endif
                    </ul>
                </li>

                <li class="nav-item
                   {{
                     Request::is('*/iig-license-*/*') ||
                     Request::is('*/icx-license-*/*') ||
                     Request::is('*/igw-license-*/*') ||
                     Request::is('*/itc-license-*/*') ||
                     Request::is('*/mno-license-*/*') ||
                     Request::is('*/tc-license-*/*') ||
                     Request::is('*/mnp-license-*/*') ||
                     Request::is('*/bwa-license-*/*') ||
                     Request::is('*/scs-license-*/*') ||
                     Request::is('*/ss-license-*/*')
                     ? 'active menu-is-opening menu-open' : ''
                    }}
                ">
                @if(!in_array($type[0], [11]))
                    <a href="#" class="nav-link" >
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Bidding License
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                @endif
                    <ul class="nav nav-treeview">
                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/iig-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/iig-license-issue/list/'. Encryption::encodeId(17)) }}"
                                   class="nav-link {{ Request::is('*/iig-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>IIG License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/icx-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/icx-license-issue/list/'. Encryption::encodeId(33)) }}"
                                   class="nav-link {{ Request::is('*/icx-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>ICX License</p>
                                </a>

                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/igw-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/igw-license-issue/list/'. Encryption::encodeId(37)) }}"
                                   class="nav-link {{ Request::is('*/igw-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>IGW License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/itc-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/itc-license-issue/list/'. Encryption::encodeId(54)) }}"
                                   class="nav-link {{ Request::is('*/itc-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>ITC License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/mno-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/mno-license-issue/list/'. Encryption::encodeId(58)) }}"
                                   class="nav-link {{ Request::is('*/mno-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>MNO License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/scs-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/scs-license-issue/list/'. Encryption::encodeId(62)) }}"
                                   style="display: flex; column-gap: 5px;"
                                   class="nav-link {{ Request::is('*/scs-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon" style="display: block;"></i>
                                    <p style="margin-top: -3px;">Submarine Cable Service License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/tc-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/tc-license-issue/list/'. Encryption::encodeId(66)) }}"
                                   class="nav-link {{ Request::is('*/tc-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tower Sharing License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/mnp-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/mnp-license-issue/list/'. Encryption::encodeId(70)) }}"
                                   class="nav-link {{ Request::is('*/mnp-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>MNP License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/bwa-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/bwa-license-issue/list/'. Encryption::encodeId(74)) }}"
                                   class="nav-link {{ Request::is('*/bwa-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>PSTN License</p>
                                </a>
                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/ss-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/ss-license-issue/list/'. Encryption::encodeId(78)) }}"
                                   class="nav-link {{ Request::is('*/ss-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Satellite Service License</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                <li class="nav-item
                    {{
                    Request::is('*/bpo-or-call-center-*/*') ||

                    Request::is('*/tvas-license-*/*')

                    ? 'active menu-is-opening menu-open' : '' }}">
                    @if(!in_array($type[0], [11]))
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Registration Certificate
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @endif
                    <ul class="nav nav-treeview">
                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/bpo-or-call-center-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/bpo-or-call-center-new-app/list/'. Encryption::encodeId(5)) }}"
                                   class="nav-link {{ Request::is('*/bpo-or-call-center-new-app/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>BPO/Call Center Registration <span class="margin-extra">Certificate</span></p>
                                </a>

                            </li>
                        @endif

                        @if ($is_eligibility || in_array($type[0], [1,10, 2, 4, 6, 8]))
                            <li class="nav-item {{ Request::is('*/tvas-license-*/*') ? 'active menu-is-opening menu-open active-menu-color' : '' }}">
                                <a href="{{ url($prefix.'/tvas-license-issue/list/'. Encryption::encodeId(25)) }}"
                                   class="nav-link {{ Request::is('*/tvas-license-issue/list/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>TVAS Certificate</p>
                                </a>

                            </li>
                        @endif
                    </ul>
                </li>
                @if(!in_array($type[0], [11]))
                <li class="nav-item  ">
                    <a href="{{ url('/special_service/list') }}"
                        class="nav-link {{ Request::is('/special_service/*  ') ? 'active' : '' }}">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Other Licenses
                        </p>
                    </a>
                </li>
                @endif

                @if (in_array($type[0], [1,10, 2, 4, 6, 8]))
                    <li class="nav-item  ">
                        <a href="{{ url('/users/lists/') }}"
                           class="nav-link  {{ Request::is('users/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endif

                @if (in_array($type[0], [1,10, 2, 4, 6,11,15]))
                    <li class="nav-item  ">
                        <a href="{{ url('/reportv2') }}"
                           class="nav-link {{ Request::is('reportv2') || Request::is('reportv2/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                {!! trans('messages.report') !!}
                            </p>
                        </a>
                    </li>
                @endif

                @if (in_array($type[0], [1, 10]))
                    @if (in_array($type[0], [1]))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>
                                {!! trans('messages.online-payment') !!}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview" style="display: none;">

                            <li class="nav-item">
                                <a href="{{ url('/spg/list') }}"
                                   class="nav-link {{ Request::is('spg/list') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{!! trans('messages.list') !!}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('/spg/payment-configuration') }}"
                                   class="nav-link {{ Request::is('spg/payment-configuration') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{!! trans('messages.configuration') !!}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('ipn/ipn-list') }}"
                                   class="nav-link {{ Request::is('ipn/*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{!! trans('messages.ipn') !!}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="nav-item {{ Request::is('settings/*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="javascript:void(0)"
                           class="nav-link  {{ Request::is('settings/*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-wrench"></i>
                            <p>{!!trans('messages.settings')!!}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav-item nav-treeview">

                            <li class="nav-item @if(Request::is('/settings/special-service/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/special-service') }}">
                                    <i class="fa fa-file fa-fw nav-icon" aria-hidden="true"></i>
                                    <span> Other License generate</span>
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/application-guideline/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/application-guideline') }}">
                                    <i class="fa fa-file fa-fw nav-icon" aria-hidden="true"></i>
                                    <span> {!! trans('messages.application_guideline') !!}</span>
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/index#/home-page/user-manual') OR Request::is('/settings/index#/home-page/create-user-manual') OR Request::is('/settings/index#/home-page/edit-user-manual/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/home-page/user-manual') }}">
                                    <i class="fa fa-circle nav-icon"></i> {!! trans('messages.user_manual') !!}
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/document-v2*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/document-v2') }}">
                                    <i class="fa fa-file fa-fw nav-icon" aria-hidden="true"></i>
                                    <span>{!! trans('messages.document') !!}</span>
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/area-list') || Request::is('settings/index#/create-area') || Request::is('settings/index#/edit-area/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/area-list') }}">
                                    <i class="fa fa-map-marker fa-fw nav-icon"></i> {!!trans('messages.area')!!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/eligible-area-list') ) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/eligible-area-list') }}">
                                    <i class="fa fa-map-marker fa-fw nav-icon"></i> {!!trans('messages.eligibility_area')!!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/bank-list') || Request::is('settings/index#/bank/create') || Request::is('settings/index/bank#/edit/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/bank-list') }}">
                                    <i class="fa fa-home fa-fw nav-icon"></i> {!! trans('messages.bank') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/branch-list') || Request::is('settings/index#/branch/create') || Request::is('settings/index#/branch/edit/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/branch-list') }}">
                                    <i class="fa fa-home fa-fw nav-icon"></i> {!! trans('messages.bank_branch') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/notice') || Request::is('/settings/index#/create-notice') || Request::is('/settings/index#/edit-notice/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/notice-list') }}">
                                    <i class="fa fa-list-alt fa-fw nav-icon"></i> {!! trans('messages.notice') !!}
                                </a>
                            </li>
                            @if (in_array($type[0], [1]))
                            <li class="nav-item @if(Request::is('/settings/index#/security') || Request::is('/settings/index#/edit-security/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/security') }}">
                                    <i class="fa fa-key fa-fw nav-icon"></i> {!! trans('messages.security_profile') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/company-info') || Request::is('/settings/index#/company-info') || Request::is('/settings/index#/create-company') || Request::is('/settings/index#/rejected-company') || Request::is('/settings/index#/company-info-action/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/company-info') }}">
                                    <i class="fa fa-industry nav-icon" aria-hidden="true"></i>  {!! trans('messages.company_info') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/currency-list') || Request::is('/settings/index#/create-currency') || Request::is('/settings/index#/edit-currency/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/currency-list') }}">
                                    <i class="fa fa-money-bill nav-icon" aria-hidden="true"></i>  {!! trans('messages.currency') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/user-type') || Request::is('/settings/index#/edit-user-type/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/user-type') }}">
                                    <i class="fa fa-user nav-icon" aria-hidden="true"></i>  {!! trans('messages.user_types') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/pdf-print-requests') || Request::is('/settings/index#/get-pdf-print-requests') || Request::is('/settings/index#/edit-pdf-print-requests/*') || Request::is('/settings/index#/pdf-print-request-verify/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/pdf-print-requests') }}">
                                    <i class="fa fa-file-pdf  nav-icon" aria-hidden="true"></i>  {!! trans('messages.pdf-print-requests') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/email-sms-queue') || Request::is('/settings/index#/email-sms-queue/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/email-sms-queue') }}">
                                    <i class="fa fa-sync  nav-icon" aria-hidden="true"></i>  {!! trans('messages.email_sms_queue') !!}
                                </a>
                            </li>
                            {{-- <li class="nav-item @if(Request::is('/settings/index#/terms-condition') OR Request::is('/settings/index#/create-terms-condition') OR Request::is('/settings/index#/edit-terms-condition/*')) active @endif">
                                 <a class="nav-link" href="{{ url ('/settings/index#/terms-condition') }}">
                                     <i class="fa fa-book  nav-icon" aria-hidden="true"></i>  {!! trans('messages.terms_and_condition') !!}
                                 </a>
                             </li>--}}
                            {{--<li class="nav-item @if(Request::is('/settings/index#/service-details/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/service-details') }}">
                                    <i class="fa fa-cogs nav-icon" aria-hidden="true"></i>  {!! trans('messages.services_and_forms') !!}
                                </a>
                            </li>--}}

                            <li class="@if(Request::is('/settings/index#/edit-logo')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/edit-logo') }}">
                                    <i class="fa fa-list-alt nav-icon" aria-hidden="true"></i>  {!! trans('messages.title_logo') !!}
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="javascript:void(0)"
                                   class="nav-link  {{ (Request::is('/settings/index#/home-page/*') ? 'active' : '') }}">
                                    <i class="nav-icon fa fa-wrench"></i>
                                    <p>{!!trans('messages.sidebar.home_page')!!}</p>
                                    <i class="fas fa-angle-left right"></i>
                                </a>
                                <ul class="nav-item nav-treeview" style="display: none;">
                                    <li class="nav-item @if(Request::is('/settings/index#/home-page/home-page-slider') OR Request::is('/settings/index#/home-page/create-home-page-slider') OR Request::is('/settings/home-page/edit-home-page-slider/*')) active @endif">
                                        <a class="nav-link" href="{{ url ('/settings/index#/home-page/home-page-slider') }}">
                                            <i class="fa fa-circle nav-icon" aria-hidden="true"></i>
                                            {!! trans('messages.home_page_slider') !!}
                                        </a>
                                    </li>
{{--                                    <li class="nav-item @if(Request::is('/settings/index#/home-page/user-manual') OR Request::is('/settings/index#/home-page/create-user-manual') OR Request::is('/settings/index#/home-page/edit-user-manual/*')) active @endif">--}}
{{--                                        <a class="nav-link" href="{{ url ('/settings/index#/home-page/user-manual') }}">--}}
{{--                                            <i class="fa fa-circle nav-icon"></i> {!! trans('messages.user_manual') !!}--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                    <li class="nav-item @if(Request::is('/settings/index#/home-page/home-page-content') OR Request::is('/settings/index#/home-page/create-home-page-content') OR Request::is('/settings/index#/home-page/edit-home-page-content/*')) active @endif">
                                        <a  class="nav-link" href="{{ url ('/settings/index#/home-page/home-page-content') }}">
                                            <i class="fa fa-circle nav-icon"></i> {!! trans('messages.home-page-content') !!}
                                        </a>
                                    </li>
{{--                                    <li class="nav-item @if(Request::is('/settings/index#/home-page/industrial-city') OR Request::is('/settings/index#/home-page/create-industrial-city') OR Request::is('/settings/index#/home-page/edit-industrial-city/*')) active @endif">--}}
{{--                                        <a  class="nav-link" href="{{ url ('/settings/index#/home-page/industrial-city') }}">--}}
{{--                                            <i class="fa fa-circle nav-icon"></i> {!! trans('messages.industrial-city') !!}--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                    <li class="nav-item @if(Request::is('/settings/index#/home-page/home-page-articles') OR Request::is('/settings/index#/home-page/create-home-page-articles') OR Request::is('/settings/index#/home-page/edit-home-page-articles/*')) active @endif">
                                        <a class="nav-link" href="{{ url ('/settings/index#/home-page/home-page-articles') }}">
                                            <i class="fa fa-circle nav-icon"></i> {!! trans('messages.home-page-articles') !!}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item @if(Request::is('settings/maintenance-mode')) active @endif">
                                <a class="nav-link" href="{{ url ('settings/maintenance-mode') }}">
                                    <i class="fa fa-wrench fa-fw nav-icon"></i> {!! trans('messages.maintenance_mode') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('settings/app-rollback') || Request::is('settings/app-rollback-open')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/app-rollback') }}">
                                    <i class="fa fa-undo  nav-icon" aria-hidden="true"></i>
                                    App Rollback
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('settings/forcefully-data-update') || Request::is('settings/forcefully-data-update')) active @endif">
                                <a class="nav-link" href="{{ url ('settings/forcefully-data-update') }}">
                                    <i class="fa fa-database nav-icon" aria-hidden="true"></i>
                                    Forcefully data update
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('settings/bidding-license-configuration')) active @endif">
                                <a class="nav-link" href="{{ url ('settings/bidding-license-configuration') }}">
                                    <i class="fa fa-database nav-icon" aria-hidden="true"></i>
                                    Bidding License Configuration
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if($type[0] == 2)
                    <li class="nav-item {{ Request::is('settings/*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="javascript:void(0)"
                           class="nav-link  {{ Request::is('settings/*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-wrench"></i>
                            <p>{!!trans('messages.support')!!}</p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav-item nav-treeview">


                            <li class="nav-item @if(Request::is('/settings/document-v2*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/document-v2') }}">
                                    <i class="fa fa-file fa-fw nav-icon" aria-hidden="true"></i>
                                    <span>{!! trans('messages.document') !!}</span>
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/index#/notice') || Request::is('/settings/index#/create-notice') || Request::is('/settings/index#/edit-notice/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/notice-list') }}">
                                    <i class="fa fa-list-alt fa-fw nav-icon"></i> {!! trans('messages.notice') !!}
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/index#/company-info') || Request::is('/settings/index#/company-info') || Request::is('/settings/index#/create-company') || Request::is('/settings/index#/rejected-company') || Request::is('/settings/index#/company-info-action/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/company-info') }}">
                                    <i class="fa fa-industry nav-icon" aria-hidden="true"></i>  {!! trans('messages.company_info') !!}
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('/settings/index#/pdf-print-requests') || Request::is('/settings/index#/get-pdf-print-requests') || Request::is('/settings/index#/edit-pdf-print-requests/*') || Request::is('/settings/index#/pdf-print-request-verify/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/pdf-print-requests') }}">
                                    <i class="fa fa-file-pdf  nav-icon" aria-hidden="true"></i>  {!! trans('messages.pdf-print-requests') !!}
                                </a>
                            </li>
                            <li class="nav-item @if(Request::is('/settings/index#/email-sms-queue') || Request::is('/settings/index#/email-sms-queue/*')) active @endif">
                                <a class="nav-link" href="{{ url ('/settings/index#/email-sms-queue') }}">
                                    <i class="fa fa-sync  nav-icon" aria-hidden="true"></i>  {!! trans('messages.email_sms_queue') !!}
                                </a>
                            </li>

                            <li class="nav-item @if(Request::is('settings/forcefully-data-update') || Request::is('settings/forcefully-data-update')) active @endif">
                                <a class="nav-link" href="{{ url ('settings/forcefully-data-update') }}">
                                    <i class="fa fa-database nav-icon" aria-hidden="true"></i>
                                    Forcefully data update
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if($type[0] == 1)
                <li class="nav-item @if(Request::is('settings/license-data-upload') || Request::is('settings/license-data-upload')) active @endif">
                    <a class="nav-link" href="/settings/license-data-upload">
                        <i class="fa fa-database nav-icon" aria-hidden="true"></i>
                        License Data Upload
                    </a>
                </li>
                @endif
                @if(in_array($type[0], [1]))
                    {{--                    <li class="@if (Request::is('custom-notification/*')) active @endif">--}}
                    {{--                        <a href="{{ url ('/custom-notification/list') }}">--}}
                    {{--                            <i class="fa fa-users fa-fw"></i> {!!trans('messages.batch_email')!!}--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}

                    <li class="nav-item {{ (Request::is('common/activities/activities-summary') ? 'active' : '') }}">
                        <a class="nav-link" href="{{ url ('/common/activities/activities-summary')}}"><i
                                    class="fa fa-list nav-icon"></i>
                            <p>
                                {!! trans('messages.activities_summary') !!}
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

