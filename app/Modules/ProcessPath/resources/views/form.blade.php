
@extends('layouts.admin')
@section('title', $process_info->drop_down_label.' | Bangladesh Telecommunication Regulatory Commission')

@section('header-resources')
    @include('partials.form-add-edit-css', ['viewMode' => $viewMode])
    <link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.css") }}"/>

    @if(env('IS_MOBILE'))
    <link rel="stylesheet" href="{{asset('assets/mobile.css')}}">
    @endif

    <style>
        .msg {
            max-width: 200px;
            margin: 0 auto;
        }

        /*Style for SVG*/
        .node:active {
            fill: #fffa90;
        }

        svg {
            /*border: 1px solid #ccc;*/
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto;
        }

        .node rect {
            stroke: #333;
            fill: #fff;
        }

        .edgePath path {
            stroke: #333;
            fill: #333;
            stroke-width: 1.5px;
        }
    </style>


@endsection

@section('content')

    @include('partials.messages')
    <?php
    $session_get = Session::get('is_batch_update');
    $is_delegation = Session::get('is_delegation');
    $single_process_id_encrypt = Session::get('single_process_id_encrypt');
    $next_app_info = Session::get('next_app_info');
    $total_selected_app = Session::get('total_selected_app');
    $total_process_app = Session::get('total_process_app');
    ?>
    {{-- Process panel --}}
    @if ($viewMode == 'on' && $hasDeskOfficeWisePermission === true)
        <div class="row">
            <div class="col-sm-12">
                @include('ProcessPath::batch-process')
            </div>
        </div>
    @elseif($session_get == 'batch_update')
        @include('ProcessPath::batch-process-skip')
    @endif
    {{-- End Process panel --}}

    <div id="printableArea">
        {{-- Process info e.g: process map, shadow file history --}}
        @if ($viewMode == 'on')
            <div class="card with-nav-tabs card-info">
                <div class="card-header">
                    <ul class="nav nav-tabs nav-item">
                        <li>
                            <a class="nav-link active" data-toggle="tab" role="tab" href="#appStatus"
                                aria-controls="appStatus" aria-expanded="true">
                                <b><i class="fa fa-info-circle"></i> {!! trans('ProcessPath::messages.application_status') !!}</b>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" role="tab" href="#paymentInfo"
                                aria-controls="paymentInfo" aria-expanded="true">
                                <b><i class="fa fa-money"></i> {!! trans('ProcessPath::messages.payment_info') !!}</b>
                            </a>
                        </li>

                        @if (in_array(Auth::user()->user_type, ['1x101', '3x303', '4x404', '13x303']))
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" data-toggle="tab" role="tab" href="#processMap"--}}
{{--                                    aria-controls="processMap" aria-expanded="true">--}}
{{--                                    <b><i class="fa fa-map"></i> {!! trans('ProcessPath::messages.process_map') !!}</b>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" data-toggle="tab" role="tab" href="#shadowFileHistory"--}}
{{--                                    aria-controls="shadowFileHistory" aria-expanded="true">--}}
{{--                                    <b><i class="fa fa-file-zip-o"></i> {!! trans('ProcessPath::messages.shadow_file_history') !!}</b>--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            @if (in_array(Auth::user()->user_type, ['1x101', '3x303', '4x404']))
                                <li class="nav-link" class="nav-item">
                                    <a data-toggle="tab" role="tab" href="#processHistory"
                                        aria-controls="processHistory" aria-expanded="true">
                                        <b><i class="fa fa-history"></i> {!! trans('ProcessPath::messages.process_history') !!}</b>
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="appStatus">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card well-sm bg-deep disabled color-palette no-margin">

                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="clearfix no-margin row">
                                                        <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.tracking_no') !!}. </label>
                                                        <div class="col-md-7 col-xs-7">
                                                            <span>: {{ $process_info->tracking_no }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="clearfix no-margin row">
                                                        <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.date_of_submission') !!} </label>
                                                        <div class="col-md-7 col-xs-7">
                                                            @if ($process_info->submitted_at != '0000-00-00 00:00:00' && $process_info->submitted_at != null)
                                                                <span>:
                                                                    {{ \App\Libraries\CommonFunction::formateDate($process_info->submitted_at) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
{{--                                                    <div class="clearfix no-margin row">--}}
{{--                                                        <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.current_status') !!} </label>--}}
{{--                                                        <div class="col-md-7 col-xs-7">--}}
{{--                                                            <span>--}}
{{--                                                                @if ($process_info->status_id == -1)--}}
{{--                                                                    : Draft--}}
{{--                                                                @else--}}
{{--                                                                    : {!! $process_info->status_name !!}--}}
{{--                                                                @endif--}}
{{--                                                            </span>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

                                                    <div class="clearfix no-margin row">
                                                        <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.current_desk') !!} </label>
                                                        <div class="col-md-7 col-xs-7">
                                                            <span>
                                                                @if ($process_info->desk_id != 0)
                                                                    : {{ $process_info->desk_name }}
                                                                @else
                                                                    : Applicant
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($ref_table_info->license_no))
                                                    <div class="clearfix no-margin row">
                                                        <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.license_number') !!} </label>
                                                        <div class="col-md-7 col-xs-7">
                                                            <span>:
                                                                {{ $ref_table_info->license_no }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($process_info->status_id == 6)
                                                <div class="row">
                                                    <div class="col-sm-6">

                                                        <div class="form-group row no-margin">
                                                            <label class="col-md-5 col-xs-5">{!! trans('ProcessPath::messages.discard_reason') !!}
                                                            </label>
                                                            <div class="col-md-7 col-xs-7">
                                                                <span>
                                                                   : {{ !empty($process_info->process_desc) ? $process_info->process_desc : 'N/A' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="nav ">
                                        @if(!env('IS_MOBILE'))
                                        <li class="nav-item">
                                            <button  class="btn btn-danger btn-sm btn-block" title="Download Approval Copy"
                                                    id="html2pdf"><i class="fa fa-download"></i> Download as PDF</button>
                                        </li>&nbsp;
                                       @endif
{{--                                        @if ($process_info->status_id == 25 && !empty($process_info->certificate_link))--}}
                                        @if (!empty($process_info->certificate_link))
                                            <li class="nav-item">
                                                <a href="{{ $process_info->certificate_link }}"
                                                   title="Certificate" target="_blank">
                                                    <button class="btn btn-info btn-sm btn-block"> <i
                                                            class="fa fa-file-pdf-o"></i>{!! trans('ProcessPath::messages.download_certificate') !!}
                                                    </button>
                                                </a>
                                            </li>&nbsp;

                                            @if (Auth::user()->user_type == '1x101' || in_array(4, explode(',', Auth::user()->desk_id)))
                                                <li class="nav-item">
                                                    <a href="{{ url('process/certificate-regeneration/' . $encoded_app_id . '/' . $encoded_process_type_id) }}"
                                                       title="Certificate regenerate"
                                                       target="_self"><button class="btn bg-maroon btn-sm btn-block"> <i class="fa fa-file-pdf-o" aria-expanded="true"></i> {!! trans('ProcessPath::messages.regenerate_certificate') !!}
                                                        </button>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="paymentInfo">
                            <div class="text-center" id="payment-loading">
                                <br />
                                <br />
                                <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                <br />
                                <br />
                            </div>
                            <div id="payment_content_area"></div>
                        </div>
                        @if (in_array(Auth::user()->user_type, ['1x101', '3x303', '4x404', '13x303']))
                            <div class="tab-pane" id="processMap">
                                <div class="text-center" id="map-loading">
                                    <br />
                                    <br />
                                    <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                    <br />
                                    <br />
                                </div>

                                <h5 id="mapShortfallStatus"></h5>

                                <svg width="100%" height="220">
                                    <g></g>
                                </svg>
                            </div>

                            <div class="tab-pane" id="shadowFileHistory">
                                <div class="overlay" id="shadow-file-loading">
                                    <div class="col-md-12">
                                        <div class="row  d-flex justify-content-center">
                                            <i class="fas fa-3x fa-sync-alt fa-spin text-center"></i>
                                        </div>
                                        <div class="row  d-flex justify-content-center">
                                            <div class="text-bold pt-2 text-center">
                                                Loading...</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="shadow_file_content_area"></div>
                            </div>

                            @if (in_array(Auth::user()->user_type, ['1x101', '3x303', '4x404']))
                                <div class="tab-pane" id="processHistory">
                                    <div class="text-center" id="history-loading">
                                        <br />
                                        <br />
                                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                                        <br />
                                        <br />
                                    </div>
                                    <div id="history_content_area"></div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- End Process info e.g: process map, shadow file history --}}

        <div class="row">
            <div class="col-sm-12">
                <div id="loading">
                    <div class="msg"></div>
                    <script>
                        let form_load_status;
                        const start_time = new Date();
                        let interval;

                        function checkTimeDif() {
                            const now_is_the_time = new Date();
                            return Math.floor((now_is_the_time - start_time) / 1000);
                        }

                        function timeDelay() {
                            // Difference between Start time and now is the time
                            if (checkTimeDif() > 10) {
                                document.getElementsByClassName('msg')[0].innerHTML =
                                    '<div class="alert alert-success"><i class="fa fa-spinner fa-spin"></i> Opening form...</div>';
                            } else if (checkTimeDif() > 6) {
                                document.getElementsByClassName('msg')[0].innerHTML =
                                    '<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> It is almost done...</div>';
                            } else if (checkTimeDif() > 2) {
                                document.getElementsByClassName('msg')[0].innerHTML =
                                    '<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i> Preparing all data...</div>';
                            } else {
                                document.getElementsByClassName('msg')[0].innerHTML =
                                    '<div class="alert alert-danger"><i class="fa fa-spinner fa-spin"></i> Please Wait...</div>';
                            }

                            interval = setTimeout(timeDelay, 1000);
                        }
                        timeDelay()
                    </script>
                </div>
            </div>
        </div>

        @if (count($errors) > 0)
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <div class="row">
                            @foreach ($errors->all() as $error)
                                <li class="col-md-4 col-sm-12">{{ $error }}</li>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <!--   Load full content here  -->
        <div class="row <?php echo env('IS_MOBILE') ? 'btrc-row-nospace' : '' ?>">
            <div class="col-md-12 <?php echo env('IS_MOBILE') ? 'btrc-col-nospace' : '' ?>">
                <div class="load_content"></div>
            </div>
        </div>
        <!--   End Load full content here  -->

    </div>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

@endsection


@section('footer-script')
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ mix('assets/scripts/common_form_script.min.js') }}"></script>

    @include('partials.form-add-edit-js', ['viewMode' => $viewMode])


    <script>
        // This variable will be used for document loading and other tasks
        const encoded_process_type_id = '{{ $encoded_process_type_id }}';
        const encoded_process_list_id = '{{ $encoded_process_list_id }}';
        const encoded_app_id = '{{ isset($encoded_app_id) ? $encoded_app_id : 0 }}';
        const viewMode = '{{ $viewMode }}';
        const openMode = '{{ $openMode }}';

        loadApplicationForm(viewMode, openMode, encoded_process_type_id, '{{ $url }}', encoded_app_id);


        // Loaded Application form
        $(document).ready(function() {
            function activespecifictab(tab) {
                $('.nav-tabs a[href="#' + tab + '"]').tab('show');
            };

            @if ($process_info->status_id == 3)
                activespecifictab('paymentInfo');
                loadPaymentInfo(encoded_process_type_id, encoded_app_id, 'payment_content_area', 'payment-loading');
            @endif

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                const new_tab = e.target.getAttribute('aria-controls') // newly activated tab

                if (new_tab === 'processMap') {
                    sendRequestAndDraw(encoded_process_type_id, encoded_app_id,
                        '{{ isset($cat_id) ? $cat_id : 0 }}');
                } else if (new_tab === 'shadowFileHistory') {
                    loadShadowFileHistory(encoded_process_type_id, encoded_app_id);
                } else if (new_tab === 'processHistory') {
                    loadApplicationHistory(encoded_process_list_id);
                } else if (new_tab === 'paymentInfo') {
                    loadPaymentInfo(encoded_process_type_id, encoded_app_id, 'payment_content_area',
                        'payment-loading');
                }
            });
        });

        $(document).on('click', '#request_shadow_file', function() {
            const btn = $(this);
            const btn_content = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

            $.ajax({
                type: "GET",
                url: "/process-path/request-shadow-file",
                data: {
                    module_name: '{{ $process_info->acl_name }}',
                    ref_id: encoded_app_id,
                    process_id: encoded_process_list_id,
                    process_type_id: encoded_process_type_id
                },
                success: function(response) {
                    if (response.responseCode == 1) {
                        btn.prop('disabled', false);
                        document.location.reload()
                    } else if (response.responseCode == 0) {
                        toastr.error("", response.messages, {
                            timeOut: 6000,
                            extendedTimeOut: 1000,
                            positionClass: "toast-bottom-right"
                        });
                        btn.prop('disabled', false);
                    }
                }
            });
        });

        // Loading the Documents of Application Add, Edit
        function loadApplicationDocs(result_id, doc_type_key = '') {
            $.ajax({
                type: "GET",
                url: '/documents/get-app-docs',
                dataType: "json",
                data: {
                    encoded_process_type_id: encoded_process_type_id,
                    encoded_app_id: encoded_app_id,
                    view_mode: viewMode,
                    openMode: openMode,
                    doc_type_key: doc_type_key,
                },
                success: function(result) {
                    if (result.html != undefined) {
                        $('#' + result_id).html(result.html);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert('Unknown error occurred during attachment loading. Please, try again after reload');
                },
            });
        }
    </script>
    @if ($session_get == 'batch_update')
        <script>
            (function(global) {

                if (typeof(global) === "undefined") {
                    throw new Error("window is undefined");
                }

                var _hash = "!";
                var noBackPlease = function() {
                    global.location.href += "#";

                    // making sure we have the fruit available for juice....
                    // 50 milliseconds for just once do not cost much (^__^)
                    global.setTimeout(function() {
                        global.location.href += "!";
                    }, 50);
                };

                // Earlier we had setInerval here....
                global.onhashchange = function() {
                    if (global.location.hash !== _hash) {
                        global.location.hash = _hash;
                    }
                };

                global.onload = function() {

                    noBackPlease();

                    // disables backspace on page except on input fields and textarea..
                    document.body.onkeydown = function(e) {
                        var elm = e.target.nodeName.toLowerCase();
                        if (e.which === 8 && (elm !== 'input' && elm !== 'textarea')) {
                            e.preventDefault();
                        }
                        // stopping event bubbling up the DOM tree..
                        e.stopPropagation();
                    };

                };

            })(window);
        </script>
    @endif

    @yield('footer-script2')
@endsection
