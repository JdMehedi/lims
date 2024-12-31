<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bangladesh Telecommunication Regulatory Commission</title>
    <link href="{{asset('assets/images/new_images/favicon.png')}}" type="image/x-icon" rel="icon"/>
    <link href="{{asset('assets/images/new_images/favicon.png')}}" type="image/x-icon" rel="shortcut icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&amp;display=swap' rel='stylesheet'>
    <link rel="stylesheet" href="{{asset('assets/stylesheets/new_css/bootstrap.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('assets/stylesheets/new_css/main.css?v=20221003.1215')}}">
    <link rel="stylesheet" href="{{asset('assets/stylesheets/new_css/responsive.css?v=20221003.1215')}}">
    {{--new added--}}
    <link rel="stylesheet" href="{{asset('assets/stylesheets/new_css/select2.min.css')}}">

    @if(env('IS_MOBILE'))
    <link rel="stylesheet" href="{{asset('assets/mobile.css')}}">
    @endif



    <style>
        html {
            scroll-behavior: smooth;
        }
        .nav-item {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .logo-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            /*column-gap: 5px;*/
        }
        .logo-wrapper .logo {
            width: 22%;
        }
        .logo-wrapper .logo-text {
            width: 60%;
            color: black;
            /*font-weight: bolder;*/
        }
        .logo-wrapper .logo img {
            width: 70px !important;
            object-fit: contain;
        }
        .footer_style {
            background: #03a755;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .footer_text {
            color: white;
            font-size: 0.875rem;
            margin: 0px;
        }
        .url_style {
            text-decoration: underline !important;
        }
        .url_style:hover, .log_url_style:hover {
            color: inherit !important;
        }
        .alert-success {
            color: #fff;
            background-color: #28a745 !important;
            border-color: #23923d !important;
        }
        .alert-danger {
            color: #fff;
            background-color: #a94442 !important;
            border-color: #f2dede !important;
        }
        .alert-dismissible {
            padding-right: 4rem !important;
        }
    </style>

    <style>
        @stack('style')
    </style>
</head>
<body>
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="site-main">
        <header class="main-header">
            <div class="container">
                <div class="header-content">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand log_url_style" href="{{URL::to('/')}}" style="max-width: 380px;">
                            <div class="logo-wrapper">
                                <div class="logo"><img src="{{asset('assets/images/btrc-logo-updated.png')}}" alt="logo"></div>
                                <div class="logo-text">
                                    <p style="padding: 0; margin: 0;">License Issuance & Management System</p>
                                    <p style="padding: 0; margin: 0; font-size: 11.5px;">Bangladesh Telecommunication Regulatory Commission</p>
                                </div>
                            </div>
                        </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#btrcNav" aria-controls="btrcNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="btrcNav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{URL('/')}}">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{URL('/')}}">Services</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{URL('/user-manual')}}">User Manual</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{URL('/')}}#contactUs">Contact</a>
                                </li>
                            </ul>
                            <div class="header-login">
                                <div class="login-btn">
                                    <a href="{{URL::to('/login-with-mobile')}}">Login / Signup</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
        @yield('body')
        <footer>
            <div class="footer_style">
                <p class="footer_text">Managed by <a class="url_style" href="http://btrc.gov.bd/" target="_blank">Bangladesh Telecommunication Regulatory Commission</a></p>
{{--                <p class="footer_text">Managed by <a class="url_style" href="https://ba-systems.com/" target="_blank">Business Automation Limited</a> on behalf of <a class="url_style" href="http://btrc.gov.bd/" target="_blank">Bangladesh Telecommunication Regulatory Commission</a></p>--}}
            </div>
        </footer>
    </div>
</body>

<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/js/custom.js?v=20221003.1215')}}"></script>
{{--new added--}}
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js" integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('footer-script')
<script>
    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && (e.keyCode == "61" || e.keyCode == "107" || e.keyCode == "173" || e.keyCode == "109" || e.keyCode == "187" || e.keyCode == "189")) {
            e.preventDefault();
        }
    }, { passive: false });
    document.addEventListener("wheel", function (e) {
            if (e.ctrlKey) e.preventDefault();
    }, { passive: false });
</script>

</html>
