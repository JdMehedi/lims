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

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header" style="font-size: 24px; font-weight: 400">Application for Nationwide Telecommunication Transmission Network (NTTN) Surrender</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('nttn-license-issue/store'),
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
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])
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
                                            <?php
                                            $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                                            ?>
                                            {!! Form::select('company_type', $companyType, isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control',
                                            'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                                            'id' => 'company_type']) !!}
                                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            {{-- Registered Office Address --}}
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Registered Office Address
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
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
                                                    {!! Form::select('reg_office_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'reg_office_thana']) !!}
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

                            {{-- Operational Office Address --}}
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Operational Office Address
                                    <span style="float: right; cursor: pointer;" class="m-l-auto" id="permanentSameAsRegisterdAddressSec">
                                        {!! Form::checkbox('permanentSameAsRegisterdAddress', 'YES', false, ['id'=> 'permanentSameAsRegisterdAddress']) !!}
                                        {!! Form::label('permanentSameAsRegisterdAddress', 'Same as Registered office address') !!}
                                    </span>
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
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
                                                    {!! Form::select('permanent_office_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select district at first', 'id' => 'permanent_office_thana']) !!}
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
                                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_name', '', ['class' => 'form-control ', 'placeholder' => 'Enter Name', 'id' => 'applicant_name']) !!}
                                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_mobile_no', 'Mobile Number', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_mobile_no') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_mobile_no', '', ['class' => 'form-control ', 'placeholder' => 'Enter Mobile No', 'id' => 'applicant_mobile_no']) !!}
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
                                            {!! Form::text('applicant_telephone_no', '', ['class' => 'form-control ', 'placeholder' => 'Enter Telephone No.', 'id' => 'applicant_telephone_no']) !!}
                                            {!! $errors->first('applicant_telephone_no', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_email', '', ['class' => 'form-control ', 'placeholder' => 'Email', 'id' => 'applicant_email']) !!}
                                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_district', $districts, '', ['class' => 'form-control ', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_thana', 'Upazila / Thana', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control ', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
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
                                            {!! Form::text('applicant_address', '', ['class' => 'form-control ', 'placeholder' => 'Enter  Address', 'id' => 'applicant_address']) !!}
                                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_website', '', ['class' => 'form-control url', 'placeholder' => 'Website', 'id' => 'applicant_website']) !!}
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
                                        <div class="card card-magenta border border-magenta">
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
                                                            {!! Form::label('contact_person_name_1', 'Name', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_1']) !!}
                                                                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_district_1', 'District', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_district') ? 'has-error' : '' }}">
                                                                {!! Form::select('contact_person_district[1]', $districts, '', ['class' => 'form-control contact_person_district', 'id' => 'contact_person_district_1', 'onchange' => "getThanaByDistrictId('contact_person_district_1', this.value, 'contact_person_thana_1',0)"]) !!}
                                                                {!! $errors->first('contact_person_district', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_thana_1', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_thana') ? 'has-error' : '' }}">
                                                                {!! Form::select('contact_person_thana[1]', [], '', ['class' => 'form-control contact_person_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_person_thana_1']) !!}
                                                                {!! $errors->first('contact_person_thana', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_address_1', 'Address', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_1']) !!}
                                                                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_mobile[1]', '', ['class' => 'form-control contact_person_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_person_mobile_1']) !!}
                                                                {!! $errors->first('contact_person_mobile', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_image_1', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('contact_person_image') ? 'has-error' : '' }}">
                                                                <div class="row"
                                                                     style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                    <div class="col-md-8">
                                                                        <input type="file"
                                                                               style="border: none; margin-bottom: 5px;"
                                                                               value=""
                                                                               class="form-control input-sm contact_person_image"
                                                                               name="contact_person_image[1]" onchange="imagePreview(this,'image_preview_0')"
                                                                               id="contact_person_image_1"

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
                                                                                <img id="image_preview_0"
                                                                                     style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                                     src="{{ url('assets/images/demo-user.jpg') }}"
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
                                                            {!! Form::label('contact_person_email_1', 'Email', ['class' => 'col-md-4']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_1']) !!}
                                                                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            {!! Form::label('contact_person_designation_1', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                                            <div
                                                                class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                                {!! Form::text('contact_person_designation[1]', '', ['class' => 'form-control contact_person_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_person_designation_1']) !!}
                                                                {!! $errors->first('contact_person_designation', '<span class="help-block">:message</span>') !!}
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
                                                            {!! Form::label('shareholder_name_1', 'Name', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_1']) !!}
                                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_designation_1', 'Designation', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_1']) !!}
                                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_email_1', 'Email', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_1']) !!}
                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_1']) !!}
                                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_share_of_1', '% of share', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_1']) !!}
                                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                                                            {!! Form::label('shareholder_nid_1', 'National ID No', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="passportCopyBlock_1"  style="display: none;">
                                                            {!! Form::label('shareholder_passport_copy_1', 'Passport Copy', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-4 {{ $errors->has('shareholder_passport_copy') ? 'has-error' : '' }}">
                                                                <input type="file" accept="image/*" class="form-control shareholder_passport_copy input-sm" style="border: none; margin-bottom: 5px;" name="shareholder_passport_copy[1]"  onchange="imagePreview(this,'shareholder_passport_copy_preview_1')" id="shareholder_passport_copy_1" />
                                                                {!! $errors->first('shareholder_passport_copy', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="center-block image-upload passport_copy_preview_sec"
                                                                       for="shareholder_passport_copy_preview_1">
                                                                    <figure>
                                                                        <img id="shareholder_passport_copy_preview_1"
                                                                             style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                             src="{{ url('assets/images/photo_default.png') }}"
                                                                             class="img-responsive img-thumbnail"/>
                                                                    </figure>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row" style="margin-bottom:0px!important;">
                                                            {!! Form::label('correspondent_photo_1', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                                                {{--start--}}

                                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                    <div class="col-md-8">
                                                                        <input type="file"
                                                                               style="border: none; margin-bottom: 5px;"
                                                                               class="form-control input-sm correspondent_photo {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                               name="correspondent_photo[1]" id="correspondent_photo_1"
                                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_1', 'correspondent_photo_base64_1')"
                                                                               size="300x300" />
                                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                                    [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                                    <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                                </span>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="center-block image-upload"
                                                                               for="correspondent_photo_1">
                                                                            <figure>
                                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                                     class="img-responsive img-thumbnail"
                                                                                     id="correspondent_photo_preview_1" />
                                                                            </figure>
                                                                            <input type="hidden" id="correspondent_photo_base64_1"
                                                                                   name="correspondent_photo_base64[1]" />
                                                                        </label>

                                                                    </div>
                                                                </div>

                                                                {{--end--}}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" style="margin-top:10px;">
                                                            {!! Form::label('shareholder_dob_1', 'Date of Birth', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                                {{--                                                    {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
                                                                {{--                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
                                                                <div class="input-group date datetimepicker4"
                                                                     id="datepicker0" data-target-input="nearest">
                                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_1']) !!}
                                                                    <div class="input-group-append"
                                                                         data-target="#datepicker0"
                                                                         data-toggle="datetimepicker">
                                                                        <div class="input-group-text"><i
                                                                                class="fa fa-calendar"></i></div>
                                                                    </div>
                                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_nationality_1', 'Nationality', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>



                                                        <div class="form-group row" id="passportBlock_1"  style="display: none;">
                                                            {!! Form::label('shareholder_passport_1', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
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

                <h3>Attachment</h3>
                {{--                <br>--}}
                <fieldset>

                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box):
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>

                </fieldset>

                <h3>Payment & Submit</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Service Fee Payment --}}
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
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:20px;']) }}
                                    {{--                                    <a href="#" data-toggle="modal" data-target="#terms_and_conditions_modal">Terms and Conditions.</a>--}}
                                    {{--                                    <a href="#" data-toggle="modal" data-target="#otp_modal" class="btn btn-info btn-block btn-md"><strong><i--}}
                                    {{--                                                class=" fa fa-lock "></i> Login with--}}
                                    {{--                                            OTP</strong></a>--}}
                                </div>
                            </div>
                        </div>
                    </div>


                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/nttn-license-cancellation/list/'. Encryption::encodeId(53)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreview()">Submit
                    </button>
                </div>

                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn"
                        id="save_as_draft">Save as Draft
                </button>
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
    var selectCountry = '';

    $(document).ready(function() {
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
                        SetErrorInShareOfInputField();
                        return false;
                    }


                    // Allways allow previous action even if the current form is not valid!
                    console.log(currentIndex,newIndex)
                    if (currentIndex > newIndex) {
                        return true;
                    }

                }

                if (newIndex == 2) {
                    let errorStatus = false;

                    if(errorStatus) return false;

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

                if(newIndex < currentIndex) return true;

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
                console.log('finished');
                return false;
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

        $("#company_type").css('pointer-events','none');

        var popupWindow = null;
        $('#submitForm').on('click', function (e) {
            e.preventDefault();

            {{--if ($('#accept_terms').is(":checked")){--}}
            {{--    $('#accaccept_termseptTerms').removeClass('error');--}}
            {{--    $('#accept_terms').next('label').css('color', 'black');--}}
            {{--    // $('body').css({"display": "none"});--}}
            {{--    // $("form").submit();--}}
            {{--    window.open('<?php echo URL::to('/nttn-license-issue/preview'); ?>');--}}
            {{--} else {--}}
            {{--    $('#accept_terms').addClass('error');--}}
            {{--    return false;--}}
            {{--}--}}
            let preview = 1;
            // If select online payment
            if($("#online_payment").is(':checked')) {
                new swal({
                    title: 'Online Payment',
                    text: 'Online Payment is not available now. Please proceed to Pay Order.',
                });
                return;
            }
            // field validation
            const fields = document.querySelectorAll('#payment_panel .required');
            for (const element of fields) {
                if(!element.value) {
                    element.classList.add('error');
                    preview *= 0;
                } else preview *= 1;
            }

            if(!$('#accept_terms').prop('checked')){
                $('#accept_terms').addClass('error');
                return false;
            }

            if(preview) window.open('<?php echo URL::to('/nttn-license-issue/preview'); ?>');
        });

        $('.finish').on('click', function (e) {
            if(!$('#accept_terms').prop('checked')){
                $('#accept_terms').addClass('error');
                return false;
            }
            popupWindow = window.open('<?php echo URL::to('/nttn-license-issue/preview'); ?>', 'Sample', '');
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

    $(document).ready(function() {

        //add shareholder row
        var rowId = 0;
        $(".addShareholderRow").on('click', function() {
            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);
            $('#shareholderRow').append(
                `<tr class="client-rendered-row" id="R_${lastRowId+1}">
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
                {!! Form::label('shareholder_name_${lastRowId+1}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_name[${lastRowId+1}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_designation_${lastRowId+1}', 'Designation', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_designation[${lastRowId+1}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_email_${lastRowId+1}', 'Email', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_email[${lastRowId+1}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_mobile_${lastRowId+1}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_mobile[${lastRowId+1}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_share_of_${lastRowId+1}', '% of share', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                    {!! Form::number('shareholder_share_of[${lastRowId+1}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="nidBlock_${lastRowId+1}" style="display: none;">
                {!! Form::label('shareholder_nid_${lastRowId+1}', 'National ID No', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_nid[${lastRowId+1}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'National ID No', 'id' => 'shareholder_nid_'.'${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="passportCopyBlock_${lastRowId+1}"  style="display: none;">

                                                        {!! Form::label('shareholder_passport_copy_${lastRowId+1}', 'Passport Copy', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-4 {{ $errors->has('shareholder_passport_copy') ? 'has-error' : '' }}">
                                                            <input type="file" accept="image/*" onchange="imagePreview(this,'shareholder_passport_copy_preview_${lastRowId+1}')" style="border: none; margin-bottom: 5px;" class="form-control shareholder_passport_copy input-sm {{ !empty(Auth::user()->user_pic) ? '' : 'required' }}" name="shareholder_passport_copy[${lastRowId+1}]" id="shareholder_passport_copy_${lastRowId+1}" />
                                                            {!! $errors->first('shareholder_passport_copy', '<span class="help-block">:message</span>') !!}


                </div>
                 <div class="col-md-4">
                                                                    <label class="center-block image-upload passport_copy_preview_sec"
                                                                           for="shareholder_passport_copy_preview_${lastRowId+1}">
                                                                    <figure>
                                                                        <img id="shareholder_passport_copy_preview_${lastRowId+1}"
                                                                             style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                                                             src="{{ url('assets/images/photo_default.png') }}"
                                                                                 class="img-responsive img-thumbnail"/>
                                                                        </figure>
                                                                    </label>
                                                                </div>
        </div>

</div>


<div class="col-md-6">
<div class="form-group row" style="margin-bottom:0px!important;">
{!! Form::label('correspondent_photo_${lastRowId+1}', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                        <div class="col-md-8">
                            <input type="file" style="border: none; margin-bottom: 5px;" class="form-control  correspondent_photo input-sm required"
                                   name="correspondent_photo[${lastRowId+1}]" id="correspondent_photo_${lastRowId+1}" size="300x300"
                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${lastRowId+1}', 'correspondent_photo_base64_${lastRowId+1}')" />

                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]
                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                        </span>
                        </div>
                        <div class="col-md-4">
                            <label class="center-block image-upload" for="correspondent_photo_${lastRowId+1}">
                                <figure>
                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                         src="{{asset('assets/images/demo-user.jpg') }}"
                                         class="img-responsive img-thumbnail" id="correspondent_photo_preview_${lastRowId+1}" />
                                </figure>
                                <input type="hidden" id="correspondent_photo_base64_${lastRowId+1}" name="correspondent_photo_base64[]" />
                            </label>
                        </div>



                </div>
            </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
                {!! Form::label('shareholder_dob_${lastRowId+1}', 'Date of Birth', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                    {{--                                        {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
                {{--                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
                <div class="input-group date datetimepicker4" id="dob${lastRowId}" data-target-input="nearest">
                        {!! Form::text('shareholder_dob[${lastRowId+1}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${lastRowId+1}']) !!}
                <div class="input-group-append" data-target="#dob${lastRowId}" data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
{!! Form::label('shareholder_nationality_${lastRowId+1}', 'Nationality', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                    {!! Form::select('shareholder_nationality[${lastRowId+1}]', $nationality, '', ['class' => 'form-control nationality', 'id' => 'shareholder_nationality_'.'${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row" id="passportBlock_${lastRowId+1}" style="display: none;">
                {!! Form::label('shareholder_passport_${lastRowId+1}', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_passport[${lastRowId+1}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</td>

</tr>`);
            getHelpText();
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

        //add shareholder row
        //contact person row
        $(".addContactPersonRow").on('click', function () {
            let lastRowId = parseInt($('#contactPersonRow tr:last').attr('id').split('_')[2]);
            $('#contactPersonRow').append(
                `<tr id="cp_r_${lastRowId + 1}" style="border-bottom: 1px solid #999;">
                    <td>
                    <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Contact Person Information
                                                <span style="float: right; cursor: pointer;" class="addContactPersonRow">
                                                     <button type="button" class="btn btn-danger btn-sm contactPersonRow cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_person_name_${lastRowId+1}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[${lastRowId+1}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_district_${lastRowId+1}', 'District', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_person_district[${lastRowId+1}]', $districts, '', ['class' => 'form-control contact_person_district', 'id' => 'contact_person_district_${lastRowId+1}', 'onchange' => "getThanaByDistrictId('contact_person_district_".'${lastRowId+1}'."', this.value, 'contact_person_thana_".'${lastRowId+1}'."',0)"]) !!}
                {!! $errors->first('contact_person_district', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_thana_${lastRowId+1}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_person_thana[${lastRowId+1}]', [], '', ['class' => 'form-control contact_person_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_person_thana_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_thana', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_address_${lastRowId+1}', 'Address', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[${lastRowId+1}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_mobile_${lastRowId+1}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_mobile[${lastRowId+1}]', '', ['class' => 'form-control contact_person_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_person_mobile_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_image_${lastRowId + 1}', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_image') ? 'has-error' : '' }}">
            <div class="row"
                 style="margin-bottom:0px!important; padding-bottom:0px!important;">
                <div class="col-md-8">
                    <input type="file" style="border: none; margin-bottom: 5px;" onchange="imagePreview(this,'image_preview_${lastRowId + 1}')"
                           value=""
                           class="form-control input-sm contact_person_image"
                           name="contact_person_image[${lastRowId+1}]"
                           id="contact_person_image_${lastRowId + 1}"

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
                           for="correspondent_photo_${lastRowId + 1}">
                        <figure>
                            <img id="image_preview_${lastRowId + 1}"
                                 style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                 src="{{   url('assets/images/demo-user.jpg') }}"
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
{!! Form::label('contact_person_email_${lastRowId+1}', 'Email', ['class' => 'col-md-4' ]) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[${lastRowId+1}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
{!! Form::label('contact_person_designation_${lastRowId+1}', 'Designation', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('contact_person_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_designation[${lastRowId+1}]', '', ['class' => 'form-control contact_person_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_person_designation_${lastRowId+1}']) !!}
                {!! $errors->first('contact_person_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</td>
</tr>`);
            getHelpText();
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


        $(document).on('change','.nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#shareholder_nid_' + lastRowId).addClass('required');

                $('#passportBlock_'+lastRowId).hide();
                $('#shareholder_passport_' + lastRowId).removeClass('required');

                $('#passportCopyBlock_'+lastRowId).hide();
                $('#shareholder_passport_copy_' + lastRowId).removeClass('required');

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#shareholder_nid_' + lastRowId).removeClass('required');

                $('#passportBlock_'+lastRowId).show();
                $('#shareholder_passport_' + lastRowId).addClass('required');

                $('#passportCopyBlock_'+lastRowId).show();
                $('#shareholder_passport_copy_' + lastRowId).addClass('required');
            }
        });

        $(document).on('change', '#permanentSameAsRegisterdAddress', function (e) {
            if (this.checked === true) {
                let office_district = $("#reg_office_district").val();
                let office_upazilla_thana = $("#reg_office_thana").val();
                $("#permanent_office_district").val(office_district);
                getThanaByDistrictId('permanent_office_district', office_district, 'permanent_office_thana', office_upazilla_thana.trim());
                $("#permanent_office_address").val($("#reg_office_address").val());

            } else {
                $("#permanent_office_thana").val('');
                $("#permanent_office_address").val('');
                $("#permanent_office_district").val('');
            }
        })



        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }



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


    });

    // load payment panel
    const fixed_amounts = {
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
        fixed_amounts);




    function openPreview() {

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }else{
            $('#accept_terms').removeClass('error');
        }

        window.open('<?php echo URL::to('/nttn-license-issue/preview'); ?>');
    }


    function openPreviewV2() {
        let preview = 1;
        // If select online payment
        if($("#online_payment").is(':checked')) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }
        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').addClass('error');
            return false;
        }

        if(preview) window.open('<?php echo URL::to('/nttn-license-issue/preview'); ?>');
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

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

</script>
