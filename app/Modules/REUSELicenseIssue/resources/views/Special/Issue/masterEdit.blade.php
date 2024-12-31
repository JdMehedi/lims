<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .card-header::after {
        display: none;
    }

    .d\:flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    .mr-5 {
        margin-right: 5px;
    }

    .wizard {
        overflow: visible;
    }

    .wizard > .content {
        overflow: visible;
    }

    .wizard > .actions {
        width: 70% !important;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }

    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }

    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }

    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    /*.wizard > .actions {*/
    /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

    /*form label {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*.table>thead:first-child>tr:first-child>th {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*td {*/
    /*    font-size: 14px;*/
    /*}*/

    .signature-upload-input {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
        background-color: blue;
    }

    .sign_div {
        position: relative;
    }

    .signature-upload {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        padding: 5px 10px;
        font-size: 1rem;
        text-align: center;
        color: #000;
        background-color: #F5FAFE;
        border-radius: 0 !important;
        border: 0;
        height: 160px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }

    .custom_error {
        outline: 1px dashed red;
    }

    .email {
        font-family: Arial !important;
    }

    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
    }

    .verticalAlignMiddle {
        vertical-align: middle !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard > .actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard > .content > .body label {
            margin-top: .5em;
            margin-bottom: 0;
        }

        .tabInput {
            width: 120px;
        }

        .tabInput_sm {
            width: 75px;
        }

        .tabInputDate {
            width: 150px;
        }

        .table_responsive {
            overflow-x: auto;
        }

        /*.bootstrap-datetimepicker-widget {*/
        /*    position: relative !important;*/
        /*    top: 0 !important;*/
        /*}*/
        .prevMob {
            margin-top: 45px;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

    .wizard > .steps > ul > li {
        width: 33.2% !important;
    }

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    /*.wizard > .content > .body ul > li {*/
    /*    display: list-item !important;*/
    /*}*/

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px;">
            <div class="card-header d:flex justify-between">
                <h4>Application For New Internet Service Provider (ISP) License Issue</h4>
                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye" style="margin-right: 5px;"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                {!! Form::open([
                    'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                    'method' => 'post',
                    'class' => 'form-horizontal',
                    'id' => 'application_form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                ]) !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">
                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}

                </div>
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Contact Person Profile --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Types of ISP License Applied for--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Types Of ISP License Applied for
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('type_of_isp_licensese', 'Types Of ISP License', ['class' => 'col-md-4']) !!}
                                        <div
                                            class="col-md-8 {{ $errors->has('type_of_isp_licensese') ? 'has-error' : '' }}">
                                            {!! Form::select('type_of_isp_licensese', [''=>'Select',1=>'Nationwide',2=>'Divisional',3=>'District', 4=>'Thana/Upazila'], $appInfo->isp_license_type, ['class' => 'form-control', 'id' => 'type_of_isp_licensese']) !!}
                                            {!! $errors->first('type_of_isp_licensese', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="division" style="display: none;">
                                    <div class="form-group row">
                                        {!! Form::label('isp_licensese_area_division', 'Division', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_division', $divisions, $appInfo->isp_license_division, ['class' => 'form-control isp_licensese_area_division', 'id' => 'isp_licensese_area_division','data-type' => '2','onchange' => "getDistrictByDivisionId('isp_licensese_area_division', this.value, 'isp_licensese_area_district',0)"]) !!}
                                            {!! $errors->first('isp_licensese_area_division', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="district" style="display: none;">
                                    <div class="form-group row">
                                        {!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}
                                        <div
                                            class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_district', $districts, $appInfo->isp_license_district, ['class' => 'form-control isp_licensese_area_district', 'data-type' => '3', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}
                                            {!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row" id="thana" style="display: none;">
                                        {!! Form::label('isp_licensese_area_thana', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                        <div
                                            class="col-md-8 {{ $errors->has('isp_licensese_area_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_thana',[''=>'Select'],$appInfo->isp_license_upazila, ['class' => 'form-control isp_licensese_area_thana', 'data-type' => '4', 'id' => 'isp_licensese_area_thana']) !!}
                                            {!! $errors->first('isp_licensese_area_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'edit','selected' => 1])

                    {{-- Service Profile (If Applicable) --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Service Profile (If Applicable)--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--                            <br>--}}

{{--                            --}}{{--  Location of Installation --}}
{{--                            <div class="card card-magenta border border-magenta">--}}
{{--                                <div class="card-header">--}}
{{--                                    Location of Installation--}}
{{--                                </div>--}}
{{--                                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('location_of_ins_district', 'District', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('location_of_ins_district') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('location_of_ins_district', $districts, $appInfo->location_of_ins_district, ['class' => 'form-control', 'id' => 'location_of_ins_district', 'onchange' => "getThanaByDistrictId('location_of_ins_district', this.value, 'location_of_ins_thana',0)"]) !!}--}}
{{--                                                    {!! $errors->first('location_of_ins_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('location_of_ins_thana', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('location_of_ins_thana') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('location_of_ins_thana', [],$appInfo->location_of_ins_thana, ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'location_of_ins_thana']) !!}--}}
{{--                                                    {!! $errors->first('location_of_ins_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('location_of_ins_address', 'Address Line 1', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('location_of_ins_address') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::text('location_of_ins_address',$appInfo->location_of_ins_address, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 1', 'id' => 'location_of_ins_address']) !!}--}}
{{--                                                    {!! $errors->first('location_of_ins_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('location_of_ins_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                        class="col-md-8 {{ $errors->has('location_of_ins_address2') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::text('location_of_ins_address2',$appInfo->location_of_ins_address2, ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'location_of_ins_address2']) !!}--}}
{{--                                                    {!! $errors->first('location_of_ins_address2', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- Number of Clients/Users of Internet --}}
{{--                            <div class="card card-magenta border border-magenta">--}}
{{--                                <div class="card-header">--}}
{{--                                    Number Of Clients/ Users Of Internet (If Applicable)--}}
{{--                                </div>--}}
{{--                                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('home', 'Home', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div class="col-md-8 {{ $errors->has('home') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('home', $appInfo->home, ['class' => 'form-control', 'placeholder' => 'Enter Home', 'id' => 'home']) !!}--}}
{{--                                                    {!! $errors->first('home', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('cyber_cafe', 'Cyber Cafe', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('cyber_cafe') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('cyber_cafe',$appInfo->cyber_cafe, ['class' => 'form-control', 'placeholder' => 'Enter Cyber Cafe', 'id' => 'cyber_cafe']) !!}--}}
{{--                                                    {!! $errors->first('cyber_cafe', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('office', 'Office', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div class="col-md-8 {{ $errors->has('office') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('office', $appInfo->office, ['class' => 'form-control', 'placeholder' => 'Enter Office', 'id' => 'office']) !!}--}}
{{--                                                    {!! $errors->first('office', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('others', 'Others', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div class="col-md-8 {{ $errors->has('others') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('others', $appInfo->others, ['class' => 'form-control', 'placeholder' => 'Enter Cyber Cafe', 'id' => 'others']) !!}--}}
{{--                                                    {!! $errors->first('others', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- Number of Clients/Users of Internet --}}
{{--                            <div class="card card-magenta border border-magenta">--}}
{{--                                <div class="card-header">--}}
{{--                                    Numbers And List Of Clients/ Users Of Domestic  Point To Point Data Connectivity--}}
{{--                                </div>--}}
{{--                                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('corporate_user', 'Corporate user', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('corporate_user') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('corporate_user', $appInfo->corporate_user, ['class' => 'form-control', 'placeholder' => 'Enter Corporate user', 'id' => 'corporate_user']) !!}--}}
{{--                                                    {!! $errors->first('corporate_user', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('personal_user', 'Personal User', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('personal_user') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('personal_user', $appInfo->personal_user, ['class' => 'form-control', 'placeholder' => 'Enter Personal User', 'id' => 'personal_user']) !!}--}}
{{--                                                    {!! $errors->first('personal_user', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('branch_user', 'Branch User', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('branch_user') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::number('branch_user', $appInfo->branch_user, ['class' => 'form-control', 'placeholder' => 'Enter Branch User', 'id' => 'branch_user']) !!}--}}
{{--                                                    {!! $errors->first('branch_user', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('list_of_clients', 'List of Clients/ Users', ['class' => 'col-md-4 ']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('list_of_clients') ? 'has-error' : '' }}">--}}
{{--                                                    @php $listOfClientRequiredClass  =  !empty($appInfo->list_of_clients)? 'declarationFile':''  @endphp--}}
{{--                                                    {{ Form::file('list_of_clients',['class'=>"form-control input $listOfClientRequiredClass",'id'=>'list_of_clients', 'onchange'=>"createObjUrl(event,'list_of_clients_images_base64')"])}}--}}
{{--                                                    {!! $errors->first('list_of_clients', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    @isset($appInfo->list_of_clients)--}}
{{--                                                        <a href="{{ asset($appInfo->list_of_clients)}}"--}}
{{--                                                           id="list_of_clients_preview" target="_blank"--}}
{{--                                                           class="btn btn-sm btn-danger float-right  "--}}
{{--                                                           style="margin-top: 5px">Open</a>--}}
{{--                                                    @endisset--}}
{{--                                                    <input  type="hidden" name="list_of_clients_base64" id="list_of_clients_images_base64" value="" >--}}
{{--                                                    <div  id="list_of_clients_images_preview"></div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Equipment List --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Equipment List
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment_tbl">
                                <thead>
                                <tr class="text-center">
                                    <th width="7%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="25%" class="verticalAlignMiddle">Equipment Name</th>
                                    <th width="20%" class="verticalAlignMiddle">Brand & Model</th>
                                    <th width="20%" class="verticalAlignMiddle">Quantity</th>
                                    <th width="20%" class="verticalAlignMiddle">Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($isp_equipment_list) && count($isp_equipment_list) > 0)
                                    @foreach($isp_equipment_list as $index=>$value)
                                        <tr row_id="equipment_{{$index+1}}">
                                            <td class="d-flex justify-content-center">{{$index+1}}</td>
                                            <td><input type="text" value="{{ $value->name }}"
                                                       style="text-align: center;"
                                                       class="form-control equipment_name"
                                                       name="equipment_name[{{$index}}]"
                                                       placeholder="Enter equipment name"></td>
                                            <td><input type="text"  value="{{ $value->brand_model }}"
                                                       style="text-align: center;"
                                                       class="form-control equipment_brand_model"
                                                       name="equipment_brand_model[{{$index}}]"
                                                       placeholder="Enter brand & model"></td>
                                            <td><input type="number" value="{{ $value->quantity }}"
                                                       style="text-align: center;"
                                                       class="form-control equipment_quantity"
                                                       name="equipment_quantity[{{$index}}]"
                                                       placeholder="Enter quantity"></td>
                                            <td><input type="text" value="{{ $value->remarks }}"
                                                       style="text-align: center;"
                                                       class="form-control equipment_remarks"
                                                       name="equipment_remarks[{{$index}}]" placeholder="Enter remarks">
                                            </td>
                                            <td class="d-flex justify-content-center">
                                                @if($index == 0)
                                                    <button
                                                        class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                                @else
                                                    <button
                                                        class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                        type="button"><i class="fa fa-minus"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Proposed Tariff Chart --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Proposed Tariff Chart
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="tariffChart_tbl">
                                <thead>
                                <tr class="text-center">
                                    <th width="3%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="23%" class="verticalAlignMiddle">Packages Name/Plan</th>
                                    <th width="22%" class="verticalAlignMiddle">Internet Bandwidth Package <br> Speed
                                        (Kbps/Mbps)
                                    </th>
                                    <th width="14%" class="verticalAlignMiddle">Price(BDT)</th>
                                    <th width="15%" class="verticalAlignMiddle">Duration(in Days)</th>
                                    <th width="15%" class="verticalAlignMiddle">Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($isp_tariff_chart_list) && count($isp_tariff_chart_list) > 0)
                                    @foreach($isp_tariff_chart_list as $index=>$value)
                                        <tr row_id="tariffChart_{{$index+1}}">
                                            <td class="d-flex justify-content-center">{{$index+1}}</td>
                                            <td><input type="text" value="{{ $value->package_name_plan }}"
                                                       style="text-align: center;"
                                                       class="form-control tariffChart_package_name_plan"
                                                       name="tariffChart_package_name_plan[{{$index}}]"
                                                       placeholder="Enter packages name/ plan"></td>
                                            <td><input type="number" value="{{ $value->bandwidth_package }}"
                                                       style="text-align: center;"
                                                       class="form-control tariffChart_bandwidth_package"
                                                       name="tariffChart_bandwidth_package[{{$index}}]"
                                                       placeholder="Enter Speed (Kbps/Mbps)"></td>
                                            <td><input type="number" value="{{ $value->price }}"
                                                       style="text-align: center;"
                                                       class="form-control tariffChart_price"
                                                       name="tariffChart_price[{{$index}}]"
                                                       placeholder="Enter price(BDT)"></td>
                                            <td><input type="text" value="{{ $value->duration }}"
                                                       style="text-align: center;"
                                                       class="form-control tariffChart_duration"
                                                       name="tariffChart_duration[{{$index}}]"
                                                       placeholder="Enter duration"></td>
                                            <td><input type="text" value="{{ $value->remarks }}"
                                                       style="text-align: center;"
                                                       class="form-control tariffChart_remarks"
                                                       name="tariffChart_remarks[{{$index}}]"
                                                       placeholder="Enter remarks"></td>
                                            <td class="d-flex justify-content-center">
                                                @if($index == 0)
                                                    <button
                                                        class="btn btn-success rounded-circle btn-sm text-white add_row"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                                @else
                                                    <button
                                                        class="btn btn-danger rounded-circle btn-sm text-white remove_row"
                                                        type="button"><i class="fa fa-minus"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Necessary attachment --}}
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'edit'])

                    {{-- Declaration --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            Has any Application for License of ISP been rejected before?
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input', 'id'=>'if_declaration_q1_yes', 'style'=>($appInfo->declaration_q1 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                            </div>
                                        </li>
                                        <li>
                                            Has any License of ISP issued previously to the Applicant/ any Share
                                            Holder/ Partner been cancelled?
                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>($appInfo->declaration_q2 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                            </div>
                                        </li>
                                        <li>
                                            Do the Applicant/ any Share Holder/ Partner hold any other Operator Licenses
                                            from the Commission?
                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                <input type="file"
                                                       value="{{ '/uploads/' . $appInfo->declaration_q3_doc }}"
                                                       class="form-control input-sm declarationFile"
                                                       name="declaration_q3_images" id="if_declaration_q3_yes"
                                                       style="border: none;{{($appInfo->declaration_q3 == 'Yes') ? 'display:block;' :'display:none;'.' margin-bottom: 20px;'}}"
                                                       size="300x300"
                                                       onchange="generateBase64String('if_declaration_q3_yes','declaration_q3_images_base64')"/>
                                                <input type="hidden" name="declaration_q3_images_base64"
                                                       id="declaration_q3_images_base64"
                                                       value="{{ !empty($appInfo->declaration_q3_doc)? asset($appInfo->declaration_q3_doc) : '' }}">

                                                @isset($appInfo->declaration_q3_doc)
                                                    <a href="{{ asset($appInfo->declaration_q3_doc)}}"
                                                       id="declaration_q3_images_preview" target="_blank"
                                                       class="btn btn-sm btn-danger" style="margin-top: 5px">Open</a>
                                                @endisset
                                            </div>
                                        </li>
                                        <li><span class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span>
                                            hereby certify that <span class="i_we_dynamic">I/We</span> have carefully
                                            read the guidelines/ terms and conditions, for the license and <span
                                                class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                            conditions therein.
                                        </li>
                                        <li style="margin-top: 20px;"><span
                                                class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span>
                                            hereby certify that <span class="i_we_dynamic">I/We</span> have carefully
                                            read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and
                                            <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining
                                            the license.
                                        </li>
                                        <li style="margin-top: 20px;"><span
                                                class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span>
                                            understand that any information furnished in this application are found fake
                                            or false or this application form is not duly filled up, the Commission, at
                                            any time without any reason whatsoever, may reject the whole application.
                                        </li>
                                        <li style="margin-top: 20px;"><span
                                                class="i_we_dynamic">{{ $appInfo->org_type == 2 ? 'I' : 'We' }}</span>
                                            understand that if at any time any information furnished for obtaining the
                                            license is found incorrect then the license if granted on the basis of such
                                            application shall deemed to be cancelled and shall be liable for action as
                                            per Bangladesh Telecommunication Regulation Act, 2001.
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Note --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Note <span style="float: right;"></span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol style="list-style-type:disc;">
                                        <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/ fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                                        <li>Application without the submission of complete documents and information will not be accepted.</li>
                                        <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                                        <li>Fees and charges are not refundable.</li>
                                        <li>The Commission is entitled to change this from time to time if necessary.</li>
                                        <li>Updated documents shall be submitted during application.</li>
                                        <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
{{--                                        <li>For New Applicant only A, B and E will be applicable.</li>--}}
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Payment & Submit</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Service Fee Payment --}}
                    @if($appInfo->status_id != 5)
                        <div id="payment_panel"></div>
                    @elseif(($appInfo->status_id == 5) && (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0))
                        <div id="payment_panel"></div>
                    @endif

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="float-left">
                    <a href="{{ url('/isp-license-issue/list/'. Encryption::encodeId(1)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md" value="submit" name="actionBtn"
                                onclick="openPreviewV2()">Submit
                        </button>
                    @endif
                    @if($appInfo->status_id == 5)
                        <button type="submit" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md"
                                value="Re-submit" name="actionBtn">Re-submit
                        </button>
                    @endif
                </div>

                @if(!in_array($appInfo->status_id,[5]))
                    <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                            name="actionBtn" id="save_as_draft">Save as Draft
                    </button>
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close"
                                                                                 data-dismiss="modal"
                                                                                 style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <div class="row">
                            <div class="col-md-12">
                                <ol style="list-style-type:disc;">
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the
                                        expiration of duration of its license or else the license shall be cancelled as
                                        per law and penal action shall follow, if the licensee continues its business
                                        thereafter without valid license. The late fees/fines shall be recoverable under
                                        the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to
                                        submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will
                                        not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh
                                        Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>
@include('partials.image-upload')

<script>
    let isAppLimitExceeded = 0;
    $(document).ready(function () {
        getHelpText();

        if(!"{{ config('app.app_submit_by_isp_nationwide')  }}"){
            document.getElementById('type_of_isp_licensese').children[1].disabled = true;
        }

        // Registered Office Address
        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset
        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset

        // Applicant Profile
        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
        @endisset

        // Contact Information
        @isset($contact_person)
            @foreach($contact_person as $index => $person)
                @if(empty($person->district) || empty($person->upazila))
                @continue
                @endif
                getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},
                    'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});
            @endforeach
        @endisset

        // Location of Installation
        @isset($appInfo->location_of_ins_district)
        getThanaByDistrictId('location_of_ins_district', {{ $appInfo->location_of_ins_district ?? '' }},
            'location_of_ins_thana', {{ $appInfo->location_of_ins_thana ?? '' }});
        @endisset

        @if(!empty($appInfo->isp_license_type))
            let ispLicenseType = '{{$appInfo->isp_license_type}}';
            let areaId = '';
            @if($appInfo->isp_license_type  == 2)
                $('#division').css('display', 'inline');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
                setTimeout(addEventListenerToIspType, 1500,"isp_licensese_area_division");
                areaId = '{{$appInfo->isp_license_division}}';
            @elseif($appInfo->isp_license_type  == 3)
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#thana').css('display', 'none');
                areaId = '{{$appInfo->isp_license_district}}';
                setTimeout(addEventListenerToIspType, 1500,"isp_licensese_area_district");
                getDistrictByDivisionId('isp_licensese_area_division', '{{$appInfo->isp_license_division}}', 'isp_licensese_area_district', '{{$appInfo->isp_license_district}}')
            @elseif($appInfo->isp_license_type  == 4)
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#thana').css('display', 'flex');
                areaId = '{{$appInfo->isp_license_upazila}}';
                setTimeout(addEventListenerToIspType, 1500,"isp_licensese_area_thana");
                getThanaByDistrictId('isp_licensese_area_district', '{{$appInfo->isp_license_district}}', 'isp_licensese_area_thana', '{{$appInfo->isp_license_upazila}}')
            @endif
            checkApplicationLimit(ispLicenseType,areaId);
        @endif

        //license type part
        $(document).on('change', '#isp_licensese_area_division', function () {
            $("#isp_licensese_area_thana").prop('selectedIndex', 0);
        });

        // step part
        let form = $("#application_form").show();
        let popupWindow = null;
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                let errorStatus = false;
                if (newIndex == 1) {

                    if(isAppLimitExceeded){
                        alert("According to the BTRC guideline you are not allowed to apply for this category license in this designated area.")
                        return false;
                    }
                    var total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        // alert("The value of the '% of share field' should be a total of 100.");
                        new swal({
                            type: 'error',
                            text: 'The value of the % of share field should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }


                    $(".shareholder_nationality").each(function (){
                        let id = $(this).attr('id');
                        let countryCode = $(this).val();
                        console.log(countryCode);
                        let lastId = id.split('_')[2];
                        if(countryCode == 18){
                            let nidValue = $("#shareholder_nid_"+lastId).val();
                            if(nidValue.length == 10 || nidValue.length == 13 || nidValue.length == 17){
                                errorStatus = false;
                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please provide valid NID number.',
                                });
                                $(this).addClass('error')
                                errorStatus = true;
                            }
                        }

                    });

                    // //validate client side rendered fields
                    // const requiredClientFields = document.querySelectorAll('.client-rendered-row input, .client-rendered-row select');
                    // let nextAvailable = true;
                    // for(const elem of requiredClientFields) {
                    //     if(elem.classList.contains('required') && !elem.value) {
                    //         elem.classList.add('error');
                    //         nextAvailable = false;
                    //     }
                    // }
                    // if(!nextAvailable) return false;
                }
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                if (newIndex == 2) {
                    // let dec_validation_status = declarationSectionValidation();
                    // if(!dec_validation_status){
                    //     return dec_validation_status;
                    // }
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                // return true;
                            } else {
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        } else {
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }
                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }

                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if (!form.valid()) errorStatus = true;
                if (errorStatus) return false;
                return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex > 0) {
                    form.find('#submitForm').css('display', 'none');
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }
                if (currentIndex === 1) {
                    form.find('#submitForm').css('display', 'none');
                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");
                } else {
                    form.find('#submitForm').css('display', 'none');
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('.previous').removeClass('prevMob');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")) {
                $('#accaccept_termseptTerms').removeClass('error');
                $('#accept_terms').next('label').css('color', 'black');
                $('body').css({"display": "none"});
                $("form").submit();
            } else {
                $('#acceptTerms').addClass('error');
                return false;
            }
        });
        $('.finish').on('click', function (e) {
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>', 'Sample', '');--}}
            openPreviewV2();
        });

        // let today = new Date();
        // let yyyy = today.getFullYear();
        // $('.datepicker').datetimepicker({
        //     viewMode: 'years',
        //     format: 'DD-MM-YYYY',
        //     extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
        //     maxDate: 'now',
        //     minDate: '01/01/1905'
        // });

        // datepicker part
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });


        datePickerHide('datetimepicker4');

        // declaration part
        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
        });
        $("#declaration_q1_no").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'none');
        });

        $("#declaration_q2_yes").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'inline');
        });
        $("#declaration_q2_no").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'none');
        });

        $("#declaration_q3_yes").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'inline');
        });
        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
        });

        //get share value and number of share
        $("#shareholderRow").on("keyup", ".share-value", function () {
            calculateShareValue();
        });

        $("#shareholderRow").on('keyup', '.no-of-share', function () {
            calculateShareValue();
        });

        // total no. of share and total value calculation
        function calculateShareValue() {
            let sum_share = 0;
            $(".share-value").each(function () {
                sum_share = sum_share + parseInt(this.value);
            });
            $("#total_share_value").val(sum_share);
            // let sum_value = document.getElementById('total_no_of_share').value === '' ? 0 : parseInt(document.getElementById('total_no_of_share').value);
            let sum_value = 0;
            $(".no-of-share").each(function () {
                sum_value = sum_value + parseInt(this.value);
            });
            $("#total_no_of_share").val(sum_value);
        }

        $('#type_of_isp_licensese').on('change', function () {
            if (this.value == 1 || this.value == "") {
                $('#division').css('display', 'none');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
                isAppLimitExceeded = 0;
            }
            if (this.value == 2) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
                addEventListenerToIspType('isp_licensese_area_division')
            }

            if (this.value == 3) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#thana').css('display', 'none');
                addEventListenerToIspType('isp_licensese_area_district')
            }

            if (this.value == 4) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#thana').css('display', '');
                addEventListenerToIspType('isp_licensese_area_thana')
            }
            getHelpText();
        });
        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });
        var company_type = "{{$appInfo->org_type}}";
        if (company_type == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (company_type == 1) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        // payment part
        @if(!empty($appInfo->isp_license_type) && $appInfo->status_id != 5 )
        let oss_fee = 0;
        let vat = 0;
        $.ajax({
            type: "POST",
            url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",
            data: {
                process_type_id: {{ $process_type_id }},
                payment_type: 1,
                license_type: {{$appInfo->isp_license_type}}
            },
            success: function (response) {
                if (response.responseCode == -1) {
                    alert(response.msg);
                    return false;
                }
                oss_fee = parseFloat(response.data.oss_fee);
                vat = parseInt(response.data.vat);
            },
            complete: function () {
                var unfixed_amounts = {
                    1: 0,
                    2: oss_fee,
                    3: 0,
                    4: 0,
                    5: vat,
                    6: 0
                };
                const payOrderInfo = @json($pay_order_info);
                loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
                    'payment_panel',
                    "{{ CommonFunction::getUserFullName() }}",
                    "{{ Auth::user()->user_email }}",
                    "{{ Auth::user()->user_mobile }}",
                    "{{ Auth::user()->contact_address }}",
                    unfixed_amounts, JSON.stringify(payOrderInfo));
            }
        });
        @endif
        @if(!empty($appInfo->isp_license_type) && ($appInfo->status_id == 5) &&
            ($appInfo->payment_type == 'pay_order') &&
            (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0)
        )
        let oss_fees = 0;
        let vats = 0;
        $.ajax({
            type: "POST",
            url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",
            data: {
                process_type_id: {{ $process_type_id }},
                payment_type: 1,
                license_type: {{$appInfo->isp_license_type}}
            },
            success: function (response) {
                if (response.responseCode == -1) {
                    alert(response.msg);
                    return false;
                }
                oss_fees = parseFloat(response.data.oss_fee);
                vats = parseInt(response.data.vat);
            },
            complete: function () {
                var unfixed_amounts = {
                    1: 0,
                    2: oss_fees,
                    3: 0,
                    4: 0,
                    5: vats,
                    6: 0
                };
                const payOrderInfo = @json($pay_order_info);
                loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
                    'payment_panel',
                    "{{ $appInfo->pop_name }}",
                    "{{ $appInfo->pop_email }}",
                    "{{ $appInfo->pop_mobile }}",
                    "{{ $appInfo->pop_address }}"
                    , unfixed_amounts, JSON.stringify(payOrderInfo));
            }
        });
        @endif
        $("#type_of_isp_licensese").change(function () {
            let oss_fee = 0;
            let vat = 0;
            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",
                data: {
                    process_type_id: {{ $process_type_id }},
                    payment_type: 1,
                    license_type: $(this).val()
                },
                success: function (response) {
                    if (response.responseCode == -1) {
                        alert(response.msg);
                        return false;
                    }

                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function () {
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0
                    };
                    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        });

        // add row part
        $('.add_row').click(function () {
            var btn = $(this);
            btn.prop("disabled", true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled", false);
                    getHelpText();
                }
            });
        });
        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });
    });

    function mobile_no_validation(id) {
        var onlyNumber = $("#" + id).val();
        var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode ?? "880";
        var regex = /^01[0-9]{9}$/;
        var result = regex.test(onlyNumber);

        if (countryCode === '880') {
            if (onlyNumber === "") {
                console.log('label:', 0, `#${id}`)
                $(`#${id}`).addClass('error');
            } else if (result === false) {
                console.log('label:', 1)
                $(`#${id}`).addClass('error');
            } else {
                console.log('label:', 2)
                $(`#${id}`).removeClass('error');
            }
        }
    }

    function generateBase64String(source, destination) {
        const inputfile = document.getElementById(source);
        const file = inputfile.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            document.getElementById(destination).value = reader.result;
        };
        reader.onerror = function (error) {
            console.log(error);
        };
    }

    function openPreview() {

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').focus()
            $('#accept_terms').addClass('error');
            return false;
        }

        window.open('<?php echo URL::to( '/isp-license-issue/preview' ); ?>');
    }

    function openPreviewV2() {
        let preview = 1;
        // If select online payment
        const onlinePayment = "{{config('payment.online_payment')}}";
        if ($("#online_payment").is(':checked') && !onlinePayment) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }
        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if (!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            return false;
        }

        if (preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function declarationSectionValidation() {
        let error_status = true;
        if ($("#declaration_q1_yes").is(":checked") && $("#if_declaration_q1_yes").val() === "") {
            $("#if_declaration_q1_yes").addClass('error');
            error_status = false;
        }

        if ($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === "") {
            $("#if_declaration_q2_yes").addClass('error');
            error_status = false;
        }

        let declaration_q3_images_preview = $("#declaration_q3_images_preview").attr('href');
        if ($("#declaration_q3_yes").is(":checked") && ($("#if_declaration_q3_yes").val() === "" && !Boolean(declaration_q3_images_preview))) {
            console.log($("#if_declaration_q3_yes").val());
            $("#if_declaration_q3_yes").addClass('error');
            error_status = false;
        }

        return error_status;
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

    const ispLicenseAreaListeners = {
        'isp_licensese_area_division' : 0,
        'isp_licensese_area_district' : 0,
        'isp_licensese_area_thana' : 0,
    };
    function addEventListenerToIspType(selector) {
        const element = document.getElementById(selector);
        if(!ispLicenseAreaListeners[selector]) {
            element.addEventListener("change", function (event) {
                const ispLicenseType = document.getElementById('type_of_isp_licensese').value;
                const currentIspLicenseType = event.target.dataset.type;
                const ISP_LICENSE_TYPES = {
                    2: 'isp_licensese_area_division',
                    3: 'isp_licensese_area_district',
                    4: 'isp_licensese_area_thana',
                };
                let areaId = ISP_LICENSE_TYPES[ispLicenseType] ? document.getElementById(ISP_LICENSE_TYPES[ispLicenseType]).value : '';

                if (!areaId || (ispLicenseType != currentIspLicenseType)) {
                    return
                }
                checkApplicationLimit(ispLicenseType, areaId);
            });
            ispLicenseAreaListeners[selector] = 1;
        }
    }

    function checkApplicationLimit(ispLicenseType,areaId){
        let app_id = document.getElementById('app_id').value;
        $.ajax({
            type: "POST",
            url: "{{ url('isp-license-issue/check-application-limit') }}",
            data: {ispLicenseType,areaId,app_id},
            success: function(response) {
                // error
                if(parseInt(response.responseCode) == 2){
                    alert(response.message);
                    return false;
                }
                isAppLimitExceeded = parseInt(response.responseCode);
                if(isAppLimitExceeded) alert("The quota for this category of license in this designated area has already been filled up, hence, you are not allowed to apply for this category of license.");
            },
            error: function(error){
                alert(error)
            }
        });
    }
</script>
