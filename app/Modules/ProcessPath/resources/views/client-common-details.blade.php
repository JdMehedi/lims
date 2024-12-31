<?php

if (!empty($process_info)) {
    $accessMode = ACL::getAccsessRight($process_info->acl_name);
    if (!ACL::isAllowed($accessMode, '-V-'))
        die('no access right!');
}

$moduleName = Request::segment(1);
$user_type = CommonFunction::getUserType();
$desk_id_array = explode(',', \Session::get('user_desk_ids'));

?>
@extends('layouts.admin')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset('assets/plugins/swiper-bundle.css') }}"/>
    <style>
        input:disabled {
            background: #ccc;
        }
        .disabled_style{

        }
        .coll-section{
            margin: 5px 0px;
            background-color: #f5f5f5;
            border:1px solid #ddd;
            border-radius: 5px;
        }
        .collapsible {
            color: #333333;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            text-align: left;
            outline: none;
            font-size: 20px;
            background-color: #f5f5f5;
            border:1px solid #ddd;

        }

        .active, .collapsible:hover {
            color: #333333;
            background-color: #f5f5f5;
        }

        .content1 {
            padding: 0 18px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
            background-color: #fff;
            border:1px solid #ddd;

        }

        .unreadMessage td {
            font-weight: bold;
        }

        .col-centered {
            float: none;
            margin: 0 auto;
        }
        /* The container */
        .checkbox-inline-custom {
            position: relative;
            padding-left: 28px;
            margin-right: 12px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .checkbox-inline-custom input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkBox {
            position: absolute;
            top: 0;
            left: 0;
            height: 22px;
            width: 22px;
            background-color: #eee;
            border: 1px solid #6ca2a0;
        }

        /* On mouse-over, add a grey background color */
        .checkbox-inline:hover input ~ .checkBox {
            background-color: #ccc;
        }

        .checkbox-inline-custom:hover input ~ .checkBox {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .checkbox-inline input:checked ~ .checkBox {
            background-color: #2196F3;
        }

        .checkbox-inline-custom input:checked ~ .checkBox {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkBox:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .checkbox-inline input:checked ~ .checkBox:after {
            display: block;
        }
        .checkbox-inline-custom input:checked ~ .checkBox:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .checkbox-inline .checkBox:after {
            left: 8px;
            top: 4px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .checkbox-inline-custom .checkBox:after {
            left: 8px;
            top: 4px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }


        .tooltip-inner {
            font-style: italic;
        }

        /* The container */
        .checkbox-inline {
            position: relative;
            padding-left: 30px;
            /*margin-bottom: 8px;*/
            cursor: pointer;
            /*font-size: 15px;*/
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

        }

        /* Hide the browser's default radio button */
        .checkbox-inline input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 23px;
            width: 23px;
            background-color: #eee;
            border-radius: 50%;
            border: 1px solid #6ca2a0;
        }

        /* On mouse-over, add a grey background color */
        .checkbox-inline:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .checkbox-inline input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .checkbox-inline input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .checkbox-inline .checkmark:after {
            top: 7px;
            left: 7px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }
        .panel-heading .accordion-toggle:after {
            /* symbol for "opening" panels */
            font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
            content: "\e114";    /* adjust as needed, taken from bootstrap.css */
            float: right;        /* adjust as needed */
            color: grey;         /* adjust as needed */
        }
        .panel-heading .accordion-toggle.collapsed:after {
            /* symbol for "collapsed" panels */
            content: "\e080";    /* adjust as needed, taken from bootstrap.css */
        }
        .textArea{
            border: none;
            border-bottom: 1px solid #DFDFDF;
        }

        .thumbnail img {
            width: 100%;
        }

        .thumbnail .play_btn {
            background-color: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 66px;
            height: 66px;
            border: 1px solid transparent;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.5s;
        }

        .thumbnail .play_btn:hover {
            border: 1px solid #fff;
            transition: all 0.5s;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .bscic_video, .bscic_video iframe {
            width: 100%;
            height: 350px;
        }

        @media (max-width: 1450px) {
            .checkbox-inline{
                font-size: 14px;
            }
            .checkmark {
                position: absolute;
                top: 0;
                left: 0;
                height: 18px;
                width: 18px;
                background-color: #eee;
                border-radius: 50%;
                border: 1px solid #6ca2a0;
            }
            /* Style the indicator (dot/circle) */
            .checkbox-inline .checkmark:after {
                top: 4px;
                left: 4px;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: white;
            }
        }
        @media (max-width: 1200px) {
            .checkbox-inline{
                font-size: 12px;
            }
            .checkmark {
                position: absolute;
                top: 0;
                left: 0;
                height: 16px;
                width: 16px;
                background-color: #eee;
                border-radius: 50%;
                border: 1px solid #6ca2a0;
            }
            /* Style the indicator (dot/circle) */
            .checkbox-inline .checkmark:after {
                top: 3px;
                left: 3px;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: white;
            }
        }

        @media (min-width: 767px) {
            .tutorial{
                padding-left: 25px;
                margin-top: -35px;
            }
        }

    </style>
    @include('partials.datatable-css')
@endsection

@section('content')

    @include('partials.messages')
    <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="local_machine_modal_head" class="modal-title" style="color: #452A73; font-size: 14px">
                        Youtube Video
                    </h4>
                    <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="bscic_video">
                        <iframe title="video" allow="fullscreen">
                                src="">
                        </iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm close_modal" data-dismiss="modal"
                            style="float: right;">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="card" style="border-radius: 10px;width: 100%">
                <div class="card-header text-center" style="padding: 10px 15px">
                </div>
                <div class="card-body" style="padding-bottom: 50px">
                    {{--<div class="row">--}}
                        {{--<div class="col-md-8 col-md-offset-3">--}}
                            {{--<p style="font-size: 36px; font-weight: 400; margin-bottom: 0;" >{{ $app->group_nm_bn ?? "" }}</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row">
                        {{--<div class="col-md-3">--}}
                            {{--<img src="{{ '/'.@$app->logo }}" alt="" style="margin-left: 15px; width: 180px">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-5">--}}
                            {{--<br>--}}
                            {{--<br>--}}
                            {{--<div class="col-md-12">--}}
                                {{--<p style="color: #452A73; font-size: 14px" class="text-justify">--}}
                                    {{--{{ $app->details ?? "" }}--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-md-2"></div>
                        <div class="col-md-8 text-center" style="border-left: 1px solid #DFDFDF">
                            <p style="font-size: 25px; font-weight: normal;margin: 0px">{!! trans('ProcessPath::messages.industry_reg_application') !!}</p>
                            <p style="font-size: 24px; font-weight: normal">{!! trans('ProcessPath::messages.select_your_service') !!}</p>


                            @foreach($ProcessTypeData as $key=>$item)
                                @if($key == 2)
                                    <br>
                                    <br>
                                @endif
                                <span style="padding: 8px;; border: 1px solid  #452A73;margin-right: 15px; font-size: 20px;">
                                    <label class="checkbox-inline">{{$item->name_bn}}
                                        <?php
                                        $class = 'other';
                                        $exp = explode('-',$item->type_key);
                                        if(end($exp) == 'cancellation'){ // the value is fixed all modules
                                            $class = 'cancellationClass';
                                        }
                                        ?>
                                        <input  type="radio" class="{{$class}}" data-id ='{{App\Libraries\Encryption::encodeId($item->id)}}' name="item" value="{{'/client/process/'.$item->form_url.'/add/'.\App\Libraries\Encryption::encodeId($item->id)}}">
                                         <span class="checkmark"></span>
                                    </label>
                                </span>
                            @endforeach
                            <br>
                            <br>

                            <div id="trackingSelectDiv" class="row hidden">
                                <div class="col-centered col-md-8">
                                    <select class="form-control" name="" id="trackingSelect"></select>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a href="/client/process/list" class="btn btn-default" style="width:80px;  margin-right: 15px">Cancel</a>
                                    <a href="javascript:void(0)" onclick="nextMethod()" class="btn btn-primary" style="width:90px;background: #0084FF">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

{{--        <div class="col-12">--}}
{{--            <div class="card" style="border-radius: 10px; padding-bottom: 25px">--}}
{{--                <div class="card-header text-center" style="padding: 10px 15px">--}}
{{--                </div>--}}

{{--                <div class="card-body" style="padding: 0 45px">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <p style="font-size: 32px; font-weight: 400;" >{!! trans('ProcessPath::messages.details_about_this_service') !!}</p>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-12">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-8">--}}
{{--                                <div class="panel-group" id="accordion">--}}
{{--                                    @foreach($details as $detail)--}}
{{--                                        <div class="coll-section">--}}
{{--                                            <button class="collapsible"> {{ $detail->service_heading }} <span class="float-right"><i class="fa fa-angle-down"></i></span></button>--}}
{{--                                            <div class="content1 text-justify">--}}
{{--                                                <br>--}}
{{--                                                {!! $detail->details !!}--}}
{{--                                                <br>--}}
{{--                                                <br>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-4 tutorial" >--}}
{{--                                <p style="font-size: 18px; color: #452A73;">টিউটোরিয়াল</p>--}}
{{--                                <div class=" embed-responsive-16by9 thumbnail">--}}
{{--                                    <img alt='...' class="img"--}}
{{--                                         src="{{ asset('assets/images/oss.png') }}">--}}
{{--                                    <img alt='...' class="play_btn" src="{{ asset('assets/images/youtube.png') }}" data-toggle="modal"--}}
{{--                                         data-target="#video_modal" style="cursor: pointer">--}}
{{--                                </div>--}}
{{--                                <div class="card" style="border-radius: 10px;">--}}
{{--                                    <div class="card-header" style="background: #F0F1F2">--}}
{{--                                        <p style="font-size: 14px; font-weight: 400; padding: 8px; padding-bottom: 0">ডকুমেন্ট</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-body" style="background: #F8F9FA; padding: 15px">--}}
{{--                                        <p>--}}
{{--                                            কিছু দ্রুত উদাহরণ--}}
{{--                                        </p>--}}
{{--                                        @foreach($docs as $doc)--}}
{{--                                            <a href="{{ '/'.$doc->file_path }}" target="_blank" style="color: #4086F4">--}}
{{--                                                <i class="fa fa-file-text-o"></i> {{ $doc->doc_name }}--}}
{{--                                            </a>--}}

{{--                                            <a href="{{ '/'.$doc->file_path }}" class="pull-right" download>--}}
{{--                                                <i class="fa fa-download"></i>--}}
{{--                                            </a>--}}
{{--                                            <br>--}}
{{--                                            <br>--}}
{{--                                        @endforeach--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/swiper-bundle.js') }}"></script>
    <script>

        function myFuction(){
            var process_type_id = $(".cancellationClass").attr('data-id');
            if(process_type_id != null){
                $.ajax({
                    type: "get",
                    url: "<?php echo url('client/process/check-cancellation'); ?>",
                    // dataType: "json",
                    data: {
                        process_type_id: process_type_id
                    },
                    success: function (response) {
                        if (response.responseCode === 1){
                            $('#trackingSelect').html("<option value=''>বাতিলের জন্য এপ্লিকেশন নির্বাচন করুন</option>");
                            $.each(response.data, function (index, value) {
                                var html = '<option value="'+value.id+'">'+value.tracking_no+' ('+ value.project_nm + ') '+'</option>';

                                $('#trackingSelect').append(html);
                            });
                        } else if(response.responseCode === 0){
                            $('.cancellationClass').attr('disabled', true);
                            $('.cancellationClass').closest('span').css({"border":"none","color":"gray","background-color":"#E2E5E7","pointer-events":"none"});
                            $('.cancellationClass').closest('span').css({"display":"none"});
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('.cancellationClass').attr('disabled', true);
                        $('.cancellationClass').closest('span').css({"border":"none","color":"gray","background-color":"#E2E5E7","pointer-events":"none"});
                        $('.cancellationClass').closest('span').css({"display":"none"});
                    },
                });
            }
        }

        myFuction();

        $('.cancellationClass').change(
            function(){
                if ($(this.checked)) {
                    $('#trackingSelectDiv').removeClass('hidden');
                }
            });
        $('.other').change(
            function(){
                if ($(this.checked)) {
                    $('#trackingSelectDiv').addClass('hidden');
                }
            });


        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight){
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        }
    </script>
<script>

    function nextMethod() {

        var is_checked = false;
        var url = '';
        var checked = '';
        $('input[type=radio]').each(function () {

            if (this.checked) {
                if (this.className === 'cancellationClass') {
                    var selectedId = $('#trackingSelectDiv option:selected').val();
                    if (selectedId === '') {
                       checked = "cancel"
                    }else{
                        is_checked = true;
                        url = $(this).val();
                    }
                }else{
                    is_checked = true;
                    url = $(this).val();
                }

            }
        });
        console.log(url)
        // return false;
        if(is_checked){
            location.replace(url);
        }else if(checked === 'cancel' && is_checked === false){
            toastr.warning('Please select Industry registration tracking number first!');
        }
        else{
            alert("Please select one")
        }

        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 6,
            spaceBetween: 0,
            autoplay: true,
            // init: false,
            pagination: {
                // el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                1024: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
            }
        });

    }


    $(document).ready(function (){
        $("#trackingSelect").change(function (){
            var id = $(this).val();
            $.ajax({
                type: "get",
                url: "/client/process/set-can-app",
                // dataType: "json",
                data: {
                    id: id
                },
                success: function (response) {
                    if (response.responseCode === 1){
                        console.log("session set");
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("something was wrong!");
                },
            });
        })
    })

    // Youtube video on click
    $('#video_modal').on('shown.bs.modal', function (e) {
        if (!$('.bscic_video').hasClass('has_video')){
            $('.bscic_video').addClass('has_video');
            $("#video_modal iframe").attr('src', '{{ $app->tutorial_link ?? "" }}');
        }

    })
    $('#video_modal').on('hidden.bs.modal', function (e) {
        $("#video_modal iframe").attr("src", $("#video_modal iframe").attr("src"));
    })
</script>

@endsection
