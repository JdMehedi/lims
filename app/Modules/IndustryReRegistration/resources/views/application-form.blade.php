<style>

.bootstrap-datetimepicker-widget{
            z-index: 2000;
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
    #total_fixed_ivst_million{
        pointer-events: none;
    }

    /*.wizard > .actions {*/
        /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }
    .radio{
        cursor: pointer;
    }

    .table > thead:first-child > tr:first-child > th {
        font-weight: normal;
        font-size: 16px;
    }

    td {
        font-size: 16px;
    }

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
    .custom_error{
        outline: 1px dashed red;
    }
    .email{
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body{
            padding: 0 15px;
        }
    }



@media (max-width: 480px) {
    .wizard > .actions
    {
        width: 55% !important;
        position: inherit;
    }
    .cacrd-body{
        padding: 0;
    }
    .form-group{
        margin-bottom: 0;
    }
    .wizard>.content>.body label {
        margin-top: .5em;
        margin-bottom: 0;
    }

    .tabInput{
        width: 120px;
    }
    .tabInput_sm{
        width: 75px;
    }
    .tabInputDate{
        width: 150px;
    }

    .table_responsive{
        overflow-x: auto;
    }

    .bootstrap-datetimepicker-widget{
        position: relative !important;
        top:0 !important;
    }

    .prevMob{
        margin-top: 45px;
    }

    /*.wizard > .actions {*/
    /*display: block !important;*/
    /*width: 100% !important;*/
    /*text-align: center;*/
    /*}*/
}
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content load_modal"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12">

    <div class="modal fade" id="ImageUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"> Photo resize</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="upload_crop_area" class="center-block" style="padding-bottom: 25px"></div>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <div id="cropping_msg" class="alert alert-info text-center hidden">
                        <i class="fa fa-spinner fa-pulse"></i> Please wait, Face detecting
                    </div>
                    <div id="button_area">
                        <div class="float-left">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <div class="float-right">
                            <button type="button" id="cropImageBtn" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            {{--Industry registration--}}
            <div class="card" style="border-radius: 10px;">
                <div style="padding: 10px 15px">
                    <span class="section_head">{!!trans('IndustryReRegistration::messages.ind_re_reg')!!}</span>
                </div>
                <div class="card-body">
                    {!! Form::open(array('url' => url('industry-re-registration/store'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'application_form',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'onSubmit' => 'enablePath()')) !!}
                    <h3>{!!trans('CompanyProfile::messages.general_information')!!}</h3>
                    <br>

                    <fieldset>
                        <div id="section_one">
                            {!! Form::hidden('steps', 'step_one' ,['class' => 'form-control input-md required']) !!}
                            {!! Form::hidden('app_id', '' ,['class' => 'form-control input-md  app_id']) !!}
                        {{--Re-reg Info--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('IndustryReRegistration::messages.re_reg_info')!!}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_name_bangla', trans('IndustryReRegistration::messages.company_name_bangla'),
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_name_bangla') ? 'has-error': ''}}">
                                                {!! Form::text('company_name_bangla', (isset($companyInfo->org_nm_bn) ? $companyInfo->org_nm_bn : ''), ['placeholder' => trans("CompanyProfile::messages.write_company_name_bangla"),
                                               'class' => 'form-control input-md bnEng','id'=>'company_name_bangla', 'readonly'=> $companyUserType != 'Employee' ? true : false]) !!}
                                                {!! $errors->first('company_name_bangla','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('service_name_manual', trans('IndustryReRegistration::messages.manual_service_name'),
                                            ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('service_name') ? 'has-error': ''}}">
                                                {!! Form::text('service_name_manual', 'পুনঃ নিবন্ধন', ['placeholder' => trans("IndustryReRegistration::messages.write_manual_service_name"),
                                               'class' => 'form-control input-md','id'=>'service_name_manual', 'readonly' => true]) !!}
                                                {!! $errors->first('service_name_manual','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('manual_reg_number', trans('IndustryReRegistration::messages.manual_reg_number'),
                                                                                        ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('manual_reg_number') ? 'has-error': ''}}">
                                                {!! Form::text('manual_reg_number', null, ['placeholder' => trans("IndustryReRegistration::messages.write_manual_reg_number"),
                                               'class' => 'form-control input-md','id'=>'manual_reg_number']) !!}
                                                {!! $errors->first('manual_reg_number','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row {{$errors->has('manual_reg_date') ? 'has-error': ''}}">
                                            {!! Form::label('manual_reg_date',trans('IndustryReRegistration::messages.manual_reg_date'),['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="input-group date datetimepicker4" id="manual_reg_date" data-target-input="nearest">
                                                    {!! Form::text('manual_reg_date', null , ['class'=>'form-control input_ban', 'id'=>'manual_reg_date']) !!}

                                                    <div class="input-group-append" data-target="#manual_reg_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('manual_reg_date', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('bscic_office_id', trans('IndustryReRegistration::messages.reg_issued_office'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('pref_reg_office') ? 'has-error': ''}}">
                                                {!! Form::select('pref_reg_office', $regOffice, (isset($companyInfo->bscic_office_id) ? $companyInfo->bscic_office_id : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'pref_reg_office']) !!}
                                                {!! $errors->first('pref_reg_office','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('total_investment', trans('CompanyProfile::messages.total_investment'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('total_investment') ? 'has-error': ''}}">
                                                {!! Form::text('total_investment', (isset($companyInfo->investment_limit) ? $companyInfo->investment_limit : ''), ['placeholder' => trans("CompanyProfile::messages.write_total_investment"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'total_investment']) !!}
                                                {!! $errors->first('total_investment','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--General Info--}}


                        {{--Company ceo--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('CompanyProfile::messages.company_ceo')!!}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;" id="ceoInfoDIV">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a  data-toggle="modal" data-target="#directorModel"
                                           onclick="openModal(this)"
                                           data-action="{{ url('client/company-profile/create-info') }}">
                                            <button style="margin-right: 15px; margin-top: 5px;" type="button" class="btn btn-primary ceoInfoDirector " id="addMoreDirector">{!!trans('CompanyProfile::messages.select_identity')!!}</button>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('select_directors', trans('CompanyProfile::messages.select_directors'), ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('select_directors') ? 'has-error': ''}}">
                                                {!! Form::select('select_directors', $companyDirector, (isset($companyInfo->director_type) ? $companyInfo->director_type : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'select_directors']) !!}
                                                {!! $errors->first('select_directors','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_name', trans('CompanyProfile::messages.name'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_name') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_name', (isset($companyInfo->ceo_name) ? $companyInfo->ceo_name : ''), ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                   'class' => 'form-control input-md','id'=>'company_ceo_name', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_fatherName', trans('CompanyProfile::messages.father_name'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_fatherName') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_fatherName', (isset($companyInfo->ceo_father_nm) ? $companyInfo->ceo_father_nm : ''), ['placeholder' => trans("CompanyProfile::messages.write_father_name"),
                                                   'class' => 'form-control input-md','id'=>'company_ceo_fatherName']) !!}
                                                {!! $errors->first('company_ceo_fatherName','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_nationality', trans('CompanyProfile::messages.nationality'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nationality') ? 'has-error': ''}}">
                                                {!! Form::select('company_ceo_nationality', $nationality, (isset($companyInfo->nationality) ? $companyInfo->nationality : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select"),'id'=>'company_ceo_nationality']) !!}
                                                {!! $errors->first('company_ceo_nationality','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="company_ceo_nid_section">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_nid', trans('CompanyProfile::messages.nid'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_nid', (isset($companyInfo->nid) ? $companyInfo->nid : ''), ['placeholder' => trans("CompanyProfile::messages.write_nid"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'company_ceo_nid', 'readonly'=>true ]) !!}
                                                {!! $errors->first('company_ceo_nid','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="company_ceo_passport_section">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_passport', trans('CompanyProfile::messages.passport'),
                                                                                        ['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_passport',(isset($companyInfo->passport) ? $companyInfo->passport : ''), ['placeholder' => trans("CompanyProfile::messages.write_passport"),
                                               'class' => 'form-control input-md required','id'=>'company_ceo_passport', 'readonly'=>true]) !!}
                                                {!! $errors->first('company_ceo_passport','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row {{$errors->has('company_ceo_dob') ? 'has-error': ''}}">
                                            {!! Form::label('company_ceo_dob',trans('CompanyProfile::messages.dob'),['class'=>'col-md-5']) !!}
                                            <div class=" col-md-7">
                                                <div class="input-group date datetimepicker4" id="company_ceo_dob" data-target-input="nearest">
                                                    {!! Form::text('company_ceo_dob', (isset($companyInfo->dob) ? date('d-m-Y', strtotime($companyInfo->dob)) : '') , ['class'=>'form-control input_ban', 'id'=>'company_ceo_dob', 'readonly'=>true]) !!}

                                                    <div class="input-group-append" data-target="#company_ceo_dob"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    {!! $errors->first('company_ceo_dob', '<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_designation_id', trans('CompanyProfile::messages.designation'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_designation_id') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_designation_id', (isset($companyInfo->designation) ? $companyInfo->designation : ''), ['class'=>'form-control required', 'placeholder' => trans("CompanyProfile::messages.write_designation"), 'id'=>'company_ceo_designation_id', 'readonly'=>true]) !!}
                                                {{--                                                {!! Form::select('company_ceo_designation_id', $designation, (isset($companyInfo->designation) ? $companyInfo->designation : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_designation"),'id'=>'company_ceo_designation_id']) !!}--}}
                                                {!! $errors->first('company_ceo_designation_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_division_id', trans('CompanyProfile::messages.division'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_division_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_division_id', $divisions, (isset($companyInfo->ceo_division) ? $companyInfo->ceo_division : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_division"),--}}
{{--'id'=>'company_ceo_division_id', 'onchange'=>"getDistrictByDivisionId('company_ceo_division_id', this.value, 'company_ceo_district_id')"]) !!}--}}
                                                {{--{!! $errors->first('company_ceo_division_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_district_id', trans('CompanyProfile::messages.district'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_district_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_district_id', [], (isset($companyInfo->ceo_district) ? $companyInfo->ceo_district : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_district"),--}}
{{--'id'=>'company_ceo_district_id', 'onchange'=>"getThanaByDistrictId('company_ceo_district_id', this.value, 'company_ceo_thana_id')"]) !!}--}}
                                                {{--{!! $errors->first('company_ceo_district_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_thana_id', trans('CompanyProfile::messages.thana'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_thana_id') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::select('company_ceo_thana_id', [], (isset($companyInfo->ceo_thana) ? $companyInfo->ceo_thana : ''), ['class' =>'form-control input-md','placeholder'=> trans("CompanyProfile::messages.select_thana"),'id'=>'company_ceo_thana_id']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-md-6">--}}
                                            {{--{!! Form::label('company_ceo_postCode', trans('CompanyProfile::messages.post_code'),--}}
                                            {{--['class'=>'col-md-5']) !!}--}}
                                            {{--<div class="col-md-7 {{$errors->has('company_ceo_postCode') ? 'has-error': ''}}">--}}
                                                {{--{!! Form::text('company_ceo_postCode', (isset($companyInfo->ceo_postcode) ? $companyInfo->ceo_postcode : ''), ['placeholder' => trans("CompanyProfile::messages.write_post_code"),--}}
                                               {{--'class' => 'form-control input-md  input_ban','id'=>'company_ceo_postCode']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_postCode','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-md-12">--}}
                                            {{--{!! Form::label('company_ceo_address', trans('CompanyProfile::messages.address'),--}}
                                            {{--['class'=>'col-md-2']) !!}--}}
                                            {{--<div class="col-md-10 {{$errors->has('company_ceo_address') ? 'has-error': ''}}" style="width: 79.5%; float: right">--}}
                                                {{--{!! Form::text('company_ceo_address', (isset($companyInfo->ceo_location) ? $companyInfo->ceo_location : ''), ['placeholder' => trans("CompanyProfile::messages.write_address"),--}}
                                               {{--'class' => 'form-control input-md','id'=>'company_ceo_address']) !!}--}}
                                                {{--{!! $errors->first('company_ceo_address','<span class="help-block">:message</span>') !!}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_email', trans('CompanyProfile::messages.email'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_email', (isset($companyInfo->ceo_email) ? $companyInfo->ceo_email : ''), ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email','id'=>'company_ceo_email']) !!}
                                                {!! $errors->first('company_ceo_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('company_ceo_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('company_ceo_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('company_ceo_mobile', (isset($companyInfo->ceo_mobile) ? $companyInfo->ceo_mobile : ''), ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'company_ceo_mobile', 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('company_ceo_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="col-md-7 col-centered">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="signature-upload" id="sign_div_error">
                                                <div class="col-md-12">
                                                    <label class="center-block image-upload" for="correspondent_signature">
                                                        <figure>
                                                            <img src="{{  (!empty($companyInfo->entrepreneur_signature)? url('/'.$companyInfo->entrepreneur_signature) : url('assets/images/photo_default.png')) }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="correspondent_signature_preview" width="75px"/>
                                                        </figure>
                                                        <input type="hidden" id="correspondent_signature_base64"
                                                               name="correspondent_signature_base64"/>
                                                    </label>
                                                    <p style="font-size: 16px;">
                                                        আপনার স্ক্যান স্বাক্ষরটি এখানে আপলোড করুন বা <strong style="color: #259BFF">ব্রাউজ করুন</strong>
                                                    </p>
                                                    <span style="font-size: 10px; font-weight: bold; display: block; color: #A6A6A6">
                                                                [File Format: *.jpg/ .jpeg .png | Maximum 5 MB]
                                                                </span>
                                                    <input type="file" class="form-control signature-upload-input input-sm  {{ !empty($companyInfo->entrepreneur_signature)? '':'required' }}"
                                                           name="correspondent_signature"
                                                           id="correspondent_signature"
                                                           onchange="imageUploadWithCropping(this, 'correspondent_signature_preview', 'correspondent_signature_base64')"
                                                           size="300x80"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <p style="font-size: 16px;">
                                                        <a target="_blank" href="https://picresize.com/">আপনার  ইমেজ মডিফাই  করতে পারেন</a>
                                                    </p>
                                                    <span  style="color: #FF7272">প্রয়োজনীয় সকল কাগজপত্র এই স্বাক্ষরের মাধ্যমে স্বাক্ষরিত হতে হবে</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div style="height: 45px; margin: 0 -25px; background: #F2F3F5;"></div>

                        {{--Reg info--}}
                        <div style="padding: 10px 0px">
                            <span class="section_head">{!!trans('CompanyProfile::messages.reg_information')!!}</span>
                        </div>

                        {{--Investment--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                চ. {!!trans('CompanyProfile::messages.investment')!!}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        {!! trans('CompanyProfile::messages.country_wise_loan_source') !!}
                                    </div>
                                    <div class="card-body" style="padding: 0">
                                        <div class="table_responsive">
                                            <table id="loanSourceTable" class="table table-bordered">
                                                <thead>
                                                <tr class="text-center section_heading2" style="font-size: 16px;">
                                                    <th style="font-weight: normal;" class="text-center">
                                                        {!! trans('CompanyProfile::messages.country_name') !!}</th>
                                                    <th style="font-weight: normal;" class="text-center">
                                                        {!! trans('CompanyProfile::messages.org_name') !!}</th>
                                                    <th style="font-weight: normal;" class="text-center">
                                                        {!! trans('CompanyProfile::messages.loan_amount') !!}</th>
                                                    <th style="font-weight: normal;" class="text-center">
                                                        {!! trans('CompanyProfile::messages.loan_taking_date') !!}</th>
                                                    <th style="font-weight: normal;" class="text-center">
                                                        {!! trans('CompanyProfile::messages.action') !!}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id="loanSourceRow" data-number="1">
                                                    <td class="text-cente">
                                                        {!! Form::select('loan_country_id[0]', $country, '', [
                                                            'class' => 'form-control input-md tabInput',
                                                            'placeholder' => trans('CompanyProfile::messages.select'),
                                                            'id' => 'loan_country_id',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('loan_org_name[0]', '', [
                                                            'class' => 'form-control input-md text-center tabInput',
                                                            'placeholder' => trans('CompanyProfile::messages.org_name'),
                                                            'id' => 'loan_org_name',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('loan_amount[0]', '', [
                                                            'class' => 'form-control input-md text-center onlyNumber input_ban',
                                                            'placeholder' => trans('CompanyProfile::messages.loan_amount'),
                                                            'id' => 'loan_amount',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        <div class="input-group date datetimepicker4"
                                                             id="datepicker0" data-target-input="nearest">
                                                            {!! Form::text('loan_receive_date[0]', '', ['class' => 'form-control input_ban', 'id' => 'loan_receive_date']) !!}
                                                            <div class="input-group-append"
                                                                 data-target="#datepicker0"
                                                                 data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                            {!! $errors->first('loan_receive_date', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-info addTableRows"
                                                           title="Add more"
                                                           onclick="addTableRow('loanSourceTable', 'loanSourceRow');"><i
                                                                class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.machinery')!!}</h3>
                    <fieldset>
                        <div id="section_two">
                            {!! Form::hidden('steps', 'step_two' ,['class' => 'form-control input-md required']) !!}
                            {!! Form::hidden('app_id', '' ,['class' => 'form-control input-md app_id']) !!}


                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('CompanyProfile::messages.raw_material_package_details')!!}
                            </div>
                            <div class="card-body table-responsive" style="padding: 15px 25px;">

                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header" style="border-bottom: none; padding: 7px 25px;">
                                        ক. {!!trans('CompanyProfile::messages.locally_collected')!!}
                                    </div>
                                    <div class="card-body table-responsive" style="padding: 15px 25px;">
                                        <?php
                                        $elementArray = [
                                            ['label' => trans('CompanyProfile::messages.raw_material_package_name'), 'type' => 'input', 'field_id' => 'local_raw_material_name','placeholder'=>'নাম লিখুন', 'class'=>'text-center'],

                                            ['label' => trans('CompanyProfile::messages.amount'), 'type' => 'input', 'field_id' => 'local_raw_material_quantity','placeholder'=>'পরিমাণ লিখুন','class'=>'onlyNumber input_ban tabInput text-center'
                                            ],
                                            ['label' => trans('CompanyProfile::messages.amount_unit'), 'type' => 'select', 'field_id' => 'local_raw_material_unit','placeholder'=>trans('CompanyProfile::messages.select'),'class'=>'input-md tabInput','db_table' => 'apc_units', 'option_value' => 'id', 'option_text' => 'name_bn','sql' => 'select id, name_bn as value from apc_units ORDER BY name_bn ASC'],
                                            ['label' => trans('CompanyProfile::messages.price'), 'type' => 'input', 'field_id' => 'local_raw_material_amount_bdt','placeholder'=>'মূল্য','class'=>'onlyNumber text-center input_ban local_raw_material_amount_bdt tabInput','js_function'=>"onkeyup=\"calculateMachineryTotal('local_raw_material_amount_bdt', 'local_raw_price_total')\""]
                                        ];

                                        $tableInfo = ['locallyCollectedRawMaterialTable', 'locallyCollectedRow0', '',  'section_heading1'];
                                        ?>

                                        {!! CommonComponent()->addRow($elementArray, $tableInfo) !!}
                                        <table class="table">
                                            <tr>

                                                <td class="text-right" width="70%">{!!trans('CompanyProfile::messages.grand_total')!!}</td>

                                                <td rowspan="1">
                                                    {!! Form::text('local_raw_price_total', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'local_raw_price_total']) !!}
                                                    {!! $errors->first('local_raw_price_total','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="text-right" width="10%"></td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>

                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        খ. {!!trans('CompanyProfile::messages.imported')!!}
                                    </div>
                                    <div class="card-body table-responsive" style="padding: 15px 25px;">
                                        <?php
                                        $elementArray = [
                                            ['label' => trans('CompanyProfile::messages.raw_material_package_name'), 'type' => 'input', 'field_id' => 'imported_raw_material_name','placeholder'=>'নাম লিখুন', 'class'=>'text-center'],

                                            ['label' => trans('CompanyProfile::messages.amount'), 'type' => 'input', 'field_id' => 'imported_raw_material_quantity','placeholder'=>'পরিমাণ লিখুন','class'=>'onlyNumber input_ban tabInput text-center'
                                            ],
                                            ['label' => trans('CompanyProfile::messages.amount_unit'), 'type' => 'select', 'field_id' => 'imported_raw_material_unit','placeholder'=>trans('CompanyProfile::messages.select'),'class'=>'input-md tabInput','db_table' => 'apc_units', 'option_value' => 'id', 'option_text' => 'name_bn','sql' => 'select id, name_bn as value from apc_units ORDER BY name_bn ASC'],
                                            ['label' => trans('CompanyProfile::messages.price'), 'type' => 'input', 'field_id' => 'imported_raw_material_amount_bdt','placeholder'=>'মূল্য','class'=>'onlyNumber text-center input_ban imported_raw_material_amount_bdt tabInput','js_function'=>"onkeyup=\"calculateMachineryTotal('imported_raw_material_amount_bdt', 'imported_raw_price_total')\""]
                                        ];

                                        $tableInfo = ['importedRawMaterialTable', 'importedRow0', '',  'section_heading1'];
                                        ?>

                                        {!! CommonComponent()->addRow($elementArray, $tableInfo) !!}
                                        <table class="table">
                                            <tr>

                                                <td class="text-right" width="70%">{!!trans('CompanyProfile::messages.grand_total')!!}</td>

                                                <td rowspan="1">
                                                    {!! Form::text('imported_raw_price_total', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.price_taka'), 'id'=> 'imported_raw_price_total']) !!}
                                                    {!! $errors->first('imported_raw_price_total','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="text-right" width="10%"></td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>

{{--                                <table id=""--}}
{{--                                       class="table table-bordered"--}}
{{--                                       cellspacing="0">--}}
{{--                                    <thead>--}}
{{--                                    <tr style="background: #F7F7F7">--}}
{{--                                        <th class="text-center">{!!trans('CompanyProfile::messages.number')!!}</th>--}}
{{--                                        <th class="text-center">{!!trans('CompanyProfile::messages.raw_material_package_source')!!}</th>--}}
{{--                                        <th class="text-center">{!!trans('CompanyProfile::messages.n_number')!!}</th>--}}
{{--                                        <th class="text-center">{!!trans('CompanyProfile::messages.price_taka')!!}</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            ১--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            স্থানীয়--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('local_raw_material_number', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.n_number'),--}}
{{--'id'=> 'local_raw_material_number', 'onkeyup'=>"calculateRawMaterialNumber()"]) !!}--}}
{{--                                            {!! $errors->first('local_raw_material_number','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('local_raw_material_price', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.price_taka'),--}}
{{--'id'=> 'local_raw_material_price', 'onkeyup'=>"calculateRawMaterialPrice()"]) !!}--}}
{{--                                            {!! $errors->first('local_raw_material_price','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            2--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            আমদানিযোগ্য--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('import_raw_material_number', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.n_number'),--}}
{{--'id'=> 'import_raw_material_number', 'onkeyup'=>"calculateRawMaterialNumber()"]) !!}--}}
{{--                                            {!! $errors->first('import_raw_material_number','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('import_raw_material_price', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'placeholder'=> trans('CompanyProfile::messages.price_taka'),--}}
{{--'id'=> 'import_raw_material_price', 'onkeyup'=>"calculateRawMaterialPrice()"]) !!}--}}
{{--                                            {!! $errors->first('import_raw_material_price','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            ৩--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            সর্বমোট--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('raw_material_total_number', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.n_number'), 'id'=> 'raw_material_total_number']) !!}--}}
{{--                                            {!! $errors->first('raw_material_total_number','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {!! Form::text('raw_material_total_price', '', ['class' => 'form-control input-md text-center onlyNumber input_ban tabInput', 'readonly', 'placeholder'=> trans('CompanyProfile::messages.taka'), 'id'=> 'raw_material_total_price']) !!}--}}
{{--                                            {!! $errors->first('raw_material_total_price','<span class="help-block">:message</span>') !!}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.attachments')!!}</h3>
                    <fieldset>
                        <div id="section_three">
                            {!! Form::hidden('steps', 'step_three' ,['class' => 'form-control input-md required']) !!}
                            {!! Form::hidden('app_id', '' ,['class' => 'form-control input-md  app_id']) !!}
                        {{--Applicant info--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('CompanyProfile::messages.approved_applicant_info')!!}
                            </div>

                            <div class="card-body" style="padding: 15px 25px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_nm', trans('CompanyProfile::messages.name'),
                                                                                        ['class'=>'col-md-5 ']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_nm') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_nm', Auth::user()->user_first_name, ['placeholder' => trans("CompanyProfile::messages.write_name"),
                                                   'class' => 'form-control input-md','id'=>'auth_person_nm']) !!}
                                                {!! $errors->first('auth_person_nm','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_desig', trans('CompanyProfile::messages.designation'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_desig') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_desig',Auth::user()->designation, ['placeholder' => trans("CompanyProfile::messages.write_designation"),
                                                   'class' => 'form-control input-md','id'=>'auth_person_desig']) !!}
                                                {!! $errors->first('auth_person_desig','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_address', trans('CompanyProfile::messages.address'),
                                                                                        ['class'=>'col-md-3']) !!}
                                            <div class="col-md-8 {{$errors->has('auth_person_address') ? 'has-error': ''}}" style="margin-left: -48px;">
                                                {!! Form::text('auth_person_address', '', ['placeholder' => trans("CompanyProfile::messages.write_address"),
                                               'class' => 'form-control input-md','id'=>'auth_person_address']) !!}
                                                {!! $errors->first('auth_person_address','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_mobile', trans('CompanyProfile::messages.mobile_no'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_mobile',Auth::user()->user_mobile, ['placeholder' => trans("CompanyProfile::messages.write_mobile_no"),
                                               'class' => 'form-control input-md input_ban onlyNumber','id'=>'auth_person_mobile' , 'onkeyup' => 'mobile_no_validation(this.id)']) !!}
                                                {!! $errors->first('auth_person_mobile','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('auth_person_email', trans('CompanyProfile::messages.email'),
                                                                                        ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('auth_person_email') ? 'has-error': ''}}">
                                                {!! Form::text('auth_person_email',Auth::user()->user_email, ['placeholder' => trans("CompanyProfile::messages.write_email"),
                                               'class' => 'form-control input-md email','id'=>'auth_person_email']) !!}
                                                {!! $errors->first('auth_person_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('trade_license') ? 'has-error': ''}}">
                                        <div class="form-group row">
                                            {!! Form::label('trade_license ','Trade license :',['class'=>'text-left col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                <input type="file"  class="form-control input-sm " name="authorization_letter" id="trade_license"
                                                       onchange="uploadAppDocument('preview_trade_license', this.id, 'validate_field_trade_license', '0', '1024', '1')"/>
                                                {!! $errors->first('trade_license','<span class="help-block">:message</span>') !!}

                                                <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg. Example file.jpg]</span>

                                                <div id="preview_trade_license">
                                                    <input type="hidden"
                                                           id="validate_field_trade_license"
                                                           name="validate_field_trade_license" class="required">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            {!! Form::label('correspondent_photo', trans('CompanyProfile::messages.image'), ['class' => 'col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-sm-9">
                                                        <input type="file"
                                                               class="form-control input-sm {{ !empty(Auth::user()->user_pic) ? '' : 'required' }}"
                                                               name="correspondent_photo" id="correspondent_photo"
                                                               onchange="imageUploadWithCroppingAndDetect(this, 'correspondent_photo_preview', 'correspondent_photo_base64')"
                                                               size="300x300" />
                                                        <span class="text-success"
                                                              style="font-size: 9px; font-weight: bold; display: block;">[File
                                                        Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]
                                                        <p style="font-size: 12px;">
                                                            <a target="_blank" href="https://picresize.com/">আপনার
                                                                ইমেজ মডিফাই
                                                                করতে পারেন</a>
                                                        </p>
                                                    </span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="center-block image-upload"
                                                               for="correspondent_photo">
                                                            <figure>
                                                                <img src="{{ !empty(\Illuminate\Support\Facades\Auth::user()->user_pic) ? '/' . \Illuminate\Support\Facades\Auth::user()->user_pic : url('assets/images/photo_default.png') }}"
                                                                     class="img-responsive img-thumbnail"
                                                                     id="correspondent_photo_preview" />
                                                                <figcaption><i class="fa fa-camera"></i></figcaption>
                                                            </figure>
                                                            <input type="hidden" id="correspondent_photo_base64"
                                                                   name="correspondent_photo_base64" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        {{--Necessary attachment--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('CompanyProfile::messages.necessary_attachment')!!}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <input type="hidden" id="doc_type_key" name="doc_type_key">
                                <div id="docListDiv"></div>
                                <span><p>সংযুক্তির নির্দেশিকা- সার্ভিসের নাম ও প্রতিষ্ঠানের ধরণ নির্ধারণের পর সংযুক্তির তালিকা প্রদর্শিত হবে।</p></span>
                            </div>
                        </div>

                        {{--Announcement--}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                {!!trans('CompanyProfile::messages.announcement')!!}
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <p style="color: #452A73;">
                                    বাংলাদেশ ক্ষুদ্র ও কুটির শিল্প করপোরেশন (বিসিক) এর ওয়ান স্টপ সার্ভিস সিস্টেমের
                                    মাধ্যমে জমাকৃত আবেদনপত্রে কোন ধরণের অসঙ্গতি পরিলক্ষিত হলে গণপ্রজাতন্ত্রী বাংলাদেশ
                                    সরকারের আইসিটি আইনের আওতায় দায়বদ্ধ থাকবেন।
                                </p>
                                <br>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                        আমি এই মর্মে ঘোষণা করছি যে উপরে বর্ণিত যাবতীয় তথ্য সঠিক আছে। কোন তথ্য গোপন করা
                                        হয় নাই। কোন তথ্য সঠিক পাওয়া না গেলে কর্তৃপক্ষ শিল্প প্রতিষ্ঠানটির নিবন্ধন বাতিল
                                        করতে পারবেন এবং যে কোন প্রকার প্রশাসনিক ও আইনানুগ ব্যবস্থা গ্রহণ করতে পারবে। আমি
                                        আরো ঘোষণা করছি যে, কর্তৃপক্ষ কর্তৃক সরেজমিনে পরিদর্শনকালে সকল প্রকার সহযোগিতা
                                        প্রদান করতে বাধ্য থাকবো।
                                    </label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <h3>{!!trans('CompanyProfile::messages.payment_and_submit')!!}</h3>
                    <fieldset>
                        <div id="section_four">
                            {!! Form::text('steps', 'step_four' ,['class' => 'form-control input-md required']) !!}
                            {!! Form::hidden('app_id', '' ,['class' => 'form-control input-md  app_id']) !!}

                        </div>
                        <div id="payment_panel"></div>
                    </fieldset>

                        <div class="float-left">
{{--                            <button type="submit" class="btn btn-info btn-md cancel"--}}
{{--                                    value="draft" name="actionBtn" id="save_as_draft">Save as Draft--}}
{{--                            </button>--}}

                            <button type="button" class="btn btn-info btn-md cancel"
                                    value="section_one" name="actionBtn" id="saveData">
                                    <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                        <div class="float-left" style="padding-left: 1em;">
                            <button type="button" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md"
                                    value="Submit" name="actionBtn">Pay Now
                                <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info." aria-describedby="tooltip"></i>
                            </button>
                        </div>

                    {!! Form::close() !!}

                </div>

            </div>{{--Industry registration--}}

    </div>
</div>


<!-- Modal Govt Payment-->
<div class="modal fade" id="feeModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">সরকারী ফি ক্যালকুলেটর এবং শিল্পের ধরণ</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">সি:নং</th>
                        <th colspan="3" scope="colgroup">ফি এর পর্যায়ক্রম টাকায় </th>
                        <th scope="col">ফি টাকা </th>
                        <th scope="col">শিল্পের ধরণ</th>
                        <th scope="col">সার্ভিস ফি</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($totalFee as $fee)
                        <tr>
                            <td scope="row">{{ $i++ }}</td>
                            <td>{{ $fee->inv_limit_start }}</td>
                            <td>টু</td>
                            <td>{{ $fee->inv_limit_end }}</td>
                            <td>{{ $fee->reg_fee }}</td>
                            <td>{{ $fee->name_bn }}</td>
                            <td>{{ $fee->oss_fee }}</td>

                        </tr>
                    @endforeach

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

@include('partials.image-upload')

<script src="{{ asset('assets/scripts/common-component.js') }}" type="text/javascript"></script>
<script>
    function enablePath() {
        document.getElementById('company_type_id').disabled= "";
        document.getElementById('company_office_division_id').disabled= "";
        document.getElementById('company_office_district_id').disabled= "";
        document.getElementById('company_office_thana_id').disabled= "";
    }

    var selectCountry = '';

    $(document).ready(function () {

        @isset($companyInfo->office_division)
        getDistrictByDivisionId('company_office_division_id', {{$companyInfo->office_division ?? ""}}, 'company_office_district_id', {{$companyInfo->office_district ?? ""}});
        @endisset
        @isset($companyInfo->office_district)
        getThanaByDistrictId('company_office_district_id', {{$companyInfo->office_district ?? ""}}, 'company_office_thana_id', {{$companyInfo->office_thana ?? ""}});
        @endisset


        @isset($companyInfo->factory_division)
        getDistrictByDivisionId('company_factory_division_id', {{$companyInfo->factory_division ?? ""}}, 'company_factory_district_id', {{$companyInfo->factory_district ?? ""}});
        @endisset
        @isset($companyInfo->factory_district)
        getThanaByDistrictId('company_factory_district_id', {{$companyInfo->factory_district ?? ""}}, 'company_factory_thana_id', {{$companyInfo->factory_thana ?? ""}});
        @endisset

        // ceo section
        @isset($companyInfo->ceo_division)
        getDistrictByDivisionId('company_ceo_division_id', {{$companyInfo->ceo_division ?? ""}}, 'company_ceo_district_id', {{$companyInfo->ceo_district ?? ""}});
        @endisset
        @isset($companyInfo->ceo_district)
        getThanaByDistrictId('company_ceo_district_id', {{$companyInfo->ceo_district ?? ""}}, 'company_ceo_thana_id', {{$companyInfo->ceo_thana ?? ""}});
        @endisset


        loadApplicationDocs('docListDiv', '');

        var form = $("#application_form").show();
        // form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            enableFinishButton: false,
            onInit: function (event, currentIndex) {
                $('.actions > ul > li:first-child').attr('style', 'display:none');

                $(".actions a[href='#next']").attr({ style:"background:#3273AB", value:"draft" });
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                 return true;
                var imagePath = "{{!empty($companyInfo->entrepreneur_signature)? $companyInfo->entrepreneur_signature : ''}}";

                if (imagePath == ""){
                    if ($("#correspondent_signature").hasClass("error") || $("#correspondent_signature").val() == ""){
                        $('.signature-upload').addClass('custom_error')
                    }else{
                        $('.signature-upload').removeClass('custom_error')
                    }
                }
                 // retuauth_person_mobilern true;
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex)
                {
                    return true;
                }

                if (newIndex === 2)
                {
                    local_machinery_ivst = $('#local_machinery_ivst').val();

                    local_machinery_total = parseInt($('#local_machinery_total').val());
                    imported_machinery_total = parseInt($('#imported_machinery_total').val());
                    totalsum= local_machinery_total + imported_machinery_total;
                    // alert(local_machinery_ivst);
                    // alert(totalsum);
                    //
                    // if(local_machinery_ivst != totalsum){
                    //     swal({
                    //         type: 'error',
                    //         text: 'নোটিশঃ আবেদন এর প্রথম ধাপের "চ. বিনিয়োগ" সেকশনের মধ্যে "স্থায়ী বিনিয়োগ" সাব-সেকশনের এর "যন্ত্রপাতি ও সরঞ্জামাদি" এর জন্য যত টাকা দেয়া হবে, আবেদনের দ্বিতীয় ধাপে যন্ত্রপাতি ও সরঞ্জামাদির তথ্য তে স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য + আমদানিকৃত/আমদানিতব্য মোট টাকার পরিমান সমান হতে হবে। অন্যথায় আবেদন গ্রহণ করা যাবেনা।',
                    //     });
                    //     return false;
                    // }
                    // local_machinery_ivst == local_machinery_total+imported_machinery_total

                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18)
                {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
                // return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                var form1;
                var actionBtn = $( "#application_form" ).find( ".next" ).attr("value");

                if (currentIndex > 0) {
                    $('.actions > ul > li:first-child').attr('style', '');
                } else {
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                }

                if (currentIndex === 1) {
                    form1 = $("#section_one :input").serialize(); //Get Form ID
                    var company_ceo_mobile = $("#company_ceo_mobile").intlTelInput('getNumber');
                    form1 = form1 + "&company_ceo_mobile=" + company_ceo_mobile + "&actionBtn=" + actionBtn

                    if (storeData(form1) === 'success') {
                        $("#saveData").val('section_two')
                    } else {
                        $(this).steps("previous");
                    }
                }

                if (currentIndex === 2) {
                    form1 = $("#section_two :input").serialize(); //Get Form ID
                    form1 = form1 + "&actionBtn=" + actionBtn
                    var mobile_no = $("#auth_person_mobile").intlTelInput('getNumber');
                    form1 = form1 + "&mobile_no=" + mobile_no + "&actionBtn=" + actionBtn

                    if (storeData(form1) === 'success') {
                        $("#saveData").val('section_three');
                    } else {
                        $(this).steps("previous");
                    }
                }

                if (currentIndex === 3) {
                    form1 = $("#section_three :input").serialize(); //Get Form ID
                    var mobile_no = $("#sfp_contact_phone").intlTelInput('getNumber');
                    form1 = form1 + "&mobile_no=" + mobile_no + "&actionBtn=" + actionBtn

                    if (storeData(form1) === 'success') {
                        $("#saveData").val('section_four')
                    } else {
                        $(this).steps("previous");
                    }

                    form.find('#submitForm').css('display', 'block');
                    form.find('.previous').addClass('prevMob');
                } else {
                    $('ul[aria-label=Pagination] input[class="btn"]').remove();
                    form.find('#submitForm').css('display', 'none');
                    form.find('.previous').removeClass('prevMob');
                }
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                alert("Submitted!");
            },
            values:{

            },
            labels: {
                // cancel: "Cancel",
                // current: "current step:",
                // pagination: "Pagination",
                // finish: 0,
                next: "Save and Next",
                // previous: "Previous",
                // loading: "Loading ..."
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) { element.before(error); },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        $("#submitForm").click(function (){
            // var value = $(this).val();
            var value = 'Submit';
            var form4 = $("#section_four :input").serialize(); //Get Form ID
            var mobile_no = $("#sfp_contact_phone").intlTelInput('getNumber');
            var form5 = form4 + "&sfp_contact_phone=" + mobile_no + "&actionBtn=" + value;
            storeData(form5);
        })

        $("#saveData").click(function () { // for individual data store
            var value = $(this).val();
            var actionBtn = 'draft';

            var form;
            if (value === 'section_one') {
                form = $("#section_one :input").serialize(); //Get Form ID
                var company_ceo_mobile = $("#company_ceo_mobile").intlTelInput('getNumber');
                form = form + "&company_ceo_mobile=" + company_ceo_mobile + "&actionBtn=" + actionBtn

            } else if (value === 'section_two') {
                form = $("#section_two :input").serialize(); //Get Form ID
                form = form + "&actionBtn=" + actionBtn

            } else if (value === 'section_three') {
                form = $("#section_three :input").serialize(); //Get Form ID

                var mobile_no = $("#auth_person_mobile").intlTelInput('getNumber');
                form = form + "&mobile_no=" + mobile_no + "&actionBtn=" + actionBtn


            } else if (value === 'section_four') {
                form = $("#section_three :input").serialize(); //Get Form ID
                var mobile_no = $("#sfp_contact_phone").intlTelInput('getNumber');
                form = form + "&mobile_no=" + mobile_no + "&actionBtn=" + actionBtn
            } else {
                toastr.success('Please data entry properly');
                return false;
            }

            storeData(form);
        });


        function storeData(form){
            console.log(form);
            var result = 'error';
            $.ajax({
                type: 'POST',
                url: '/industry-re-registration/ajax-store',
                data: form,
                async: false,
                dataType: 'json',
                beforeSend: function (msg) {
                    console.log("before send");
                    // $("#block_create_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    // $("#block_create_btn").prop('disabled', true); // disable button
                },
                success: function (response) {
                    console.log(response)
                    result = 'success';
                    if(parseInt(response.responseCode) === 1){
                        $(".app_id").val(response.app_id);
                        if(response.redirectUrl){
                            document.location.href = response.redirectUrl
                        }else{
                            toastr.success('Data save successfully for the step')
                            window.history.pushState('', '', '/client/process/industry-re-registration/edit/'+response.encrypted_ids);
                        }
                    }
                    if(parseInt(response.responseCode) === 0){
                        alert(response.html)
                    }

                    return true;
                },
                error: function (response) {
                    result = 'error';
                    const errors = response.html;
                    toastr.error('Something was wrong');
                    console.log(errors);
                }
            });
            return result;
        }

    });

    $(document).ready(function () {
        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');

        {{--initail -input mobile plugin script start--}}
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
        {{--initail -input mobile plugin script end--}}

    })

    function mobile_no_validation(id){
        var id = id;
        $("#"+id).on('keyup', function () {
            var countryCode = $("#"+id).intlTelInput("getSelectedCountryData").dialCode;

            if (countryCode === "880"){
                var mobile = $("#"+id).val();
                var reg = /^0/;
                if (reg.test(mobile)) {
                    $("#"+id).val("");
                }
                if(mobile.length !=10){
                    $("#"+id).addClass('error')
                }
            }
        });
    }

    function CalculateTotalInvestmentTk() {
        var land = parseFloat(document.getElementById('local_land_ivst').value);
        var building = parseFloat(document.getElementById('local_building_ivst').value);
        var machine = parseFloat(document.getElementById('local_machinery_ivst').value);
        var other = parseFloat(document.getElementById('local_others_ivst').value);
        var wcCapital = parseFloat(document.getElementById('local_wc_ivst').value);
        var totalInvest = ((isNaN(land) ? 0 : land) + (isNaN(building) ? 0 : building) + (isNaN(machine) ? 0 : machine) + (isNaN(other) ? 0 : other) + (isNaN(wcCapital) ? 0 : wcCapital)).toFixed(2);
        var totalTk = totalInvest;
        document.getElementById('total_fixed_ivst_million').value = totalTk;
        // document.getElementById('total_invt_bdt').value = totalTk;
        if ($('#total_fixed_ivst_million').val() != null){
            calculateTotalDollar();
        }

        var totalFee = '<?php echo json_encode($totalFee); ?>';

        var fee = 0;
        if (totalTk != 0) {
            $.each(JSON.parse(totalFee), function (i, row) {
                if ((totalTk >= parseInt(row.inv_limit_start)) && (totalTk <= parseInt(row.inv_limit_end))) {
                    fee = parseInt(row.reg_fee);
                }
                if (totalTk >= 500000001) {
                    fee = 3000;
                }
            });
        } else {
            fee = 0;
        }
        $("#total_fee").val(fee.toFixed(2));
    }

    function calculateTotalDollar(){
        var total_fixed_ivst_million = $('#total_fixed_ivst_million').val();
        var usd_exchange_rate = $('#usd_exchange_rate').val();
        var usd_amount = (total_fixed_ivst_million/usd_exchange_rate).toFixed(2);
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
            (isNaN(import_raw_material_number) ? 0 : import_raw_material_number));
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
    $(document).on('keydown','#local_wc_ivst_ccy',function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#usd_exchange_rate').focus();
        }
    })
    $(document).on('keydown','#usd_exchange_rate',function (e) {
        if (e.which == 9) {
            e.preventDefault();
            $('#ceo_taka_invest').focus();
        }
    })
    $(document).on('change','.companyInfoChange',function (e){
        $('#same_address').trigger('change');
    })
    $(document).on('blur','.companyInfoInput',function (e){
        $('#same_address').trigger('change');
    })
    $(document).ready(function () {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && ($companyInfo->is_same_address === 0)}}"){
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false){
            $('#company_factory_div').removeClass('hidden');
        }

        $('#same_address').change(function() {

            if(this.checked === false) {
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
            }
            else{
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
                    if(investment_type_id == 1){
                        $('#investing_country_id').attr('multiple', 'multiple');
                        //Select2
                        $("#investing_country_id").select2();
                    }else{
                        if ($("#investing_country_id").data('select2')){
                            $("#investing_country_id").select2('destroy');
                        }
                        $('#investing_country_id').removeAttr('multiple');
                    }

                    if(investment_type_id == 3){
                        var option = "";
                    }else{
                        var option = '<option value="">{{trans("CompanyProfile::messages.select")}}</option>';
                    }
                    selectCountry  = "{{$investing_country->country_id ?? ""}}";
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            var repId = (id.replace(' ',''))
                            if($.inArray(repId,selectCountry.split(',')) != -1){
                                option += '<option value="' + repId + '" selected>' + value + '</option>';
                            }else{
                                option += '<option value="' + repId + '">' + value + '</option>';
                            }

                        });
                    }
                    $("#investing_country_id").html(option);
                    $(self).next().hide();
                    // multiple if type one
                    // multiple if type one
                    var country_ids = "{{ (isset($investing_country->country_id) ? $investing_country->country_id : '') }}";
                    @if((isset($companyInfo->invest_type) ? $companyInfo->invest_type == '1' : '') )
                    $('#investing_country_id').val(country_ids.split(',')).change();
                    @else
                    $("#investing_country_id").val(country_ids).change();
                    @endif

                }
            });
        });
        $("#investment_type_id").trigger('change');

        $("#total_investment").change(function() {
            var total_investment = $('#total_investment').val();
            var vat_percentage = parseFloat('{{ $vat_percentage }}');

            let oss_fee = 0;
            let vat = 0;

            $.ajax({
                type: "GET",
                url: "{{ url('industry-re-registration/get-industry-type-by-investment') }}",
                data: {
                    total_investment: total_investment
                },
                success: function(response) {
                    $("#industrial_category_id").val(response.data);
                    if (response.data !== "") {
                        $("#industrial_category_id").find("[value!='" + response.data +
                            "']").prop("disabled", true);
                        $("#industrial_category_id").find("[value='" + response.data + "']")
                            .prop("disabled", false);
                    }

                    oss_fee = parseFloat(response.oss_fee);
                    vat = (oss_fee / 100) * vat_percentage;
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

        $("#total_investment").trigger('change');

        {{--$("#total_investment").change(function () {--}}
        {{--    var total_investment = $('#total_investment').val();--}}
        {{--    --}}{{--var service_fee = parseFloat('{{$payment_config->amount}}');--}}
        {{--    var vat_percentage = parseFloat('{{$vat_percentage}}');--}}
        {{--    $.ajax({--}}
        {{--        type: "GET",--}}
        {{--        url: "{{ url('industry-re-registration/get-industry-type-by-investment') }}",--}}
        {{--        data: {--}}
        {{--            total_investment: total_investment--}}
        {{--        },--}}
        {{--        success: function (response) {--}}
        {{--            $("#industrial_category_id").val(response.data);--}}
        {{--            if (response.data !== "") {--}}
        {{--                $("#industrial_category_id").find("[value!='" + response.data + "']").prop("disabled", true);--}}
        {{--                $("#industrial_category_id").find("[value='" + response.data + "']").prop("disabled", false);--}}
        {{--            }--}}

        {{--            console.log(response);--}}
        {{--            var oss_fee = parseFloat(response.oss_fee);--}}
        {{--            var vat = (oss_fee/100) * vat_percentage;--}}
        {{--            var total_fee = oss_fee + vat;--}}

        {{--            $("#sfp_pay_amount").val(oss_fee);--}}
        {{--            $("#sfp_vat_on_pay_amount").val(vat);--}}
        {{--            $("#sfp_total_amount").val(total_fee);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

        {{--$("#total_investment").trigger('change');--}}

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

                    var option = '<option value="">{{trans("CompanyProfile::messages.select")}}</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {

                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#industrial_sub_sector_id").html(option);
                    @if(isset($companyInfo->ins_sub_sector_id))
                    $("#industrial_sub_sector_id").val("{{$companyInfo->ins_sub_sector_id}}").change();
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
            var foreign_female = $('#foreign_female').val() ? parseFloat($('#foreign_female').val()) : 0;
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
                $('#myModal').modal('show', {backdrop: 'static'});
            });
        }
    }

    $(document).ready(function () {
        if ("{{$companyInfo->nid ?? "" }}"){
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        }else{
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url("client/company-profile/load-listof-directors-session") }}",
            type: "POST",
            data: {

                _token : $('input[name="_token"]').val()
            },
            success: function(response){
                var html = '';
                if (response.responseCode == 1){

                    var edit_url = "{{url('/client/company-profile/edit-director')}}";
                    var delete_url = "{{url('/client/company-profile/delete-director-session')}}";

                    var count = 1;
                    $.each(response.data, function (id, value) {
                        var sl = count ++;
                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' + edit_url + '/' + id + '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                        if(sl!=1){
                            html +='<a data-action="' + delete_url + '/' + id + '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if(response.ceoInfo !=null){
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth =moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                        if(response.ceoInfo.nationality == 'Bangladeshi'){
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if(response.ceoInfo.identity_type == 'passport'){
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nationality").val('');
                            $("#company_ceo_nid").val('');
                        }else{
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    }else{
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' + '<span class="text-danger">No data available!</span>' + '</td>';
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
                success: function(response){
                    if (response.responseCode == 1){
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
    $(document).ready(function (){
        $('#business_category_id').on('change', function (){
            var businessCategoryId = $('#business_category_id').val();
            var oldBusinessCategoryId = '{{(isset($companyInfo->business_category_id) ? $companyInfo->business_category_id : '')}}';

            if(businessCategoryId != oldBusinessCategoryId){
                $('#company_ceo_designation_id').val('');
            }else{
                $('#company_ceo_designation_id').val('{{(isset($companyInfo->designation) ? $companyInfo->designation : '')}}');
            }
        })
    })
</script>
<script type="text/javascript" src="{{ asset("assets/scripts/custom.min.js") }}"></script>
<script type="text/javascript">
    $(function () {
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
