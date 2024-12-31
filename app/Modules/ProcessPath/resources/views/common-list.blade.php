
<style>
    .nextButtonStyle{
        display: flex;
        justify-content: space-between;
        padding-left: 25px;
        padding-right: 10px;
        margin: 2%;
        background-color: #dcdcdc;
        font-size: 20px;
        height: 50px;
        padding-top: 10px;
    }
    .
</style>
<?php

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
if (!empty($process_info)) {
    $accessMode = ACL::getAccsessRight($process_info->acl_name);
    if (!ACL::isAllowed($accessMode, '-V-')) {
        die('no access right!');
    }
}

$moduleName = Request::segment(1);
$user_type = CommonFunction::getUserType();
$desk_id_array = explode(',', \Session::get('user_desk_ids'));
$delegatedUserDeskOfficeIds = CommonFunction::getDelegatedUserDeskOfficeIds();

?>
@extends('layouts.admin')
@section('title', $process_info->drop_down_label.' | Bangladesh Telecommunication Regulatory Commission')

@section('header-resources')
    <style>
        html {
            scroll-behavior: smooth;
        }
        .disabled {
            pointer-events: none;
            cursor: default;
        }
        .unreadMessage td {
            font-weight: bold;
        }

        #pdf-container {
            /*margin-top: 10px;*/
            height: 52vh;
            width: 100%;
            overflow: hidden;

        }

        .hide-card {
            display: none;
        }

        iframe {
            width: 100%;
            height: 100%;
        }

        .modal-bottom-btn {
            width: 100%;
            height: 50px;
            margin: auto;
            background-color: white;
            border: 1px solid #c7c2c2;
            padding: 0px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 2px;
        }

        #otp_modal {
            background-color: rgba(0, 0, 0, 0.3);
        }
        .application-btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
            margin: 10px 0;
        }
        .application-btn-group button {
            width: 100% !important;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .application-btn-group button b {
            font-size: 1.5rem !important;
        }
        @media (max-width: 1000px) {
            .application-btn-group {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
        }
        @media (max-width: 500px) {
            .application-btn-group {
                grid-template-columns: 1fr;
                gap: 5px;
            }
        }
        @media (max-width: 500px) {
            .dataTables_length {
                display: none;
            }
        }
        .disable-button-opacity{
            opacity: 0.5;
        }
        .search_date {
            background-color: white;
            pointer-events: none;
        }
    </style>
    @include('partials.datatable-css')
    <link rel="stylesheet"
          href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
@endsection

@section('content')
    @include('partials.messages')
    @if (empty($delegated_desk))
        <div class="modal fade" id="ProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="frmAddProject"></div>
            </div>
        </div>
    @endif
    <div class="application-btn-group">
        <?php
            $current_services=[];
            $group_wise_services = \App\Modules\ProcessPath\Models\ProcessType::where('group_name', $process_info->group_name)->orderBy('id','asc')->get();

            $biddingLicense = \App\Modules\Settings\Models\BiddingLicenseConfigure::where('status',1)->get();
            $showModuleId = 0;
//            if(!empty($group_wise_services[0]->group_name)) {
//                $biddingLicense = \App\Modules\Settings\Models\BiddingLicenseConfigure::query()
//                    ->where('module_name', $group_wise_services[0]->group_name)
//                    ->where('status',1)
//                    ->get()
//                    ->filter(function($item) {
//                        if (\Carbon\Carbon::now()->between($item->start_date, $item->end_date)) {
//                            return $item;
//                        }
//                    })
//                    ->pluck('module_name')
//                    ->toArray();
//                if(empty($biddingLicense)) { // hide item when item not found in date range
//                    $showModuleId = $group_wise_services[0]->id;
//                }
//            }

            if(!empty($group_wise_services[0]) && !empty($biddingLicense)){
                foreach ($biddingLicense as $index=>$value){
                    if($group_wise_services[0]->group_name == $value->module_name){
                        if(\Carbon\Carbon::now()->between($value->start_date, $value->end_date)){
                            $showModuleId = $group_wise_services[0]->id;
                        }
                    }
                }
            }

            $index = 0;
            foreach ($group_wise_services as $key => $service) {
            $current_services[$service->id]=$service->drop_down_label;
            $issue_warning_bool = 0;
            $issue_warning_msg = "";
            if($response=CommonFunction::warningProcessType($service->id)){
                if(array_key_exists('html',$response)){
                    $issue_warning_msg = $response['html'];
                    $issue_warning_bool = $response['issue_warning_bool'];
                }
            }

            // $ISSUE_WARNING_PROCESS_TYPE = env('ISSUE_WARNING_PROCESS_TYPE');
            // $ISSUE_WARNING_PROCESS_TYPE_ARRAY = [];
            // if(!empty($ISSUE_WARNING_PROCESS_TYPE)){
            //     $ISSUE_WARNING_PROCESS_TYPE_ARRAY = explode(",", $ISSUE_WARNING_PROCESS_TYPE);
            // }
            // if(in_array($service->id, $ISSUE_WARNING_PROCESS_TYPE_ARRAY)){
            //     if($service->id == 9){
            //         $issue_warning_msg = "Submission of new application for NIX is closed currently.";
            //     }elseif($service->id == 50){
            //         $issue_warning_msg = "Submission of new application for NTTN is closed currently.";
            //     }elseif($service->id == 1){
            //         $issue_warning_msg = "Submission of new application for ISP is closed currently.";
            //     }
            //     $issue_warning_bool = 1;
            // }
            //echo "<a class='btn btn-success' style='margin:5px;color:white;'>" . ucfirst(last(explode('-', $service->type_key))) . "</a>";
        ?>
        @if (ACL::getAccsessRight($service->acl_name, '-A-') )
                <a @if($issue_warning_bool == 0) href="{{ URL::to("client/process/$service->form_url/add/" . \App\Libraries\Encryption::encodeId($service->id)) }}" @endif
                       class="{{($service->license_type == 'bidding_license') && ($index == 0) && ($showModuleId != $service->id) ? 'disabled disable-button-opacity' : ''}}" @if($issue_warning_bool == 0) data-target="#otp_modal" data-toggle="modal" @endif>
                    {!! Form::button('<b>'.ucfirst(last(explode('-', $service->type_key))).'</b>', [
                        'type' => 'button',
                        'class' => 'btn btn-success new_application', 'id' => "setcolor_" . $service->id , 'onclick'=> $issue_warning_bool ? "alert('$issue_warning_msg');return false;" : "openModal('".URL::to("client/process/$service->form_url/add/" . \App\Libraries\Encryption::encodeId($service->id))."')"
                    ]) !!}
                </a>
        @endif

        @if($user_type != '5x505')
            <a href="{{ url($service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($service->id)) }}"
                id = "setcolor_{{ $service->id }}"   class="btn btn-success" style="border-radius: 10px;">
                <b style="font-size: 1.4rem !important;">{{ucfirst(last(explode('-', $service->type_key)))}}</b>
            </a>
        @endif
        <?php
        $index++;
            }
        ?>
    </div>

    {{--<button>Renew</button>--}}
    {{--<button>Amendment</button>--}}
    {{--<button>Cancellation</button>--}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-magenta border border-magenta" style="">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><i class="fa fa-list"></i> <b>{!! trans('ProcessPath::messages.application_list') !!}
                                    <span class="list_name"></span>
                                    @if (isset($process_info->drop_down_label) && $process_info->is_special !=1)
                                        for
                                        <?php
                                            $name = explode(' ', $process_info->drop_down_label);
                                            if(!empty($name)) {
                                                if(count($name) > 2) {
                                                    if($name[0] === 'BPO/'){
                                                        $name = $name[0].' '.'Call Center Registration Certificate';
                                                    }else if($name[0] === 'TVAS'){
                                                        $name = $name[0].' '.'Certificate';
                                                    }
                                                    else{
                                                        $name = $name[0].' '.$name[1];
                                                    }

                                                }
                                            }
                                        ?>
                                        {{ $name ?? $process_info->drop_down_label }}
                                </b>
                                @elseif($process_info->is_special ==1)
                                for Other Licenses
                                </b>
                                @endif
                            </h5>
                        </div>
                        {{--<div class="col-lg-6">--}}
                            {{--@if (!empty($process_info))--}}
                                {{--@if (ACL::getAccsessRight($process_info->acl_name, '-A-'))--}}
                                    {{--<a href="{{ URL::to('client/process/' . $process_info->form_url . '/add/' . \App\Libraries\Encryption::encodeId($process_info->id)) }}"--}}
                                       {{--class="float-right" data-target="#otp_modal" data-toggle="modal">--}}
                                        {{--{!! Form::button('<i class="fa fa-plus"></i> <b>New Application</b>', [--}}
                                            {{--'type' => 'button', 'id' => 'new_application',--}}
                                            {{--'class' => 'btn btn-default'--}}
                                        {{--]) !!}--}}
                                    {{--</a>--}}
                                {{--@endif--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="clearfix">
                        <div class="" id="statuswiseAppsDiv"></div>
                    </div>
                    <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
                        <nav class="navbar navbar-expand-mdjustify-content-center">
                            <ul class="nav nav-tabs">
                                @if ($user_type != '1x101' && $user_type != '10x101' && $user_type != '5x505' && $user_type != '6x606')
                                    <li id="tab1" class="nav-item ">
                                        <a data-toggle="tab" href="#list_desk" class="mydesk nav-link active"
                                           aria-expanded="true">
                                            <b>{!! trans('ProcessPath::messages.my_desk') !!}</b>
                                        </a>
                                    </li>
                                    @if (!empty($delegatedUserDeskOfficeIds))
                                        <li id="tab2" class="nav-item" style="width: 180px;"
                                            title="Delegate tab application is pendding">
                                            <img class="img-responsive float-left"
                                                 src="/assets/images/bell_animated.gif"
                                                 alt="Your Alt Tag is Here"
                                                 title="Delegation tab application is pendding"
                                                 style="width: 40px;">
                                    @else
                                        <li id="tab2" class="">
                                            @endif
                                            <a data-toggle="tab" href="#list_delg_desk" aria-expanded="false"
                                               class=" nav-link delgDesk">
                                                <b>{!! trans('ProcessPath::messages.delegation_desk') !!}</b>
                                            </a>
                                        </li>
                                        @else
                                            <li id="tab1" class="nav-item active">
                                                <a data-toggle="tab" href="#list_desk" class="mydesk nav-link active"
                                                   aria-expanded="true">
                                                    <b>{!! trans('ProcessPath::messages.list') !!}</b>
                                                </a>
                                            </li>
                                        @endif

                                        <li id="tab4" class="nav-item">
                                            <a data-toggle="tab" href="#favoriteList" class="favorite_list nav-link"
                                               aria-expanded="true">
                                                <b>{!! trans('ProcessPath::messages.favourite') !!}</b>
                                            </a>
                                        </li>

                                        <li id="tab3" class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#list_search"
                                               id="search_by_keyword"
                                               aria-expanded="false">
                                                <b>{!! trans('ProcessPath::messages.search') !!}</b>
                                            </a>
                                        </li>
                            </ul>

                            <ul class="navbar-nav ml-auto">
                                <div class="row">
                                    <li class="process_type_tab nav-item" id="processDropdown">
                                        {{--                                        {!! Form::select('ProcessType', ['0' => 'সকল তথ্য'] + $ProcessType, $process_type_id, [--}}
                                        {{--                                            'class' => 'form-control ProcessType',--}}
                                        {{--                                        ]) !!}--}}
                                        {!! Form::select('ProcessType', $current_services, $process_type_id, [
                                            'class' => 'form-control ProcessType',
                                        ]) !!}
                                    </li>
                                </div>
                            </ul>
                        </nav>
                        <div id="reyad" class="tab-content">

                            <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                <table id="table_desk" class="table table-striped table-bordered display"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 15%;">{!! trans('ProcessPath::messages.tracking_no') !!}</th>
                                        <th>{!! trans('ProcessPath::messages.current_desk') !!}</th>
                                        <th>{!! trans('ProcessPath::messages.process_type') !!}</th>
                                        <th style="width: 15%">License Category</th>
                                        <th style="width: 35%">{!! trans('ProcessPath::messages.reference_data') !!}</th>
                                        <th>License No</th>
                                        <th>{!! trans('ProcessPath::messages.status_') !!}</th>
                                        <th>{!! trans('ProcessPath::messages.modified') !!}</th>
                                        <th>{!! trans('ProcessPath::messages.action') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="list_search" class="tab-pane" style="margin-top: 20px">
                                @include('ProcessPath::search')
                            </div>
                            <div id="list_delg_desk" class="tab-pane" style="margin-top: 20px">
                                <div class="table-responsive">
                                    <table id="table_delg_desk" class="table table-striped table-bordered display"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th style="width: 15%;">{!! trans('ProcessPath::messages.tracking_no') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.current_desk') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.process_type') !!}</th>
                                            <th style="width: 15%">License Category</th>
                                            <th style="width: 35%">{!! trans('ProcessPath::messages.reference_data') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.status_') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.modified') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.action') !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="favoriteList" class="tab-pane" style="margin-top: 20px">
                                <div class="table-responsive">
                                    <table id="favorite_list" class="table table-striped table-bordered display"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th style="width: 15%;">{!! trans('ProcessPath::messages.tracking_no') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.current_desk') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.process_type') !!}</th>
                                            <th style="width: 15%">License Category</th>
                                            <th style="width: 35%">{!! trans('ProcessPath::messages.reference_data') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.status_') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.modified') !!}</th>
                                            <th>{!! trans('ProcessPath::messages.action') !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Request::segment(3) != null)
        {{--        <div class="modal fade bd-example-modal-lg" id="otp_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">--}}
        {{--            <div class="modal-dialog modal-lg">--}}
        {{--                <div class="modal-content">--}}
        {{--                    asdfasdfasdf--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}


        <div id="otp_modal" class="modal fade bd-example-modal-lg" role="dialog">
            <div class="modal-dialog modal-lg user-login-modal-container">
                <div class="modal-content user-login-modal-body">
                    <div class="modal-body login-otp user-login-modal-content"  id="outerDiv" style="height:95vh; overflow: auto;">

                        <h5 style="width: 100%; margin: auto; padding: 15px 0px; font-weight: bold; text-align: center; font-size: 1.5rem">{{$guideline_config_text}}</h5>

                        <div id="pdf_canvas" style="width: 100%"></div>
{{--                        <h5 style="width: 100%; margin: auto; padding: 15px 0px; font-weight: bold; font-size: 1.5rem">{{$guideline_config_text}}</h5>--}}

                        {{--                        <button type="button" style="float: right;" class="close" data-dismiss="modal">&times;</button>--}}
                        {{--                        <br><br>--}}
                        {{--                        <span style="font-size: 18px; margin-top: 50px; padding: 15px;">--}}
                        {{--                            Have you read the specific guidelines for this service?--}}
                        {{--                            <a--}}
                        {{--                                href="{{url('/guidelines/'.Encryption::encode($process_info->group_name).'/'.Encryption::encode($process_info->id))}}"--}}
                        {{--                                    target="_blank" style="float: right; font-size: 14px; color:#fff;"--}}
                        {{--                                    class="btn btn-warning btn-sm">--}}
                        {{--                                Guideline--}}
                        {{--                            </a>--}}
                        {{--                        </span>--}}

                        {{--                        <span style="font-size: 18px; margin-top: 50px; padding: 15px;">Have you read the specific guidelines for this service? <a--}}
                        {{--                                    href="{{url('/isp-license-issue/guidelines')}}" target="_blank"--}}
                        {{--                                    style="float: right; font-size: 14px; color:#fff;" class="btn btn-warning btn-sm">Guideline</a></span>--}}

                        {{--                        <div style="margin-top: 20px; margin-left: 20px;">--}}
                        {{--                            <input class="" style="" id="guidelines_yes" name="guidelines" type="radio" value="Yes">--}}
                        {{--                            <label for="guidelines_yes" class="" style="margin-left:10px;">Yes</label>--}}

                        {{--                            <input class="" style="margin-left:10px;" id="guidelines_no" name="guidelines" type="radio"--}}
                        {{--                                   value="No">--}}
                        {{--                            <label for="guidelines_no" class="" style="display: inline; margin-left:10px;">No</label>--}}
                        {{--                        </div>--}}
                        {{--                        <div id="pdf-container">--}}
                        {{--                        </div>--}}

{{--                        <iframe id="guideLineFrame" scroll="no" style="overflow:hidden"--}}
{{--                                src="<?php echo URL::to('/guidelines/' . Encryption::encode($process_info->group_name) . '/' . Encryption::encode($process_info->id)) ?>"></iframe>--}}
{{--                        <div class="row modal-bottom-btn">--}}
{{--                            <div>--}}
{{--                                {{ Form::checkbox('accept_terms', 1,null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'isReadGuideLine']) }}--}}
{{--                                {{ Form::label('isReadGuideLine', 'I have read the guidelines for this License/Certificate.', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <a href="{{ URL::to('client/process/' . $process_info->form_url . '/add/' . \App\Libraries\Encryption::encodeId($process_info->id)) }}"--}}
{{--                                   class="" style="display: none;" id="guidBtn">--}}
{{--                                {!! Form::button('Next',[--}}
{{--                                'type' => 'button',--}}
{{--                                'class' => 'btn btn-success',--}}
{{--                                ]) !!}--}}
{{--                            </div>--}}
                        </div>
{{--                        <div class="card card-magenta border-magenta">--}}
{{--                            <div class="card-body" style="padding: 9px 27px;"></div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection
@section('footer-script')
    @include('partials.datatable-js')
    <script src="{{ asset('assets/scripts/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script language="javascript">

        function openModal(url){
            pdfToHtml(url);
            /* $("#guidBtn").attr('href',url); */
            $('#otp_modal').modal({backdrop: false, keyboard: false});
        }

        $.ajax({
            type: "GET",
            url: "<?php echo URL::to('/guidelines/' . Encryption::encode($process_info->group_name) . '/getpdfpagenumber'); ?>",
            success: function (response) {
                var heightpx = response*1031;
                $('#guideLineFrame').css("height", heightpx+'px');
            }
        });

        // let newApplicationElement = document.getElementById('new_application');
        //
        // if (newApplicationElement) {
        //     newApplicationElement.addEventListener('click', function () {
        //
        //     })
        // }


        $('.mydesk').click(function () {
            $('#processDropdown').show();
        });

        $('.favorite_list').click(function () {
            $('#processDropdown').hide();
        });

        $('.search_by_keyword').click(function () {
            $('#processDropdown').hide();
        });

        $(function () {
            // Global search or dashboard search option
            @if (isset($search_by_keyword) && !empty($search_by_keyword))
            $('#search_by_keyword').trigger('click');
            return false;
                    @endif

            var table = [];

            /**
             * set selected ProcessType in session
             * load data by ProcessType, on change ProcessType select box
             * @type {jQuery}
             */
             $('.ProcessType').change(function () {
                var process_type_id = $(this).val();
                let processTypeValues = @json($current_services);

                var current_id = '#setcolor_';

                // Iterate through the keys and values of processTypeValues
                Object.entries(processTypeValues).forEach(([key, value]) => {
                    if (key == process_type_id) {
                        $(current_id + process_type_id).removeClass("btn-success").addClass("btn-primary");

                    } else {
                        $(current_id + key).removeClass("btn-primary").addClass("btn-success");

                    }
                });

              sessionStorage.setItem("process_type_id", process_type_id);
});
            $('.ProcessType').trigger('change');

            /**
             * table desk script
             * @type {jQuery}
             */
            table_desk = $('#table_desk').DataTable({
                iDisplayLength: '{{ $number_of_rows }}',
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                "bDestroy": true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '{{ route('process.getList', ['-1000', 'my-desk']) }}',
                    method: 'get',
                    data: function (d) {
                        d.process_type_id = parseInt(sessionStorage.getItem("process_type_id"));
                    }
                },
                columns: [{
                    data: 'tracking_no',
                    name: 'tracking_no',
                    orderable: false,
                    searchable: true
                },
                    {
                        data: 'user_desk.desk_name',
                        name: 'user_desk.desk_name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'drop_down_label',
                        name: 'drop_down_label',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'license_json',
                        name: 'license_json',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'json_object',
                        name: 'json_object',
                        orderable: false,
                    },
                    {
                        data: 'license_no',
                        name: 'license_no',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'process_status.status_name',
                        name: 'process_status.status_name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "aaSorting": []
            });

            if(parseInt(sessionStorage.getItem("process_type_id")) == 1 || parseInt(sessionStorage.getItem("process_type_id")) == 2 || parseInt(sessionStorage.getItem("process_type_id")) == 3 || parseInt(sessionStorage.getItem("process_type_id")) == 4|| parseInt(sessionStorage.getItem("process_type_id")) == 13|| parseInt(sessionStorage.getItem("process_type_id")) == 14|| parseInt(sessionStorage.getItem("process_type_id")) == 15|| parseInt(sessionStorage.getItem("process_type_id")) == 16 ||parseInt(sessionStorage.getItem("process_type_id")) == 21|| parseInt(sessionStorage.getItem("process_type_id")) == 22|| parseInt(sessionStorage.getItem("process_type_id")) == 23|| parseInt(sessionStorage.getItem("process_type_id")) == 24){
                table_desk.column(3).visible(true);
            }else{
                table_desk.column(3).visible(false);
            }

            /**
             * on click Delegation Desk load table with delegated application list
             * @type {jQuery}
             */
            var deleg_list_flag = 0;
            $('.delgDesk').click(function () {
                /**
                 * delegated application list table script
                 * @type {jQuery}
                 */
                if (deleg_list_flag == 0) {
                    deleg_list_flag = 1;
                    var table_delg_desk = $('#table_delg_desk').DataTable({
                        iDisplayLength: '{{ $number_of_rows }}',
                        processing: true,
                        serverSide: true,
                        searching: true,
                        responsive: true,
                        ajax: {
                            url: '{{ route('process.getList', ['-1000', 'my-delg-desk']) }}',
                            method: 'get',
                            data: function (d) {
                                d._token = $('input[name="_token"]').val();
                                d.process_type_id = parseInt(sessionStorage.getItem(
                                    "process_type_id"));
                            }
                        },
                        columns: [{
                            data: 'tracking_no',
                            name: 'tracking_no',
                            orderable: false,
                            searchable: true
                        },
                            {
                                data: 'user_desk.desk_name',
                                name: 'user_desk.desk_name',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'process_name',
                                name: 'process_name',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'license_json',
                                name: 'license_json',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'json_object',
                                name: 'json_object',
                                orderable: false,
                            },
                            {
                                data: 'process_status.status_name',
                                name: 'process_status.status_name',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        "aaSorting": []
                    });
                    if(parseInt(sessionStorage.getItem("process_type_id")) == 1){
                        //table_delg_desk.column(3).visible(true);
                    }else{
                        //table_delg_desk.column(3).visible(false);
                    }
                }

            });


            /**
             * on click favourite Desk load table with favourite application list
             * @type {jQuery}
             */
            var fav_list_flag = 0;
            $('.favorite_list').click(function () {
                /**
                 * delegated application list table script
                 * @type {jQuery}
                 */
                if (fav_list_flag == 0) {
                    fav_list_flag = 1;
                    var favorite_list = $('#favorite_list').DataTable({
                        iDisplayLength: '{{ $number_of_rows }}',
                        processing: true,
                        serverSide: true,
                        searching: true,
                        responsive: true,
                        ajax: {
                            url: '{{ route('process.getList', ['-1000', 'favorite_list']) }}',
                            method: 'get',
                            data: function (d) {
                                d._token = $('input[name="_token"]').val();
                                d.process_type_id = parseInt(sessionStorage.getItem(
                                    "process_type_id"));
                            }
                        },
                        columns: [{
                            data: 'tracking_no',
                            name: 'tracking_no',
                            orderable: false,
                            searchable: true
                        },
                            {
                                data: 'user_desk.desk_name',
                                name: 'user_desk.desk_name',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'process_name',
                                name: 'process_name',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'license_json',
                                name: 'license_json',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'json_object',
                                name: 'json_object',
                                orderable: false,
                            },
                            {
                                data: 'process_status.status_name',
                                name: 'process_status.status_name',
                                orderable: false,
                                searchable: true
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        "aaSorting": []
                    });
                    if(parseInt(sessionStorage.getItem("process_type_id")) == 1){
                        //favorite_list.column(3).visible(true);
                    }else{
                        //favorite_list.column(3).visible(false);
                    }
                }
            });
        });

        $('body').on('click', '.favorite_process', function () {

            var process_list_id = $(this).attr('id');
            $(this).css({
                "color": "#f0ad4e"
            }).removeClass('fa-star-o favorite_process').addClass('fa fa-star remove_favorite_process');
            $(this).attr("title", "Added to your favorite list");
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo url('/process/favorite-data-store'); ?>",
                data: {
                    _token: _token,
                    process_list_id: process_list_id
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                    }
                }
            });
        });

        $('body').on('click', '.remove_favorite_process', function () {

            var process_list_id = $(this).attr('id');
            $(this).css({
                "color": ""
            }).removeClass('fa fa-star remove_favorite_process').addClass('fa fa-star-o favorite_process');
            $(this).attr("title", "Add to your favorite list");


            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo url('/process/favorite-data-remove'); ?>",
                data: {
                    _token: _token,
                    process_list_id: process_list_id
                },
                success: function (response) {
                    btn.html(btn_content);
                    if (response.responseCode == 1) {
                    }
                }
            });
        });

        @if (\App\Libraries\CommonFunction::getUserType() == '4x404')
        //current used the code for update batch
        $('body').on('click', '.is_delegation', function () {
            var is_blank_page = $(this).attr('target');
            var _token = $('input[name="_token"]').val();
            var current_process_id = $(this).parent().parent().find('.batchInputStatus').val();

            $.ajax({
                type: "get",
                url: "<?php echo url('/'); ?>/process/batch-process-set",
                async: false,
                data: {
                    _token: _token,
                    is_delegation: true,
                    current_process_id: current_process_id,
                },
                success: function (response) {

                    if (response.responseType == 'single') {
                        // window.location.href = response.url;
                        if (is_blank_page === undefined) {
                            window.location.href = response.url;
                        }
                        window.open(response.url, '_blank');
                    }
                    if (response.responseType == false) {
                        toastr.error('did not found any data for search list!');
                    }
                }

            });
            return false;
        });

        $('body').on('click', '.common_batch_update', function () {
            var current_process_id = $(this).parent().find('.batchInput').val();

            process_id_array = [];
            $('.batchInput').each(function (i, obj) {
                process_id_array.push(this.value);
            });
            process_id_array = process_id_array.filter(onlyUnique);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "get",
                url: "<?php echo url('/'); ?>/process/batch-process-set",
                async: false,
                data: {
                    _token: _token,
                    process_id_array: process_id_array,
                    current_process_id: current_process_id,
                },
                success: function (response) {
                    if (response.responseType == 'single') {
                        // return false
                        window.location.href = response.url;
                    }
                    if (response.responseType == false) {
                        toastr.error('did not found any data for search list!');
                    }
                }

            });
            return false;
        });

        function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
        }

        @endif
        @if ($user_type != '5x505')
        $('body').on('change', '.ProcessType', function () {
            var current_process_id = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "post",
                url: "<?php echo url('/'); ?>/process/get-servicewise-count",
                async: false,
                data: {
                    _token: _token,
                    current_process_id: current_process_id,
                },
                success: function (response) {
                    if (response) {
                        $("#statuswiseAppsDiv").html(response).show();
                    }
                }

            });

        });
        @endif
    </script>
    <?php
    if(Request::segment(3) != null){
    ?>
    <script>
        $('body').on('change', '#guidelines_yes', function () {
            document.getElementById("guidBtn").style.display = "inline";
            document.getElementById('pdf-container').style.visibility = "hidden";
            document.getElementById('pdf-container').style.height = "0";
            document.getElementById('pdf-container-footer').style.visibility = "hidden";
            document.getElementById('pdf-container-footer').style.height = "0";
            document.getElementById('pdf-container-footer').style.marginTop = "0";
        });

        $('body').on('change', '#guidelines_no', function () {
            document.getElementById('pdf-container').style.visibility = "visible";
            document.getElementById('pdf-container').style.height = "52vh";
            document.getElementById('pdf-container-footer').style.visibility = "visible";
            document.getElementById('pdf-container-footer').style.height = "42px";
            document.getElementById('pdf-container-footer').style.marginTop = "15px";
            document.getElementById('pdf-container-footer').style.marginBottom = "15px";
            document.getElementById("guidBtn").style.display = "none";
            document.getElementById("isReadGuideLine").checked = false;
            {{--popupWindow = window.open('<?php echo URL::to('/guidelines/' . Encryption::encode($process_info->group_name) . '/' . Encryption::encode($process_info->id)) ?>', 'Sample', '');--}}
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/guidelines'); ?>', 'Sample', '');--}}
        });


    </script>
    <?php
    }
    ?>
    @yield('footer-script2')
    {{--    <script>--}}
    {{--        function setIframeHeight() {--}}
    {{--            console.log('hello');--}}
    {{--            const frame = document.getElementById('guideLineFrame');--}}
    {{--            console.log('fram', frame.contentWindow.document.body);--}}
    {{--            frame.style.height = 48450 + 'px';--}}
    {{--        }--}}
    {{--    </script>--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js" integrity="sha512-BagCUdQjQ2Ncd42n5GGuXQn1qwkHL2jCSkxN5+ot9076d5wAI8bcciSooQaI3OG3YLj6L97dKAFaRvhSXVO0/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        @php
            $processName = DB::table('application_guideline')->where([
                                                            'group_nm' => $service->group_name
                                                                ])->value('pdf_file');
        @endphp
        function pdfToHtml(url) {
            const pdf = "{{env('PROJECT_ROOT').'/'.$processName}}";
            const pdfModal = document.getElementById('pdf_canvas');
            pdfModal.innerHTML = '<p style="text-align: center;">Loading......</p>';
            let loadingTask = pdfjsLib.getDocument(pdf);
            loadingTask.promise.then(function(pdf) {
                pdfModal.innerHTML = '';
                for (let index = 1; index <= pdf._pdfInfo.numPages; index++) {
                    pdf.getPage(index).then(function(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport({ scale: scale, });

                        var outputScale = window.devicePixelRatio || 1;
                        let canvasElement = document.createElement('canvas');
                        canvasElement.id = 'canvas_'+index;
                        pdfModal.appendChild(canvasElement);
                        var canvas = document.getElementById('canvas_'+index);
                        var context = canvas.getContext('2d');
                        canvas.width = Math.floor(viewport.width * outputScale);
                        canvas.height = Math.floor(viewport.height * outputScale);
                        /* canvas.style.width = Math.floor(viewport.width) + "px"; */
                        /* canvas.style.height =  Math.floor(viewport.height) + "px"; */
                        canvas.style.width = "100%";
                        /* canvas.style.height =  "100%"; */
                        var transform = outputScale !== 1
                            ? [outputScale, 0, 0, outputScale, 0, 0]
                            : null;

                        var renderContext = {
                            canvasContext: context,
                            transform: transform,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }

                let btnElement = document.createElement('div');
                btnElement.id = 'nextButton';
                btnElement.style.textAlign = 'center';
                btnElement.classList.add('nextButtonStyle');
                btnElement.innerHTML = `
                                            <div>
                                                {{ Form::checkbox('accept_terms', 1,null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'isReadGuideLine']) }}
                                                {{ Form::label('isReadGuideLine', 'I have read the guidelines for this License/Certificate.', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                            </div>
                                            <div>
                                                <a href='${url}'
                                                class="" style="display: none;" id="guidBtn">
                                                {!! Form::button('Next',[
                                                'type' => 'button',
                                                'class' => 'btn btn-success',
                                                ]) !!}
                                            </div>
                `;
                let outerDiv = document.getElementById('outerDiv')
                outerDiv.appendChild(btnElement)

                let checkbox = document.querySelector("#isReadGuideLine");
                checkbox.addEventListener('change', function () {
                    document.getElementById("guidBtn").style.display = this.checked ? "inline" : "none";
                });
            });
        }
    </script>
@endsection
