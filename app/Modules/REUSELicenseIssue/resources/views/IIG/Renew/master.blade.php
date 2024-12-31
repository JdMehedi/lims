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
    .p-r-0 {
        padding-right: 0;
    }
    .m-l-auto {
        margin-left: auto;
    }
    .card-magenta:not(.card-outline)>.card-header {
        display: inherit;
    }
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 33% !important;
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

    /*.wizard>.steps>ul>li {*/
    /*    width: 33.2% !important;*/
    /*}*/

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

            <h4 class="card-header">Application for IIG License Renew</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('iig-license-issue/store'),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>

                {{--Basic Information--}}
                <h3>Basic Information</h3>
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])

                    {{-- Company info --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Company/Organization Informaiton
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('company_or_organization', 'Company/ Organization  Name', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                                            {!! Form::text('company_name', isset($companyInfo->org_nm) ? $companyInfo->org_nm : '',
                                            ['class' => 'form-control',
											'readonly' => isset($companyInfo->org_nm) ?? 'readonly',
                                            'placeholder' => 'Enter Name',
                                            'id' => 'company_name']) !!}
                                            {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('company_type', 'company Type', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                                            {!! Form::select('company_type',[''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'],
                                             isset($companyInfo->org_type) ? $companyInfo->org_type : '',
                                             ['class' => 'form-control',
								               'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                                             'id'=> 'company_type']) !!}
                                            {!! $errors->first('Company_type', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Registered Office Address--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Registered Office Address</legend>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('office_district', 'District', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('office_district') ? 'has-error' : '' }}">
                                                {!! Form::select('office_district',$districts,'',['class'=>'form-control', 'id'=> 'office_district', 'onchange' => "getThanaByDistrictId('office_district', this.value, 'office_upazilla_thana',0)"])  !!}
                                                {!! $errors->first('office_district', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('office_upazilla_thana', 'Upazilla/Thana', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('office_upazilla_thana') ? 'has-error' : '' }}">
                                                {!! Form::select('office_upazilla_thana',[],'', ['class' => 'form-control', 'id'=>'office_upazilla_thana']) !!}
                                                {!! $errors->first('office_upazilla_thana', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('office_address', 'Address', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('office_address') ? 'has-error' : '' }}">
                                                {!! Form::text('office_address', '', ['class' => 'form-control', 'placeholder' => 'Address', 'id' => 'office_address']) !!}
                                                {!! $errors->first('office_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                            {{--parmanent office address--}}
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"> parmanent office address </legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('par_office_district', 'District', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('par_office_district') ? 'has-error' : '' }}">
                                                {!! Form::select('par_office_district',$districts,'',['class'=>'form-control', 'id'=> 'par_office_district', 'onchange' => "getThanaByDistrictId('par_office_district', this.value, 'par_office_upazilla_thana',0)"])  !!}
                                                {!! $errors->first('par_office_district', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('par_office_upazilla_thana', 'Upazilla/Thana', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('par_office_upazilla_thana') ? 'has-error' : '' }}">
                                                {!! Form::select('par_office_upazilla_thana',[],'', ['class' => 'form-control', 'id'=>'par_office_upazilla_thana']) !!}
                                                {!! $errors->first('par_office_upazilla_thana', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('par_office_address', 'Address', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('par_office_address') ? 'has-error' : '' }}">
                                                {!! Form::text('par_office_address', '', ['class' => 'form-control', 'placeholder' => 'Address', 'id' => 'par_office_address']) !!}
                                                {!! $errors->first('par_office_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    {{-- Applicant Profile --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Applicant Profile
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('applicant_name', 'Name of Applicant', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_name') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_name','',['class'=>'form-control','placeholder'=>'Enter the name of entity', 'id' => 'applicant_name'] ) !!}
                                            {!! $errors->first('applicant_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_telephone', 'Telephone No', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_telephone') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_telephone','',['class'=>'form-control','placeholder'=>'Enter the telephone number', 'id'=> 'applicant_telephone'] ) !!}
                                            {!! $errors->first('applicant_telephone', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_district', 'District', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_district') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_district',$districts,'',['class'=>'form-control', 'id'=>'applicant_district', 'onchange' => "getThanaByDistrictId('applicant_district', this.value, 'applicant_upazila_thana',0)"] ) !!}
                                            {!! $errors->first('applicant_district', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_address', 'Address', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_address') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_address','',['class'=>'form-control','placeholder'=>'Enter the address', 'id'=>'applicant_address'] ) !!}
                                            {!! $errors->first('applicant_address', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6"  >
                                    <div class="form-group row">
                                        {!! Form::label('applicant_mobile', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_mobile') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_mobile','', ['class' => 'form-control','placeholder'=>'Enter the telephone number', 'id'=>'applicant_mobile']) !!}
                                            {!! $errors->first('applicant_mobile', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_email', 'Email', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_email') ? 'has-error' : '' }}">
                                            {!! Form::text('applicant_email','', ['class' => 'form-control','placeholder'=>'Enter the email', 'id'=>'applicant_email']) !!}
                                            {!! $errors->first('applicant_email', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_upazila_thana', 'Upazila/Thana', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_upazila_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_upazila_thana',[],'', ['class' => 'form-control', 'id'=>'applicant_upazila_thana']) !!}
                                            {!! $errors->first('applicant_upazila_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">
                                            {!! Form::url('applicant_website','', ['class' => 'form-control','placeholder'=>'Enter the telephone number', 'id'=>'applicant_website']) !!}
                                            {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="district" style="display: none;"></div>
                                <div class="col-md-6" id="thana" style="display: none;">
                                    <div class="form-group row" >
                                        {!! Form::label('applicant_upazila_thana', 'Thana', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_upazila_thana') ? 'has-error' : '' }}">
                                            {!! Form::select('applicant_upazila_thana',[''=>'Select'],'', ['class' => 'form-control', 'id' => 'applicant_upazila_thana']) !!}
                                            {!! $errors->first('applicant_upazila_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--contact person--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Contact Person
                            <span style="float: right; cursor: pointer;" class="addContactPersonRow m-l-auto">
                            <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                        </span>
                        </div>
                        <div class="card-wrapper" id="contactPersonRow" style="padding: 15px 25px;">
                            <div class="card-body" style="padding: 0px 0px;" id="contact_1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('signatory_name_1', 'Name of Authorized Signatory', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('signatory_name') ? 'has-error' : '' }}">
                                                {!! Form::text('signatory_name[1]', '', ['class' => 'form-control signatory_name', 'placeholder' => 'Enter the Name of authorized sign', 'id' => 'signatory_name_1']) !!}
                                                {!! $errors->first('signatory_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_person_name_1', 'Name', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter Name', 'id' => 'contact_person_name_1']) !!}
                                                {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_designation_1', 'Designation', ['class' => 'col-md-4']) !!}
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
                                            {!! Form::label('contact_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_mobile[1]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'contact_mobile_1']) !!}
                                                {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_email_1', 'Email', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Email', 'id' => 'contact_email_1']) !!}
                                                {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_district_1', 'District', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_district[1]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_1', 'onchange' => "getThanaByDistrictId('contact_district_1', this.value, 'contact_thana_1',0)"]) !!}
                                                {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_thana_1', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_thana[1]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_1']) !!}
                                                {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('contact_person_address_1', 'Address', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_1']) !!}
                                                {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row" style="margin-bottom:0px!important;">
                                            {!! Form::label('contact_photo_1', 'Photograph', ['class' => 'col-md-4']) !!}
                                            <div class="col-md-8 {{ $errors->has('contact_photo') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               class="form-control contact_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                               name="contact_photo[1]" id="contact_photo_1"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'contact_photo_preview_1', 'contact_photo_base64_1')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="contact_photo_1">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="contact_photo_preview_1" />
                                                            </figure>
                                                            <input type="hidden" id="contact_photo_base64_1"
                                                                   name="contact_photo_base64[1]" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
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
                            <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table-responsive" style="width: 100%;     display: inline-table!important;" id="shareholderRow">
                                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                                <tr id="r_1" style="border-bottom: 1px solid #999;">
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                        {!! Form::select('shareholder_nationality[1]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_1']) !!}
                                                        {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="margin-top:10px;">
                                                    {!! Form::label('shareholder_dob', 'Date of Birth', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                        {{--                                                    {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
                                                        {{--                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
                                                        <div class="input-group date datetimepicker4"
                                                             id="datepicker0" data-target-input="nearest">
                                                            {!! Form::text('shareholder_dob[1]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob']) !!}
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
                                                    {!! Form::label('shareholder_designation', 'Designation', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Designation ', 'id' => 'shareholder_designation']) !!}
                                                        {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_email', 'Email', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Email', 'id' => 'shareholder_email']) !!}
                                                        {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="margin-bottom:0px!important;">
                                                    {!! Form::label('correspondent_photo_1', 'Photograph', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                                        {{--start--}}
                                                        <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                            <div class="col-md-8">
                                                                <input type="file"
                                                                       class="form-control correspondent_photo  input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
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
                                                                           name="correspondent_photo_base64[]" />
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row" id="nidBlock_1" style="display: none;">
                                                    {!! Form::label('shareholder_nid', 'NID No', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_nid[1]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'National ID No', 'id' => 'shareholder_nid_1']) !!}
                                                        {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_name', 'Name', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_name[1]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name']) !!}
                                                        {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_mobile', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile']) !!}
                                                        {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                                                        {!! Form::number('shareholder_share_of[1]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of']) !!}
                                                        {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
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

                {{--Attachment Declration--}}
                <h3>Attachment & Declration</h3>
                <br>
                <fieldset>
                    {{--Required Documents--}}
                    <div class="card card-magenta border border-magenta">
                        <div  class="card-header">
                            Required Documents for attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>
                    {{--Declaration--}}
                    <div class="card card-magenta border border-magenta mt-4">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li ><span class="i_we_dynamic">I/We</span> declare that all the information furnished in this application form is true and correct. <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> understand that approval from the Commission for this application is based on information as declared in this application Should any of the information as declared be incorrect, then any License granted by the Commission may be</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> also declare that I/we have read, understood and undertake to comply with ad the terms and conditions outlined or referred to in the Commision document ented Regulatory and Licensing Guidelines for invitation of application for granting of Gicense in the country, and those terms and conditions included in the License to be issued to us/me, if this application is approved by the Commission</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Payment and Submit--}}
                <h3>Payment & Submit</h3>
                <br>
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
                    <a href="{{ url('client/iig-license-renew/list/'. Encryption::encodeId(18)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
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
    let selectCountry = '';
    $(document).ready(function() {
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

        let check = $('#same_address').prop('checked');
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
            let investment_type_id = $('#investment_type_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            let self = $(this);
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
                        let option = "";
                    } else {
                        let option =
                            '<option value="">{{ trans('CompanyProfile::messages.select') }}</option>';
                    }
                    selectCountry = "{{ $investing_country->country_id ?? '' }}";
                    if (response.responseCode == 1) {
                        $.each(response.data, function(id, value) {
                            let repId = (id.replace(' ', ''))
                            if ($.inArray(repId, selectCountry.split(',')) != -1) {
                                option += '<option value="' + repId +
                                    '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + epIrd + '">' + value +
                                    '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();
                    // multiple if type one
                    // multiple if type one
                    let country_ids =
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
            let industrial_sector_id = $('#industrial_sector_id').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            let self = $(this);
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-profile/get-sub-sector-by-sector') }}",
                data: {
                    industrial_sector_id: industrial_sector_id
                },
                success: function(response) {

                    let option =
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
            let local_sales_per = this.value;
            if (local_sales_per <= 100 && local_sales_per >= 0) {
                let cal = 100 - local_sales_per;
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
            let foreign_sales_per = this.value;
            if (foreign_sales_per <= 100 && foreign_sales_per >= 0) {
                let cal = 100 - foreign_sales_per;
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
            let local_male = $('#local_male').val() ? parseFloat($('#local_male').val()) : 0;
            let local_female = $('#local_female').val() ? parseFloat($('#local_female').val()) : 0;
            let local_total = parseInt(local_male + local_female);
            $('#local_total').val(local_total);


            let foreign_male = $('#foreign_male').val() ? parseFloat($('#foreign_male').val()) : 0;
            let foreign_female = $('#foreign_female').val() ? parseFloat($('#foreign_female').val()) :
                0;
            let foreign_total = parseInt(foreign_male + foreign_female);
            $('#foreign_total').val(foreign_total);

            let mp_total = parseInt(local_total + foreign_total);
            $('#mp_total').val(mp_total);

            let mp_ratio_local = parseFloat(local_total / mp_total);
            let mp_ratio_foreign = parseFloat(foreign_total / mp_total);

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

        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100) {
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }
                    if (currentIndex > newIndex) {
                        return true;
                    }
                }
                if (newIndex == 2) {
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
                    form.find('#submitForm').css('display', 'block');
                    // form.steps("next");
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

        let popupWindow = null;
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
            popupWindow = window.open('<?php echo URL::to('/iig-license-issue/preview'); ?>', 'Sample', '');
        });

        function SetErrorInShareOfInputField(){
            $( ".shareholder_share_of" ).each(function( index ) {
                $(this).addClass('error');
            });
        }


        function attachmentLoad() {
            let reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            let company_type_id = parseInt($("#company_type_id").val()); //order 2
            let industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            let investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            let key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }
        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');

        let today = new Date();
        let yyyy = today.getFullYear();

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

        $('#business_category_id').on('change', function() {
            let businessCategoryId = $('#business_category_id').val();
            let oldBusinessCategoryId =
                '{{ isset($companyInfo->business_category_id) ? $companyInfo->business_category_id : '' }}';

            if (businessCategoryId != oldBusinessCategoryId) {
                $('#company_ceo_designation_id').val('');
            } else {
                $('#company_ceo_designation_id').val(
                    '{{ isset($companyInfo->designation) ? $companyInfo->designation : '' }}');
            }
        })

        $(function() {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

        //form & field operation
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
            let lastRowId = $('#contactPersonRow .card-body:last').attr('id').split('_')[1];
            let updateRowId = parseInt(lastRowId)+1;
            $("#contactPersonRow").append(`
            <div class="card-body" style="padding: 5px 0px 0px; border-top: 1px solid #999999;" id="contact_${updateRowId}">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm shareholderRow" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                    </div>
                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('signatory_name_${updateRowId}', 'Name of Authorized Signatory', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('signatory_name') ? 'has-error' : '' }}">
                                                {!! Form::text('signatory_name[${updateRowId}]', '', ['class' => 'form-control signatory_name', 'placeholder' => 'Enter the Name of authorized sign', 'id' => 'signatory_name_${updateRowId}']) !!}
            {!! $errors->first('signatory_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>

</div>
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
                                                {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter Designation', 'id' => 'contact_designation_${updateRowId}']) !!}
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
{!! Form::label('contact_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Email', 'id' => 'contact_email_${updateRowId}']) !!}
            {!! $errors->first('contact_person_email', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_district_${updateRowId}', 'District', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_district') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_${updateRowId+1}', 'onchange' => "getThanaByDistrictId('contact_district_".'${updateRowId+1}'."', this.value, 'contact_thana_".'${updateRowId+1}'."',0)"]) !!}
            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
                                                {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_${updateRowId+1}']) !!}
            {!! $errors->first('contact_thana', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_person_address_${updateRowId}', 'Address', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_address') ? 'has-error' : '' }}">
                                                {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter  Address', 'id' => 'contact_person_address_${updateRowId}']) !!}
            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row" style="margin-bottom:0px!important;">
{!! Form::label('contact_photo_${updateRowId}', 'Photograph', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                    <div class="col-md-8">
                                                        <input type="file"
                                                               class="form-control contact_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                               name="contact_photo[${updateRowId}]" id="contact_photo_${updateRowId}"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'contact_photo_preview_${updateRowId}', 'contact_photo_base64_${updateRowId}')"
                                                               size="300x300" />
                                                        <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="center-block image-upload"
                                                               for="contact_photo_${updateRowId}">
                                                            <figure>
                                                                <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="contact_photo_preview_${updateRowId}" />
                                                            </figure>
                                                            <input type="hidden" id="contact_photo_base64_${updateRowId}"
                                                                   name="contact_photo_base64[${updateRowId}]" />
                                                        </label>
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
                `<tr id="R_${updateRowId+1}" style="border-bottom: 1px solid #999;">
<td>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger btn-sm shareholderRow" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
        </div>
    </div>
    <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('shareholder_nationality_${updateRowId}', 'Nationality', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                                                        {!! Form::select('shareholder_nationality[${updateRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_${updateRowId}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
{!! Form::label('shareholder_dob_${updateRowId}', 'Date of Birth', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                                                        {{--                                                    {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
                {{--                                                    {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
                <div class="input-group date datetimepicker4"
                     id="datepicker${lastRowId}" data-target-input="nearest">
{!! Form::text('shareholder_dob[${updateRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${updateRowId}']) !!}
                <div class="input-group-append"
                     data-target="#datepicker${lastRowId}"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i
                            class="fa fa-calendar"></i></div>
                </div>
{!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
{!! Form::label('shareholder_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_designation[${updateRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Designation ', 'id' => 'shareholder_designation_${updateRowId}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
{!! Form::label('shareholder_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_email[${updateRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Email', 'id' => 'shareholder_email_${updateRowId}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px!important;">
{!! Form::label('correspondent_photo_${updateRowId}', 'Photograph', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                                                        {{--start--}}
                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                    <div class="col-md-8">
                        <input type="file"
                               class="form-control correspondent_photo input-sm {{ !empty(Auth::user()->user_pic) ? '' : '' }}"
                                                                       name="correspondent_photo[${updateRowId}]" id="correspondent_photo_${updateRowId}"
                                                                       onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${updateRowId}', 'correspondent_photo_base64_${updateRowId}')"
                                                                       size="300x300" />
                                                                <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                                            [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX | Max Size: 4 KB]
                                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                                        </span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="center-block image-upload"
                                                                       for="correspondent_photo_${updateRowId}">
                                                                    <figure>
                                                                        <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;" src="{{asset('assets/images/demo-user.jpg') }}"
                                                                             class="img-responsive img-thumbnail"
                                                                             id="correspondent_photo_preview_${updateRowId}" />
                                                                    </figure>
                                                                    <input type="hidden" id="correspondent_photo_base64_${updateRowId}"
                                                                           name="correspondent_photo_base64[${updateRowId}]" />
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row" id="nidBlock_1" style="display: none;">
                                                    {!! Form::label('shareholder_nid_${updateRowId}', 'NID No', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_nid[${updateRowId}]', '', ['class' => 'form-control ', 'placeholder' => 'National ID No', 'id' => 'shareholder_nid_${updateRowId}']) !!}
                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
{!! Form::label('shareholder_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_name_${updateRowId}') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_name[${updateRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter Name', 'id' => 'shareholder_name_${updateRowId}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
{!! Form::label('shareholder_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_mobile_${updateRowId}') ? 'has-error' : '' }}">
                                                        {!! Form::text('shareholder_mobile[${updateRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter Mobile Number', 'id' => 'shareholder_mobile-${updateRowId}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row">
{!! Form::label('shareholder_share_of', '% of share', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of_${updateRowId}') ? 'has-error' : '' }}">
                                                        {!! Form::number('shareholder_share_of[${updateRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${updateRowId}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
</div>
</td>

</tr>`);

            $("#shareholderDataCount").val(lastRowId+1);
            let today = new Date();
            let yyyy = today.getFullYear();

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

            let child = $(this).closest('tr').nextAll();

            child.each(function () {
                let id = $(this).attr('id');
                let idx = $(this).children('.row-index').children('p');
                let dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
            rowId--;
            $("#shareholderDataCount").val(prevDataCount - 1);
        });

        // nationality
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

        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }

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
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
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
                    '{!! Form::label('isp_licensese_area_district', 'District', ['class' => 'col-md-4']) !!}'+
                    '<div class="col-md-8 {{ $errors->has('isp_licensese_area_district') ? 'has-error' : '' }}">'+
                    '{!! Form::select('isp_licensese_area_district', [''=>'Select'], '', ['class' => 'form-control', 'id' => 'isp_licensese_area_district', 'onchange' => "getThanaByDistrictId('isp_licensese_area_district', this.value, 'isp_licensese_area_thana',0)"]) !!}'+
                    '{!! $errors->first('isp_licensese_area_district', '<span class="help-block">:message</span>') !!}'+
                    '</div>'+
                    '</div>');
                $('#thana').css('display','inline');
            }
        });

        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        let company_type = "{{$companyInfo->org_type}}";
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
        $('.add_row').click(function(){
            let btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                type: "POST",
                url: "{{ url('vsat-license-issue/add-row') }}",
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
                    let html = '';
                    if (response.responseCode == 1) {

                        let edit_url = "{{ url('/client/company-profile/edit-director') }}";
                        let delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                        let count = 1;
                        $.each(response.data, function(id, value) {
                            let sl = count++;
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
                            let date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
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
            let sure_delete = confirm("Are you sure you want to delete?");
            if (sure_delete) {
                let url = btn.getAttribute('data-action');
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

        function calculateMachineryTotal(className, totalShowFieldId) {
            let total = 0.00;
            $("." + className).each(function() {
                total = total + (this.value ? parseFloat(this.value) : 0.00);
            })
            $("#" + totalShowFieldId).val(total.toFixed(2));
        }

        function mobile_no_validation(id) {
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

        function calculateTotalDollar() {
            let total_fixed_ivst_million = $('#total_fixed_ivst_million').val();
            let usd_exchange_rate = $('#usd_exchange_rate').val();
            let usd_amount = (total_fixed_ivst_million / usd_exchange_rate).toFixed(2);
            document.getElementById('total_invt_dollar').value = usd_amount;
        }

        function calculateInvSourceTaka() {
            let ceo_taka_invest = parseFloat(document.getElementById('ceo_taka_invest').value);
            let local_loan_taka = parseFloat(document.getElementById('local_loan_taka').value);
            let foreign_loan_taka = parseFloat(document.getElementById('foreign_loan_taka').value);
            let total_inv_taka = ((isNaN(ceo_taka_invest) ? 0 : ceo_taka_invest) +
                (isNaN(local_loan_taka) ? 0 : local_loan_taka) +
                (isNaN(foreign_loan_taka) ? 0 : foreign_loan_taka)).toFixed(2);
            document.getElementById('total_inv_taka').value = total_inv_taka;
        }

        function calculateInvSourceDollar() {
            let ceo_dollar_invest = parseFloat(document.getElementById('ceo_dollar_invest').value);
            let local_loan_dollar = parseFloat(document.getElementById('local_loan_dollar').value);
            let foreign_loan_dollar = parseFloat(document.getElementById('foreign_loan_dollar').value);
            let total_inv_dollar = ((isNaN(ceo_dollar_invest) ? 0 : ceo_dollar_invest) +
                (isNaN(local_loan_dollar) ? 0 : local_loan_dollar) +
                (isNaN(foreign_loan_dollar) ? 0 : foreign_loan_dollar)).toFixed(2);
            document.getElementById('total_inv_dollar').value = total_inv_dollar;
        }

        function calculateRawMaterialNumber() {
            let local_raw_material_number = parseFloat(document.getElementById('local_raw_material_number').value);
            let import_raw_material_number = parseFloat(document.getElementById('import_raw_material_number').value);
            let raw_material_total_number = ((isNaN(local_raw_material_number) ? 0 : local_raw_material_number) +
                (isNaN(import_raw_material_number) ? 0 : import_raw_material_number)).toFixed(2);
            document.getElementById('raw_material_total_number').value = raw_material_total_number;
        }

        function calculateRawMaterialPrice() {
            let local_raw_material_price = parseFloat(document.getElementById('local_raw_material_price').value);
            let import_raw_material_price = parseFloat(document.getElementById('import_raw_material_price').value);
            let raw_material_total_price = ((isNaN(local_raw_material_price) ? 0 : local_raw_material_price) +
                (isNaN(import_raw_material_price) ? 0 : import_raw_material_price)).toFixed(2);
            document.getElementById('raw_material_total_price').value = raw_material_total_price;
        }

        function openModal(btn) {
            //e.preventDefault();
            let this_action = btn.getAttribute('data-action');
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
    });

    // display payment panel
    let fixed_amounts = {
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
        window.open('<?php echo URL::to('/vsat-license-issue/preview'); ?>');
    }
    function deleteContactRow(element) {
        element.remove();
    }
</script>
