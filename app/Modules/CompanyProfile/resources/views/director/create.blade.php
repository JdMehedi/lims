<link rel="stylesheet" href="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/companyProfile/identity_verify.css') }}">

<style>
    .margin-bottom {
        margin-bottom: 3px;
    }


    .radio-inline {
        padding-top: 0px !important;
    }

    .round-save {
        border-radius: 30px !important;
        min-width: 80px;
    }
</style>

{!! Form::open(['url' => 'client/company-profile/store-verify-director-session', 'method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'directorVerifyForm', 'enctype' => 'multipart/form-data', 'role' => 'form']) !!}

<input name="source" type="hidden" value="{{ $is_source ?? '' }}">

<!-- Passport error modal -->
<div class="modal fade" id="PassportErrorModal" role="dialog" aria-labelledby="PassportErrorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-danger" id="PassportErrorModalTitle">
                    <strong>Oh snap!</strong> Your passport verification failed.
                </h4>
            </div>
            <div class="modal-body">
                <div style="padding: 20px; text-align: center;">
                    <button type="button" id="passport_error_retry" class="btn btn-primary">Retry</button>
                    <p style="margin: 15px 0">OR</p>
                    <button type="button" id="passport_error_manual" class="btn btn-warning">Manually Input</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Passport error modal -->

{{-- <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" onclick="closeModal()"><span
            aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> {!! trans('CompanyProfile::messages.director_info') !!}</h4>
</div> --}}

<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">{!! trans('CompanyProfile::messages.director_info') !!}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    {{-- <input type="hidden" value="{{ Encryption::encodeId($viewMode) }}" name="view_mode"> --}}


    <div class="container-fluid">

        {{-- the code only for single inverstor info --}}
        @if (isset($is_source) && $is_source == 'ceoInfo')
            <div class="form-group" id="director_info">
                <div class="row">
                    {!! Form::label('director_info', trans('CompanyProfile::messages.select_directors'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">
                            {!! Form::radio('director_name', '0', 0, ['class' => 'required', 'onclick' => 'directoryType(this.value)']) !!}
                            {!! trans('CompanyProfile::messages.director_name') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('director_name', '1', 0, ['class' => 'required', 'onclick' => 'directoryType(this.value)']) !!}
                            {!! trans('CompanyProfile::messages.other') !!}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group hidden" id="nationality_type">
                <div class="row">
                    {!! Form::label('nationality_type', trans('CompanyProfile::messages.nationality_type'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">
                            {!! Form::radio('nationality_type', 'bangladeshi', 0, ['class' => 'required', 'onclick' => 'setUserNationality(this.value)']) !!}
                            {!! trans('CompanyProfile::messages.bangladeshi') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('nationality_type', 'foreign', 0, ['class' => 'required', 'onclick' => 'setUserNationality(this.value)']) !!}
                            {!! trans('CompanyProfile::messages.foreign') !!}
                        </label>
                    </div>
                </div>
            </div>
        @else
            {{-- Nationality type for all section --}}
            <div class="form-group row" id="nationality_type">
                {!! Form::label('nationality_type', trans('CompanyProfile::messages.nationality_type'), ['class' => 'col-md-3 text-left required-star']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('nationality_type', 'bangladeshi', 0, ['class' => 'required', 'onclick' => 'setUserNationality(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.bangladeshi') !!}
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('nationality_type', 'foreign', 0, ['class' => 'required', 'onclick' => 'setUserNationality(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.foreign') !!}
                    </label>
                </div>
            </div>
        @endif

        {{-- bangladeshi indentity type fields --}}
        <div class="form-group margin-bottom" id="bd_nationality_fields" style="display:none">
            <div class="row">
                {!! Form::label('identity_type', trans('CompanyProfile::messages.identity_type'), ['class' => 'col-md-3 text-left required-star']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('identity_type', 'nid', 0, ['class' => 'required', 'onclick' => 'setUserIdentity(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.nid') !!}
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('identity_type', 'tin', 0, ['class' => 'required', 'onclick' => 'setUserIdentity(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.tin_bd') !!}
                    </label>
                </div>
            </div>
        </div>

        {{-- foreigner indentity type fields --}}
        <div class="form-group margin-bottom" id="foreign_nationality_fields" style="display:none">
            <div class="row">
                {!! Form::label('identity_type', trans('CompanyProfile::messages.identity_type'), ['class' => 'col-md-3 text-left required-star']) !!}
                <div class="col-md-9">
                    <label class="radio-inline">
                        {!! Form::radio('identity_type', 'tin', 0, ['class' => 'required', 'onclick' => 'setUserIdentity(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.tin_bd') !!}
                    </label>
                    <label class="radio-inline">
                        {!! Form::radio('identity_type', 'passport', 0, ['class' => 'required', 'onclick' => 'setUserIdentity(this.value)']) !!}
                        {!! trans('CompanyProfile::messages.passport') !!}
                    </label>
                </div>
            </div>
        </div>



        {{-- NIDVerify step one --}}
        <div class="row" id="nid_verify" style="display:none">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Step 1</legend>

                    <div class="errorMsgNID alert alert-danger alert-dismissible hidden">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>

                    <!-- NID Verification response div -->
                    <div class="alert alert-info" id="NIDVerificationResponse" style="display: none"></div>

                    <!-- Total spent time to NID verification -->
                    <div id="NIDVerificationTimeCounting" class="text-success" style="display: none">
                        Waiting for the connection to the national ID server, <span id="NIDVerifyTimeSpent">0</span>
                        seconds
                        passed.
                    </div>

                    <div class="form-group row">
                        {!! Form::label('user_nid', trans('CompanyProfile::messages.nid'), ['class' => 'col-md-4 required-star']) !!}
                        <div class="col-md-8 {{ $errors->has('user_nid') ? 'has-error' : '' }}">
                            {!! Form::text('user_nid', '', ['class' => 'form-control input-md required bd_nid']) !!}
                            {!! $errors->first('user_nid', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('nid_dob', trans('Signup::messages.date_of_birth'), ['class' => 'col-md-4 required-star']) !!}
                        <div class="col-sm-8">
                            <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                {!! Form::text('nid_dob', '', ['class' => 'form-control inputBox datetimepicker-input', 'placeholder' => 'ক্যালেন্ডার হতে জন্মতারিখ নির্বাচন করুন', 'data-target' => '#datetimepicker4', 'id' => 'nid_dob', 'required', 'autocomplete' => 'off']) !!}
                                <div class="input-group-append" data-target="#datetimepicker4"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="float-right">
                        <button class="btn btn-info round-btn" id="NIDVerifyBtn" name="nid_verify_btn"
                            onclick="submitIdentityVerifyForm('directorVerifyForm')"><i class="fa fa-check-circle"
                                aria-hidden="true"></i> Verify</button>
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- ETINVerify step one --}}
        <div class="row" id="tin_verify" style="display:none">
            <div class="col-md-9 col-sm-12 col-xs-12">
                <fieldset class="scheduler-border">

                    <legend class="scheduler-border">Step 1</legend>

                    <div class="errorMsgTIN alert alert-danger alert-dismissible" style="display: none">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>

                    <div class="alert alert-info" id="ETINResponseCountMsg" style="display: none"></div>

                    <div id="ETINVerifySuccessMsg" class="text-danger"></div>

                    <div class="form-group row">
                        {!! Form::label('user_tin', trans('CompanyProfile::messages.tin'), ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::text('user_tin', '', ['class' => 'form-control input-md required number', 'id' => 'etin_number']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('etin_dob', trans('CompanyProfile::messages.dob'), ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-sm-9">
                            <div class="input-group date" id="datetimepicker5" data-target-input="nearest">
                                {!! Form::text('etin_dob', '', ['class' => 'form-control inputBox datetimepicker-input', 'placeholder' => 'ক্যালেন্ডার হতে জন্মতারিখ নির্বাচন করুন', 'data-target' => '#datetimepicker5', 'id' => 'etin_dob', 'required', 'autocomplete' => 'off']) !!}
                                <div class="input-group-append" data-target="#datetimepicker5"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="float-right">
                        <button class="btn btn-info round-btn" id="TINVerifyBtn" name="tin_verify_btn"
                            onclick="submitIdentityVerifyForm('directorVerifyForm')"><i class="fa fa-check-circle"
                                aria-hidden="true"></i> Verify</button>
                    </div>
                </fieldset>
            </div>
        </div>

    </div>



    {{-- NIDVerify step two save information --}}
    <div class="row" id="nid_save" style="display: none">
        <div class="col-md-offset-2 col-md-8">

            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Step 2</legend>

                <div class="form-group row">
                    {!! Form::label('user_nid', trans('CompanyProfile::messages.nid'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9">
                        {!! Form::text('user_nid', '', ['class' => 'form-control input-md', 'id' => 'user_nid', 'readonly' => 'true']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('user_dob_nid', trans('CompanyProfile::messages.dob'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9">
                        <span class="form-control input-md" id="save_nid_dob"
                            style="background:#eee; height: auto;min-height: 30px;"></span>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('nid_name', trans('CompanyProfile::messages.name'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9">
                        {!! Form::text('nid_name', '', ['class' => 'form-control input-md', 'id' => 'nid_name', 'readonly' => 'true']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('gender', trans('CompanyProfile::messages.gender'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'male', 0, ['class' => 'required', 'id' => 'maleRadio']) !!}
                            {!! trans('CompanyProfile::messages.male') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'female', 0, ['class' => 'required', 'id' => 'femaleRadio']) !!}
                            {!! trans('CompanyProfile::messages.female') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'other', 0, ['class' => 'required', 'id' => 'otherRadio']) !!}
                            {!! trans('CompanyProfile::messages.other') !!}
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('nid_designation', trans('CompanyProfile::messages.designation'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::text('nid_designation', '', ['class' => 'form-control required bnEng', 'placeholder' => trans('CompanyProfile::messages.write_designation'), 'id' => 'nid_designation']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('nid_nationality', trans('CompanyProfile::messages.nationality'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('nid_nationality', $nationality, '', ['class' => 'form-control input-md required', 'id' => 'nid_nationality']) !!}
                    </div>
                </div>

                <div class="float-right">
                    <button type="submit" class="btn btn-warning round-btn" id="btn_save" name="btn_save"
                        value="NID" onclick="submitIdentityVerifyForm('directorVerifyForm')"><i
                            class="fa fa-save"></i> Load</button>
                </div>
            </fieldset>
        </div>
    </div>

    {{-- ETINVerify step two and save information --}}
    <div class="row" id="tin_save" style="display: none">
        <div class="col-md-offset-2 col-md-8">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Step 2</legend>
                <div class="form-group row">
                    {!! Form::label('etin_name', trans('CompanyProfile::messages.name'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9 {{ $errors->has('etin_name') ? 'has-error' : '' }}">
                        {!! Form::text('etin_name', '', ['class' => 'form-control input-md', 'id' => 'etin_name', 'readonly' => 'true']) !!}
                        {!! $errors->first('etin_name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('user_etin', trans('CompanyProfile::messages.tin'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9 {{ $errors->has('user_etin') ? 'has-error' : '' }}">
                        {!! Form::text('user_etin', '', ['class' => 'form-control input-md', 'id' => 'user_etin', 'readonly' => 'true']) !!}
                        {!! $errors->first('user_etin', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('user_tin_dob', trans('CompanyProfile::messages.dob'), ['class' => 'col-md-3 text-left']) !!}
                    <div class="col-md-9">
                        <span class="form-control input-md" id="save_etin_dob"
                            style="background:#eee; height: auto;min-height: 30px;"></span>
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('gender', trans('CompanyProfile::messages.gender'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9 {{ $errors->has('gender') ? 'has-error' : '' }}">
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'male', 0, ['class' => 'required']) !!}
                            {!! trans('CompanyProfile::messages.male') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'female', 0, ['class' => 'required']) !!}
                            {!! trans('CompanyProfile::messages.female') !!}
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('gender', 'other', 0, ['class' => 'required']) !!}
                            {!! trans('CompanyProfile::messages.other') !!}
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('etin_designation', trans('CompanyProfile::messages.designation'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9 {{ $errors->has('etin_designation') ? 'has-error' : '' }}">
                        {!! Form::text('etin_designation', '', ['class' => 'form-control required bnEng', 'placeholder' => trans('CompanyProfile::messages.write_designation'), 'id' => 'etin_designation']) !!}
                        {{-- {!! Form::select('etin_designation', $designation,'', ['class' => 'form-control input-md required', 'id'=>'etin_designation']) !!} --}}
                        {!! $errors->first('etin_designation', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('etin_nationality', trans('CompanyProfile::messages.nationality'), ['class' => 'col-md-3 text-left required-star']) !!}
                    <div class="col-md-9 {{ $errors->has('etin_nationality') ? 'has-error' : '' }}">
                        {!! Form::select('etin_nationality', $nationality, '', ['class' => 'form-control input-md required', 'id' => 'etin_nationality']) !!}
                        {!! $errors->first('etin_nationality', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="float-right">
                    <button class="btn btn-warning round-btn" id="ETINVerifySaveBtn" name="btn_save" value="ETIN"
                        onclick="submitIdentityVerifyForm('directorVerifyForm')"><i class="fa fa-save"></i>
                        Save</button>
                </div>
            </fieldset>
        </div>
    </div>

    {{-- passport information --}}
    <div class="row" id="passport_div" style="display: none">
        <div class="col-md-offset-1 col-md-10">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Passport information</legend>
                <div id="passport_upload_wrapper" class="passport-upload-wrapper">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <span id="passport_upload_error" class="text-danger text-left"></span>

                            <div style="text-align: center;" id="passport_upload_div">
                                <div class="passport-upload" style="height: 300px;">
                                    <div class="passport-upload-message">
                                        <i class="fas fa-cloud-upload-alt fa-3x passport-upload-icon"></i>
                                        <p>
                                            Drop Your Passport scan copy here or
                                            <span style="color:#258DFF;">Browse</span>
                                            <small class="help-block" style="font-size: 9px;">[File Format: *.jpg/
                                                .jpeg/ .png | Maximum 5 MB | Width 746 to 3500 pixel | Height 1043 to
                                                4500 pixel]</small>
                                        </p>
                                    </div>
                                    <input accept="image/*" type="file" name="passport_upload"
                                        id="passport_upload" class="passport-upload-input"
                                        onchange="getPassportImage(this);">
                                </div>

                            </div>
                            {{-- <div style="text-align: center"> --}}
                            {{-- <a href="{{ asset('assets/images/sample_passport.jpg') }}" ref="noopener" target="_blank">Sample Passport</a> --}}
                            {{-- </div> --}}

                            <div id="passport_preloader" class="text-center fa-3x"
                                style="display: none; padding: 10px 0;"><i class="fa fa-spinner fa-pulse"></i></div>
                        </div>
                    </div>
                    <div id="passport_upload_view_wrapper" style="display: none">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div id="passport_upload_view_div">
                                    <img class="img-thumbnail" id="passport_upload_view" src="#"
                                        alt="Investor passport upload copy">
                                    <input type="hidden" name="passport_upload_base_code"
                                        id="passport_upload_base_code">
                                    <input type="hidden" name="passport_upload_manual_file"
                                        id="passport_upload_manual_file">
                                    <input type="hidden" name="passport_file_name" id="passport_file_name">
                                </div>

                                <div id="passport_cropped_result" class="panel panel-info">

                                </div>

                                <div style="margin-top: 15px;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button style="display: none;" type="button" id="passport_edit_btn"
                                                class="btn btn-info float-left">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>

                                            <button type="button" id="passport_crop_btn"
                                                class="btn btn-info float-left">
                                                <i class="fa fa-check-circle"></i> Done
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            {{-- Data: <span id="crop_data_info"></span> --}}
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" id="passport_reset_btn"
                                                class="btn btn-link float-right">
                                                <i class="fa fa-undo"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading" style="padding: 10px 15px;">
                                        <h3 class="panel-title">FAQ</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <strong>Ques: </strong> How to zoom in or zoom out? <br>
                                                <strong>Ans: </strong> Enable to zoom by wheeling mouse over the image.
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Ques: </strong> How to resize? <br>
                                                <strong>Ans: </strong> Click and drag on the image to make the
                                                selection.
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Ques: </strong> How to crop? <br>
                                                <strong>Ans: </strong> After choosing the image position, just click
                                                your mouse on the <code>Done</code> button to crop.
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Ques: </strong> How to passport verify? <br>
                                                <strong>Ans: </strong> After done your image you will get a <code>green
                                                    color verify</code> button. Just click and wait for result.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="preloader" class="text-center fa-3x" style="display: none; padding: 10px 0"></div>
                <div id="passport_div_verification" style="display: none">
                    <div class="alert alert-success">
                        Please check your passport information data before submitting it.
                    </div>

                    <div class="col-md-8">
                        {{-- Passport Nationality --}}
                        <div
                            class="form-group row has-feedback {{ $errors->has('passport_nationality') ? 'has-error' : '' }}">
                            <label for="passport_nationality"
                                class="col-md-4 text-left required-star">{!! trans('CompanyProfile::messages.passport_nationality') !!}</label>
                            <div class="col-md-8">
                                {!! Form::select('passport_nationality', $passport_nationalities, '', $attributes = ['class' => 'form-control input-sm required', 'placeholder' => 'Select one', 'id' => 'passport_nationality', 'readonly' => 'true']) !!}
                                {!! $errors->first('passport_nationality', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Passport Type --}}
                        <div class="form-group row has-feedback {{ $errors->has('passport_type') ? 'has-error' : '' }}">
                            <label for="passport_type" class="col-md-4 required-star">{!! trans('CompanyProfile::messages.passport_type') !!}</label>
                            <div class="col-md-8">
                                {!! Form::select('passport_type', $passport_types, '', $attributes = ['class' => 'form-control input-sm required', 'placeholder' => 'Select one', 'id' => 'passport_type', 'readonly' => 'true']) !!}
                                {!! $errors->first('passport_type', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Passport No --}}
                        <div class="form-group row has-feedback {{ $errors->has('passport_no') ? 'has-error' : '' }}">
                            <label for="passport_no" class="col-md-4 required-star">{!! trans('CompanyProfile::messages.passport_no') !!}</label>
                            <div class="col-md-8">
                                {!! Form::text('passport_no', null, $attributes = ['class' => 'form-control required alphaNumeric input-sm', 'data-rule-maxlength' => '20', 'placeholder' => 'Enter your passport number', 'id' => 'passport_no']) !!}
                                {!! $errors->first('passport_no', '<span class="help-block">:message</span>') !!}
                                <span class="text-danger pss-error"></span>
                            </div>
                        </div>

                        {{-- Surname --}}
                        <div
                            class="form-group row has-feedback {{ $errors->has('passport_surname') ? 'has-error' : '' }}">
                            <label for="passport_surname"
                                class="col-md-4 required-star">{!! trans('CompanyProfile::messages.sure_name') !!}</label>
                            <div class="col-md-8">
                                {!! Form::text('passport_surname', null, $attributes = ['class' => 'form-control textOnly input-sm required', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your surname', 'id' => 'passport_surname']) !!}
                                {!! $errors->first('passport_surname', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Given Name --}}
                        <div
                            class="form-group row has-feedback {{ $errors->has('passport_given_name') ? 'has-error' : '' }}">
                            <label for="passport_given_name"
                                class="col-md-4 required-star">{!! trans('CompanyProfile::messages.name') !!}</label>
                            <div class="col-md-8">
                                {!! Form::text('passport_given_name', null, $attributes = ['class' => 'form-control textOnly input-sm required', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your given name', 'id' => 'passport_given_name']) !!}
                                {!! $errors->first('passport_given_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Given Name --}}
                        <div class="form-group row has-feedback {{ $errors->has('gender') ? 'has-error' : '' }}">
                            <label for="gender" class="col-md-4 required-star">{!! trans('CompanyProfile::messages.gender') !!}</label>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    {!! Form::radio('gender', 'male', 0, ['class' => 'required']) !!}
                                    {!! trans('CompanyProfile::messages.male') !!}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('gender', 'female', 0, ['class' => 'required']) !!}
                                    {!! trans('CompanyProfile::messages.female') !!}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('gender', 'other', 0, ['class' => 'required']) !!}
                                    {!! trans('CompanyProfile::messages.other') !!}
                                </label>
                                {!! $errors->first('gender', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Designation --}}
                        <div class="form-group row">
                            {!! Form::label('l_director_designation', trans('CompanyProfile::messages.designation'), ['class' => 'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8 {{ $errors->has('l_director_designation') ? 'has-error' : '' }}">
                                {!! Form::text('l_director_designation', '', ['class' => 'form-control required bnEng', 'placeholder' => trans('CompanyProfile::messages.write_designation'), 'id' => 'l_director_designation']) !!}
                                {{-- {!! Form::select('l_director_designation', $designation,'', ['class' => 'form-control input-md required', 'id'=>'l_director_designation']) !!} --}}
                                {!! $errors->first('l_director_designation', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Personal No --}}
                        <div
                            class="form-group row has-feedback {{ $errors->has('passport_personal_no') ? 'has-error' : '' }}">
                            <label for="passport_personal_no" class="col-md-4">{!! trans('CompanyProfile::messages.personal_no') !!}</label>
                            <div class="col-md-8">
                                {!! Form::text('passport_personal_no', null, $attributes = ['class' => 'form-control alphaNumeric input-sm', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your personal number', 'id' => 'passport_personal_no']) !!}
                                {!! $errors->first('passport_personal_no', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="form-group row has-feedback {{ $errors->has('passport_DOB') ? 'has-error' : '' }}">
                            <label for="passport_DOB" class="col-md-4 required-star">{!! trans('CompanyProfile::messages.dob') !!}</label>
                            <div class="col-md-8">
                                <div class="passportDOB input-group date">
                                    {!! Form::text('passport_DOB', '', ['class' => 'form-control input-sm required', 'id' => 'passport_DOB', 'placeholder' => 'Pick from calendar']) !!}
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                                {!! $errors->first('passport_DOB', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Date of Expire --}}
                                                <div
                            class="form-group row has-feedback {{ $errors->has('passport_date_of_expire') ? 'has-error' : '' }}">
                            <label for="passport_date_of_expire"
                                class="col-md-4 required-star">{!! trans('CompanyProfile::messages.expiry_date') !!}</label>
                            <div class="col-md-8">
                                <div class="passExpiryDate input-group date">
                                    {!! Form::text('passport_date_of_expire', '', ['class' => 'form-control input-sm required', 'id' => 'passport_date_of_expire', 'placeholder' => 'Pick from calendar']) !!}
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                                {!! $errors->first('passport_date_of_expire', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="magnify">
                            <div class="large" id="magnify_image_large"></div>
                            <small>File name: <span id="passport_file_name_show"></span></small>
                            <img class="small img-responsive" id="magnify_image_small" alt="Investor passport copy"
                                src="">
                        </div>
                    </div>

                    <div class="form-group col-md-8 float-right">
                        <div class="row">
                            <button class="btn btn-warning round-btn" id="passport_save" name="btn_save"
                                value="passport" onclick="submitIdentityVerifyForm('directorVerifyForm')"
                                style="display: none"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="float-right">
                    <button type="button" class="btn btn-md btn-success round-btn float-right" id="passport_verify"
                        style="display: none"><strong>Verify</strong></button>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<div class="modal-footer hidden" id="directorLists" style="text-align:left;">
    <div class="table-responsive" style="height: 250px">
        <table id="directorListModal" class="table table-striped table-bordered dt-responsive" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{!! trans('CompanyProfile::messages.name') !!}</th>
                    <th>{!! trans('CompanyProfile::messages.designation') !!}</th>
                    <th>{!! trans('CompanyProfile::messages.nationality') !!}</th>
                    <th>{!! trans('CompanyProfile::messages.nid_tin_passport_no') !!}</th>
                    <th>{!! trans('CompanyProfile::messages.action') !!}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="float-left"></div>
    <div class="float-right">
        {!! Form::button('<i class="fa fa-times"></i> Close', ['type' => 'button', 'class' => 'btn btn-danger round-btn', 'data-dismiss' => 'modal', 'onclick' => 'closeModal()']) !!}
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


<script src="{{ asset('assets/scripts/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/modules/companyProfile/identity_verify.js') }}" type="text/javascript"></script>

<script>
    /**
     * Load modal list of directors here
     * @constructor
     * @param $app_id
     * @param $process_type_id
     */

    function LoadModalListOfDirectors() {
        $.ajax({
            url: "{{ url('client/company-profile/load-listof-directors-session') }}",
            type: "POST",
            data: {
                {{-- app_id: "{{ $app_id }}", --}}
                {{-- process_type_id: "{{ $encoded_process_type }}", --}}
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                var html = '';
                if (response.responseCode == 1) {
                    var count = 1;
                    $.each(response.data, function(id, value) {
                        var sl = count++;

                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        @if (isset($is_source) && !empty($is_source))
                            html +=
                                '<td><a class="btn btn-success btn-xs" onclick="setDirectorInfo(' +
                                id + ')"><i class="fa fa-plus"></i> প্রতিনিধির তথ্য</a></td>';
                        @endif
                        html += '</tr>';
                    });
                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorListModal tbody').html(html);
            }
        });
    }
    $('.modal').on('hidden.bs.modal', function() {
        $("html").css({
            "overflow": "auto"
        });
    });

    function directoryType(val) {

        if (val == 1) { // 1 = others
            $("#nationality_type").removeClass('hidden');
            $("#directorLists").addClass('hidden');

        } else {
            @if (isset($app_id) && !empty($app_id))
                selectDirectorInfo();
            @endif

            $("#nationality_type").addClass('hidden');
            $("#directorLists").removeClass('hidden');

            $("#bd_nationality_fields").hide();
            $("#foreign_nationality_fields").hide();
            $("#nid_verify").hide();
            $("#passport_div").hide();
        }
    }

    function setDirectorInfo(id) {
        $.ajax({
            url: "{{ url('client/company-profile/set-single-director-info') }}",
            type: "GET",
            data: {
                sessionId: id,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                LoadListOfDirectors();
                toastr.success('Director selected successfully');
                $("#myModal").modal('hide');
            }
        });
    }

    function setDirectorInfoDB(id) {
        $.ajax({
            url: "{{ url('client/company-profile/set-single-director-info') }}",
            type: "GET",
            data: {
                sessionId: id,
                type: 'db',
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                LoadListOfDirectors();
                toastr.success('প্রতিনিধির তথ্য যোগ  করা হয়েছে');
                $("#myModal").modal('hide');
            }
        });
    }

    function selectDirectorInfo(app_id) {
        $.ajax({
            url: "{{ url('client/company-profile/select-director-info') }}",
            type: "GET",
            data: {
                app_id: '{{ $app_id ?? '' }}',
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                var html = '';
                if (response.responseCode == 1) {
                    var count = 1;
                    $.each(response.data, function(id, value) {
                        var sl = count++;

                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.investor_nm + '</td>';
                        html += '<td>' + value.designation + '</td>';
                        html += '<td>' + value.nationality_name + '</td>';
                        html += '<td>' + value.identity_type + '</td>';
                        html +=
                            '<td><a class="btn btn-success btn-xs" onclick="setDirectorInfoDB(' +
                            value.id + ')"><i class="fa fa-plus"></i> প্রতিনিধির তথ্য</a></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr>';
                    html += '<td colspan="6" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorListModal tbody').html(html);
            }
        });
    }
</script>
