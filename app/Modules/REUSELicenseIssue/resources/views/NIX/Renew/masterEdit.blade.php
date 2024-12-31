<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .font-normal {
        font-weight: normal;
    }

    .input-disable {
        pointer-events: none;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

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

    mr-2 {
        margin-right: 0.5rem;
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
        width: 50% !important;
    }

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

</style>
<div id="renewForm">
    <div class="row">
        <div class="col-md-12 col-lg-12" id="inputForm">
            @if (in_array($appInfo->status_id, [5, 6]))
                @include('ProcessPath::remarks-modal')
            @endif
            {{-- Industry registration --}}
            <div class="card border-magenta" style="border-radius: 10px; ">
                <div class="card-header d:flex justify-between">
                    <h4 class="card-header">Application for National Internet Exchange (NIX) Renew</h4>
                    <div class="clearfix">
                        @if (in_array($appInfo->status_id, [5]))
                            <div class="col-md-12">
                                <div class="float-right" style="margin-right: 1%;">
                                    <a data-toggle="modal" data-target="#remarksModal">
                                        {!! Form::button('<i class="fa fa-eye mr-2"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open([
                            'url' => url('nix-license-renew/store'),
                            'method' => 'post',
                            'class' => 'form-horizontal',
                            'id' => 'application_form',
                            'enctype' => 'multipart/form-data',
                            'files' => 'true'
                        ])
                    !!}
                    @csrf
                    <div style="display: none;" id="pcsubmitadd">
                        <input type="hidden" id="openMode" name="openMode" value="edit">
                        {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                        {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                    </div>
                    <h3>Basic Information</h3>
                    <fieldset>
{{--                        @includeIf('common.subviews.licenseInfo', ['mode' => 'renew-form-edit'])--}}
                        {{--                        <div class="d-flex">--}}
                        {{--                            {!! Form::label('license_no', 'License No.', ['class' => 'col-md-2 required-star']) !!}--}}
                        {{--                            <div class="form-group col-md-5">--}}
                        {{--                                <div class="input-group mb-3">--}}
                        {{--                                    <input type="text" class="form-control" id="license_no"--}}
                        {{--                                           value="{{ $appInfo->license_no }}" name="license_no" readonly>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{-- Company Informaiton --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Company Informaiton
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_name', 'Company Name', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                                                {!! Form::text('company_name', isset($companyInfo->org_nm) ? $companyInfo->org_nm : '',
                                                    ['class' => 'form-control',
                                                    'readonly' => isset($companyInfo->org_nm) ?? 'readonly',
                                                    'placeholder' => 'Enter Name', 'id' => 'company_name']) !!}
                                                {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 required-star']) !!}
                                            <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                                                {!! Form::select('company_type',[''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'], isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control',
                                                'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                                                'id' => 'company_type']) !!}
                                                {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-12 border border-header-box">
                                        <span class="border-header-txt">Registered Office Address</span>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('reg_office_district', $districts, $appInfo->reg_office_district, ['class' => 'form-control','readonly','disabled', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                                        {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('reg_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('reg_office_thana', [], $appInfo->reg_office_thana, ['class' => 'form-control','readonly','disabled', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
                                                        {!! $errors->first('reg_office_thana', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('reg_office_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('reg_office_address', $appInfo->reg_office_address, ['class' => 'form-control', 'placeholder' => 'Enter  Address','readonly', 'id' => 'reg_office_address']) !!}
                                                        {!! $errors->first('reg_office_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 border border-header-box">
                                        <span class="border-header-txt">Permanent Office Address</span>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('permanent_office_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('reg_office_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('permanent_office_district', $districts,$appInfo->per_office_district, ['class' => 'form-control','readonly','disabled', 'id' => 'permanent_office_district', 'onchange' => "getThanaByDistrictId('permanent_office_district', this.value, 'permanent_office_thana',0)"]) !!}
                                                        {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('permanent_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('permanent_office_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('permanent_office_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select district at first','readonly','disabled', 'id' => 'permanent_office_thana']) !!}
                                                        {!! $errors->first('permanent_office_thana', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('permanent_office_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                    <div
                                                        class="col-md-8 {{ $errors->has('permanent_office_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('permanent_office_address',$appInfo->per_office_address, ['class' => 'form-control ', 'placeholder' => 'Enter  Address','readonly', 'id' => 'permanent_office_address']) !!}
                                                        {!! $errors->first('permanent_office_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>

                        {{-- Applicant Profile --}}
{{--                        <div class="card card-magenta border border-magenta">--}}
{{--                            <div class="card-header">--}}
{{--                                Applicant Profile--}}
{{--                            </div>--}}
{{--                            <div class="card-body" style="padding: 15px 25px;">--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_name', 'Name of Entity', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::text('applicant_name', $appInfo->applicant_name,--}}
{{--                                                    ['class' => 'form-control',--}}
{{--                                                    'placeholder' => 'Enter Name','readonly', 'id' => 'applicant_name']) !!}--}}
{{--                                                {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::select('applicant_district', $districts, $appInfo->applicant_district, ['class' => 'form-control', 'id' => 'applicant_district','readonly','disabled', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}--}}
{{--                                                {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::select('applicant_thana', [], $appInfo->applicant_thana, ['class' => 'form-control', 'placeholder' => 'Select district at first','readonly','disabled', 'id' => 'applicant_thana']) !!}--}}
{{--                                                {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::textarea('applicant_address', $appInfo->applicant_address, ['class' => 'form-control', 'placeholder' => 'Enter  Address','readonly','id' => 'applicant_address','rows'=>'1']) !!}--}}
{{--                                                {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::text('applicant_email', $appInfo->applicant_email, ['class' => 'form-control',--}}
{{--                                                    'placeholder' => 'Email','readonly', 'id' => 'applicant_email']) !!}--}}
{{--                                                {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6">--}}

{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_mobile_no', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_mobile_no') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::text('applicant_mobile', $appInfo->applicant_mobile, ['class' => 'form-control',--}}
{{--                                                'placeholder' => 'Enter Mobile Number','readonly', 'id' => 'applicant_mobile_no' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}--}}
{{--                                                {!! $errors->first('applicant_mobile_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}


{{--                                <div class="row">--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_telephone_no', 'Telephone No.', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::text('applicant_telephone', $appInfo->applicant_telephone, ['class' => 'form-control','readonly', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone_no']) !!}--}}
{{--                                                {!! $errors->first('applicant_telephone_no', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group row">--}}
{{--                                            {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}--}}
{{--                                            <div--}}
{{--                                                class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">--}}
{{--                                                {!! Form::text('applicant_website',$appInfo->applicant_website, ['class' => 'form-control', 'placeholder' => 'Website','readonly','id' => 'applicant_website']) !!}--}}
{{--                                                {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
                        @includeIf('common.subviews.applicantProfile', ['mode' => 'edit'])

                        {{-- Name of Authorized Signatory and Contact Person --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Contact Person
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <table class="table-responsive"
                                       style="width: 100%;     display: inline-table!important;"
                                       id="contactPersonRow">
                                    <input type="hidden" id="contactPersonDataCount" name="contactPersonDataCount"
                                           value="{{ count($contact_person) }}"/>
                                    @foreach($contact_person as $key => $contactData)
                                        @php $row = $key+1;  @endphp
                                        <tr id="cp_r_{{$key+1}}">
                                            <td>
                                                <div class="card card-magenta border border-magenta"
                                                     style="margin-top: 20px;" id="contact_1">
                                                    <div class="card-header">
                                                        Contact Person Information
                                                    </div>
                                                    <div class="card-body" style="padding: 15px 25px;">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                                        {!! Form::text('contact_person_name[]', $contactData->name, ['class' => 'form-control', 'placeholder' => 'Enter Name','readonly', 'id' => 'contact_name']) !!}
                                                                        {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                                        {!! Form::select('contact_district[]', $districts, $contactData->district, ['class' => 'form-control cp_districts','readonly','disabled', 'id' => "contact_district_$row", 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                                                        {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                                        {!! Form::select('contact_thana[]', [], $contactData->upazila, ['class' => 'form-control cp_thana','readonly','disabled', 'data-id'=>$contactData->cntct_prsn_upazila, 'placeholder' => 'Select district at first', 'id' => "contact_thana_$row"]) !!}
                                                                        {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_person_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                                        {!! Form::text('contact_person_address[]', $contactData->address, ['class' => 'form-control','readonly', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address']) !!}
                                                                        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                                        {!! Form::text('contact_mobile[]', $contactData->mobile, ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number','readonly', 'id' => 'contact_mobile']) !!}
                                                                        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">

                                                                    {!! Form::label('contact_image', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_image') ? 'has-error' : '' }}">
                                                                        <div class="row"
                                                                             style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                            <div class="col-md-8">
                                                                                <input type="file"
                                                                                       style="border: none; margin-bottom: 5px;"
                                                                                       value="{{ $contactData->cntct_prsn_img }}"
                                                                                       class="form-control input-sm input-disable"
                                                                                       name="contact_image[{{$key}}]"
                                                                                       onchange="imagePreview(this,'image_preview_{{$key+1}}')"
                                                                                       id="contact_image_{{ $key+1 }}"

                                                                                       size="300x300"/>
                                                                                <input type="hidden"
                                                                                       name="contact_person_pre_img[]"
                                                                                       value="{{ $contactData->cntct_prsn_img }}"/>


                                                                                <span class="text-success"
                                                                                      style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                                    <p style="font-size: 12px;"><a target="_blank"
                                                                                                   href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="center-block image-upload"
                                                                                       for="correspondent_photo_{{$key+1}}">
                                                                                    <figure>
                                                                                        <img
                                                                                            id="image_preview_{{$key+1}}"
                                                                                            style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                                            src="{{ !empty($contactData->cntct_prsn_img) ? asset($contactData->cntct_prsn_img) :  url('assets/images/demo-user.jpg') }}"
                                                                                            class="img-responsive img-thumbnail"/>
                                                                                    </figure>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                                        {!! Form::text('contact_person_email[]', $contactData->email, ['class' => 'form-control','readonly', 'placeholder' => 'Email', 'id' => 'contact_email']) !!}
                                                                        {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                                        {!! Form::text('contact_designation[]', $contactData->designation, ['class' => 'form-control','readonly', 'placeholder' => 'Designation', 'id' => 'contact_designation']) !!}
                                                                        {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>


                        {{-- Shareholder/partner/proprietor Details --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Shareholder/partner/proprietor Details
                                {{--                                <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
                                {{--                                    <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
                                {{--                                </span>--}}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <table class="table-responsive"
                                       style="width: 100%;     display: inline-table!important;"
                                       id="shareholderRow">

                                    <input type="hidden" id="shareholderDataCount" name="shareholderDataCount"
                                           value="{{ count($shareholders_data) }}"/>
                                    @foreach($shareholders_data as $index=>$shareholder)
                                        <tr id="r_{{$index+1}}">
                                            <td>
                                                <div class="card card-magenta border border-magenta">
                                                    <div class="card-header">
                                                        Shareholder/partner/proprietor Details Information
                                                    </div>
                                                    <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                                        {!! Form::text("shareholder_name[$index]", $shareholder->shareholders_name, ['class' => 'form-control','readonly', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                                        {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                                        {!! Form::text("shareholder_designation[$index]", $shareholder->shareholders_designation, ['class' => 'form-control', 'placeholder' => 'Designation ','readonly',  'id' => 'shareholder_designation']) !!}
                                                                        {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                                        {!! Form::text("shareholder_email[$index]", $shareholder->shareholders_email, ['class' => 'form-control', 'placeholder' => 'Email', 'readonly', 'id' => 'shareholder_email']) !!}
                                                                        {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>


                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_nid', 'National ID No.', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                        {!! Form::text("shareholder_nid[$index]", $shareholder->shareholders_nid, ['class' => 'form-control required', 'placeholder' => 'Nid','readonly',  'id' => 'shareholder_nid']) !!}
                                                                        {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>


                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group row"
                                                                     style="margin-bottom:0px!important;">
                                                                    {!! Form::label('shareholder_image', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                                        <div class="row"
                                                                             style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                            <div class="col-md-8">
                                                                                <input type="file"
                                                                                       style="border: none; margin-bottom: 5px;"
                                                                                       value="{{ $shareholder->shareholders_image }}"
                                                                                       class="form-control input-sm input-disable"
                                                                                       name="correspondent_photo[{{$index}}]"
                                                                                       id="correspondent_photo_{{$index}}"
                                                                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_{{$index}}', 'correspondent_photo_base64_{{$index}}')"
                                                                                       size="300x300"/>

                                                                                <span class="text-success"
                                                                                      style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                            <p style="font-size: 12px;"><a target="_blank"
                                                                                           href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="center-block image-upload"
                                                                                       for="correspondent_photo_{{$index}}">
                                                                                    <figure>
                                                                                        <img
                                                                                            style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                                            src="{{ !empty($shareholder->shareholders_image) ? $shareholder->shareholders_image : url('assets/images/demo-user.jpg') }}"
                                                                                            class="img-responsive img-thumbnail"
                                                                                            id="correspondent_photo_preview_{{$index}}"/>
                                                                                    </figure>
                                                                                    <input type="hidden"
                                                                                           id="correspondent_photo_base64_{{$index}}"
                                                                                           value="{{ $shareholder->shareholders_image }}"
                                                                                           name="correspondent_photo_base64[{{$index}}]"/>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                                        {!! Form::text("shareholder_mobile[$index]", $shareholder->shareholders_mobile, ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number','readonly',  'id' => 'shareholder_mobile']) !!}
                                                                        {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 required-star']) !!}
                                                                    <div
                                                                        class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                                        {!! Form::number("shareholder_share_of[$index]", $shareholder->shareholders_share_percent, ['class' => 'form-control shareholder_share_of','readonly','placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                                        {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                        @includeIf('common.subviews.Shareholder', ['mode' => 'edit'])
                        @if($appInfo->status_id == 5)
                            @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'shortfall'])
                        @endif


                    </fieldset>

                    <h3>Attachment & Declaration & Submit</h3>
                    <fieldset>
                        {{-- Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box): --}}
                        {{--                    <div class="card card-magenta border border-magenta">--}}
                        {{--                        <div class="card-header">--}}
                        {{--                            Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box):--}}
                        {{--                        </div>--}}
                        {{--                        <div class="card-body" style="padding: 15px 25px;">--}}

                        {{--                            <div class="row">--}}
                        {{--                                <div class="col-md-12">--}}
                        {{--                                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">--}}
                        {{--                                        <thead>--}}
                        {{--                                        <tr>--}}
                        {{--                                            <th style="text-align: center; width: 5%;">Serial</th>--}}
                        {{--                                            <th style="text-align: center; width: 70%;">Items</th>--}}
                        {{--                                            <th style="text-align: center; width: 30%;">Attached</th>--}}
                        {{--                                        </tr>--}}
                        {{--                                        </thead>--}}
                        {{--                                        <tbody>--}}
                        {{--                                        <tr>--}}
                        {{--                                            <td style="text-align: center; padding: 5px;">1</td>--}}
                        {{--                                            <td style="padding: 5px;"> Application in a Letter Head Pad </td>--}}
                        {{--                                            <td style="padding: 5px;">--}}
                        {{--                                                {!! Form::file('new_license_document[]', ['class' => 'form-control','id' => 'new_license_document']) !!}--}}
                        {{--                                            </td>--}}
                        {{--                                        </tr>--}}
                        {{--                                        </tbody>--}}
                        {{--                                    </table>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}

                        {{--                        </div>--}}
                        {{--                    </div>--}}

                        {{-- Necessary attachment --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Documents to be enclosed for New License and Renewal (Use tick [√] mark in the
                                appropriate
                                box):
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <input type="hidden" id="doc_type_key" name="doc_type_key">
                                <div id="docListDiv"></div>
                            </div>
                        </div>


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
                                                <label class="required-star !font-normal">
                                                    Declaration Has any Application for License of ISP been rejected
                                                    before?
                                                </label>

                                                <div style="margin: 10px 0;" id="declaration_q1">
                                                    {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div style="margin-top: 20px; {{ ($appInfo->declaration_q1 == 'Yes') ? 'display:block;' :'display:none;' }}" id="if_declaration_q1_yes" >
                                                    {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input','id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5))}}
                                                </div>

                                            </li>
                                            <li>
                                                <label class="required-star !font-normal">
                                                    Has any License of ISP issued previously to the Applicant/any Share
                                                    Holder/Partner been cancelled?
                                                </label>

                                                <div style="margin: 10px 0;" id="declaration_q2">
                                                    {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline','id' => 'declaration_q2_yes']) }}
                                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;','id' => 'declaration_q2_no']) }}
                                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div style="margin-top: 20px; {{ ($appInfo->declaration_q2 == 'Yes') ? 'display:block;' :'display:none;' }}" id="if_declaration_q2_yes">
                                                    {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input', 'id'=>'declaration_q2_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                                </div>

                                            </li>

                                            <li>
                                                <label class="required-star !font-normal">
                                                    Do the Applicant/any Share Holder/Partner hold any other Operator
                                                    Licenses from the Commission?
                                                </label>

                                                <div style="margin: 10px 0;" id="declaration_q3">
                                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;','id' => 'declaration_q3_no']) }}
                                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div style="margin-top: 20px; {{ ($appInfo->declaration_q3 == 'Yes') ? 'display:block;' :'display:none;' }}" id="if_declaration_q3_yes" >
                                                    {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input', 'id'=>'if_declaration_q3_yes', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                                </div>

                                            </li>
                                            <li><span class="i_we_dynamic">I/We</span> hereby certify that <span
                                                    class="i_we_dynamic">I/We</span> have carefully read the
                                                guidelines/terms and conditions, for the license and <span
                                                    class="i_we_dynamic">I/We</span> undertake to comply with the terms
                                                and
                                                conditions therein.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                                certify that <span class="i_we_dynamic">I/We</span> have carefully read
                                                the
                                                section 36 of Bangladesh Telecommunication Regulation Act, 2001 and
                                                <span
                                                    class="i_we_dynamic">I/We</span> are not disqualified from obtaining
                                                the
                                                license.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span>
                                                understand
                                                that any information furnished in this application are found fake or
                                                false
                                                or this application form is not duly filled up, the Commission, at any
                                                time
                                                without any reason whatsoever, may reject the whole application.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span>
                                                understand
                                                that if at any time any information furnished for obtaining the license
                                                is
                                                found incorrect then the license if granted on the basis of such
                                                application
                                                shall deemed to be cancelled and shall be liable for action as per
                                                Bangladesh Telecommunication Regulation Act, 2001.
                                            </li>
                                        </ol>
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
                                        {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                        {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','id'=>'termsCheck','style'=>'display: inline;  margin-left:10px;']) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>


                    <div class="float-left">
                        <a href="{{ url('client/nix-license-renew/list/'. Encryption::encodeId(10)) }}"
                           class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                           id="save_as_draft">Close
                        </a>
                    </div>

                    <div class="float-right">
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" name="actionBtn" onclick="openPreview()">{{$appInfo->status_id != 5 ? 'Submit': 'Re-Submit'  }}
                        </button>
                    </div>
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                                name="actionBtn"
                                id="save_as_draft">Save as Draft
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


                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>


    var selectCountry = '';

    $(document).ready(function () {

        getHelpText('nix-license-renew');

        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset

        @isset($appInfo->per_office_district)
        getThanaByDistrictId('permanent_office_district', {{ $appInfo->per_office_district ?? '' }},
            'permanent_office_thana', {{ $appInfo->per_office_thana ?? '' }});
        @endisset

        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
        @endisset

        @isset($contact_person)
        @foreach($contact_person as $index => $person)
        getThanaByDistrictId('contact_district_{{$index+1}}', {{ $person->district ?? ''}},
            'contact_thana_{{$index+1}}', {{ $person->upazila ?? '' }});
        @endforeach
        @endisset

        @isset($appInfo->per_office_district)
        getThanaByDistrictId('permanent_office_district', {{ $appInfo->per_office_district ?? '' }},
            'permanent_office_thana', {{ $appInfo->per_office_thana ?? '' }});
        @endisset

        var form = $("#application_form").show();
        // form.find('#save_as_draft').css('display','none');
        // form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                if (newIndex == 1) {
                    //return true;

                    var total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        return false;
                    }
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }

                }

                if (newIndex == 2) {

                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                return true;
                            } else {
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                return false;
                            }
                        } else {
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            return false;
                        }
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        return false;
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

                if(currentIndex > newIndex) return true ;

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {

                if (currentIndex > 0) {
                    form.find('#submitForm').css('display', 'none');
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }

                if (currentIndex === 1) {
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

        var popupWindow = null;
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
            e.preventDefault();
            let errorStatus = sectionValidation("#docListDiv input");
            if (declarationSectionValidation()) errorStatus = true;
            if (isCheckedAcceptTerms()) errorStatus = true;
            if (errorStatus) return false;
            popupWindow = window.open('<?php echo URL::to( '/nix-license-renew/preview' ); ?>', 'Sample', '');

        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;

            $("#doc_type_key").val(key);
            loadApplicationDocs('docListDiv', null);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function () {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            // maxDate: 'now',
            // minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            // maxDate: '01/01/' + (yyyy + 20),
            // minDate: '01/01/1905'
        })

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{-- initail -input mobile plugin script start --}}
        $("#company_office_mobile").intlTelInput({
            hiddenInput: "company_office_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#company_factory_mobile").intlTelInput({
            hiddenInput: "company_factory_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#company_ceo_mobile").intlTelInput({
            hiddenInput: "company_ceo_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#auth_person_mobile").intlTelInput({
            hiddenInput: "auth_person_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        {{-- initail -input mobile plugin script end --}}

    })

    function mobile_no_validation(id) {

        var id = id;
        $("#" + id).on('keyup', function () {

            var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode;

            if (countryCode === "880") {
                var mobile = $("#" + id).val();
                var reg = /^0/;
                if (reg.test(mobile)) {
                    $("#" + id).val("");
                }
                if (mobile.length != 10) {
                    $("#" + id).addClass('error')
                }
            }
        });
    }

    function calculateTotalDollar() {
        var total_fixed_ivst_million = $('#total_fixed_ivst_million').val();
        var usd_exchange_rate = $('#usd_exchange_rate').val();
        var usd_amount = (total_fixed_ivst_million / usd_exchange_rate).toFixed(2);
        document.getElementById('total_invt_dollar').value = usd_amount;
    }

    function calculateInvSourceTaka() {
        var ceo_taka_invest = parseFloat(document.getElementById('ceo_taka_invest').value);
        var local_loan_taka = parseFloat(document.getElementById('local_loan_taka').value);
        var foreign_loan_taka = parseFloat(document.getElementById('foreign_loan_taka').value);
        var total_inv_taka = ((isNaN(ceo_taka_invest) ? 0 : ceo_taka_invest) +
            (isNaN(local_loan_taka) ? 0 : local_loan_taka) +
            (isNaN(foreign_loan_taka) ? 0 : foreign_loan_taka)).toFixed(2);
        document.getElementById('total_inv_taka').value = total_inv_taka;
    }

    function calculateInvSourceDollar() {
        var ceo_dollar_invest = parseFloat(document.getElementById('ceo_dollar_invest').value);
        var local_loan_dollar = parseFloat(document.getElementById('local_loan_dollar').value);
        var foreign_loan_dollar = parseFloat(document.getElementById('foreign_loan_dollar').value);
        var total_inv_dollar = ((isNaN(ceo_dollar_invest) ? 0 : ceo_dollar_invest) +
            (isNaN(local_loan_dollar) ? 0 : local_loan_dollar) +
            (isNaN(foreign_loan_dollar) ? 0 : foreign_loan_dollar)).toFixed(2);
        document.getElementById('total_inv_dollar').value = total_inv_dollar;
    }

    function calculateRawMaterialNumber() {
        var local_raw_material_number = parseFloat(document.getElementById('local_raw_material_number').value);
        var import_raw_material_number = parseFloat(document.getElementById('import_raw_material_number').value);
        var raw_material_total_number = ((isNaN(local_raw_material_number) ? 0 : local_raw_material_number) +
            (isNaN(import_raw_material_number) ? 0 : import_raw_material_number)).toFixed(2);
        document.getElementById('raw_material_total_number').value = raw_material_total_number;
    }

    function calculateRawMaterialPrice() {
        var local_raw_material_price = parseFloat(document.getElementById('local_raw_material_price').value);
        var import_raw_material_price = parseFloat(document.getElementById('import_raw_material_price').value);
        var raw_material_total_price = ((isNaN(local_raw_material_price) ? 0 : local_raw_material_price) +
            (isNaN(import_raw_material_price) ? 0 : import_raw_material_price)).toFixed(2);
        document.getElementById('raw_material_total_price').value = raw_material_total_price;
    }
</script>

<script>
    var selectCountry = '';
    $(document).on('keydown', '#local_wc_ivst_ccy', function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#usd_exchange_rate').focus();
        }
    })
    $(document).on('keydown', '#usd_exchange_rate', function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#ceo_taka_invest').focus();
        }
    })
    $(document).on('change', '.companyInfoChange', function (e) {
        $('#same_address').trigger('change');
    })
    $(document).on('blur', '.companyInfoInput', function (e) {
        $('#same_address').trigger('change');
    })
    $(document).ready(function () {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function () {

            if (this.checked === false) {
                $('#company_factory_div').removeClass('hidden');
                this.checked = false;
                // $('#company_factory_division_id').val($('#company_office_division_id').val())
                // getDistrictByDivisionId('company_factory_division_id', $('#company_office_division_id').val(), 'company_factory_district_id',$('#company_office_district_id').val());
                // getThanaByDistrictId('company_factory_district_id', $('#company_office_district_id').val(), 'company_factory_thana_id', $('#company_office_thana_id').val());
                // // $('#company_factory_thana_id').val($('#company_office_thana_id').val())
                // $('#company_factory_postCode').val($('#company_office_postCode').val())
                // $('#company_factory_address').val($('#company_office_address').val())
                // $('#company_factory_email').val($('#company_office_email').val())
                // $('#company_factory_mobile').val($('#company_office_mobile').val())
                //
                $("#company_factory_division_id").val("");
                $("#company_factory_district_id").val("");
                $("#company_factory_thana_id").val("");
                $("#company_factory_postCode").val("");
                $("#company_factory_address").val("");
                $("#company_factory_email").val("");
                $("#company_factory_mobile").val("");
            } else {
                this.checked = true;
                $('#company_factory_div').addClass('hidden');
                $("#company_factory_division_id").val("").removeClass('error');
                $("#company_factory_district_id").val("").removeClass('error');
                $("#company_factory_thana_id").val("").removeClass('error');
                $("#company_factory_postCode").val("").removeClass('error');
                $("#company_factory_address").val("").removeClass('error');
                $("#company_factory_email").val("").removeClass('error');
                $("#company_factory_mobile").val("").removeClass('error');
            }

        });

        // $("#same_address").trigger('change');

        $("#investment_type_id").change(function () {
            var investment_type_id = $('#investment_type_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-country-by-investment-type') }}",
                data: {
                    investment_type_id: investment_type_id
                },
                success: function (response) {
                    if (investment_type_id == 1) {
                        $('#investing_country_id').attr('multiple', 'multiple');
                        //Select2
                        $("#investing_country_id").select2();
                    } else {
                        if ($("#investing_country_id").data('select2')) {
                            $("#investing_country_id").select2('destroy');
                        }
                        $('#investing_country_id').removeAttr('multiple');
                    }

                    if (investment_type_id == 3) {
                        var option = "";
                    } else {
                        var option =
                            '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    }
                    selectCountry = "{{ $investing_country->country_id ?? '' }}";
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            var repId = (id.replace(' ', ''))
                            if ($.inArray(repId, selectCountry.split(',')) != -1) {
                                option += '<option value="' + repId +
                                    '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + repId + '">' + value +
                                    '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();
                    // multiple if type one
                    // multiple if type one
                    var country_ids =
                        "{{ isset($investing_country->country_id) ? $investing_country->country_id : '' }}";
                    @if (isset($companyInfo->invest_type) ? $companyInfo->invest_type == '1' : '')
                    $('#investing_country_id').val(country_ids.split(',')).change();
                    @else
                    $("#investing_country_id").val(country_ids).change();
                    @endif

                }
            });
        });
        $("#investment_type_id").trigger('change');


        $("#total_investment").trigger('change');

        $("#industrial_sector_id").change(function () {
            var industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function (response) {

                    var option =
                        '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {

                            option += '<option value="' + id + '">' + value +
                                '</option>';
                        });
                    }
                    $("#industrial_sub_sector_id").html(option);
                    @if (isset($companyInfo->ins_sub_sector_id))
                    $("#industrial_sub_sector_id").val(
                        "{{ $companyInfo->ins_sub_sector_id }}").change();
                    @endif
                    $(self).next().hide();
                }
            });
        });

        // $("#industrial_sector_id").trigger('change');

        // Sales (in 100%)
        $("#local_sales_per").on('keyup', function () {
            var local_sales_per = this.value;
            if (local_sales_per <= 100 && local_sales_per >= 0) {
                var cal = 100 - local_sales_per;
                $('#foreign_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        $("#foreign_sales_per").on('keyup', function () {
            var foreign_sales_per = this.value;
            if (foreign_sales_per <= 100 && foreign_sales_per >= 0) {
                var cal = 100 - foreign_sales_per;
                $('#local_sales_per').val(cal);
                // $("#total_sales").val(100);
            } else {
                alert("Please select a value between 0 & 100");
                $('#local_sales_per').val(0);
                $('#foreign_sales_per').val(0);
                // $("#total_sales").val(0);
            }
        });

        //------- Manpower start -------//
        $('#manpower').find('input').keyup(function () {
            var local_male = $('#local_male').val() ? parseFloat($('#local_male').val()) : 0;
            var local_female = $('#local_female').val() ? parseFloat($('#local_female').val()) : 0;
            var local_total = parseInt(local_male + local_female);
            $('#local_total').val(local_total);


            var foreign_male = $('#foreign_male').val() ? parseFloat($('#foreign_male').val()) : 0;
            var foreign_female = $('#foreign_female').val() ? parseFloat($('#foreign_female').val()) :
                0;
            var foreign_total = parseInt(foreign_male + foreign_female);
            $('#foreign_total').val(foreign_total);

            var mp_total = parseInt(local_total + foreign_total);
            $('#mp_total').val(mp_total);

            var mp_ratio_local = parseFloat(local_total / mp_total);
            var mp_ratio_foreign = parseFloat(foreign_total / mp_total);

            //            mp_ratio_local = Number((mp_ratio_local).toFixed(3));
            //            mp_ratio_foreign = Number((mp_ratio_foreign).toFixed(3));

            //---------- code from bida old
            mp_ratio_local = ((local_total / mp_total) * 100).toFixed(2);
            mp_ratio_foreign = ((foreign_total / mp_total) * 100).toFixed(2);
            // if (foreign_total == 0) {
            //     mp_ratio_local = local_total;
            // } else {
            //     mp_ratio_local = Math.round(parseFloat(local_total / foreign_total) * 100) / 100;
            // }
            // mp_ratio_foreign = (foreign_total != 0) ? 1 : 0;
            // End of code from bida old -------------

            $('#mp_ratio_local').val(mp_ratio_local);
            $('#mp_ratio_foreign').val(mp_ratio_foreign);

        });

        LoadListOfDirectors();
    })
</script>
<script>
    function openModal(btn) {
        //e.preventDefault();
        var this_action = btn.getAttribute('data-action');
        if (this_action != '') {
            $.get(this_action, function (data, success) {
                if (success === 'success') {
                    $('#myModal .load_modal').html(data);
                } else {
                    $('#myModal .load_modal').html('Unknown Error!');
                }
                $('#myModal').modal('show', {
                    backdrop: 'static'
                });
            });
        }
    }

    $(document).ready(function () {
        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url('client/company-profile/load-listof-directors-session') }}",
            type: "POST",
            data: {
                {{-- app_id: "{{ Encryption::encodeId($appInfo->id) }}", --}}
                    {{-- process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}", --}}
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                var html = '';
                if (response.responseCode == 1) {

                    var edit_url = "{{ url('/client/company-profile/edit-director') }}";
                    var delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                    var count = 1;
                    $.each(response.data, function (id, value) {
                        var sl = count++;
                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' +
                            edit_url + '/' + id +
                            '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                        if (sl != 1) {
                            html += '<a data-action="' + delete_url + '/' + id +
                                '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if (response.ceoInfo != null) {
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                        if (response.ceoInfo.nationality == 'Bangladeshi') {
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if (response.ceoInfo.identity_type == 'passport') {
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nationality").val('');
                            $("#company_ceo_nid").val('');
                        } else {
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    } else {
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorList tbody').html(html);
            }
        });
    }

    //confirm delete alert
    function ConfirmDelete(btn) {
        var sure_delete = confirm("Are you sure you want to delete?");
        if (sure_delete) {
            var url = btn.getAttribute('data-action');
            $.ajax({
                url: url,
                type: "get",
                success: function (response) {
                    if (response.responseCode == 1) {
                        toastr.success(response.msg);
                    }

                    LoadListOfDirectors();
                }
            });

        } else {
            return false;
        }
    }
</script>

<script>
    function calculateMachineryTotal(className, totalShowFieldId) {
        var total = 0.00;
        $("." + className).each(function () {
            total = total + (this.value ? parseFloat(this.value) : 0.00);
        })
        $("#" + totalShowFieldId).val(total.toFixed(2));
    }
</script>
<script>
    $(document).ready(function () {
        $('#business_category_id').on('change', function () {
            var businessCategoryId = $('#business_category_id').val();
            var oldBusinessCategoryId =
                '{{ isset($companyInfo->business_category_id) ? $companyInfo->business_category_id : '' }}';

            if (businessCategoryId != oldBusinessCategoryId) {
                $('#company_ceo_designation_id').val('');
            } else {
                $('#company_ceo_designation_id').val(
                    '{{ isset($companyInfo->designation) ? $companyInfo->designation : '' }}');
            }
        })
    })
</script>

<script type="text/javascript">
    $(function () {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            // maxDate: 'now',
            // minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).ready(function () {


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


        //add shareholder row
{{--        var rowId = 0;--}}
{{--        $(".addShareholderRow").on('click', function () {--}}
{{--            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);--}}
{{--            $('#shareholderRow').append(--}}
{{--                `<tr id="R_${lastRowId + 1}">--}}
{{--<td>--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-6">--}}

{{--            <div class="form-group row">--}}
{{--                {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">--}}
{{--                    {!! Form::text('shareholder_name[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group row">--}}
{{--                {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">--}}
{{--                    {!! Form::text('shareholder_designation[]', '', ['class' => 'form-control required', 'placeholder' => 'Designation ', 'id' => 'shareholder_designation_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group row">--}}
{{--                {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">--}}
{{--                    {!! Form::text('shareholder_email[]', '', ['class' => 'form-control required', 'placeholder' => 'Email', 'id' => 'shareholder_email_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-group row">--}}
{{--                                                {!! Form::label('shareholder_nid', 'National ID No.', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">--}}
{{--                                                    {!! Form::text('shareholder_nid[]', '', ['class' => 'form-control required', 'placeholder' => 'Nid', 'id' => 'shareholder_nid_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}


{{--</div>--}}


{{--<div class="col-md-6">--}}
{{--<div class="form-group row" style="margin-bottom:0px!important;">--}}
{{--{!! Form::label('shareholder_image', 'Image', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">--}}

{{--                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">--}}
{{--                        <div class="col-md-8">--}}
{{--                            <input type="file" style="border: none; margin-bottom: 5px;" class="form-control input-sm {{ !empty(Auth::user()->user_pic) ? '' : 'required' }}"--}}
{{--                                   name="correspondent_photo[]" id="correspondent_photo_${lastRowId + 1}" size="300x300"--}}
{{--                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${lastRowId + 1}', 'correspondent_photo_base64_${lastRowId + 1}')" />--}}

{{--                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">--}}
{{--                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]--}}
{{--                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>--}}
{{--                                        </span>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4">--}}
{{--                            <label class="center-block image-upload" for="correspondent_photo_${lastRowId + 1}">--}}
{{--                                <figure>--}}
{{--                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"--}}
{{--                                         src="{{asset('assets/images/demo-user.jpg') }}"--}}
{{--                                         class="img-responsive img-thumbnail" id="correspondent_photo_preview_${lastRowId + 1}" />--}}
{{--                                </figure>--}}
{{--                                <input type="hidden" id="correspondent_photo_base64_${lastRowId + 1}" name="correspondent_photo_base64[]" />--}}
{{--                            </label>--}}
{{--                        </div>--}}



{{--                </div>--}}
{{--            </div>--}}
{{--            </div>--}}
{{--            <div class="form-group row">--}}
{{--                {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">--}}
{{--                    {!! Form::text('shareholder_mobile[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}


{{--         <div class="form-group row">--}}
{{--                {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 required-star']) !!}--}}
{{--                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">--}}
{{--                    {!! Form::number('shareholder_share_of[]', '', ['class' => 'form-control shareholder_share_of required', 'placeholder' => '', 'id' => 'shareholder_share_of_${lastRowId+1}']) !!}--}}
{{--                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</td>--}}

{{--</tr>`);--}}

{{--            $("#shareholderDataCount").val(lastRowId + 1);--}}
{{--            var today = new Date();--}}
{{--            var yyyy = today.getFullYear();--}}

{{--            $('.datetimepicker4').datetimepicker({--}}
{{--                format: 'DD-MMM-YYYY',--}}
{{--                maxDate: 'now',--}}
{{--                minDate: '01/01/' + (yyyy - 110),--}}
{{--                ignoreReadonly: true,--}}
{{--            });--}}
{{--        });--}}


{{--        $('#shareholderRow').on('click', '.shareholderRow', function () {--}}
{{--            let prevDataCount = $("#shareholderDataCount").val();--}}

{{--            var child = $(this).closest('tr').nextAll();--}}

{{--            child.each(function () {--}}
{{--                var id = $(this).attr('id');--}}
{{--                var idx = $(this).children('.row-index').children('p');--}}
{{--                var dig = parseInt(id.substring(1));--}}
{{--                idx.html(`Row ${dig - 1}`);--}}
{{--                $(this).attr('id', `R${dig - 1}`);--}}
{{--            });--}}
{{--            $(this).closest('tr').remove();--}}
{{--            rowId--;--}}
{{--            $("#shareholderDataCount").val(prevDataCount - 1);--}}
{{--        });--}}

        //add shareholder row
        //contact person row
        $(".addContactPersonRow").on('click', function () {
            let lastRowId = parseInt($('#contactPersonRow tr:last').attr('id').split('_')[2]);
            $('#contactPersonRow').append(
                `<tr id="cp_r_${lastRowId + 1}">
                    <td>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_name', 'Contact Person', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'contact_name']) !!}
                {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_district[]', $districts, '', ['class' => 'form-control', 'id' => 'contact_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('contact_district_".'${lastRowId+1}'."', this.value, 'contact_thana_".'${lastRowId+1}'."',0)"]) !!}
                {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_thana[]', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_${lastRowId+1}']) !!}
                {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[]', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address']) !!}
                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_mobile[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile']) !!}
                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                    {!! Form::label('contact_image', 'Image', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_image') ? 'has-error' : '' }}">
                                                            <div class="row"
                                                                 style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                <div class="col-md-8">
                                                                    <input type="file" style="border: none; margin-bottom: 5px;" onchange="imagePreview(this,'image_preview_${lastRowId + 1}')"
                                                                           value="{{ $contactData->cntct_prsn_img }}"
                                                                           class="form-control input-sm"
                                                                           name="contact_image[]"
                                                                           id="contact_image_{{$key+1}}"

                                                                           size="300x300"/>

                                                                    <span class="text-success"
                                                                          style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                            <p style="font-size: 12px;"><a target="_blank"
                                                                                           href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="center-block image-upload"
                                                                           for="correspondent_photo_{{$key+1}}">
                                                                        <figure>
                                                                            <img id="image_preview_${lastRowId + 1}"
                                                                                style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                                src="{{ !empty($contactData->cntct_prsn_img) ? asset($contactData->cntct_prsn_img) :  url('assets/images/demo-user.jpg') }}"
                                                                                class="img-responsive img-thumbnail"/>
                                                                        </figure>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                {!! $errors->first('contact_image', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
<div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[]', '', ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'contact_email']) !!}
                {!! $errors->first('contact_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_designation[]', '', ['class' => 'form-control', 'placeholder' => 'Designation', 'id' => 'contact_designation']) !!}
                {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
</td>
</tr>`);

            $("#contactPersonDataCount").val(lastRowId + 1);
        });
        $('#contactPersonRow').on('click', '.contactPersonRow', function () {
            let prevDataCount = $("#contactPersonDataCount").val();

            var child = $(this).closest('tr').nextAll();

            child.each(function () {
                var id = $(this).attr('id');
                var idx = $(this).children('.row-index').children('p');
                var dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#contactPersonDataCount").val(prevDataCount - 1);
        });
        //contact person row


    });
</script>
<script>
    $(document).ready(function () {


        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#passportBlock_' + lastRowId).hide();

            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#passportBlock_' + lastRowId).show();
            }
        });

        $('#type_of_isp_licensese').on('change', function () {
            if (this.value == 1 || this.value == "") {
                $('#division').css('display', 'none');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
            }
            if (this.value == 2) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'none');
                $('#thana').css('display', 'none');
            }

            if (this.value == 3) {
                $('#division').css('display', 'none');
                $('#district').css('display', 'inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">' +
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}' +
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">' +
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}' +
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}' +
                    '</div>' +
                    '</div>');
                $('#thana').css('display', 'none');
            }

            if (this.value == 4) {
                $('#division').css('display', 'inline');
                $('#district').css('display', 'inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">' +
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}' +
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">' +
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}' +
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}' +
                    '</div>' +
                    '</div>');
                $('#thana').css('display', 'inline');
            }
        });

        // $('#company_type').on('change', function () {
        //     if(this.value == ""){
        //         $('.i_we_dynamic').text('');
        //         $('.i_we_dynamic').text('I/We');
        //     }else if(this.value == 2){
        //         $('.i_we_dynamic').text('');
        //         $('.i_we_dynamic').text('I');
        //     }else{
        //         $('.i_we_dynamic').text('');
        //         $('.i_we_dynamic').text('We');
        //     }
        // });

        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

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


        $('.add_row').click(function () {
            var btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
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
                }
            });
        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });


        $("#type_of_isp_licensese").change(function () {
            {{--var total_investment = $('#total_investment').val();--}}
            {{--var vat_percentage = parseFloat('{{ $vat_percentage }}');--}}

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
                    /*$("#industrial_category_id").val(response.data);
                    if (response.data !== "") {
                        $("#industrial_category_id").find("[value!='" + response.data +
                            "']").prop("disabled", true);
                        $("#industrial_category_id").find("[value='" + response.data + "']")
                            .prop("disabled", false);
                    }*/
                    if (response.responseCode == -1) {
                        alert(response.msg);
                        return false;
                    }

                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function () {
                    {{--console.log({{ $process_type_id }});--}}
                    var unfixed_amounts = {
                        1: 0,
                        2: oss_fee,
                        3: 0,
                        4: 0,
                        5: vat,
                        6: 0
                    };
                    loadPaymentPanel('', '{{ $process_type_id }}', '1',
                        'payment_panel',
                        "{{ CommonFunction::getUserFullName() }}",
                        "{{ Auth::user()->user_email }}",
                        "{{ Auth::user()->user_mobile }}",
                        "{{ Auth::user()->contact_address }}",
                        unfixed_amounts);
                }
            });
        });
    });

    function openPreview() {
        let errorStatus = sectionValidation("#docListDiv input");
        if (declarationSectionValidation()) errorStatus = true;
        if (isCheckedAcceptTerms()) errorStatus = true;
        if (errorStatus) return false;
        window.open('<?php echo URL::to( '/nix-license-renew/preview' ); ?>');
    }

    function imagePreview(input, img_preview_id) {

        if (input.files && input.files[0]) {
            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                input.value = '';
                alert('Image format is not valid. Only PNG or JPEG or JPG type images are allowed.');
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(img_preview_id).setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById(base64_value_target).value = '';
            document.getElementById(img_preview_id).setAttribute('src', '<?php echo e( url( 'assets/images/no_image.png' ) ); ?>');
        }
    }

    function declarationSectionValidation() {
        let error_status = false;
        if ($("#declaration_q1_yes").is(":checked") && $("#declaration_q1_text").val() === "") {
            $("#declaration_q1_text").addClass('error');
            error_status = true;
        } else {
            $("#declaration_q1_text").removeClass('error');
        }

        if ($("#declaration_q2_yes").is(":checked") && $("#declaration_q2_text").val() === "") {
            $("#declaration_q2_text").addClass('error');
            error_status = true;
        } else {
            $("#declaration_q2_text").removeClass('error');
        }

        if ($("#declaration_q3_yes").is(":checked") && $("#declaration_q3_text").val() === "") {
            $("#declaration_q3_text").addClass('error');
            error_status = true;
        } else {
            $("#declaration_q3_text").removeClass('error');
        }

        return error_status;
    }

    function sectionValidation(selector) {
        const requiredClientFields = document.querySelectorAll(selector);
        let errorStatus = false;
        for (const elem of requiredClientFields) {
            if (elem.classList.contains('required') && !elem.value) {
                elem.classList.add('error');
                errorStatus = true;
            }
        }
        return errorStatus;
    }

    function isCheckedAcceptTerms() {
        let errorStatus = false;
        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            errorStatus = true;
        } else {
            $('#accept_terms').removeClass('error');
            errorStatus = false;
        }
        return errorStatus;
    }
</script>
