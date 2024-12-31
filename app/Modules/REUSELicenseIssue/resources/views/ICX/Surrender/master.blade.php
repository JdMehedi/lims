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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
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

    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        /*font-weight: bold !important;*/
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application for Interconnection Exchange (ICX) Services License Surrender</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('icx-license-issue/store'),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd">

                </div>
                <h3>Basic Information</h3>
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])

                    {{-- Company Informaiton --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Company/Organization Informaiton
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('company_name', 'Company/ Organization  Name', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                                            {!! Form::text('company_name', isset($companyInfo->org_nm) ? $companyInfo->org_nm : '',
                                                ['class' => 'form-control',
                                                'readonly' => isset($companyInfo->org_nm) ?? 'readonly',
                                                'placeholder' => 'Enter Company/Organization', 'id' => 'company_name']) !!}
                                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                                            {!! Form::select('company_type', [''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'], isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control',
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
                                                    {!! Form::select('reg_office_district', $districts, '', ['class' => 'form-control', 'id' => 'reg_office_district', 'onchange' => "getThanaByDistrictId('reg_office_district', this.value, 'reg_office_thana',0)"]) !!}
                                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('reg_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('reg_office_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select('reg_office_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'reg_office_thana']) !!}
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
                                                    {!! Form::text('reg_office_address', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'reg_office_address']) !!}
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
                                                    {!! Form::select('permanent_office_district', $districts, '', ['class' => 'form-control', 'id' => 'permanent_office_district', 'onchange' => "getThanaByDistrictId('permanent_office_district', this.value, 'permanent_office_thana',0)"]) !!}
                                                    {!! $errors->first('reg_office_district', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('permanent_office_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                                <div
                                                    class="col-md-8 {{ $errors->has('permanent_office_thana') ? 'has-error' : '' }}">
                                                    {!! Form::select('permanent_office_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select', 'id' => 'permanent_office_thana']) !!}
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
                                                    {!! Form::text('permanent_office_address', '', ['class' => 'form-control ', 'placeholder' => 'Enter  Address', 'id' => 'permanent_office_address']) !!}
                                                    {!! $errors->first('permanent_office_address', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>



                        </div>
                    </div>
                    {{-- Applicant Person --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Applicant Profile
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_name', 'Name of Applicant', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_name', '', ['class' => 'form-control ', 'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_mobile_no', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_mobile_no') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_mobile_no', '', ['class' => 'form-control ', 'placeholder' => 'Mobile No', 'id' => 'applicant_mobile_no', 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                            {!! $errors->first('applicant_mobile_no', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_telephone_no', 'Telephone No.', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_telephone_no') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_telephone_no', '', ['class' => 'form-control ', 'placeholder' => 'Enter the Telephone No.', 'id' => 'applicant_telephone_no']) !!}
                                            {!! $errors->first('applicant_telephone_no', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_email', '', ['class' => 'form-control ', 'placeholder' => 'Enter the Email', 'id' => 'applicant_email']) !!}
                                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control ', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select', 'id' => 'applicant_thana']) !!}
                                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_address', '', ['class' => 'form-control ', 'placeholder' => 'Enter The Address', 'id' => 'applicant_address']) !!}
                                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_website', '', ['class' => 'form-control', 'placeholder' => 'Enter The Website', 'id' => 'applicant_website']) !!}
                                            {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                    {{--  Contact Person --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Contact Person
                            {{--                            <span style="float: right; cursor: pointer;" class="addContactPersonRow">--}}
                            {{--                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
                            {{--                            </span>--}}
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                                   id="contactPersonRow">
                                <input type="hidden" id="contactPersonDataCount" name="contactPersonDataCount"
                                       value="1"/>
                                <tr id="cp_r_1">
                                    <td>
                                        <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_1">
                                            <div class="card-header">
                                                Contact Person Information
                                                <span style="float: right; cursor: pointer;" class="addContactPersonRow">
                                                    <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_name_of_authorized_signatory', 'Name of Authorized Signatory', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_name_of_authorized_signatory') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_name_of_authorized_signatory[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Name of Authorized Signatory', 'id' => 'contact_name_of_authorized_signatory_0']) !!}
                                                                {!! $errors->first('contact_name_of_authorized_signatory', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_name', 'Name', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_name[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name']) !!}
                                                                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_designation[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Designation of Entity', 'id' => 'contact_designation_0']) !!}
                                                                {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_mobile[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_0']) !!}
                                                                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_email', 'Email', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_email[]', '', ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'contact_person_email']) !!}
                                                                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                                {!! Form::select('contact_district[]', $districts, '', ['class' => 'form-control', 'id' => 'contact_district_0', 'onchange' => "getThanaByDistrictId('contact_district_0', this.value, 'contact_thana_0',0)"]) !!}
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
                                                                {!! Form::select('contact_thana[]', [], '', ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'contact_thana_0']) !!}
                                                                {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_address[]', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_0']) !!}
                                                                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_image', 'Photograph', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('contact_image') ? 'has-error' : '' }}">
                                                                <div class="row"
                                                                     style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                    <div class="col-md-8">
                                                                        <input type="file"
                                                                               value=""
                                                                               style="border: none; margin-bottom: 5px;"
                                                                               class="form-control input-sm"
                                                                               name="contact_image[]"
                                                                               id="contact_image_0"
                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_contact_preview_0', 'correspondent_contact_photo_base64_0')"
                                                                               size="300x300"/>
                                                                        <span class="text-success"
                                                                              style="font-size: 9px; font-weight: bold; display: block;">
                                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                                </span>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="center-block image-upload"
                                                                               for="correspondent_photo_0">
                                                                            <figure>
                                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                                     class="img-responsive img-thumbnail"
                                                                                     id="correspondent_photo_contact_preview_0" />
                                                                            </figure>
                                                                            <input type="hidden" id="correspondent_contact_photo_base64_0"
                                                                                   name="correspondent_contact_photo_base64[]" />
                                                                        </label>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>


                    {{-- Shareholder/partner/proprietor Details --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Shareholder/partner/proprietor Details
                            {{--                            <span style="float: right; cursor: pointer;" class="addShareholderRow">--}}
                            {{--                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>--}}
                            {{--                            </span>--}}
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table-responsive" style="width: 100%;     display: inline-table!important;" id="shareholderRow">
                                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                                <tr id="r_1">
                                    <td>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Shareholder/partner/proprietor Details Information
                                                <span style="float: right; cursor: pointer;" class="addShareholderRow">
                                                     <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                                {!! Form::select('shareholder_nationality[]', $nationality, '', ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'shareholder_nationality_0']) !!}
                                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nid', 'National ID No.', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_nid[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter National ID Number', 'id' => 'shareholder_nid_0']) !!}
                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                                {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Shareholder/Partner Date of Birth', 'id' => 'shareholder_dob_0']) !!}
                                                                {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_name[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Shareholder/Partner Date of Birth', 'id' => 'shareholder_name_0']) !!}
                                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_designation[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Designation of Entity', 'id' => 'shareholder_designation_0']) !!}
                                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_mobile[]', '', ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id' => 'shareholder_mobile_0']) !!}
                                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_email[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Email', 'id' => 'shareholder_email_0']) !!}
                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                                {!! Form::number('shareholder_share_of[]', '', ['class' => 'form-control required shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_0']) !!}
                                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_image', 'Photograph', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                                <div class="row"
                                                                     style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                    <div class="col-md-8">
                                                                        <input type="file"
                                                                               value=""
                                                                               style="border: none; margin-bottom: 5px;"
                                                                               class="form-control input-sm"
                                                                               name="shareholder_image[]"
                                                                               id="shareholder_image_0"
                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_0', 'correspondent_photo_base64_0')"
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
                                                                               for="correspondent_photo_0">
                                                                            <figure>
                                                                                <img id="correspondent_photo_preview_0"
                                                                                     style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                                     src="{{   url('assets/images/demo-user.jpg') }}"
                                                                                     class="img-responsive img-thumbnail"/>
                                                                            </figure>
                                                                            <input type="hidden" id="correspondent_photo_base64_0"
                                                                                   name="correspondent_photo_base64[]" />
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </fieldset>

                <h3>Attachment & Declaration</h3>
                <br>
                <fieldset>

                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Required Documents for attachment
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
                                                Has any application for any license of the applicant/any share holder/partner been rejected before?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div id="declaration_1">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            {!! Form::label('declaration_date_of_application', 'Date of Application', ['class' => 'required-star']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div
                                                            class="{{ $errors->has('declaration_date_of_application') ? 'has-error' : '' }}">
                                                            {!! Form::date('declaration_date_of_application', '', ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                            {!! $errors->first('declaration_date_of_application', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    {!! Form::textarea('declaration_q1_textarea',null, ['class' =>'form-control input required', 'rows' => '5', 'placeholder' => 'Please give detail reasons for rejection.']) !!}
                                                </div>
                                            </div>

                                        </li>
                                        <br/>
                                        <li class="required-star !font-normal">
                                            <label class="!font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the Commission?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div id="declaration_2">
                                                <div class="row">
                                                    {!! Form::textarea('declaration_q2_textarea',null, ['class' =>'form-control input required', 'rows' => '5', 'placeholder' => 'Please give detail reasons for rejection.']) !!}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <label class="!font-normal">
                                                Were the Applicants/its owner(s)/ any of its director(s)/ partner(s) involved in any illegal call termination?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div id="declaration_3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row">
                                                            {!! Form::label('declaration_q3_date_of_application', 'Date of Application', ['class' => 'required-star']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div
                                                            class="{{ $errors->has('declaration_q3__date_of_application') ? 'has-error' : '' }}">
                                                            {!! Form::date('declaration_q3__date_of_application', '', ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                            {!! $errors->first('declaration_q3__date_of_application', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    {!! Form::textarea('declaration_q3_textarea',null, ['class' =>'form-control input required', 'rows' => '5', 'placeholder' => 'Please give detail reasons for rejection.']) !!}
                                                </div>
                                            </div>
                                        </li>
                                        <br/>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any other License of the Applicant/any Share Holder Partner been rejected before?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q4', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}
                                                {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q4', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}
                                                {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div id="declaration_4">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div>
                                                            {!! Form::label('declaration_q4_period_of_indestment', 'Period of Involvement in illegal activitie', ['class' => 'col-md-12 required-star']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div
                                                            class="form-group {{ $errors->has('declaration_q4_period_of_involvement') ? 'has-error' : '' }}">
                                                            {!! Form::text('declaration_q4_period_of_involvement', '', ['class' => 'form-control', 'placeholder' => 'Enter Period of Involvement in illegal activities', 'id' => 'declaration_q4_period_of_indestment']) !!}
                                                            {!! $errors->first('declaration_q4_period_of_involvement', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 align-center" style="padding-left: 40px;">
                                                        <div class="form-group">
                                                            {!! Form::label('declaration_q4_case_no', 'Case No', ['class' => 'required-star']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div
                                                            class="{{ $errors->has('declaration_q4_case_no') ? 'has-error' : '' }}">
                                                            {!! Form::text('declaration_q4_case_no', '', ['class' => 'form-control', 'placeholder' => 'Enter Case Number', 'id' => 'declaration_q5_case_no']) !!}
                                                            {!! $errors->first('declaration_q4_case_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Fine paid to the Commission</legend>
                                                    <div class="row" style="padding-top: 20px;">
                                                        <div class="col-md-2">
                                                            <div>
                                                                {!! Form::label('declaration_q4_amount', 'Amount', ['class' => 'col-md-12']) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div
                                                                class="form-group {{ $errors->has('declaration_q4_amount') ? 'has-error' : '' }}">
                                                                {!! Form::text('declaration_q4_amount', '', ['class' => 'form-control', 'placeholder' => 'Enter Licence Number', 'id' => 'declaration_q4_amount']) !!}
                                                                {!! $errors->first('declaration_q4_amount', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="padding-left: 30px;">
                                                            <div class="form-group">
                                                                {!! Form::label('declaration_q4_cheque_or_bank_draft', 'Cheque No./ Bank Draft No', ['class' => 'required-star']) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div
                                                                class="form-group {{ $errors->has('declaration_q4_cheque_or_bank_draft') ? 'has-error' : '' }}">
                                                                {!! Form::text('declaration_q4_cheque_or_bank_draft', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'declaration_q4_cheque_or_bank_draft']) !!}
                                                                {!! $errors->first('declaration_q4_cheque_or_bank_draft', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <labe>Undertaking given to the Commission</labe>
                                                    </div>
                                                    <div class="col-md-4">
                                                        {{ Form::radio('declaration_q4_2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_2_yes']) }}
                                                        {{ Form::label('declaration_q4_2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                        {{ Form::radio('declaration_q4_2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_2_no']) }}
                                                        {{ Form::label('declaration_q4_2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <br/>
                                        <li>I/We hereby certify that I/We have carefully read the guidelines/terms and conditions for the License and I/We undertake to comply with the terms and conditions therein.</li><br/>
                                        <li>I/We also hereby certify that I/We have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 (as amended) and I/We are not disqualified from obtaining the License</li><br/>
                                        <li>I/We understand that if at any time any information furnished for obtaining the License is found incorrect then the License if granted on the basis of such

                                            application shall be deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001(as amended).</li><br/>
                                    </ol>
                                    <div class="row">
                                        <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Note
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                                Application without the submission of complete documents and information will not be accepted. Payment should be made by a Pay order/ Demand Draft in favor of
                                                Bangladesh Telecommunication Regulatory Commission (BTRC).Application fee is not refundable. Application will not be accepted if provided information do not fulfill the relevant terms and conditions of the Commission issued at various time.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h3>Payment & Submit</h3>
                <br>
                <fieldset>

                    {{-- Service Fee Payment--}}
                    <div id="payment_panel"></div>

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
                    <a href="{{ url('client/icx-license-cancellation/list/'. Encryption::encodeId(36)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn"
                        id="save_as_draft">Save as Draft
                </button>
                {{--                <div class="float-right">--}}
                {{--                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"--}}
                {{--                        value="submit" name="actionBtn" onclick="openPreview()">Submit--}}
                {{--                    </button>--}}
                {{--                </div>--}}

                {{--                <div class="float-right">--}}
                {{--                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"--}}
                {{--                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit--}}
                {{--                    </button>--}}
                {{--                </div>--}}

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
                        Terms and Conditions <span style="float: right;"><button type="button" class="close" data-dismiss="modal" style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ul>
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
    // function enablePath() {
    //     document.getElementById('company_type_id').disabled = "";
    //     document.getElementById('company_office_division_id').disabled = "";
    //     document.getElementById('company_office_district_id').disabled = "";
    //     document.getElementById('company_office_thana_id').disabled = "";
    // }

    var selectCountry = '';

    $(document).ready(function() {

        {{--        @isset($companyInfo->office_division)--}}
        {{--            getDistrictByDivisionId('company_office_division_id', {{ $companyInfo->office_division ?? '' }},--}}
        {{--                'company_office_district_id', {{ $companyInfo->office_district ?? '' }});--}}
        {{--        @endisset--}}
        {{--        @isset($companyInfo->office_district)--}}
        {{--            getThanaByDistrictId('company_office_district_id', {{ $companyInfo->office_district ?? '' }},--}}
        {{--                'company_office_thana_id', {{ $companyInfo->office_thana ?? '' }});--}}
        {{--        @endisset--}}


        {{--        @isset($companyInfo->factory_division)--}}
        {{--            getDistrictByDivisionId('company_factory_division_id',--}}
        {{--                {{ $companyInfo->factory_division ?? '' }},--}}
        {{--                'company_factory_district_id', {{ $companyInfo->factory_district ?? '' }});--}}
        {{--        @endisset--}}
        {{--        @isset($companyInfo->factory_district)--}}
        {{--            getThanaByDistrictId('company_factory_district_id', {{ $companyInfo->factory_district ?? '' }},--}}
        {{--                'company_factory_thana_id', {{ $companyInfo->factory_thana ?? '' }});--}}
        {{--        @endisset--}}

        {{--        // ceo section--}}
        {{--        @isset($companyInfo->ceo_division)--}}
        {{--            getDistrictByDivisionId('company_ceo_division_id', {{ $companyInfo->ceo_division ?? '' }},--}}
        {{--                'company_ceo_district_id', {{ $companyInfo->ceo_district ?? '' }});--}}
        {{--        @endisset--}}
        {{--        @isset($companyInfo->ceo_district)--}}
        {{--            getThanaByDistrictId('company_ceo_district_id', {{ $companyInfo->ceo_district ?? '' }},--}}
        {{--                'company_ceo_thana_id', {{ $companyInfo->ceo_thana ?? '' }});--}}
        {{--        @endisset--}}

        // loadApplicationDocs('docListDiv', 'stable_small_forgen');

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

                    var total=0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
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


                //Modified Section for design


                if (newIndex == 2) {

                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                if($("#declaration_q4_yes").is(":checked") || $("#declaration_q4_no").is(":checked")){
                                    if($("#declaration_q4_yes").is(":checked")){
                                        if($("#declaration_q4_2_yes").is(":checked") || $("#declaration_q4_2_no").is(":checked")){
                                            return true;
                                        }else{
                                            new swal({
                                                type: 'error',
                                                text: 'Please answer the Declaration section all question.',
                                            });
                                            return false;
                                        }
                                    }
                                }else{
                                    new swal({
                                        type: 'error',
                                        text: 'Please answer the Declaration section all question.',
                                    });
                                    return false;
                                }

                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                return false;
                            }
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            return false;
                        }
                    }else{
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
                    form.find('#submitForm').css('display', 'none');

                }
                // Used to skip the "Warning" step if the user is old enough.
                if (currentIndex === 2) {
                    form.find('#submitForm').css('display', 'inline');
                    form.steps("next");
                }
                else {
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
            ignore: [],
            rules: {
                reg_office_district: "required",
                'contact_name_of_authorized_signatory[]': "required",
                reg_office_address: "required",
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });


        // declaration show/hide
        $("#declaration_1").hide();
        $("#declaration_2").hide();
        $("#declaration_3").hide();
        $("#declaration_4").hide();

        $(document).on("click", ".form-check-input", function(){
            if($("input[name='declaration_q4']:checked").val() === 'Yes'){
                $("#declaration_4").show();
            }
            if($("input[name='declaration_q3']:checked").val() === 'Yes'){
                $("#declaration_3").show();
            }

            if($("input[name='declaration_q2']:checked").val() === 'Yes'){
                $("#declaration_2").show();
            }

            if($("input[name='declaration_q1']:checked").val() === 'Yes'){
                $("#declaration_1").show();
            }

            if($("input[name='declaration_q4']:checked").val() === 'No'){
                $("#declaration_4").hide();
            }
            if($("input[name='declaration_q3']:checked").val() === 'No'){
                $("#declaration_3").hide();
            }

            if($("input[name='declaration_q2']:checked").val() === 'No'){
                $("#declaration_2").hide();
            }

            if($("input[name='declaration_q1']:checked").val() === 'No'){
                $("#declaration_1").hide();
            }
        });





        var popupWindow = null;
        $('#submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")){
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
            popupWindow = window.open('<?php echo URL::to('/icx-license-issue/preview'); ?>', 'Sample', '');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);
            console.log(key);
            loadApplicationDocs('docListDiv', key);
        }
        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function() {

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: '01/01/' + (yyyy + 20),
            minDate: '01/01/1905'
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
        $("#" + id).on('keyup', function() {

            var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode;

            if (countryCode === "880") {
                console.log('inside country code.');
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
    $(document).on('keydown', '#local_wc_ivst_ccy', function(e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#usd_exchange_rate').focus();
        }
    })
    $(document).on('keydown', '#usd_exchange_rate', function(e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#ceo_taka_invest').focus();
        }
    })
    $(document).on('change', '.companyInfoChange', function(e) {
        $('#same_address').trigger('change');
    })
    $(document).on('blur', '.companyInfoInput', function(e) {
        $('#same_address').trigger('change');
    })
    $(document).ready(function() {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function() {

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

        $("#investment_type_id").change(function() {
            var investment_type_id = $('#investment_type_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-country-by-investment-type') }}",
                data: {
                    investment_type_id: investment_type_id
                },
                success: function(response) {
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
                        $.each(response.data, function(id, value) {
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

        $("#industrial_sector_id").change(function() {
            var industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function(response) {

                    var option =
                        '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function(id, value) {

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
        $("#local_sales_per").on('keyup', function() {
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

        $("#foreign_sales_per").on('keyup', function() {
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
        $('#manpower').find('input').keyup(function() {
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
            $.get(this_action, function(data, success) {
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

    $(document).ready(function() {
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
            success: function(response) {
                var html = '';
                if (response.responseCode == 1) {

                    var edit_url = "{{ url('/client/company-profile/edit-director') }}";
                    var delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                    var count = 1;
                    $.each(response.data, function(id, value) {
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
                success: function(response) {
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
        $("." + className).each(function() {
            total = total + (this.value ? parseFloat(this.value) : 0.00);
        })
        $("#" + totalShowFieldId).val(total.toFixed(2));
    }
</script>
<script>
    $(document).ready(function() {
        $('#business_category_id').on('change', function() {
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
    $(function() {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).ready(function() {


        $("#declaration_q1_yes").on('click', function() {
            $('#if_declaration_q1_yes').css('display','inline');
        });
        $("#declaration_q1_no").on('click', function() {
            $('#if_declaration_q1_yes').css('display','none');
        });

        $("#declaration_q2_yes").on('click', function() {
            $('#if_declaration_q2_yes').css('display','inline');
        });
        $("#declaration_q2_no").on('click', function() {
            $('#if_declaration_q2_yes').css('display','none');
        });

        $("#declaration_q3_yes").on('click', function() {
            $('#if_declaration_q3_yes').css('display','inline');
        });
        $("#declaration_q3_no").on('click', function() {
            $('#if_declaration_q3_yes').css('display','none');
        });



        //add shareholder row
        var rowId = 0;
        $(".addShareholderRow").on('click', function() {
            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);
            $('#shareholderRow').append(
                //newly added

                `<tr id="r_${lastRowId+1}">
                                    <td>
                  <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Shareholder/partner/proprietor Details Information
                                                <span style="float: right; cursor: pointer;">
                                                     <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                    {!! Form::select('shareholder_nationality[]', $nationality, '', ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'shareholder_nationality_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_nid', 'National ID No.', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_nid[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter National ID Number', 'id' => 'shareholder_nid_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_dob[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Shareholder/Partner Date of Birth', 'id' => 'shareholder_dob_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_name[]', '', ['class' => 'form-control required', 'placeholder' => 'Enter Shareholder/Partner Date of Birth', 'id' => 'shareholder_name_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_designation[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Designation of Entity', 'id' => 'shareholder_designation_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_mobile[]', '', ['class' => 'form-control', 'placeholder' => 'Mobile Number', 'id' => 'shareholder_mobile_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                    {!! Form::text('shareholder_email[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Email', 'id' => 'shareholder_email_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                    {!! Form::number('shareholder_share_of[]', '', ['class' => 'form-control required shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('shareholder_image', 'Photograph', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                    <div class="row"
                                                         style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                        <div class="col-md-8">
                                                            <input type="file"
                                                                   style="border: none; margin-bottom: 5px;"
                                                                   value=""
                                                                   class="form-control input-sm"
                                                                   name="shareholder_image[]"
                                                                   id="shareholder_image_${lastRowId+1}"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${lastRowId+1}', 'correspondent_photo_base64_${lastRowId+1}')"
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
                                                                   for="correspondent_photo_${lastRowId+1}">
                                                                <figure>
                                                                    <img id="correspondent_photo_preview_${lastRowId+1}"
                                                                         style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                         src="{{   url('assets/images/demo-user.jpg') }}"
                                                                         class="img-responsive img-thumbnail"/>
                                                                </figure>
                                                                <input type="hidden" id="correspondent_photo_base64_${lastRowId+1}" name="correspondent_photo_base64[]" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    </td>
                                </tr>`

            );

            $("#shareholderDataCount").val(lastRowId+1);
            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });


        $('#shareholderRow').on('click', '.shareholderRow', function () {
            let prevDataCount = $("#shareholderDataCount").val();

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
            $("#shareholderDataCount").val(prevDataCount - 1);
        });

        //contact person row
        $(".addContactPersonRow").on('click', function () {
            let lastRowId = parseInt($('#contactPersonRow tr:last').attr('id').split('_')[2]);
            $('#contactPersonRow').append(
                // last updated
                `<tr id="cp_r_${lastRowId + 1}">
                                    <td>
                                    <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_1">
                                <div class="card-header">
                                    Contact Person Information
                                    <span style="float: right; cursor: pointer;">
                                        <button type="button" class="btn btn-danger btn-sm contactPersonRow cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                    </span>
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_name_of_authorized_signatory', 'Name of Authorized Signatory', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_name_of_authorized_signatory') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_name_of_authorized_signatory[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Name of Authorized Signatory', 'id' => 'contact_name_of_authorized_signatory_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_name', 'Name', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Name of Entity', 'id' => 'contact_person_name_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_designation[]', '', ['class' => 'form-control', 'placeholder' => 'Enter The Designation of Entity', 'id' => 'contact_designation_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_mobile', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_mobile[]', '', ['class' => 'form-control', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_email', 'Email', ['class' => 'col-md-4']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[]', '', ['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'contact_person_email_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_district[]', $districts, '', ['class' => 'form-control', 'id' => 'contact_district_${lastRowId + 1}', 'onchange' => "getThanaByDistrictId('contact_district_".'${lastRowId+1}'."', this.value, 'contact_thana_".'${lastRowId+1}'."',0)"]) !!}
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
                                                        {!! Form::select('contact_thana[]', [], '', ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'contact_thana_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_address', 'Address', ['class' => 'col-md-4 required-star']) !!}
                <div
                    class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[]', '', ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${lastRowId + 1}']) !!}
                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_image', 'Photograph', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_image') ? 'has-error' : '' }}">
                                                        <div class="row"
                                                             style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                            <div class="col-md-8">
                                                                <input type="file"
                                                                       style="border: none; margin-bottom: 5px;"
                                                                       value=""
                                                                       class="form-control input-sm"
                                                                       name="contact_image[]"
                                                                       id="contact_image_${lastRowId + 1}"
                                                                       size="300x300"
                                                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_contact_preview_${lastRowId + 1}', 'correspondent_contact_photo_base64_${lastRowId + 1}')"
                                                                       />
                                                                <span class="text-success"
                                                                      style="font-size: 9px; font-weight: bold; display: block;">
                                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 MB]
                                                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                        </span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="center-block image-upload"
                                                                         for="correspondent_photo_${lastRowId + 1}">
                                                                         <figure>
                                                                             <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                             class="img-responsive img-thumbnail"
                                                                            id="correspondent_photo_contact_preview_${lastRowId + 1}" />
                                                                        </figure>
                                                                         <input type="hidden" id="correspondent_contact_photo_base64_${lastRowId + 1}"
                                                                       name="correspondent_contact_photo_base64[]" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                    </td>
                                </tr>`
            );

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
    $(document).ready(function() {

        console.log('Inside the function');

        $(document).on('change','.nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#passportBlock_'+lastRowId).hide();

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).show();
            }
        });

        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }

            if(this.value == 3){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
            }

            if(this.value == 4){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4 required-star']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');
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
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 2){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        // $('#pay_amount').val(1000);
        // $('#trnx_charge_amount').val(9.93);
        // $('#trnx_charge_amount_vat').val(1.55);
        //
        // var pay_amount = $('#pay_amount').val();
        //
        // if(pay_amount != ""){
        //     var pay_amount_vat = (15/100*pay_amount);
        //     $('#pay_amount_vat').val(pay_amount_vat);
        // }
        // var pay_amount_vat = $('#pay_amount_vat').val();
        // var trnx_charge_amount = $('#trnx_charge_amount').val();
        // var trnx_charge_amount_vat = $('#trnx_charge_amount_vat').val();
        //
        // var totalValue = 0;
        // totalValue = (parseFloat(pay_amount)+parseFloat(pay_amount_vat)+parseFloat(trnx_charge_amount)+parseFloat(trnx_charge_amount_vat));
        // console.log(totalValue);
        //
        // $('#total_amount').val(parseInt(totalValue));


        $('.add_row').click(function(){
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
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                }
            });
        });

        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        //Not in my module
        {{--$("#type_of_isp_licensese").change(function() {--}}
        {{--    --}}{{--var total_investment = $('#total_investment').val();--}}
        {{--    --}}{{--var vat_percentage = parseFloat('{{ $vat_percentage }}');--}}

        {{--    let oss_fee = 0;--}}
        {{--    let vat = 0;--}}

        {{--    $.ajax({--}}
        {{--        type: "POST",--}}
        {{--        url: "{{ url('isp-license-issue/get-payment-data-by-license-type') }}",--}}
        {{--        data: {--}}
        {{--            process_type_id: {{ $process_type_id }},--}}
        {{--            payment_type: 1,--}}
        {{--            license_type: $(this).val()--}}
        {{--        },--}}
        {{--        success: function(response) {--}}
        {{--            /*$("#industrial_category_id").val(response.data);--}}
        {{--            if (response.data !== "") {--}}
        {{--                $("#industrial_category_id").find("[value!='" + response.data +--}}
        {{--                    "']").prop("disabled", true);--}}
        {{--                $("#industrial_category_id").find("[value='" + response.data + "']")--}}
        {{--                    .prop("disabled", false);--}}
        {{--            }*/--}}
        {{--            if(response.responseCode == -1){--}}
        {{--                alert(response.msg);--}}
        {{--                return false;--}}
        {{--            }--}}

        {{--            oss_fee = parseFloat(response.data.oss_fee);--}}
        {{--            vat = parseInt(response.data.vat);--}}
        {{--        },--}}
        {{--        complete: function() {--}}
        {{--            console.log({{ $process_type_id }});--}}
        {{--            var unfixed_amounts = {--}}
        {{--                1: 0,--}}
        {{--                2: oss_fee,--}}
        {{--                3: 0,--}}
        {{--                4: 0,--}}
        {{--                5: vat,--}}
        {{--                6: 0--}}
        {{--            };--}}
        {{--            loadPaymentPanel('', '{{ $process_type_id }}', '1',--}}
        {{--                'payment_panel',--}}
        {{--                "{{ CommonFunction::getUserFullName() }}",--}}
        {{--                "{{ Auth::user()->user_email }}",--}}
        {{--                "{{ Auth::user()->user_mobile }}",--}}
        {{--                "{{ Auth::user()->contact_address }}",--}}
        {{--                unfixed_amounts);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
    });


    //Getting payment panel information for the user.
    var unfixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
    loadPaymentPanel('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        unfixed_amounts);

    {{--function openPreview() {--}}
    {{--    window.open('<?php echo URL::to('/icx-license-issue/preview'); ?>');--}}
    {{--}--}}

    function openPreviewV2() {
        console.log('Inside openPreviewV2');
        window.open('<?php echo URL::to('/icx-license-issue/preview'); ?>');
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
            document.getElementById(img_preview_id).setAttribute('src', '<?php echo e(url('assets/images/no_image.png')); ?>');
        }
    }
</script>
