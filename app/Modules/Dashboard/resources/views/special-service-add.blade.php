<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<script src="{{ asset("assets/plugins/jquery/jquery.min.js") }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ asset("assets/plugins/datepicker-oss/css/bootstrap-datetimepicker.min.css") }}"/>
<?php  
$addressDefaultLabel = 'Address';
?>

@extends('layouts.admin')

<style>
     .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
    .font-normal {
        font-weight: normal;
    }
    .input-disabled {
        pointer-events: none;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .w-60 {
        width: 70px !important;
    }
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard>.content {
        overflow: visible;
    }

    .wizard>.actions {
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
    .verticalAlignMiddle{
        vertical-align: middle !important;
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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard>.actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard>.content>.body label {
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

    .wizard>.steps>ul>li {
        width: 33.2% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>


@section('content')


<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
      
        <div class="card border-magenta" style="border-radius: 10px; ">
            
            <div class="card-body">
                {!! Form::open([
                        'url' => url('special_service/store'),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>
                <input type="hidden" id="process_type" name="process_type" value="{{$dynamic_form->process_type_id}}">
                <h3>Basic Information for: {{$application_type}} form</h3> 
                
                <fieldset>

            @if($process_data->type!=1)    
            <div class="card card-magenta border border-magenta">

                <div class="card-header">
                    License Information
                </div>

                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                {!! Form::label('search_license_no', 'License No', ['class' => 'col-md-2 ']) !!}
                                <div class="col-md-10">
                                    <input type="text" class="form-control license_no" name="license_no" value="{{isset($license_no) ? $license_no : ''}}" id="search_license_no" placeholder="14.32.0000.702.01.037.{{date('y')}}.1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" style="width: 100%;" class="btn btn-success" id="srch_license_btn">Verify</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group row" style="margin-top:10px;">
                                {!! Form::label('issue_date', 'Issue Date', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8 align_input_field {{ $errors->has('issue_date') ? 'has-error' : '' }}">
                                    <div class="input-group date datetimepicker5"
                                        id="datepicker" data-target-input="nearest">
                                        {!! Form::text('issue_date', isset($appInfo->license_issue_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->license_issue_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'issue_date', 'placeholder' => 'Issue Date']) !!}
                                        <div class="input-group-append input_disabled"
                                            data-target="#datepicker"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i
                                                    class="fa fa-calendar"></i></div>
                                        </div>
                                        {!! $errors->first('issue_date', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row" style="margin-top:10px;">
                                {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-md-4']) !!}
                                <div class="col-md-8 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                                    <div class="input-group date datetimepicker5"
                                        id="datepicker10" data-target-input="nearest">
                                        {!! Form::text('expiry_date', isset($appInfo->expiry_date) ?  \App\Libraries\CommonFunction::changeDateFormat($appInfo->expiry_date) : '' , ['class' => 'form-control input_disabled', 'id' => 'expiry_date', 'placeholder' => 'Expiry Date']) !!}
                                        <div class="input-group-append input_disabled"
                                            data-target="#datepicker10"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i
                                                    class="fa fa-calendar"></i></div>
                                        </div>
                                        {!! $errors->first('expiry_date', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @endif

                {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_district', $districts, isset($companyInfo->office_district) ? $companyInfo->office_district : '', ['class' => 'form-control', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('reg_office_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('reg_office_address', isset($companyInfo->office_location) ? $companyInfo->office_location : '', ['class' => 'form-control', 'placeholder' => 'Enter  '.$addressDefaultLabel, 'id' => 'reg_office_address']) !!}
                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        @isset($extra)
                            @if(in_array('address2', $extra))
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('reg_office_address2', 'Address Line 2', ['class' => 'col-md-4 ']) !!}
                                        <div
                                                class="col-md-8 {{ $errors->has('reg_office_address2') ? 'has-error' : '' }}">
                                            {!! Form::text('reg_office_address2', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address Line 2', 'id' => 'reg_office_address2']) !!}
                                            {!! $errors->first('reg_office_address2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>

            {{-- Operational Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Operational Office Address
                    <span style="float: right; cursor: pointer;" class="m-l-auto"
                          id="permanentSameAsRegisterdAddressSec">
                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress', 'style' => 'vertical-align:middle' ]) !!}
                        {!! Form::label('permanentSameAsRegisterdAddress', 'As Same As Registered Address') !!}
                    </span>
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_district', $districts, '', ['class' => 'form-control', 'id' => 'op_office_district', 'onchange' => "getThanaByDistrictId('op_office_district', this.value, 'op_office_thana',0)"]) !!}
                                    {!! $errors->first('op_office_district', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('noc_thana') ? 'has-error' : '' }}">
                                    {!! Form::select('op_office_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'op_office_thana']) !!}
                                    {!! $errors->first('op_office_thana', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 {{ $errors->has('op_office_address') ? 'has-error' : '' }}">
                                    {!! Form::text('op_office_address', '', ['class' => 'form-control', 'placeholder' => 'Enter  '.$addressDefaultLabel, 'id' => 'op_office_address']) !!}
                                    {!! $errors->first('op_office_address', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
                    {{-- Applicant Profile --}}
                    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Applicant Profile
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_name', 'Applicant Name', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_name', Auth::user()->user_first_name,
                                ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_first_name) ?? 'readonly',
                                'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8  {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="iti-flag bd"></span>
                                    <span>+88</span>
                                </span>
                            </div>
                            {!! Form::text('applicant_mobile', Auth::user()->user_mobile, ['class' => 'form-control applicant-mobile',
                                 'readonly' => isset(Auth::user()->user_mobile) ?? 'readonly',
                                 'placeholder' => 'Enter Mobile Number']) !!}
                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_email', Auth::user()->user_email, ['class' => 'form-control',
                                 'readonly' => isset(Auth::user()->user_email) ?? 'readonly',
                                 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_telephone', 'Telephone Number', ['class' => 'col-md-4']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_telephone',isset($companyInfo->office_phone) ? $companyInfo->office_phone: '', ['class' => 'form-control', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        {!! Form::label('applicant_address', $addressDefaultLabel, ['class' => 'col-md-4 ']) !!}
                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                            {!! Form::text('applicant_address', '', ['class' => 'form-control', 'placeholder' => 'Enter '.$addressDefaultLabel, 'id' => 'applicant_address']) !!}
                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- dynamic form inputs -->

                


                <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Other Inputs
                    
                </div>
                <div class="" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>


                   
                        @foreach($dynamic_form_data as $key1=>$val1)
                        <div class="card card-magenta border border-magenta">

                        @foreach($val1 as $key2=>$val2)
                        <div class="col-md-12 card-header">
                        
                        
                        {{$val2['section_name']}}

                        </div>
                        <div class="row col-md-12">

                        @foreach($val2['section_value'] as $key3=>$val3)

                        <div class="card-body col-md-6" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                            <div class="col-md-12">
                                <div class="form-group row">
                                <label  class="col-md-4">{{$val3['input_label']}}</label>
                                    <div class="col-md-8 {{ $errors->has('noc_district') ? 'has-error' : '' }}">


                                    @if($val3['input_type']=='Radio')
                                    <?php $options= explode(",",$val3['input_name']) ; ?>
                                        @foreach($options as $key=>$val)
                                        <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" style="display:inline" name="dynamic_{{str_replace(' ', '_', $val3['input_label'])}}" value="{{$val}}" >
                                        <label class="form-check-label" style="display:inline">{{$val}}</label>
                                        </div>
                                        @endforeach
                                        
                                        

                                    @elseif($val3['input_type']=='Text')
                                    <input type="text" class="form-control" name="dynamic_{{str_replace(' ', '_', $val3['input_label'])}}"    >
                                    @elseif($val3['input_type']=='Dropdown')
                                    <?php $options= explode(",",$val3['input_name']) ; ?>
                                        <select class="form-control" name="dynamic_{{str_replace(' ', '_', $val3['input_label'])}}"    >
                                        <option value="">Select One</option>
                                        @foreach($options as $key=>$val)

                                        <option value="{{$val}}">{{$val}}</option>
                                        

                                        @endforeach
                                        </select>

                                    
                                    @elseif($val3['input_type']=='Checkbox')
                                    <?php $options= explode(",",$val3['input_name']) ; ?>

                                        @foreach($options as $key=>$val)

                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label" for="callcenter">
                                                <input class="form-check-input" name="dynamic_{{str_replace(' ', '_', $val3['input_label'])}}[]"
                                                       type="checkbox" value="{{$val}}">
                                                       {{$val}}
                                            </label>
                                        </div>

                                        

                                        @endforeach
                                    
                                    @endif

                                        {!! $errors->first('op_office_district', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                        </div>
                        @endforeach

                        </div>
                        @endforeach
                    

                    
                </div>
            </div>

                
    <!-- dynamic form inputs end  -->

                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                  
                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Required Documents For Attachment
                        </div>
                        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            @if(!empty($dynamic_form_attachments[0]))
                            <table class="table table-striped table-bordered custom-responsive-table"
                                style="border-top: 1px solid #DEE2E6;">
                                <thead>
                                <tr>
                                    <th style="text-align: center;" width="70%">Label</th>
                                    <th style="text-align: center;">Attached</th>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                @foreach($dynamic_form_attachments as $files)
                                    <tr>
                                    <td>{{$files}}</td>
                                    <td> <input type="file"  name="documents[]"></td>
                                    </tr>
                                @endforeach
                                
                                </tbody>
                            </table>
                            @endif
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
                <fieldset>
                    {{-- Service Fee Payment --}}
                    

                    <div id="pay_order_information" class="pay_order_fields">
                    
                        <div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_0">
                            <div class="card-header">
                                Pay Order Information
                                
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col-md-6 pay_order_fields">
                                        <div class="row">
                                            {!! Form::label('pay_order_copy', 'Pay Order Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                            
                                                <div class="col-md-7" id="pay_order_copy_preview">
                                                    <input type="file" style="border: none;"
                                                           class="form-control input-md require_check"
                                                           name="pay_order_copy" id="pay_order_copy"
                                                           onchange="createObjUrl(event, 'pay_order_copy_url_base64', true)"/>
                                                    <input type="hidden" id="pay_order_copy_url_base64"/>
                                                </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_number', 'Pay Order Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_number', '', ['class' => 'form-control input-md require_check', 'id' => 'pay_order_number']) !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                
                                    <div class="pay_order_fields">
                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('pay_order_date', 'Pay Order Date', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7" id="pay_order_date_preview">
                                                        <div class="input-group date datetimepicker_pay_order"
                                                             id="datetimepicker_pay_order" data-target-input="nearest">
                                                            {!! Form::text('pay_order_date', '', ['class' => 'form-control input-md require_check datepicker', 'id' => 'pay_order_date', 'placeholder' => 'Enter pay order date']) !!}
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('bank_name', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bank_name', 'onchange' => "getBranchByBankId('bank_name', this.value, 'branch_name')"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row form-group">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {!! Form::label('branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('branch_name', [], '', ['class' => 'form-control input-md require_check', 'id' => 'branch_name']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                    
                </div>

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input required', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('/special-service/service-list/'. Encryption::encodeId($dynamic_form->process_type_id)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="closeBtn">Close
                    </a>
                </div>
                <div class="float-right">
                    <button type="submit" id="submitForm" style=" position: relative; z-index: 99999; " class="btn btn-success btn-md"
                            value="submit" name="actionBtn">Submit
                    </button>
                </div>
                <div class="float-right">
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-right: 10px;" value="draft" name="actionBtn" id="save_as_draft">
                    Save as Draft
                </button>
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>



@endsection
@include('partials.image-upload')
@section('footer-script')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/datepicker-oss/js/bootstrap-datetimepicker.js") }}"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>


<script>
   


    $(document).ready(function() {
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                sideBySide: true,
                defaultDate: new Date(),
                format: 'YYYY-MM-DD',
            });

            $("#srch_license_btn").click(function () {
        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
        btn.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "{{url('special_service/fetchAppData') }}",
            data: {
                license_no: $('#search_license_no').val(),
                process_type: $("#process_type").val(),
            },
            success: function (response) {
                // console.log(response);
                if (response.responseCode == -1) {
                    alert(response.msg);
                    $('#search_license_no').val('');
                    btn.prop('disabled', false);
                    btn.html(btn_content);
                    return false;
                } else {
                    
                    $.each(response.data.thana, function(keyName, keyValue) {

                    $('#reg_office_thana').append($('<option>', {
                        value: keyName,
                        text: keyValue,
                    }));
                    $('#op_office_thana').append($('<option>', {
                        value: keyName,
                        text: keyValue,
                    }));
                    $('#applicant_thana').append($('<option>', {
                        value: keyName,
                        text: keyValue,
                    }));
                    
                });

                $('#reg_office_district').val(response.data.appInfo.reg_office_district);
                $('#reg_office_thana').val(response.data.appInfo.reg_office_thana);
                $('#reg_office_address').val(response.data.appInfo.reg_office_address);
                $('#op_office_district').val(response.data.appInfo.op_office_district);
                $('#op_office_thana').val(response.data.appInfo.op_office_thana);
                $('#op_office_address').val(response.data.appInfo.op_office_address);
                $('#applicant_name').val(response.data.appInfo.applicant_name);
                $('#applicant_mobile').val(response.data.appInfo.applicant_mobile);
                $('#applicant_telephone').val(response.data.appInfo.applicant_telephone);
                $('#applicant_email').val(response.data.appInfo.applicant_email);
                $('#applicant_district').val(response.data.appInfo.applicant_district);
                $('#applicant_thana').val(response.data.appInfo.applicant_thana);
                $('#applicant_address').val(response.data.appInfo.applicant_address);
                $('#issue_date').val(response.data.appInfo.license_issue_date);
                $('#expiry_date').val(response.data.appInfo.expiry_date);

                }

                btn.prop('disabled', false);
                btn.html(btn_content);
            },
        });
    });

        let form = $("#application_form").show();
        let popupWindow = null;
        // step part
        form.find('.next').addClass('btn-primary');
       

        $("#permanentSameAsRegisterdAddress").on('change', function (e) {
            if (this.checked === true) {
                let office_district = $("#reg_office_district").val();
                let office_upazilla_thana = $("#reg_office_thana").val();
                $("#op_office_district").val(office_district);
                getThanaByDistrictId('op_office_district', office_district, 'op_office_thana', office_upazilla_thana.trim());
                $("#op_office_address").val($("#reg_office_address").val());
                $("#op_office_address2").val($("#reg_office_address2").val());

                    $("#op_office_district").addClass("input_disabled");
                    $("#op_office_thana").addClass("input_disabled");
                    $("#op_office_address").prop('readonly', true);
                    $("#op_office_address2").prop('readonly', true);

            } else {
                $("#op_office_thana").val('');
                $("#op_office_address").val('');
                $("#op_office_address2").val('');
                $("#op_office_district").val('');

                $("#op_office_thana").removeClass("input_disabled");
                $("#op_office_district").removeClass("input_disabled");
                $("#op_office_address").prop('readonly', false);
                $("#op_office_address2").prop('readonly', false);
            }
        })


       
        
    });

    function generateBase64String(source, destination){
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


    function deleteContactRow(element) {
        element.remove();
    }
    function declarationSectionValidation(){
        let error_status = false ;
        if($("#declaration_q1_yes").is(":checked") && $("#if_declaration_q1_yes").val() === ""){
            $("#if_declaration_q1_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === ""){
            $("#if_declaration_q2_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q3_yes").is(":checked") && $("#if_declaration_q3_yes").val() === ""){
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }

        return error_status;
    }
  



    function addEventListenerToIspType(selector) {
        const element = document.getElementById(selector);
        

    }

</script>

@endsection