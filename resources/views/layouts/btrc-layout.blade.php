<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Bangladesh telecommunication regulatory commission</title>
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
</head>

<body>
@yield('inner_content')
</body>

<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/js/custom.js?v=20221003.1215')}}"></script>
{{--new added--}}
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js" integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('footer_script')
</html>
