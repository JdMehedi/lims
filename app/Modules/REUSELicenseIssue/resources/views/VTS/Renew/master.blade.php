<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
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
        background: #563590;
        color: #fff;
        cursor: default;
    }
    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #7a5eab;
        color: #fff;
    }
    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background:  #7a5eab;
        color: #fff;
        cursor: default;
    }

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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

    .border-header-box {
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }
    .border-header-txt {
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    .email {
        font-family: Arial !important;
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
    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }

</style>

{{--<div class="row">--}}
{{--    <div class="col-md-12 col-lg-12" id="inputForm">--}}
{{--        --}}{{-- Industry registration --}}
{{--        <div class="card border-magenta" style="border-radius: 10px; ">--}}
{{--            <h4 class="card-header" style="font-size: 24px; font-weight: 400">Application for TVAS License Renewal</h4>--}}
{{--            <div class="card-body">--}}
{{--                {!! Form::open([--}}
{{--                        'url' => url('tvas-license-issue/store'),--}}
{{--                        'method' => 'post',--}}
{{--                        'class' => 'form-horizontal',--}}
{{--                        'id' => 'application_form',--}}
{{--                        'enctype' => 'multipart/form-data',--}}
{{--                        'files' => 'true'--}}
{{--                    ])--}}
{{--                !!}--}}
{{--                @csrf--}}
{{--                <div style="display: none;" id="pcsubmitadd">--}}

{{--                </div>--}}
{{--                <h3>Basic Information</h3>--}}
{{--                --}}{{--                <br>--}}
{{--                <fieldset>--}}
{{--                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])--}}
{{--                    --}}{{-- Company Informaiton --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Company/Organization Information--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('company_name', 'Company Name', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('company_name', isset($companyInfo->org_nm) ? $companyInfo->org_nm : '',--}}
{{--                                                ['class' => 'form-control',--}}
{{--                                                'readonly' => isset($companyInfo->org_nm) ?? 'readonly',--}}
{{--                                                'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}--}}
{{--                                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::select('company_type', [''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'], isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control',--}}
{{--                                            'readonly' => isset($companyInfo->org_type) ?? 'readonly',--}}
{{--                                            'id' => 'company_type']) !!}--}}
{{--                                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <br>--}}

{{--                            --}}{{-- Registered Office Address --}}
{{--                            <div class="card card-magenta border border-magenta">--}}
{{--                                <div class="card-header">--}}
{{--                                    Registered Office Address--}}
{{--                                </div>--}}
{{--                                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('reg_office_district', $districts, '', ['class' => 'form-control', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}--}}
{{--                                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('reg_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('reg_office_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}--}}
{{--                                                    {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::text('reg_office_address', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'reg_office_address']) !!}--}}
{{--                                                    {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- Operational Office Address --}}
{{--                            <div class="card card-magenta border border-magenta">--}}
{{--                                <div class="card-header">--}}
{{--                                    Operational Office Address--}}
{{--                                    <span style="float: right; cursor: pointer;" class="m-l-auto" id="permanentSameAsRegisterdAddressSec">--}}
{{--                                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress']) !!}--}}
{{--                                        {!! Form::label('permanentSameAsRegisterdAddress', 'Same as Registered office address') !!}--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                                <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('permanent_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('permanent_office_district', $districts, '', ['class' => 'form-control', 'id' => 'permanent_office_district', 'onchange' => "getThanaByDistrictId('permanent_office_district', this.value, 'permanent_office_thana',0)"]) !!}--}}
{{--                                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('permanent_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('permanent_office_thana') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::select('permanent_office_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select district at first', 'id' => 'permanent_office_thana']) !!}--}}
{{--                                                    {!! $errors->first('permanent_office_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group row">--}}
{{--                                                {!! Form::label('permanent_office_address', 'Address', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                <div--}}
{{--                                                    class="col-md-8 {{ $errors->has('permanent_office_address') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::text('permanent_office_address', '', ['class' => 'form-control ', 'placeholder' => 'Enter  Address', 'id' => 'permanent_office_address']) !!}--}}
{{--                                                    {!! $errors->first('permanent_office_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    --}}{{-- Applicant Person --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Applicant Profile--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_name', 'Name of Applicant', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_name', '', ['class' => 'form-control ', 'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}--}}
{{--                                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_mobile_no', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_mobile_no') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_mobile_no', '', ['class' => 'form-control ', 'placeholder' => 'Enter Mobile No', 'id' => 'applicant_mobile_no']) !!}--}}
{{--                                            {!! $errors->first('applicant_mobile_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_telephone_no', 'Telephone No.', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_telephone_no') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_telephone_no', '', ['class' => 'form-control ', 'placeholder' => 'Enter Telephone No.', 'id' => 'applicant_telephone_no']) !!}--}}
{{--                                            {!! $errors->first('applicant_telephone_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_email', '', ['class' => 'form-control ', 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}--}}
{{--                                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}


{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control ', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}--}}
{{--                                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}--}}
{{--                                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_address', '', ['class' => 'form-control ', 'placeholder' => 'Enter  Address', 'id' => 'applicant_address']) !!}--}}
{{--                                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="form-group row">--}}
{{--                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}--}}
{{--                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">--}}
{{--                                            {!! Form::text('applicant_website', '', ['class' => 'form-control url', 'placeholder' => 'Website', 'id' => 'applicant_website']) !!}--}}
{{--                                            {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}



{{--                        </div>--}}
{{--                    </div>--}}

{{--                    --}}{{--  Contact Person --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Name of Authorized Signatory and Contact Person--}}
{{--                            --}}{{--                            <span style="float: right; cursor: pointer;" class="addContactPersonRow">--}}
{{--                            --}}{{--                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
{{--                            --}}{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--                            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"--}}
{{--                                   id="contactPersonRow">--}}
{{--                                <input type="hidden" id="contactPersonDataCount" name="contactPersonDataCount"--}}
{{--                                       value="1"/>--}}
{{--                                <tr id="cp_r_1">--}}
{{--                                    <td>--}}
{{--                                        <div class="card card-magenta border border-magenta">--}}
{{--                                            <div class="card-header">--}}
{{--                                                Contact Person--}}
{{--                                                <span style="float: right; cursor: pointer;" class="addContactPersonRow">--}}
{{--                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
{{--                                                </span>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_name_1', 'Name', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_district_1', 'District', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_district') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::select('contact_person_district[1]', $districts, '', ['class' => 'form-control contact_person_district', 'id' => 'contact_person_district_1', 'onchange' => "getThanaByDistrictId('contact_person_district_1', this.value, 'contact_person_thana_1',0)"]) !!}--}}
{{--                                                                {!! $errors->first('contact_person_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_thana_1', 'Upazila / Thana', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_thana') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::select('contact_person_thana[1]', [], '', ['class' => 'form-control contact_person_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_person_thana_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_address_1', 'Address', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_mobile') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('contact_person_mobile[1]', '', ['class' => 'form-control contact_person_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_person_mobile_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_mobile', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_image_1', 'Photograph', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('contact_person_image') ? 'has-error' : '' }}">--}}
{{--                                                                <div class="row"--}}
{{--                                                                     style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
{{--                                                                    <div class="col-md-8">--}}
{{--                                                                        <input type="file"--}}
{{--                                                                               style="border: none; margin-bottom: 5px;"--}}
{{--                                                                               value=""--}}
{{--                                                                               class="form-control input-sm contact_person_image"--}}
{{--                                                                               name="contact_person_image[1]" onchange="imagePreview(this,'image_preview_0')"--}}
{{--                                                                               id="contact_person_image_1"--}}

{{--                                                                               size="300x300"/>--}}

{{--                                                                        <span class="text-success"--}}
{{--                                                                              style="font-size: 9px; font-weight: bold; display: block;">--}}
{{--                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]--}}
{{--                                                                    <p style="font-size: 12px;"><a target="_blank"--}}
{{--                                                                                                   href="https://picresize.com/">You may update your image.</a></p>--}}
{{--                                                                </span>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="col-md-4">--}}
{{--                                                                        <label class="center-block image-upload"--}}
{{--                                                                               for="correspondent_photo_0">--}}
{{--                                                                            <figure>--}}
{{--                                                                                <img id="image_preview_0"--}}
{{--                                                                                     style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
{{--                                                                                     src="{{ url('assets/images/demo-user.jpg') }}"--}}
{{--                                                                                     class="img-responsive img-thumbnail"/>--}}
{{--                                                                            </figure>--}}
{{--                                                                        </label>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_email_1', 'Email', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('contact_person_designation_1', 'Designation', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div--}}
{{--                                                                class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('contact_person_designation[1]', '', ['class' => 'form-control contact_person_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_person_designation_1']) !!}--}}
{{--                                                                {!! $errors->first('contact_person_designation', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}


{{--                    --}}{{-- Shareholder/partner/proprietor Details --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Shareholder/partner/proprietor Details--}}
{{--                            --}}{{--                            <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
{{--                            --}}{{--                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
{{--                            --}}{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--                            <table class="table-responsive" style="width: 100%;     display: inline-table!important;" id="shareholderRow">--}}
{{--                                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>--}}
{{--                                <tr id="r_1">--}}
{{--                                    <td>--}}
{{--                                        <div class="card card-magenta border border-magenta">--}}
{{--                                            <div class="card-header">--}}
{{--                                                Shareholder/partner/proprietor Details Information--}}
{{--                                                <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
{{--                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
{{--                                                </span>--}}
{{--                                            </div>--}}
{{--                                            <div class="card-body" style="padding: 15px 25px;">--}}
{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_name_1', 'Name', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_designation_1', 'Designation', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_email_1', 'Email', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_share_of_1', '% of share', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="form-group row" id="nidBlock_1" style="display: none;">--}}
{{--                                                            {!! Form::label('shareholder_nid_1', 'National ID No', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="form-group row" id="passportCopyBlock_1"  style="display: none;">--}}
{{--                                                            {!! Form::label('shareholder_passport_copy_1', 'Passport Copy', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-4 {{ $errors->has('shareholder_passport_copy') ? 'has-error' : '' }}">--}}
{{--                                                                <input type="file" accept="image/*" class="form-control shareholder_passport_copy input-sm" style="border: none; margin-bottom: 5px;" name="shareholder_passport_copy[1]"  onchange="imagePreview(this,'shareholder_passport_copy_preview_1')" id="shareholder_passport_copy_1" />--}}
{{--                                                                {!! $errors->first('shareholder_passport_copy', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-md-4">--}}
{{--                                                                <label class="center-block image-upload passport_copy_preview_sec"--}}
{{--                                                                       for="shareholder_passport_copy_preview_1">--}}
{{--                                                                    <figure>--}}
{{--                                                                        <img id="shareholder_passport_copy_preview_1"--}}
{{--                                                                             style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
{{--                                                                             src="{{ url('assets/images/photo_default.png') }}"--}}
{{--                                                                             class="img-responsive img-thumbnail"/>--}}
{{--                                                                    </figure>--}}
{{--                                                                </label>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                    </div>--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group row" style="margin-bottom:0px!important;">--}}
{{--                                                            {!! Form::label('correspondent_photo_1', 'Photograph', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}

{{--                                                                --}}{{--start--}}

{{--                                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
{{--                                                                    <div class="col-md-8">--}}
{{--                                                                        <input type="file"--}}
{{--                                                                               style="border: none; margin-bottom: 5px;"--}}
{{--                                                                               class="form-control input-sm correspondent_photo {{ !empty(Auth::user()->user_pic) ? '' : '' }}"--}}
{{--                                                                               name="correspondent_photo[1]" id="correspondent_photo_1"--}}
{{--                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_1', 'correspondent_photo_base64_1')"--}}
{{--                                                                               size="300x300" />--}}
{{--                                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
{{--                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]--}}
{{--                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
{{--                                                                </span>--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="col-md-4">--}}
{{--                                                                        <label class="center-block image-upload"--}}
{{--                                                                               for="correspondent_photo_1">--}}
{{--                                                                            <figure>--}}
{{--                                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"--}}
{{--                                                                                     class="img-responsive img-thumbnail"--}}
{{--                                                                                     id="correspondent_photo_preview_1" />--}}
{{--                                                                            </figure>--}}
{{--                                                                            <input type="hidden" id="correspondent_photo_base64_1"--}}
{{--                                                                                   name="correspondent_photo_base64[1]" />--}}
{{--                                                                        </label>--}}

{{--                                                                    </div>--}}
{{--                                                                </div>--}}

{{--                                                                --}}{{--end--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row" style="margin-top:10px;">--}}
{{--                                                            {!! Form::label('shareholder_dob_1', 'Date of Birth', ['class' => 'col-md-4']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">--}}
{{--                                                                --}}{{--                                                    {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
{{--                                                                --}}{{--                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
{{--                                                                <div class="input-group date datetimepicker4"--}}
{{--                                                                     id="datepicker0" data-target-input="nearest">--}}
{{--                                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_1']) !!}--}}
{{--                                                                    <div class="input-group-append"--}}
{{--                                                                         data-target="#datepicker0"--}}
{{--                                                                         data-toggle="datetimepicker">--}}
{{--                                                                        <div class="input-group-text"><i--}}
{{--                                                                                class="fa fa-calendar"></i></div>--}}
{{--                                                                    </div>--}}
{{--                                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="form-group row">--}}
{{--                                                            {!! Form::label('shareholder_nationality_1', 'Nationality', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control nationality', 'id' => 'shareholder_nationality_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}



{{--                                                        <div class="form-group row" id="passportBlock_1"  style="display: none;">--}}
{{--                                                            {!! Form::label('shareholder_passport_1', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">--}}
{{--                                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}--}}
{{--                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}



{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </fieldset>--}}

{{--                <h3>Required Documents for attachment</h3>--}}
{{--                --}}{{--                <br>--}}
{{--                <fieldset>--}}
{{--                    --}}{{-- Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box): --}}
{{--                    --}}{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                    --}}{{--                        <div class="card-header">--}}
{{--                    --}}{{--                            Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box):--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                        <div class="card-body" style="padding: 15px 25px;">--}}

{{--                    --}}{{--                            <div class="row">--}}
{{--                    --}}{{--                                <div class="col-md-12">--}}
{{--                    --}}{{--                                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">--}}
{{--                    --}}{{--                                        <thead>--}}
{{--                    --}}{{--                                        <tr>--}}
{{--                    --}}{{--                                            <th style="text-align: center; width: 5%;">Serial</th>--}}
{{--                    --}}{{--                                            <th style="text-align: center; width: 70%;">Items</th>--}}
{{--                    --}}{{--                                            <th style="text-align: center; width: 30%;">Attached</th>--}}
{{--                    --}}{{--                                        </tr>--}}
{{--                    --}}{{--                                        </thead>--}}
{{--                    --}}{{--                                        <tbody>--}}
{{--                    --}}{{--                                        <tr>--}}
{{--                    --}}{{--                                            <td style="text-align: center; padding: 5px;">1</td>--}}
{{--                    --}}{{--                                            <td style="padding: 5px;"> Application in a Letter Head Pad </td>--}}
{{--                    --}}{{--                                            <td style="padding: 5px;">--}}
{{--                    --}}{{--                                                {!! Form::file('new_license_document[]', ['class' => 'form-control','id' => 'new_license_document']) !!}--}}
{{--                    --}}{{--                                            </td>--}}
{{--                    --}}{{--                                        </tr>--}}
{{--                    --}}{{--                                        </tbody>--}}
{{--                    --}}{{--                                    </table>--}}
{{--                    --}}{{--                                </div>--}}
{{--                    --}}{{--                            </div>--}}

{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                    </div>--}}

{{--                    --}}{{-- Necessary attachment --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div  class="card-header">--}}
{{--                            Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box):--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}
{{--                            <input type="hidden" id="doc_type_key" name="doc_type_key">--}}
{{--                            <div id="docListDiv"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}


{{--                    --}}{{-- Declaration --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Declaration--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <ol>--}}
{{--                                        <li>--}}
{{--                                            <label class=" !font-normal">--}}
{{--                                                Has any application for any license of the applicant/any share holder/partner been rejected before?--}}
{{--                                            </label>--}}

{{--                                            <div style="margin-top: 20px; margin-bottom: 10px;" id="declaration_q1">--}}
{{--                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}--}}
{{--                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}--}}

{{--                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}--}}
{{--                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}--}}
{{--                                            </div>--}}
{{--                                            <div id="if_declaration_q1_yes" style="display: none">--}}
{{--                                                <div class="form-group row" style="margin-top:10px;">--}}
{{--                                                    {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2', 'style' => 'font-weight:400' ]) !!}--}}
{{--                                                    <div class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">--}}
{{--                                                        <div class="input-group date datetimepicker4"--}}
{{--                                                             id="declaration_q1_application_date_dtpicker" data-target-input="nearest">--}}
{{--                                                            {!! Form::text('declaration_q1_application_date', '', ['class' => 'form-control', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y') ] ) !!}--}}
{{--                                                            <div class="input-group-append"--}}
{{--                                                                 data-target="#declaration_q1_application_date_dtpicker"--}}
{{--                                                                 data-toggle="datetimepicker">--}}
{{--                                                                <div class="input-group-text"><i--}}
{{--                                                                        class="fa fa-calendar"></i></div>--}}
{{--                                                            </div>--}}
{{--                                                            {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div style="margin-top: 20px;">--}}
{{--                                                    {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input', 'id'=> 'declaration_q1_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}


{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <label class="!font-normal">--}}
{{--                                                Do the Applicant/any Share Holder/ Partner hold any other Operator License/ Registration Certificate from the Commission?--}}
{{--                                            </label>--}}

{{--                                            <div style="margin-top: 20px; margin-bottom: 10px;" id="declaration_q2">--}}
{{--                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}--}}
{{--                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}--}}

{{--                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}--}}
{{--                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}--}}
{{--                                            </div>--}}
{{--                                            <div style="margin-top: 20px;display: none" id="if_declaration_q2_yes">--}}
{{--                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input', 'id'=>'declaration_q2_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5 ))}}--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <label class=" !font-normal">--}}
{{--                                                Has any other License/ Registration Certificate of the Applicans/ any Share Holder/ Partner been rejected before?--}}
{{--                                            </label>--}}

{{--                                            <div style="margin-top: 20px; margin-bottom: 10px;" id="declaration_q3">--}}
{{--                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}--}}
{{--                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}--}}

{{--                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}--}}
{{--                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}--}}
{{--                                            </div>--}}
{{--                                            <div id="if_declaration_q3_yes" style="display: none" >--}}
{{--                                                <div class="form-group row" style="margin-top:10px;">--}}
{{--                                                    {!! Form::label('declaration_q3_application_date', 'Date of Application', ['class' => 'col-md-2', 'style' => 'font-weight:400' ]) !!}--}}
{{--                                                    <div class="col-md-4 {{ $errors->has('declaration_q3_application_date') ? 'has-error' : '' }}">--}}
{{--                                                        <div class="input-group date datetimepicker4"--}}
{{--                                                             id="declaration_q3_application_date_dtpicker" data-target-input="nearest">--}}
{{--                                                            {!! Form::text('declaration_q3_application_date', '', ['class' => 'form-control', 'id' => 'declaration_q3_application_date','placeholder'=> date('d-M-Y') ]) !!}--}}
{{--                                                            <div class="input-group-append"--}}
{{--                                                                 data-target="#declaration_q3_application_date_dtpicker"--}}
{{--                                                                 data-toggle="datetimepicker">--}}
{{--                                                                <div class="input-group-text"><i--}}
{{--                                                                        class="fa fa-calendar"></i></div>--}}
{{--                                                            </div>--}}
{{--                                                            {!! $errors->first('declaration_q3_application_date', '<span class="help-block">:message</span>') !!}--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div style="margin-top: 20px;">--}}
{{--                                                    {{ Form::textarea('declaration_q3_text', null, array('class' =>'form-control input','id'=>'declaration_q3_text','placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ))}}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}


{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <label class="!font-normal">--}}
{{--                                                Were the Applicants/ its owner(s)/ any of its director(s)/ partner(s) involved in any illegal call termination?--}}
{{--                                            </label>--}}

{{--                                            <div style="margin-top: 20px; margin-bottom: 10px;" id="declaration_q4">--}}
{{--                                                {{ Form::radio('declaration_q4', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}--}}
{{--                                                {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}--}}

{{--                                                {{ Form::radio('declaration_q4', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}--}}
{{--                                                {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}--}}
{{--                                            </div>--}}
{{--                                            <div id="if_declaration_q4_yes" style="display: none" >--}}
{{--                                                <div class="form-group row" style="margin-top:10px;">--}}
{{--                                                    {!! Form::label('q4_license_number', 'Period of Involvement in illegal activities', ['class' => 'col-md-4', 'style' => 'font-weight:400' ]) !!}--}}
{{--                                                    <div class="col-md-3 {{ $errors->has('q4_license_number') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::text('q4_license_number', '', ['class' => 'form-control', 'id' => 'q4_license_number', 'placeholder'=> 'Enter License Number']) !!}--}}
{{--                                                        {!! $errors->first('q4_license_number', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}

{{--                                                    {!! Form::label('q4_case_no', 'Case No', ['class' => 'col-md-2', 'style' => 'font-weight:400' ]) !!}--}}
{{--                                                    <div class="col-md-3 {{ $errors->has('q4_case_no') ? 'has-error' : '' }}">--}}
{{--                                                        {!! Form::text('q4_case_no', '', ['class' => 'form-control', 'id' => 'q4_case_no']) !!}--}}
{{--                                                        {!! $errors->first('q4_case_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="row">--}}
{{--                                                    <div class="col-md-12 border border-header-box">--}}
{{--                                                        <span class="border-header-txt">Administrative fine paid to the Commission</span>--}}
{{--                                                        <div class="row">--}}
{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group row">--}}
{{--                                                                    {!! Form::label('q4_amount', 'Amount', ['class' => 'col-md-4','style'=> 'font-weight: 400']) !!}--}}
{{--                                                                    <div--}}
{{--                                                                        class="col-md-8 {{ $errors->has('q4_amount') ? 'has-error' : '' }}">--}}
{{--                                                                        {!! Form::number('q4_amount', '', ['class' => 'form-control', 'id' => 'q4_amount']) !!}--}}
{{--                                                                        {!! $errors->first('q4_amount', '<span class="help-block">:message</span>') !!}--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group row">--}}
{{--                                                                    {!! Form::label('q4_bank_draft_no', 'Cheque No./ Bank Draft No', ['class' => 'col-md-4' ,'style'=> 'font-weight: 400' ]) !!}--}}
{{--                                                                    <div--}}
{{--                                                                        class="col-md-8 {{ $errors->has('q4_bank_draft_no') ? 'has-error' : '' }}">--}}
{{--                                                                        {!! Form::text('q4_bank_draft_no', '', ['class' => 'form-control', 'id' => 'q4_bank_draft_no']) !!}--}}
{{--                                                                        {!! $errors->first('q4_bank_draft_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="form-group row" style="margin-top:10px;">--}}
{{--                                                    {!! Form::label('q4_given_comission', 'Undertaking given to the Commission', ['class' => 'col-md-5', 'style' => 'font-weight:400' ]) !!}--}}
{{--                                                    <div class="col-md-6 {{ $errors->has('given_comission') ? 'has-error' : '' }}"  id="q4_given_comission">--}}
{{--                                                        {{ Form::radio('q4_given_comission', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id'=> ''  ]) }}--}}
{{--                                                        {{ Form::label('given_comission_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;',  'id'=> ''  ]) }}--}}

{{--                                                        {{ Form::radio('q4_given_comission', 'No', '', ['class'=>'form-check-input','checked', 'style'=>'display: inline;  margin-left:10px;', 'id'=> '' ]) }}--}}
{{--                                                        {{ Form::label('given_comission_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}--}}
{{--                                                        {!! $errors->first('q4_given_comission', '<span class="help-block">:message</span>') !!}--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}


{{--                                            </div>--}}


{{--                                        </li>--}}

{{--                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>--}}
{{--                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>--}}
{{--                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>--}}
{{--                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>--}}
{{--                                    </ol>--}}
{{--                                </div>--}}
{{--                            </div>--}}


{{--                        </div>--}}
{{--                    </div>--}}

{{--                </fieldset>--}}

{{--                <h3>Payment & Submit</h3>--}}
{{--                --}}{{--                <br>--}}
{{--                <fieldset>--}}
{{--                    --}}{{-- Service Fee Payment --}}
{{--                    <div id="payment_panel"></div>--}}

{{--                    --}}{{-- Terms and Conditions --}}
{{--                    <div class="card card-magenta border border-magenta">--}}
{{--                        <div class="card-header">--}}
{{--                            Terms and Conditions--}}
{{--                        </div>--}}
{{--                        <div class="card-body" style="padding: 15px 25px;">--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}

{{--                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}--}}
{{--                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:20px;']) }}--}}
{{--                                    --}}{{--                                    <a href="#" data-toggle="modal" data-target="#terms_and_conditions_modal">Terms and Conditions.</a>--}}
{{--                                    --}}{{--                                    <a href="#" data-toggle="modal" data-target="#otp_modal" class="btn btn-info btn-block btn-md"><strong><i--}}
{{--                                    --}}{{--                                                class=" fa fa-lock "></i> Login with--}}
{{--                                    --}}{{--                                            OTP</strong></a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}


{{--                </fieldset>--}}

{{--                <div class="float-left">--}}
{{--                    <a href="{{ url('client/vts-license-renew/list/'. Encryption::encodeId(30)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"--}}
{{--                       id="save_as_draft">Close--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <div class="float-right">--}}
{{--                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"--}}
{{--                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit--}}
{{--                    </button>--}}
{{--                </div>--}}

{{--                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn"--}}
{{--                        id="save_as_draft">Save as Draft--}}
{{--                </button>--}}
{{--                {!! Form::close() !!}--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div id="terms_and_conditions_modal" class="modal fade" role="dialog">--}}
{{--    <div class="modal-dialog ">--}}

{{--        <!-- Modal content for terms_and_conditions_modal-->--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-dialog-for-condition">--}}
{{--                --}}{{-- Terms and Conditions --}}
{{--                <div class="card card-magenta border border-magenta">--}}
{{--                    <div class="card-header">--}}
{{--                        Terms and Conditions <span style="float: right;"><button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 16px;">&times;</button></span>--}}
{{--                    </div>--}}
{{--                    <div class="card-body" style="padding: 15px 25px;">--}}

{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        Application without the submission of complete documents and information will not be accepted.--}}
{{--                                    </li>--}}
{{--                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication--}}
{{--                                        Regulatory Commission (BTRC).--}}
{{--                                    </li>--}}
{{--                                    <li>Fees and charges are not refundable.</li>--}}
{{--                                    <li>The Commission is entitled to change this from time to time if necessary.</li>--}}
{{--                                    <li>Updated documents shall be submitted during application.</li>--}}
{{--                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>--}}
{{--                                    <li>For New Applicant only A, B and E will be applicable.</li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}


{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}


<div class="col-md-12">
    <div id="fetchedData">
        @includeIf('REUSELicenseIssue::VTS.Renew.search-blank')
    </div>
</div>



<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>


