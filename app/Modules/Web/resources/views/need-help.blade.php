@extends('public_home.front')

@section('header-resources')
    <style type="text/css">

        a {
            text-decoration: none;
            color: #000;
        }

        html {
            scroll-behavior: smooth;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .q-support {
            padding: 20px 20px;
            text-align: left;
            /*height: 500px;*/
            overflow: hidden;
        }

        .item-s {
            float: left;
            width: 100%;
        }

        .q-support p a:hover {
            text-decoration: underline;
            color: #039;
        }

        .q-support h4 {
            color: #0a6829;
            padding-bottom: 3px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e1dede;
            text-shadow: 0px 1px 0px #999;
        }

        .list_style {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .green_color {
            color: #008000;;
        }

        #fb-root > div.fb_dialog.fb_dialog_advanced.fb_customer_chat_bubble_animated_no_badge.fb_customer_chat_bubble_pop_in {
            right: initial !important;
            left: 18pt;
            z-index: 9999999 !important;
        }

        .fb-customerchat.fb_invisible_flow.fb_iframe_widget iframe {
            right: initial !important;
            left: 18pt !important;
        }

        .well {
            overflow: hidden;
            display: block;
            width: 100%;
        }

        .panel {
            background-color: rgba(255,255,255,0);
        }

        .helpdesk-oper-hours a {
            padding-top: 5px;
            display: block;
            text-decoration: underline;
        }

        .contact_panel {
            border-radius: 0;
            margin: 5px;
        }

        .contact_panel .panel-heading {
            border-radius: 0;
            font-size: 20px;
            text-align: center;
            padding: 15px;
        }


        .contact_box_outer {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .contact_box {
            background-color: #fff;
            border: 1px solid #008000;
            min-height: 116px;
            overflow: hidden;
            padding: 10px;
            min-height: 150px !important;
        }

        .contact_box {
            display: flex;
        }

        .contact_image {
            text-align: center;
        }
        .contact_image img {
            width: 120px;
            height: 120px;
        }

        .contact_content {
            display: flex;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            min-height: 84px;
            padding-left: 20px;
        }

        .contact_content_title {
            margin: 0 0 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .contact_content_list {
            font-size: 12px;
        }

        @media screen and (max-width: 1199px) and (min-width: 992px) {

        }

        /* On screens that are 991 or less Extra small devices (phones, 600px and down) Small devices (portrait tablets and large phones, 600px and up) */
        @media screen and (max-width: 991px) {
            .fb_dialog {
                left: 18pt !important;
                z-index: 99999999999 !important;
                right: initial !important;
            }

            .helpdesk-oper-hours a {
                padding-top: 0px;
                padding-bottom: 10px;
                display: block;
            }
        }

        @media screen and (max-width: 768px) {
            .contact_content {
                padding-left: 0px;
            }
        }

    </style>
@endsection

@section ('body')
    <div class="container">
        <div class="singlePageDesign">
            <div class="row">
                <div class="col-md-12">
                            {{--service delivery from home start--}}
                            <div class="panel contact_panel">
                                <div class="panel-heading">
                                     বিনিয়োগকারীদের মসৃণ পরিষেবাগুলি প্রবর্তনের জন্য আমরা <br/> সামাজিক দূরত্ব বজায় রেখে  আরও ভাল পরিষেবা  সরবরাহে জন্য  নিম্নলিখিত পদক্ষেপ নিয়েছি ।
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="contact_box_outer">
                                                <div class="contact_box">
                                                    <div class="col-xs-3">
                                                        <div class="contact_image">
                                                            <img src="{{ url('assets/images/need_help/home.png') }}" alt="Support from home will be ensure">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <div class="contact_content">
                                                            <ul class="list_style">
                                                                <li class="contact_content_list">
                                                                    সেবা গ্রহণ করার সময়
                                                                    {{--                                                                        {!!trans('messages.support-from-home-will-be-ensure')!!}--}}
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    রবিবার থেকে বৃহস্পতিবার: সকাল ৯ টা থেকে সন্ধ্যা ৬ টা পর্যন্ত
                                                                    {{--                                                                        {!! trans('messages.sunday_to_thursday') !!}--}}
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    {!! trans('messages.friday-saturday') !!}
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    {!! trans('messages.all-govt-holiday') !!}
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    <a class="btn btn-info btn-xs" href="#technical_support">{!! trans('messages.more-information') !!}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="contact_box_outer">
                                                <div class="contact_box">
                                                    <div class="col-xs-3">
                                                        <div class="contact_image">
                                                            <a href="#technical_support">
                                                                <img src="{{ url('assets/images/need_help/oss_help_desk.png') }}" alt="Oss Help Desk">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <div class="contact_content">
                                                            <ul class="list_style">
                                                                <li>
                                                                    <strong> {!! trans('messages.oss-help-desk') !!}</strong>
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    কারিগরী সহায়তার জন্য আপনার যে কোন প্রশ্নের উত্তর দিতে আমরা প্রস্তুত আছি। অনুগ্রহ করে সাপোর্ট টিকেট
                                                                    সাবমিট করুন।
                                                                    {{--                                                                        {!! trans('messages.answer-any-questions') !!}--}}
                                                                </li>
                                                                <li class="contact_content_list">
                                                                    <a target="_blank" class="btn btn-info btn-xs" href="http://support.batworld.com">{!! trans('messages.submit-a-ticket') !!}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/call.png') }}" alt="Call Center">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            {!! trans('messages.please-contact-call-center') !!}
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                ০৯৬৩৯৬৫৫৫৬৫
                                                                {{--                                                                        {!! trans('messages.+8809678771353') !!}--}}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/email.png') }}" alt="Need help">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            {!! trans('messages.email-to') !!}
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                onestopservice@bscic.gov.bd
                                                            </li>
                                                            <li class="green_color">
                                                                support@ba-systems.com
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6 d-flex">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">

                                                <a href="https://download.anydesk.com/AnyDesk.exe">
                                                    <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="contact_image">
                                                            <img src="{{ url('assets/images/need_help/anydesk.png') }}" alt="Support from anydesk">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="contact_content">
                                                            <ul class="list_style">
                                                                <li class="contact_content_list">
                                                                    {!! trans('messages.anydesk') !!}
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/complain.png') }}" alt="Support related complaint number">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            {!! trans('messages.support-related-complaint-email') !!}
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                sohana@ba-systems.com
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                    </div>

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-12 text-center">--}}
{{--                                            <div style="padding: 15px 0;">--}}
{{--                                                <label class="radio-inline">{!! trans('messages.is-this-article-helpful') !!} </label>--}}
{{--                                                <label class="radio-inline">--}}
{{--                                                    <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', 4)">--}}
{{--                                                    {!! trans('messages.yes') !!}--}}
{{--                                                </label>--}}
{{--                                                <label class="radio-inline">--}}
{{--                                                    <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', 4)">--}}
{{--                                                    {!! trans('messages.no') !!}--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            {{--service delivery from home end--}}
                            <div class="col-sm-12">

                                <div class="q-support" id="technical_support">
                                   {!! $needHelp->details !!}
                                </div>
                            </div>

                </div>
            </div>
        </div>
    </div>

@endsection

