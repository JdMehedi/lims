<?php echo
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');?>
    <!DOCTYPE html>
<html lang="en" class="ie8 no-js">
<html lang="en" class="ie9 no-js">
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('PROJECT_NAME') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fav icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/favicon.ico") }}"/>
    <link rel="stylesheet" href="{{ asset('css/front.css') }}" media="all"/>
    {{--    <link rel="stylesheet" href="{{ asset("assets/stylesheets/css/main.min.css") }}" media="all"/>--}}
    {{--    <link rel="stylesheet" href="{{ asset("assets/stylesheets/custom.css") }}" media="all"/>--}}
    <link rel="stylesheet" href="{{ asset("assets/plugins/newsTicker/ticker-style.min.css") }}" media="all"/>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    {{--    <link rel="stylesheet" href="{{ url("assets/stylesheets/css/skins/_all-skins.min.css") }}">--}}
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
    {{--    <link rel="stylesheet" href="{{ asset("assets/stylesheets/css/skins/skin-blue.min.css") }}">--}}
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <style>
        .welcome_label {
            display: block;
            margin-bottom: 0;
            border-radius: 4px 4px 0 0;
        }

        .welcome_label h2 {
            margin: 5px 0;
            white-space: normal;
            font-size: 14px;
        }
        body {
            overflow-x: hidden;
        }
    </style>

    @yield('header-resources')
</head>

<body class="">
{{ csrf_field() }}

@yield('body')

{{--<div id="footer">--}}
{{--    <div class="text-center">--}}
{{--        --}}{{--            Copyright &copy; {{ date('Y') }}.--}}
{{--        Managed by--}}
{{--        <strong><a href="https://batworld.com">Business Automation Ltd. </a></strong>--}}
{{--        in association with--}}
{{--        <strong><a href="https://ocpl.com.bd/">OCPL Team.</a></strong>--}}
{{--        On behalf of--}}
{{--        <strong><a href="{{ url('/') }}">{{ env('PROJECT_NAME') }}</a></strong>--}}
{{--    </div>--}}
{{--</div>--}}

<!--  only for vue js !-->
@if(Request::is('settings/*'))
    <script src="{{ asset('js/front.js') }}"></script>
@else
    <!-- jQuery -->
    <script src="{{ asset("assets/plugins/jquery/jquery.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset('js/front.js') }}"></script>
@endif

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
<!-- if sidebar is fixed then following plugin needed -->
{{--<script src="{{ url("assets/scripts/jquery.slimscroll.min.js") }}"></script>--}}
{{--<script src="{{ url("assets/scripts/fastclick.min.js") }}"></script>--}}

<script src="{{ asset("assets/plugins/newsTicker/jquery.ticker.min.js") }}"></script>
<script>
    // Marquee Ticker
    // $(function () {
    //     var timer = !1;
    //     _Ticker = $(".TickerNews").newsTicker({});
    //     _Ticker.on("mouseenter", function () {
    //         var __self = this;
    //         timer = setTimeout(function () {
    //             __self.pauseTicker();
    //         }, 200);
    //     });
    //     _Ticker.on("mouseleave", function () {
    //         clearTimeout(timer);
    //         if (!timer) return !1;
    //         this.startTicker();
    //     });
    // });
</script>

@yield('footer-script')

<script type="text/javascript">
    $("input[type=text]:not([class*='textOnly'],[class*='email'],[class*='exam'],[class*='number'],[class*='bnEng'],[class*='textOnlyEng'],[class*='datepicker'],[class*='mobile_number_validation'])").addClass('engOnly');

    // Bootstrap Tooltip initialize
    // $('.tooltip-demo').tooltip({
    //     selector: "[data-toggle=tooltip]",
    //     container: "body"
    // });
    // // popover demo
    // $("[data-toggle=popover]")
    //     .popover()
</script>

{{-- Store URL info --}}
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">
    var ip_address = '<?php echo $_SERVER['REMOTE_ADDR'];?>';

    var user_id = '0';
    @if(\Illuminate\Support\Facades\Auth::user())
        user_id = '{{ Auth::user()->id }}';
    @endif

    var message = 'Ok';
    @if(isset($exception))
        message = "Invalid Id! 401";
    @endif

    var project_name = "{{ env('project_code') }}" + "." + "<?php echo env('SERVER_TYPE', 'unknown');?>";

</script>

<?php //if (env('mongo_audit_log')) {
//    require_once(public_path() . "/url_webservice/set-app-data.blade.php");
//} ?>

</body>
</html>
