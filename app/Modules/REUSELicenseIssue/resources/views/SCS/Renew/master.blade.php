<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
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

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application for Submarine Cable Service License Renew</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('isp-license-issue/store'),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])

                    {{-- Applicant Profile --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Applicant Profile (Registered Office Address)
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('company_name', 'Company / Organization Name', ['class' => 'col-md-4 ']) !!}
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
                                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                                            {!! Form::select('company_type', [''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'], isset($companyInfo->org_type) ? $companyInfo->org_type : '', ['class' => 'form-control input-disabled',
                                            'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                                            'id' => 'company_type']) !!}
                                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_mobile', Auth::user()->user_mobile, ['class' => 'form-control',
                                                 'readonly' => isset(Auth::user()->user_mobile) ?? 'readonly',
                                                 'placeholder' => 'Enter Mobile Number', 'id' => 'applicant_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_telephone', 'Telephone No.', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_telephone',$companyInfo->office_phone, ['class' => 'form-control', 'placeholder' => 'Enter Telephone Number', 'id' => 'applicant_telephone']) !!}
                                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
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
                                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_district', $districts, $companyInfo->office_district, ['class' => 'form-control', 'id' => 'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_thana',0)"]) !!}
                                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_thana', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_thana', [], '', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id' => 'applicant_thana']) !!}
                                            {!! $errors->first('applicant_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_address', $companyInfo->office_location, ['class' => 'form-control', 'placeholder' => 'Enter  Address', 'id' => 'applicant_address']) !!}
                                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_website','', ['class' => 'form-control', 'placeholder' => 'Enter Website', 'id' => 'applicant_website']) !!}
                                            {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Applicant Profile --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Contact Person
                        </div>
                        <div class="card-wrapper" id="contactPersonRow" style="padding: 15px 25px;">
                            <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_1">
                                <div class="card-header">
                                    Contact Person Information
                                    <span style="float: right; cursor: pointer;" class="addContactPersonRow">
                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                            </span>
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                    <div class="card-body" style="padding: 0px 0px;" id="contact_1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_person_name_1', 'Name', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_1']) !!}
                                                        {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_designation_1', 'Designation', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_designation[1]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_designation_1']) !!}
                                                        {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_mobile_1', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_mobile[1]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_1']) !!}
                                                        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_person_email_1', 'Email', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_1']) !!}
                                                        {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_website_1', 'Website', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_website[1]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Enter Website', 'id' => 'contact_website_1']) !!}
                                                        {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_district_1', 'District', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_district[1]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_1', 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                                        {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_thana_1', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                        {!! Form::select('contact_thana[1]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_1']) !!}
                                                        {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_person_address_1', 'Address', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_1']) !!}
                                                        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Types of ISP License --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Types of ISP License
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('type_of_isp_licensese', 'Types of ISP License', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('type_of_isp_licensese') ? 'has-error' : '' }}">
                                            {!! Form::select('type_of_isp_licensese', [''=>'Select',1=>'Nationwide',2=>'Divisional',3=>'District', 4=>'Thana/Upazila'], '', ['class' => 'form-control', 'id' => 'type_of_isp_licensese']) !!}
                                            {!! $errors->first('type_of_isp_licensese', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="division" style="display: none;">
                                    <div class="form-group row">
                                        {!! Form::label('isp_licensese_area_division', 'Division', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('isp_licensese_area_division') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_division', $division, '', ['class' => 'form-control isp_licensese_area_division', 'id' => 'isp_licensese_area_division', 'onchange' => "getDistrictByDivisionId('isp_licensese_area_division', this.value, 'isp_licensese_area_district',0)"]) !!}
                                            {!! $errors->first('isp_licensese_area_division', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="district" style="display: none;"></div>
                                <div class="col-md-6" id="thana" style="display: none;">
                                    <div class="form-group row" >
                                        {!! Form::label('isp_licensese_area_thana', 'Thana/Upazila', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('isp_licensese_area_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('isp_licensese_area_thana',[''=>'Select'],'', ['class' => 'form-control isp_licensese_area_thana', 'id' => 'isp_licensese_area_thana']) !!}
                                            {!! $errors->first('isp_licensese_area_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                            {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation']) !!}
                                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email']) !!}
                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                                {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="nidBlock_1" style="display: none;">
                                                            {!! Form::label('shareholder_nid', 'NID No', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                                                            {!! Form::label('shareholder_passport', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group row" style="margin-bottom:45px!important;">
                                                            {!! Form::label('correspondent_photo_1', 'Image', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                                {{--start--}}
                                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                                    <div class="col-md-8">
                                                                        <input type="file"
                                                                               style="border: none; margin-bottom: 5px;"
                                                                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
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
                                                            {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                                <div class="input-group date datetimepicker4"
                                                                     id="datepicker0" data-target-input="nearest">
                                                                    {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob', 'placeholder' => 'Enter Date Of Birth']) !!}
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
                                                            {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4 ']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                                {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
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
                {{--                <br>--}}
                <fieldset>
                    {{-- Equipment List --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex">
                            Proposed Equipment List
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment_tbl">
                                <thead>
                                <tr>
                                    <th width="7%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="25%" class="verticalAlignMiddle" >Equipment Name</th>
                                    <th width="20%" class="verticalAlignMiddle" >Brand & Model</th>
                                    <th width="20%" class="verticalAlignMiddle" >Quantity</th>
                                    <th width="20%" class="verticalAlignMiddle" >Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="equipment_1">
                                    <td class="d-flex justify-content-center w-60">1</td>
                                    <td><input type="text" class="form-control equipment_name" name="equipment_name[1]" placeholder="Enter equipment name" ></td>
                                    <td><input type="number" class="form-control equipment_brand_model" name="equipment_brand_model[1]" placeholder="Enter brand & model"></td>
                                    <td><input type="number" class="form-control equipment_quantity" name="equipment_quantity[1]" placeholder="Enter quantity"></td>
                                    <td><input type="text" class="form-control equipment_remarks" name="equipment_remarks[1]" placeholder="Enter remarks"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Proposed Tariff Chart --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header d-flex">
                            Proposed Tariff Chart
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="tariffChart_tbl">
                                <thead>
                                <tr>
                                    <th width="3%" class="text-center verticalAlignMiddle">SL No.</th>
                                    <th width="23%" class="verticalAlignMiddle" >Packages Name/Plan</th>
                                    <th width="22%" class="verticalAlignMiddle" >Internet Bandwidth Package <br> Speed (Kbps/Mbps)</th>
                                    <th width="14%" class="verticalAlignMiddle" >Price(BDT)</th>
                                    <th width="15%" class="verticalAlignMiddle" >Duration</th>
                                    <th width="15%" class="verticalAlignMiddle" >Remarks</th>
                                    <th width="8%" class="verticalAlignMiddle" >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="tariffChart_1" >
                                    <td class="d-flex justify-content-center w-60">1</td>
                                    <td><input type="text" class="form-control tariffChart_package_name_plan" name="tariffChart_package_name_plan[1]" placeholder="Enter packages name/ plan"></td>
                                    <td><input type="number" class="form-control tariffChart_bandwidth_package" name="tariffChart_bandwidth_package[1]" placeholder="Enter Speed (Kbps/Mbps)"></td>
                                    <td><input type="number" class="form-control tariffChart_price" name="tariffChart_price[1]" placeholder="Enter price(BDT)"></td>
                                    <td><input type="text" class="form-control tariffChart_duration" name="tariffChart_duration[1]" placeholder="Enter duration"></td>
                                    <td><input type="text" class="form-control tariffChart_remarks" name="tariffChart_remarks[1]" placeholder="Enter remarks"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Required Documents
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
                                            <label class=" !font-normal">
                                                Declaration Has any Application for License of ISP been rejected before?
                                            </label>
                                            <div style="margin-top: 20px;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Has any License of ISP issued previously to the Applicant/any Share Holder/Partner been cancelled?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>
                                        </li>
                                        <li>
                                            <label class=" !font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the Commission?
                                            </label>

                                            <div style="margin-top: 20px;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;">
                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input','id'=>'if_declaration_q3_yes', 'accept'=>'image/*', 'onchange'=>"generateBase64String('if_declaration_q3_yes','declaration_q3_images_base64')", 'style'=>'display:none; margin-bottom: 20px; border: none;','required'])}}
                                                <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >
                                            </div>
                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
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
                    <a href="{{ url('client/scs-license-renew/list/'. Encryption::encodeId(63)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>
                <div class="float-right">
                    <button type="button" id="submitForm" style="margin-right: 180px; position: relative; z-index: 99999; display: none;" class="btn btn-success btn-md"
                            value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                    </button>
                </div>
                <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft" name="actionBtn" id="save_as_draft">
                    Save as Draft
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
    $(document).ready(function() {
        @isset($companyInfo->office_district)
        getThanaByDistrictId('applicant_district', ' {{ $companyInfo->office_district ?? '' }}', 'applicant_thana',{{ $companyInfo->office_thana ?? '' }})
        @endisset

        let form = $("#application_form").show();
        let popupWindow = null;
        // step part
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    //return true;
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total !== 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }

                    //validate client side rendered fields
                    // const requiredClientFields = document.querySelectorAll('.client-rendered-row input, .client-rendered-row select');
                    // let nextAvailable = true;
                    // for(const elem of requiredClientFields) {
                    //     if(elem.classList.contains('required') && !elem.value) {
                    //         elem.classList.add('error');
                    //         nextAvailable = false;
                    //     }
                    // }
                    // if(!nextAvailable) return false;

                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex === 2) {
                    // declarationSectionValidation();

                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                // return true;
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
                    if(currentIndex > newIndex) {
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
                if (newIndex < currentIndex) {
                    return true;
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
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });
        $('.submitForm').on('click', function (e) {
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
            {{--popupWindow = window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>', 'Sample', '');--}}
            openPreviewV2();
        });

        // datepicker part
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        // declaration part
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

        // add contact person row
        $(".addContactPersonRow").on('click', function() {
            let lastRowId = $('#contactPersonRow .card:last').attr('id').split('_')[1];
            let updateRowId = parseInt(lastRowId)+1;
            // console.log(lastRowId);
            // return false;
            $("#contactPersonRow").append(`
                <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_${updateRowId}">
                        <div class="card-header">
                            Contact Person Information
                            <span style="float: right; cursor: pointer;">
                                 <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                            </span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
            <div class="card-body" style="padding: 5px 0px 0px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('contact_person_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                    {!! Form::text('contact_person_name[${updateRowId}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_${updateRowId}']) !!}
            {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
<div class="form-group row">
{!! Form::label('contact_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
            {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation',  'placeholder' => 'Enter Designation', 'id' => 'contact_designation_${updateRowId}']) !!}
            {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
            {!! Form::text('contact_mobile[${updateRowId}]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_${updateRowId}']) !!}
            {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_person_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
            {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter Email', 'id' => 'contact_person_email_${updateRowId}']) !!}
            {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_website_${updateRowId}', 'Website', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_website') ? 'has-error' : '' }}">
            {!! Form::text('contact_website[${updateRowId}]', '', ['class' => 'form-control contact_website', 'placeholder' => 'Enter Website', 'id' => 'contact_website_${updateRowId}']) !!}
            {!! $errors->first('contact_website', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_district_${updateRowId}', 'District', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                        {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_'.'${updateRowId}', 'onchange' => 'getThanaByDistrictId("contact_district_${updateRowId}",this.value, "contact_thana_${updateRowId}",0)']) !!}
            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
            {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_'.'${updateRowId}']) !!}
            {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_person_address_${updateRowId}', 'Address', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
            {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${updateRowId}']) !!}
            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        </div>
    </div>
</div>
</div>
</div>
`);
            getHelpText();
        });

        //add shareholder row
        let rowId = 0;
        $(".addShareholderRow").on('click', function() {
            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);
            let updateRowId = parseInt(lastRowId)+1;
            $('#shareholderRow').append(
                `<tr id="R_${updateRowId}" class="client-rendered-row">
    <td>
    <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Shareholder/partner/proprietor Details Information
                                                <span style="float: right; cursor: pointer;" class="addShareholderRow">
                                                     <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                                </span>
                                            </div>
                                            <div class="card-body" style="padding: 15px 25px;">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                {!! Form::label('shareholder_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_name[${updateRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name',  'id' => 'shareholder_name_${updateRowId}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('shareholder_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_designation[${updateRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter Designation ', 'id' => 'shareholder_designation_${updateRowId}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('shareholder_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_email[${updateRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter Email', 'id' => 'shareholder_email_${updateRowId}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('shareholder_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_mobile[${updateRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile_${updateRowId}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('shareholder_share_of_${updateRowId}', '% of share', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                    {!! Form::number('shareholder_share_of[${updateRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${updateRowId}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="nidBlock_${updateRowId}" style="display: none;">
                {!! Form::label('shareholder_nid_${updateRowId}', 'NID No', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_nid[${updateRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.'${updateRowId}']) !!}
                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="passportBlock_${updateRowId}" style="display: none;">
                {!! Form::label('shareholder_passport_${updateRowId}', 'Passport No.', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_passport[${updateRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${updateRowId}']) !!}
                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    <div class="col-md-6">
            <div class="form-group row" style="margin-bottom:45px!important;">
                {!! Form::label('correspondent_photo_${updateRowId}', 'Image', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                        <div class="col-md-8">
                            <input type="file" class="form-control input-sm correspondent_photo"
                            style="border: none; margin-bottom: 5px;"
                                   name="correspondent_photo[${updateRowId}]" id="correspondent_photo_${updateRowId}" size="300x300"
                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${updateRowId}', 'correspondent_photo_base64_${updateRowId}')" />

                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]
                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                        </span>
                        </div>
                        <div class="col-md-4">
                            <label class="center-block image-upload" for="correspondent_photo_${updateRowId}">
                                <figure>
                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                         src="{{asset('assets/images/demo-user.jpg') }}"
                                         class="img-responsive img-thumbnail" id="correspondent_photo_preview_${updateRowId}" />
                                </figure>
                                <input type="hidden" id="correspondent_photo_base64_${updateRowId}" name="correspondent_photo_base64[${updateRowId}]" />
                            </label>
                        </div>
                </div>
            </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
                {!! Form::label('shareholder_dob_${updateRowId}', 'Date of Birth', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                <div class="input-group date datetimepicker4" id="dob${lastRowId}" data-target-input="nearest">
                        {!! Form::text('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${updateRowId}', 'placeholder' => 'Enter Date Of Birth']) !!}
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
    {!! Form::label('shareholder_nationality_${updateRowId}', 'Nationality', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                    {!! Form::select('shareholder_nationality[${updateRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.'${lastRowId+1}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
    </td>
    </tr>`);

            $("#shareholderDataCount").val(lastRowId+1);
            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
            getHelpText();
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

        //nationality part
        $(document).on('change','.shareholder_nationality' ,function (){
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
        // license type part
        $('#type_of_isp_licensese').on('change', function () {
            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').removeClass('required');
                // $('#isp_licensese_area_district').removeClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').removeClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
            }

            if(this.value == 3){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', $districts, '', ['class' => 'form-control isp_licensese_area_district', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','none');
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').addClass('required');
                // $('#isp_licensese_area_thana').removeClass('required');
            }

            if(this.value == 4){
                $('#division').css('display','inline');
                $('#district').css('display','inline');
                $('#district').html('');
                $('#district').append('<div class="form-group row">'+
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control isp_licensese_area_district', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');
                // $('#isp_licensese_area_division').addClass('required');
                // $('#isp_licensese_area_district').addClass('required');
                // $('#isp_licensese_area_thana').addClass('required');
            }
            getHelpText();
        });
        $(document).on('change','#isp_licensese_area_division' ,function (){
            $("#isp_licensese_area_thana").prop('selectedIndex',0);
        });

        // Load dynamic documents
        (function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4
            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;
            $("#doc_type_key").val(key);
            loadApplicationDocs('docListDiv', null);
        })();

        // copnay type part
        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });
        let company_type = "{{$companyInfo->org_type}}";
        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 3){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        // add row part
        $('.add_row').click(function(){
            var btn = $(this);
            btn.prop("disabled",true);
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
                success: function(response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled",false);
                    getHelpText();
                }
            });
        });
        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // payment panel part
        $("#type_of_isp_licensese").change(function() {
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
                success: function(response) {
                    if(response.responseCode == -1){
                        alert(response.msg);
                        return false;
                    }
                    oss_fee = parseFloat(response.data.oss_fee);
                    vat = parseInt(response.data.vat);
                },
                complete: function() {
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
    });
    // mobile no validation
    function mobile_no_validation(elementId) {
        let id = elementId;
        $("#" + id).on('keyup', function() {
            let countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode;
            if (countryCode === "880") {
                let mobile = $("#" + id).val();
                let reg = /^0/;
                if (reg.test(mobile)) {
                    $("#" + id).val("");
                }
                if (mobile.length != 10) {
                    $("#" + id).addClass('error')
                }
            }
        });
    }

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

    // preview
    function openPreview() {
        window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>');
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

        if(preview) window.open('<?php echo URL::to('/isp-license-issue/preview'); ?>');
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
    function SetErrorInShareOfInputField(){
        $( ".shareholder_share_of" ).each(function( index ) {
            $(this).addClass('error');
        });
    }
</script>
