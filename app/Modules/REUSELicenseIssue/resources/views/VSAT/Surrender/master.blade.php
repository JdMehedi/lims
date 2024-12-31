<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input-disabled{
        pointer-events: none;
    }
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

    .cross-button{
        float:right;
        padding: 0rem .250rem !important;
    }

    .card-magenta:not(.card-outline)>.card-header {
        display: inherit;
    }

    #company_type{
        pointer-events: none;
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
            <h4 class="card-header">Application for VSAT-HUB Operator/ VSAT User License/ VSAT RT User License Surrender</h4>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('vsat-license-issue/store'),
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
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])
                    {{-- VSAT License Information --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            VSAT License Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('license_categories', 'License Categories', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('license_category') ? 'has-error' : '' }}">
                                            {!! Form::select('license_category', [''=>'Select', '1'=>'VSAT HUB Operator','2'=>'VSAT User','3'=>'VSAT RT User'],'',['class' => 'form-control', 'id'=> 'license_categories'])!!}
                                            {!! $errors->first('license_category', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        {!! Form::label('origin_or_satelite', 'Origin or Satelite Type', ['class' => 'col-md-4 p-r-0']) !!}
                                        <div class="col-md-8 {{ $errors->has('origin_or_satelite') ? 'has-error' : '' }}">
                                            {!! Form::select('origin_or_satelite', [''=>'Select', '1'=>'National Satelite','2'=>'Foreign Satelite'],' ', ['class' => 'form-control', 'id'=> 'origin_or_satelite']) !!}
                                            {!! $errors->first('origin_or_satelite', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('company_type') ? 'has-error' : '' }}">
                                            {!! Form::select('company_type',[''=>'Select', '5'=>'Public Limited','1'=>'Partnership','3'=>'Private Limited','2'=>'Proprietorship','4'=>'Government institutions','6'=>'Autonomous institutions'],
                                             isset($companyInfo->org_type) ? $companyInfo->org_type : '',
                                             ['class' => 'form-control input-disabled',
											 'readonly' => isset($companyInfo->org_type) ?? 'readonly',
                                             'id'=> 'company_type']) !!}
                                            {!! $errors->first('company_type', '<span class="help-block">:message</span>') !!}
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
                                                {!! Form::select('office_upazilla_thana',[],'', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id'=>'office_upazilla_thana']) !!}
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
                                                {!! Form::text('office_address', '', ['class' => 'form-control', 'placeholder' => 'Enter Address', 'id' => 'office_address']) !!}
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
                                                {!! Form::select('par_office_upazilla_thana',[],'', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id'=>'par_office_upazilla_thana']) !!}
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
                                                {!! Form::text('par_office_address', '', ['class' => 'form-control', 'placeholder' => 'Enter Address', 'id' => 'par_office_address']) !!}
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
                                            {!! Form::text('applicant_telephone','',['class'=>'form-control','placeholder'=>'Enter the Telephone', 'id'=> 'applicant_telephone'] ) !!}
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
                                            {!! Form::text('applicant_mobile','', ['class' => 'form-control','placeholder'=>'Enter the Mobile number', 'id'=>'applicant_mobile']) !!}
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
                                            {!! Form::select('applicant_upazila_thana',[],'', ['class' => 'form-control', 'placeholder' => 'Select district at first', 'id'=>'applicant_upazila_thana']) !!}
                                            {!! $errors->first('applicant_upazila_thana', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('applicant_website', 'Website', ['class' => 'col-md-4']) !!}
                                        <div class="col-md-8 {{ $errors->has('applicant_website') ? 'has-error' : '' }}">
                                            {!! Form::url('applicant_website','', ['class' => 'form-control','placeholder'=>'Enter the Website', 'id'=>'applicant_website']) !!}
                                            {!! $errors->first('applicant_website', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-6" id="district" style="display: none;"></div>--}}
                                {{--                                <div class="col-md-6" id="thana" style="display: none;">--}}
                                {{--                                    <div class="form-group row" >--}}
                                {{--                                        {!! Form::label('applicant_upazila_thana', 'Thanaaaaaaaaaaaaaaa', ['class' => 'col-md-4']) !!}--}}
                                {{--                                        <div class="col-md-8 {{ $errors->has('applicant_upazila_thana') ? 'has-error' : '' }}">--}}
                                {{--                                            {!! Form::select('applicant_upazila_thana',[''=>'Select'],'', ['class' => 'form-control', 'id' => 'applicant_upazila_thana']) !!}--}}
                                {{--                                            {!! $errors->first('applicant_upazila_thana', '<span class="help-block">:message</span>') !!}--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    {{--contact person--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Contact Person
                        </div>
                        <div class="card-wrapper" id="contactPersonRow" style="padding: 15px 25px;">
                            <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_1">
                                <div class="card-header">
                                    Contact Person Information
                                    <span style="float: right; cursor: pointer;" class="addContactPersonRow m-l-auto">
                                                <i style="font-size: 20px;" class="fa fa-plus-square" aria-hidden="true"></i>
                                            </span>
                                </div>
                                <div class="card-body" style="padding: 15px 25px;">
                                    <div class="card-body" style="padding: 0px 0px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_name_1', 'Name', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_name[1]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter The Name', 'id' => 'contact_name_1']) !!}
                                                        {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_designation_1', 'Designation', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_designation[1]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter The Designation', 'id' => 'contact_designation_1']) !!}
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
                                                        {!! Form::text('contact_mobile[1]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter The Mobile Number', 'id' => 'contact_mobile_1']) !!}
                                                        {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('contact_email_1', 'Email', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
                                                        {!! Form::text('contact_person_email[1]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter The Email', 'id' => 'contact_email_1']) !!}
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
                                                        {!! Form::text('contact_person_address[1]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter The Address', 'id' => 'contact_person_address_1']) !!}
                                                        {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row" style="margin-bottom:0px!important;">
                                                    {!! Form::label('contact_photo_1', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
                                                    <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">
                                                        <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                                                            <div class="col-md-8">
                                                                <input type="file"
                                                                       style="border:none; margin-bottom: 5px;"
                                                                       class="form-control input-sm contact_photo"
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
                        </div>
                    </div>

                    {{-- Shareholder/partner/proprietor Details --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Shareholder/partner/proprietor Details
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table-responsive" style="width: 100%;display: inline-table!important;" id="shareholderRow">
                                <input type="hidden" id="shareholderDataCount" name="shareholderDataCount" value="1"/>
                                <tr id="r_1">
                                    <td>
                                        <div class="card card-magenta border border-magenta">
                                            <div class="card-header">
                                                Shareholder/partner/proprietor Details Information
                                                <span style="float: right; cursor: pointer;" class="addShareholderRow m-l-auto">
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
                                                                {!! Form::text('shareholder_designation[1]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter The Designation ', 'id' => 'shareholder_designation_1']) !!}
                                                                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_email_1', 'Email', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_email[1]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter The Email', 'id' => 'shareholder_email_1']) !!}
                                                                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('shareholder_mobile_1', 'Mobile Number', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_mobile[1]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter The Mobile Number', 'id' => 'shareholder_mobile_1']) !!}
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

                                                        <div class="form-group row" id="passportBlock_1" style="display: none;">
                                                            {!! Form::label('shareholder_passport_1', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}
                                                            <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                                                                {!! Form::text('shareholder_passport[1]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_1']) !!}
                                                                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
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
                                                                               style="border:none; margin-bottom: 5px;"
                                                                               class="form-control input-sm correspondent_photo"
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
                                                            {!! Form::label('shareholder_nationality_1', 'Nationality', ['class' => 'col-md-4']) !!}
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

                {{--VSAT Information--}}
                <h3>VSAT Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{--SatelliteCommunication Service Provider Information--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Satellite Communication Service Provider Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="equipment_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Service Provider Name</th>
                                    <th width="">Service Detials</th>
                                    <th width="">Location</th>
                                    <th style="width:10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="satelite_1">
                                    <td class="text-center">1</td>
                                    <td><input type="text" class="form-control service_provider" id="service_provider_1" name="service_provider[1]" placeholder="Enter Service Provider Name"></td>
                                    <td><input type="text" class="form-control service_details" id="service_details_1" name="service_details[1]" placeholder="Enter Service Detials"></td>
                                    <td><input type="text" class="form-control service_location" id="service_location_1" name="service_location[1]" placeholder="Enter Location"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--VSAT Hub Operator Information--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            VSAT Hub Operator Information
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="vsat_tbl">
                                <thead>
                                <tr>
                                    <th class="text-center" width="10%">SL .No</th>
                                    <th width="40%">Place Name (Installed/Existing)</th>
                                    <th>Geographical Location (Measured by Set)</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="vsat_1">
                                    <td class="text-center">1</td>
                                    <td><input type="text" class="form-control vsat_place_name" id="vsat_place_name_1" name="vsat_place_name[1]"  placeholder="Place name(installed existing)"></td>
                                    <td><input type="number"  class="form-control vsat_location" id="vsat_location_1" name="vsat_location[1]" placeholder="Geographical Location (Measured by Set)"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button" ><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--Technical Specification --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Technical Specification/ Catalogue
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="tariffChart_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Name</th>
                                    <th width="">Type</th>
                                    <th width="">Manufacturer</th>
                                    <th width="">Country of Origin</th>
                                    <th width="">Power Output</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="technical_1">
                                    <td class="text-center">1</td>
                                    <td><input type="text" class="form-control technical_name" id="technical_name_1" name="technical_name[1]" placeholder="Enter  name"></td>
                                    <td><input type="text" class="form-control technical_type" id="technical_type_1" name="technical_type[1]" placeholder="Enter type"></td>
                                    <td><input type="text" class="form-control technical_manufacturer" id="technical_manufacturer_1" name="technical_manufacturer[1]" placeholder="Enter manufacturer"></td>
                                    <td><input type="text" class="form-control technical_country_of_Origin" id="technical_country_of_Origin_1" name="technical_country_of_Origin[1]" placeholder="Enter country of Origin"></td>
                                    <td><input type="text" class="form-control technical_power_output" id="technical_power_output_1" name="technical_power_output[1]" placeholder="Enter power output"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            List of Equipment for Monitoring
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <table class="table table-bordered" id="listOfEquipment_tbl">
                                <thead>
                                <tr>
                                    <th width="10%" class="text-center">SL No.</th>
                                    <th width="">Equipment Name</th>
                                    <th width="">Storage Capacity</th>
                                    <th width="">Data</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr row_id="listOfEquipment_1">
                                    <td class="text-center">1</td>
                                    <td><input type="text" class="form-control list_equipment" id="list_equipment_1" placeholder="Equipment Name" name="list_equipment[1]"></td>
                                    <td><input type="text" class="form-control list_storage" id="list_storage_1" placeholder="Storage Capacity" name="list_storage[1]"></td>
                                    <td><input type="text" class="form-control list_data" id="list_data_1" placeholder="Data" name="list_data[1]"></td>
                                    <td class="d-flex justify-content-center"><button class="btn btn-success rounded-circle btn-sm text-white add_row" type="button"><i class="fa fa-plus"></i></button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>

                {{--Attachment Declration--}}
                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
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
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any Application for License of VSAT-HUB Operator been rejected before?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q1">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none;" id="if_declaration_q1_yes">
                                                {{ Form::textarea('declaration_q1_text', null, array('class' =>'form-control input required', 'id'=>'declaration_q1_text','placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>

                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any Application for License of VSAT-HUB Operator been rejected before?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display: none" id="if_declaration_q2_yes">
                                                {{ Form::textarea('declaration_q2_text', null, array('class' =>'form-control input required', 'id'=>'declaration_q2_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                            </div>

                                        </li>

                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant(s) any Share Holder(s) Partners) hold any other Operator from the Commision?
                                            </label>

                                            <div style="margin: 10px 0;" id="declaration_q3">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin-top: 20px;display:none" id="if_declaration_q3_yes">
                                                {{ Form::file('declaration_q3_images',['class'=>'form-control input required','id'=>'declaration_q3_images', 'style'=>'margin-bottom: 20px;','required'])}}
                                                <input  type="hidden" name="declaration_q3_images_base64" id="declaration_q3_images_base64" value="" >

                                            </div>

                                        </li>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein. (Terms and Conditions of License Guidelines for VSAT-HUB Operator are available at www.btrc.gov.bd)</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that this application if found incomplete in any respect and or if found with conditional compliance shall be summarily rejected.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Payment and Submit--}}
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
                    <a href="{{ url('client/vsat-license-cancellation/list/'. Encryption::encodeId(16)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
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

        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                let errorStatus = false;
                if (newIndex == 1) {

                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
                        // alert("The value of the '% of share field' should be a total of 100.");
                        new swal({
                            type: 'error',
                            text: 'The value of the % of share field should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }
                }

                if(newIndex == 3){
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {

                            }else{
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    }else{
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
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
                if(!form.valid()) errorStatus = true;
                if(errorStatus) return false;

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
                    form.find('#submitForm').css('display', 'none');
                    // form.steps("next");
                }
                if (currentIndex === 3) {
                    form.find('#submitForm').css('display', 'inline');
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
            if(isCheckedAcceptTerms()) return false;
            popupWindow = window.open('<?php echo URL::to('/vsat-license-issue/preview'); ?>', 'Sample', '');
        });


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
            let lastRowId = $('#contactPersonRow .card:last').attr('id').split('_')[1];
            let updateRowId = parseInt(lastRowId)+1;
            // console.log(lastRowId);
            // return false;
            $("#contactPersonRow").append(`
            <div class="card card-magenta border border-magenta" style="margin-top: 20px;" id="contact_${updateRowId}">
                                        <div class="card-header">
                                            Contact Person Information
                                            <span style="float: right; cursor: pointer;" class="addContactPersonRow m-l-auto">
                                                <button type="button" onclick="deleteContactRow(contact_${updateRowId})" class="btn btn-danger btn-sm cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                            </span>
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">
            <div class="card-body" style="padding: 5px 0px 0px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('contact_name_${updateRowId}', 'Name', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_name') ? 'has-error' : '' }}">
                                {!! Form::text('contact_person_name[${updateRowId}]', '', ['class' => 'form-control contact_person_name', 'placeholder' => 'Enter The Name', 'id' => 'contact_name_${updateRowId}']) !!}
            {!! $errors->first('contact_person_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
<div class="form-group row">
{!! Form::label('contact_designation_${updateRowId}', 'Designation', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_designation') ? 'has-error' : '' }}">
             {!! Form::text('contact_designation[${updateRowId}]', '', ['class' => 'form-control contact_designation', 'placeholder' => 'Enter The Designation', 'id' => 'contact_designation_${updateRowId}']) !!}
            {!! $errors->first('contact_designation', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_mobile_${updateRowId}', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 {{ $errors->has('contact_mobile') ? 'has-error' : '' }}">
             {!! Form::text('contact_mobile[${updateRowId}]', '', ['class' => 'form-control contact_mobile', 'placeholder' => 'Enter The Mobile Number', 'id' => 'contact_mobile_${updateRowId}']) !!}
            {!! $errors->first('contact_mobile', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_email_${updateRowId}', 'Email', ['class' => 'col-md-4']) !!}
            <div class="col-md-8 {{ $errors->has('contact_person_email') ? 'has-error' : '' }}">
             {!! Form::text('contact_person_email[${updateRowId}]', '', ['class' => 'form-control contact_person_email', 'placeholder' => 'Enter The Email', 'id' => 'contact_email_${updateRowId}']) !!}
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
           {!! Form::select('contact_district[${updateRowId}]', $districts, '', ['class' => 'form-control contact_district', 'id' => 'contact_district_${updateRowId}', 'onchange' => 'getThanaByDistrictId("contact_district_${updateRowId}",this.value, "contact_thana_${updateRowId}",0)']) !!}
            {!! $errors->first('contact_district', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
        <div class="col-md-6">
        <div class="form-group row">
{!! Form::label('contact_thana_${updateRowId}', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
            <div class="col-md-8 {{ $errors->has('contact_thana') ? 'has-error' : '' }}">
             {!! Form::select('contact_thana[${updateRowId}]', [], '', ['class' => 'form-control contact_thana', 'placeholder' => 'Select district at first', 'id' => 'contact_thana_${updateRowId}']) !!}
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
            {!! Form::text('contact_person_address[${updateRowId}]', '', ['class' => 'form-control contact_person_address', 'placeholder' => 'Enter The Address', 'id' => 'contact_person_address_${updateRowId}']) !!}
            {!! $errors->first('contact_person_address', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row" style="margin-bottom:0px!important;">
            {!! Form::label('contact_photo_${updateRowId}', 'Photograph', ['class' => 'col-md-4 required-star']) !!}
            <div class="col-md-8 {{ $errors->has('contact_photo') ? 'has-error' : '' }}">
                <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                    <div class="col-md-8">
                        <input type="file"
                                style="border:none; margin-bottom: 5px;"
                               class="form-control input-sm contact_photo"
                               name='contact_photo[${updateRowId}]' id='contact_photo_${updateRowId}'
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
                                   name='contact_photo_base64[${updateRowId}]' />
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
`);
            getHelpText();
        });
//add shareholder row
        let rowId = 0;
        $(".addShareholderRow").on('click', function() {
            let lastRowId = parseInt($('#shareholderRow tr:last').attr('id').split('_')[1]);
            let newRowId = parseInt(lastRowId)+1;
            $('#shareholderRow').append(
                `<tr id="R_${newRowId}">
<td>
  <div class="card card-magenta border border-magenta">
                                        <div class="card-header">
                                            Shareholder/partner/proprietor Details Information
                                            <span style="float: right; cursor: pointer;" class="m-l-auto">
                                                <button type="button" class="btn btn-danger btn-sm shareholderRow cross-button" style="float:right; margin-bottom:10px;"><strong><i style="font-size: 16px;" class="fa fa-times" aria-hidden="true"></i></strong></button>
                                            </span>
                                        </div>
                                        <div class="card-body" style="padding: 15px 25px;">

    <div class="row">
        <div class="col-md-6">

            <div class="form-group row">
                {!! Form::label('shareholder_name_${newRowId}', 'Name', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_name') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_name[${newRowId}]', '', ['class' => 'form-control shareholder_name', 'placeholder' => 'Enter The Name', 'id' => 'shareholder_name_${newRowId}']) !!}
                {!! $errors->first('shareholder_name', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_designation_${newRowId}', 'Designation', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_designation') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_designation[${newRowId}]', '', ['class' => 'form-control shareholder_designation', 'placeholder' => 'Enter The Designation ', 'id' => 'shareholder_designation_${newRowId}']) !!}
                {!! $errors->first('shareholder_designation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_email_${newRowId}', 'Email', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_email') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_email[${newRowId}]', '', ['class' => 'form-control shareholder_email', 'placeholder' => 'Enter The Email', 'id' => 'shareholder_email_${newRowId}']) !!}
                {!! $errors->first('shareholder_email', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_mobile_${newRowId}', 'Mobile Number', ['class' => 'col-md-4 ']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_mobile') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_mobile[${newRowId}]', '', ['class' => 'form-control shareholder_mobile', 'placeholder' => 'Enter The Mobile Number', 'id' => 'shareholder_mobile_${newRowId}']) !!}
                {!! $errors->first('shareholder_mobile', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group row">
                {!! Form::label('shareholder_share_of_${newRowId}', '% of share', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_share_of') ? 'has-error' : '' }}">
                    {!! Form::number('shareholder_share_of[${newRowId}]', '', ['class' => 'form-control shareholder_share_of', 'placeholder' => '', 'id' => 'shareholder_share_of_${newRowId}']) !!}
                {!! $errors->first('shareholder_share_of', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="nidBlock_${newRowId}" style="display: none;">
                {!! Form::label('shareholder_nid_'.'${newRowId}', 'National ID No', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nid') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_nid[${newRowId}]', '', ['class' => 'form-control shareholder_nid', 'placeholder' => 'Enter National ID No', 'id' => 'shareholder_nid_'.'${newRowId}']) !!}
                {!! $errors->first('shareholder_nid', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="form-group row" id="passportBlock_${newRowId}" style="display: none;">
                {!! Form::label('shareholder_passport_'.'${newRowId}', 'Passport No.', ['class' => 'col-md-4 required-star']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_passport') ? 'has-error' : '' }}">
                    {!! Form::text('shareholder_passport[${newRowId}]', '', ['class' => 'form-control shareholder_passport', 'placeholder' => 'Enter Passport No', 'id' => 'shareholder_passport_'.'${newRowId}']) !!}
                {!! $errors->first('shareholder_passport', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

        </div>


 <div class="col-md-6">
            <div class="form-group row" style="margin-bottom:0px!important;">
                {!! Form::label('correspondent_photo_${newRowId}', 'Photograph', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_image') ? 'has-error' : '' }}">

                    <div class="row" style="margin-bottom:0px!important; padding-bottom:0px!important;">
                        <div class="col-md-8">
                            <input type="file" style="border:none; margin-bottom: 5px;" class="form-control input-sm correspondent_photo"
                                   name='correspondent_photo[${newRowId}]' id="correspondent_photo_${newRowId}" size="300x300"
                                   onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview_${newRowId}', 'correspondent_photo_base64_${newRowId}')" />

                            <span class="text-success" style="font-size: 9px; font-weight: bold; display: block;">
                                              [File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX  | Max Size: 4 KB]
                                            <p style="font-size: 12px;"><a target="_blank" href="https://picresize.com/">You may update your image.</a></p>
                                        </span>
                        </div>
                        <div class="col-md-4">
                            <label class="center-block image-upload" for="correspondent_photo_${newRowId}">
                                <figure>
                                    <img style="height: 80px; width: 80px; border: 1px solid #EBEBEB;"
                                         src="{{asset('assets/images/demo-user.jpg') }}"
                                         class="img-responsive img-thumbnail" id="correspondent_photo_preview_${newRowId}" />
                                </figure>
                                <input type="hidden" id="correspondent_photo_base64_${newRowId}" name="correspondent_photo_base64[${newRowId}]" />
                            </label>
                        </div>



                </div>
            </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
                {!! Form::label('shareholder_dob_${newRowId}', 'Date of Birth', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_dob') ? 'has-error' : '' }}">
                    {{--                                        {!! Form::date('shareholder_dob[]', '', ['class' => 'form-control', 'placeholder' => '', 'id' => 'shareholder_dob']) !!}--}}
                {{--                                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}--}}
                <div class="input-group date datetimepicker4" id="dob${newRowId}" data-target-input="nearest">
                        {!! Form::text('shareholder_dob[${newRowId}]', '', ['class' => 'form-control shareholder_dob', 'id' => 'shareholder_dob_${newRowId}']) !!}
                <div class="input-group-append" data-target="#dob${newRowId}" data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                        {!! $errors->first('shareholder_dob', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
        <div class="form-group row">
{!! Form::label('shareholder_nationality', 'Nationality', ['class' => 'col-md-4']) !!}
                <div class="col-md-8 {{ $errors->has('shareholder_nationality') ? 'has-error' : '' }}">
                    {!! Form::select('shareholder_nationality[${newRowId}]', $nationality, '', ['class' => 'form-control shareholder_nationality', 'id' => 'shareholder_nationality_'.'${newRowId}']) !!}
                {!! $errors->first('shareholder_nationality', '<span class="help-block">:message</span>') !!}
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
        $(document).on('change','.shareholder_nationality' ,function (){
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if(this.value == 18){
                $('#nidBlock_'+lastRowId).show();
                $('#passportBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).hide();
                $('#shareholder_nid_'+lastRowId).addClass('required');
                $('#shareholder_passport_'+lastRowId).removeClass('required');

            }else{
                $('#nidBlock_'+lastRowId).hide();
                $('#passportBlock_'+lastRowId).show();
                $('#shareholder_passport_'+lastRowId).addClass('required');
                $('#shareholder_nid_'+lastRowId).removeClass('required');
            }
        });

        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }


        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function() {
            $('#company_type').val(old_value);
        });

        let company_type = "{{$companyInfo->org_type}}";
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
            let btn = $(this);
            btn.prop("disabled",true);
            btn.after('<i class="fa fa-spinner fa-spin ml-2"></i>');
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
                    btn.prop("disabled",false);
                    getHelpText();
                }
            });
        });

        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });
    });

    // display payment panel
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
        if(isCheckedAcceptTerms()) return false;
        window.open('<?php echo URL::to('/vsat-license-issue/preview'); ?>');
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

    function deleteContactRow(element) {
        element.remove();
    }
    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }
</script>
