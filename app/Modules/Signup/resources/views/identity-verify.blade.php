@extends('public_home.front')

@section('header-resources')
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/signup/identity_verify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}"/>

    <style>
        .col-centered {
            float: none;
            margin: 0 auto;
        }

        label:first-child input[type=radio] {
            width: 20px;
            height: 20px;
            background-color: #858585;
        }

        .radio_label {
            margin-left: 10px;
            vertical-align: bottom;
        }

        .identityRadio {
            color: red !important;
            width: 20px;
            height: 20px;
        }

        .jumbotron {
            padding-top: 30px;
            padding-bottom: 75px;
            margin-bottom: 55px;
            /*padding: 0px;*/
        }

        .heading {
            font-weight: bold;
            font-size: 24px;
        }

        .select-nationality {
            font-weight: bold;
            color: #603433;
            margin-bottom: 25px;
        }

        .inputBox {
            height: 40px;
            border: none;
            border-radius: 3px;
        }

        .inputLabel {
            margin-top: 10px;
        }

        .btn_back {
            margin-right: 5px;
            border: 1px solid #538FF3;
            border-radius: 3px;
            background: #EEEEEE;
        }

        .btn_verify,
        .btn_verify:hover {
            color: white;
            margin-left: 5px;
            border: 1px solid #5E3736;
            border-radius: 3px;
            /*width: 100px;*/
            background: #5E3736;
        }

        .sample_passport {
            border: 1px solid #EAEAEA;
            border-radius: 5px;
        }
    </style>
@endsection

@section('body')
    <!-- NID data verification Modal -->
    <div class="modal fade" id="NidTinVerifyModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="NidTinVerifyModalTitle">
                        <img id="verify_modal_title_img" src="{{ url('/assets/images/ec.png') }}" width="auto"
                            height="40px" alt="election commision" />
                        <span class="text-success" id="verify_modal_title"></span>
                    </h4>
                </div>

                <div class="modal-body">

                    <div class="errorMsgNID alert alert-danger alert-dismissible d-none">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>

                    <!-- NID Verification response div -->
                    <div class="alert alert-info" id="NidTinVerificationResponse"></div>

                    <!-- NID data show after verification -->
                    <div id="VerifiedNIDInfo" class="clearfix d-none">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-4 text-center">
                                    <img id="nid_tin_image" class="img-thumbnail" src="" width="100px"
                                        height="100px">
                                </div>
                                <div class="col-sm-8">
                                    <br />
                                    <form>
                                        <div class="form-group row">
                                            <label>{!! trans('Signup::messages.name') !!}: </label>
                                            <span id="nid_tin_name"></span>
                                        </div>

                                        <div class="form-group row">
                                            <label>{!! trans('Signup::messages.date_of_birth') !!}: </label>
                                            <span id="nid_tin_dob"></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End NID data show after verification -->
                </div>

                <div class="modal-footer" id="NidTinVerifyModalFooter">
                    <div class="pull-left">
                        {!! Form::button('<i class="fa fa-times"></i> বাতিল ', ['type' => 'button', 'class' => 'btn btn-danger round-btn', 'data-dismiss' => 'modal']) !!}
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-success round-btn d-none" id="SaveContinueBtn"
                            onclick="document.forms['identityVerifyForm'].submit()">
                            <i class="fa fa-save"></i> Save & continue
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End NID data verification Modal -->


    <!-- Passport error modal -->
    <div class="modal fade" id="PassportErrorModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title " id="PassportErrorModalTitle">
                        <strong>Oh snap!</strong> Your passport verification failed.
                    </h4>
                </div>
                <div class="modal-body">
                    <div style="padding: 20px; text-align: center;">
                        <button type="button" onclick="retryToPassportVerify('#PassportErrorModal', '#passport_reset_btn')"
                            class="btn btn-primary">Retry
                        </button>
                        <p style="margin: 15px 0">OR</p>
                        <button type="button" id="passport_error_manual" class="btn btn-warning">Manually
                            Input
                        </button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End Passport error modal -->

    <!-- Previous Verification data -->
    @if (!empty($getPreviousVerificationData))
        <div id="previousVerificationDataModal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            <span class="text-success">You tried previously to register by verifying <span
                                    class="text-uppercase font-weight-bold">{{ $getPreviousVerificationData->identity_type }}</span></span>
                            <br />Do you want to continue with the previous verification information?
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {!! Form::open(['url' => '/client/signup/identity-verify-previous/' . \App\Libraries\Encryption::encodeId($getPreviousVerificationData->id), 'method' => 'post', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-4">
                                                <span class="v_label">{!! trans('Signup::messages.nationality_type') !!}</span>
                                                <span class="pull-right">&#58;</span>
                                            </label>
                                            <span
                                                class="col-sm-8">{{ ucfirst($getPreviousVerificationData->nationality_type) }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4">
                                                <span class="v_label">{!! trans('Signup::messages.identity_type') !!}</span>
                                                <span class="pull-right">&#58;</span>
                                            </label>
                                            <span
                                                class="col-sm-8">{{ ucfirst($getPreviousVerificationData->identity_type) }}</span>
                                        </div>

                                        @if ($getPreviousVerificationData->identity_type == 'tin')
                                            <?php
                                            $previous_tin_info = json_decode(Encryption::decode($getPreviousVerificationData->eTin_info), true);
                                            ?>
                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.tin_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-8">{{ $previous_tin_info['etin_number'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-8">{{ $previous_tin_info['assesName'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.father_name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-8">{{ $previous_tin_info['fathersName'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ date('d-M-Y', strtotime($previous_tin_info['dob'])) }}</span>
                                            </div>
                                        @elseif($getPreviousVerificationData->identity_type == 'nid')
                                            <?php
                                            $previous_nid_info = json_decode(Encryption::decode($getPreviousVerificationData->nid_info), true);
                                            ?>
                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.nid_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-8">{{ $previous_nid_info['nationalId'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-8">{{ $previous_nid_info['nameEn'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ date('d-M-Y', strtotime($previous_nid_info['dateOfBirth'])) }}</span>
                                            </div>
                                        @elseif($getPreviousVerificationData->identity_type == 'passport')
                                            <?php
                                            $previous_passport_info = json_decode(Encryption::decode($getPreviousVerificationData->passport_info), true);
                                            ?>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.passport_type') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ $previous_passport_info['passport_type'] }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.passport_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ $previous_passport_info['passport_no'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.sure_name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ $previous_passport_info['passport_surname'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ $previous_passport_info['passport_given_name'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.personal_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ $previous_passport_info['passport_personal_no'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ date('d-M-Y', strtotime($previous_passport_info['passport_DOB'])) }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4">
                                                    <span class="v_label">{!! trans('Signup::messages.expiry_date') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span
                                                    class="col-sm-8">{{ date('d-M-Y', strtotime($previous_passport_info['passport_date_of_expire'])) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Skip</button>
                        <button type="submit" class="btn btn-success">Continue</button>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    @endif


    <div class="container">
        {{-- Heading --}}
        <div class="row">
            <div class="col-md-12 text-center" style="margin-top: 15px; margin-bottom: 10px; color: #603433">
                <h2 class="heading">
                    {!! trans('Signup::messages.reg_title') !!}
                </h2>
            </div>
        </div>

        <div class="row justify-content-md-center">
            <div class="col col-lg-9">

                <div class="jumbotron">
                    {!! Form::open(['url' => '/client/signup/identity-verify', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'identityVerifyForm', 'name' => 'identityVerifyForm', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}

                    <div id="select_nationality">
                        <div class="form-group row">
                            <div class="col text-center">
                                <h2 class="select-nationality">{!! trans('Signup::messages.nationality') !!}</h2>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-light btn-lg btn-block" id="bangladeshi"
                                            value="bangladeshi" onclick="setUserNationality(this.value)">
                                            {!! trans('Signup::messages.bangladeshi') !!}</button>
                                    </div>

                                    <div class="col-6">
                                        <button type="button" class="btn btn-light btn-lg btn-block" id="foreign"
                                            value="foreign" onclick="setUserNationality(this.value)">
                                            {!! trans('Signup::messages.foreign') !!}</button>
                                    </div>

                                    <input type="hidden" name="nationality_type" id="nationality_types">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row pt-3">
                            <div class="col-md-12 text-center">
                                <a href="/" type="button" class="btn btn-outline-primary"><i
                                        class="fa fa-arrow-left"> ফিরে যান</i></a>
                            </div>
                        </div>
                    </div>

                    <div class="d-none" id="bd_nationality_fields">
                        <div class="form-group row">
                            {!! Form::label('identity_type_bd', trans('Signup::messages.identity'), ['class' => 'col-sm-3 col-form-label', 'id' => 'nationality']) !!}

                            <div class="col-sm-5">
                                {!! Form::radio('identity_type_bd', 'nid', false, ['class' => 'required identityRadio', 'id' => 'identity_type_nid', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                <label class="radio_label" for="identity_type_nid">
                                    {!! trans('Signup::messages.nid') !!}
                                </label>
                            </div>

                            <div class="col-sm-4">
                                {!! Form::radio('identity_type_bd', 'tin', false, ['class' => 'required identityRadio', 'id' => 'identity_type_tin1', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                <label class="radio_label" for="identity_type_tin1">
                                    {!! trans('Signup::messages.tin') !!}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-none" id="foreign_nationality_fields">

                        <div id="passport_radio">
                            <div class="form-group row">
                                {!! Form::label('identity_type_foreign', trans('Signup::messages.identity'), ['class' => 'col-md-3', 'id' => 'nationality']) !!}

                                <div class="col-md-9">
                                    {!! Form::radio('identity_type_foreign', 'passport', false, ['class' => 'required identityRadio', 'id' => 'identity_type_passport', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                    <label class="radio_label" for="identity_type_passport">
                                        {!! trans('Signup::messages.passport') !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- National ID No --}}
                    <div class="d-none" id="nid_field">
                        <div class="form-group row">
                            {!! Form::label('user_nid', trans('Signup::messages.nid_no'), ['class' => 'col-md-3 inputLabel', 'id' => 'nid_number']) !!}
                            <div class="col-sm-9">
                                {!! Form::text('user_nid', '', ['placeholder' => 'জাতীয় পরিচয়পত্র নম্বর লিখুন', 'class' => 'form-control inputBox bd_nid required', 'id' => 'user_nid', 'required']) !!}
                                {!! Form::hidden('verified_nid_data', null, $attributes = ['id' => 'verified_nid_data']) !!}
                            </div>
                        </div>
                    </div>

                    {{-- TIN Number --}}
                    <div class="d-none" id="etin_number_field">
                        <div class="form-group row">
                            {!! Form::label('etin_number', trans('Signup::messages.tin_no'), ['class' => 'col-md-3 inputLabel', 'id' => 'tin_number']) !!}
                            <div class="col-md-9 {{ $errors->has('nid_number') ? 'has-error' : '' }}">
                                {!! Form::text('etin_number', '', ['placeholder' => 'ই-টিন নম্বর লিখুন', 'class' => 'form-control inputBox number required', 'id' => 'etin_number', 'required']) !!}
                                {!! Form::hidden('verified_etin_data', null, $attributes = ['id' => 'verified_etin_data']) !!}
                                {!! $errors->first('etin_number', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div class="d-none" id="user_dob_field">
                        <div class="form-group row">
                            {!! Form::label('applicant_dob', trans('Signup::messages.date_of_birth'), ['class' => 'col-md-3 inputLabel', 'id' => 'applicant_dob']) !!}
                            <div class="col-sm-9">
                                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                    {!! Form::text('user_DOB', '', ['class' => 'form-control inputBox datetimepicker-input', 'placeholder' => 'ক্যালেন্ডার হতে জন্মতারিখ নির্বাচন করুন', 'data-target' => '#datetimepicker4', 'id' => 'user_DOB', 'required', 'autocomplete' => 'off']) !!}
                                    <div class="input-group-append" data-target="#datetimepicker4"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Passport Information --}}
                    <div id="passport_div" class="d-none">

                        <div id="passport_upload_wrapper" class="passport-upload-wrapper">
                            <div class="row">
                                <span id="passport_upload_error" class="text-danger text-left"></span>

                                <div hidden class="panel-body" id="passport_capture_div"
                                    style="background: white; margin: 15px 20px">

                                    <div style="text-align: center;" id="passport_capture">
                                        <div class="passport-capture">
                                            <img src="/assets/images/camera.png" alt="" style="margin-top: 15px">
                                            <p style="font-size: 12px; font-weight: 600; color: #7D7C7C">
                                                Capture your passport
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div style="text-align: center;" id="passport_upload_div">
                                    <div class="passport-upload">
                                        <div class="passport-upload-message">
                                            <img class="col-centered" src="/assets/images/cloud_upload.png"
                                                alt="">
                                            <p>
                                                Drop Your Passport scan copy here or
                                                <span style="color:#258DFF;">Browse</span>
                                                <small class="help-block" style="font-size: 12px;">[File Format:
                                                    *.jpg/ .jpeg/ .png | Maximum 5 MB | Width 746 to 3500 pixel |
                                                    Height 1043 to 4500 pixel]</small>
                                            </p>
                                        </div>
                                        <input accept="image/*" type="file" name="passport_upload"
                                            id="passport_upload" class="passport-upload-input"
                                            onchange="getPassportImage(this);">
                                    </div>

                                    <div id="sample_passport"
                                        style="margin-top: 20px; border:1px solid #EEEEEE; border-radius: 5px; padding: 10px;">
                                        <div class="row">
                                            <div class="col-xs-8">
                                                <div id="sample_passport_text"
                                                    style="display: flex;justify-content: center;align-items: center;">
                                                    <div>
                                                        <h4>Sample Passport</h4>
                                                        <p>Good quality image must be uploaded</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <img src="{{ asset('assets/images/sample_passport_n.jpg') }}"
                                                    class="img-responsive" alt="Passport sample image" />
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="passport_preloader" class="text-center fa-3x"
                                    style="display: none; padding: 10px 0;"><i class="fa fa-spinner fa-pulse"></i>
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

                                        <div id="passport_cropped_result" class="card card-info">

                                        </div>

                                        <div style="margin-top: 15px;">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <button
                                                        style="display: none;background: #EEEEEE; color: #1673E6; border: 1px solid #1673E6;"
                                                        type="button" id="passport_edit_btn" class="btn pull-left">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>

                                                    <button type="button" id="passport_crop_btn"
                                                        style="background: #5E3736; color:white;" class="btn pull-left">
                                                        Done
                                                    </button>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" id="passport_reset_btn"
                                                        class="btn btn-link  float-right">
                                                        <i class="fa fa-undo"></i> Remove Image
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="border: none;">
                                            <div class="card-header"
                                                style="padding-left: 5px; background-color: #EEEEEE">
                                                <h3 class="card-title" style="color: #6793C3">FAQ</h3>
                                            </div>
                                            <div class="card-body" style="border: none; background-color: #EEEEEE">
                                                <ul class="list-group" style="border: none; color: #7FA4CC">
                                                    <li class="list-group-item">
                                                        <strong style="color: #6793C3">Ques: How to zoom in or zoom
                                                            out?</strong>
                                                        <br>
                                                        Ans: Enable to zoom by wheeling mouse over the
                                                        image.
                                                    </li>
                                                    <br>
                                                    <li class="list-group-item">
                                                        <strong style="color: #6793C3">Ques: How to resize?</strong> <br>
                                                        Ans: Click and drag on the image to make the
                                                        selection.
                                                    </li>
                                                    <br>
                                                    <li class="list-group-item">
                                                        <strong style="color: #6793C3">Ques: How to crop?</strong> <br>
                                                        Ans: After choosing the image position, just
                                                        click your mouse on the Done button to crop.
                                                    </li>
                                                    <br>
                                                    <li class="list-group-item">
                                                        <strong style="color: #6793C3">Ques: How to passport verify?
                                                        </strong> <br>
                                                        Ans: After done your image you will get a
                                                        green color verify button. Just click and wait for
                                                        result.
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="preloader" class="text-center fa-3x" style="display: none; padding: 10px 0; "></div>

                        <div id="passport_div_verification" style="display: none">
                            <div class="row">
                               <div class="col-md-6">
                                   {{-- Passport Nationality --}}
                                   <div
                                       class="form-group row {{ $errors->has('passport_nationality') ? 'has-error' : '' }}">
                                       <label for="passport_nationality"
                                              class="col-md-5 text-left">{!! trans('Signup::messages.passport_nationality') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::select('passport_nationality', $passport_nationalities, '', $attributes = ['class' => 'form-control input-sm required', 'placeholder' => 'Select one', 'id' => 'passport_nationality']) !!}
                                           {!! $errors->first('passport_nationality', '<span class="help-block">:message</span>') !!}
                                       </div>
                                   </div>

                                   {{-- Passport Type --}}
                                   <div
                                       class="form-group row {{ $errors->has('passport_type') ? 'has-error' : '' }}">
                                       <label for="passport_type" class="col-md-5">{!! trans('Signup::messages.passport_type') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::select('passport_type', $passport_types, '', $attributes = ['class' => 'form-control input-sm required', 'placeholder' => 'Select one', 'id' => 'passport_type']) !!}
                                           {!! $errors->first('passport_type', '<span class="help-block">:message</span>') !!}
                                       </div>
                                   </div>

                                   {{-- Passport No --}}
                                   <div
                                       class="form-group row {{ $errors->has('passport_no') ? 'has-error' : '' }}">
                                       <label for="passport_no" class="col-md-5">{!! trans('Signup::messages.passport_no') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::text('passport_no', null, $attributes = ['class' => 'form-control required alphaNumeric input-sm', 'data-rule-maxlength' => '20', 'placeholder' => 'Enter your passport number', 'id' => 'passport_no']) !!}
                                           {!! $errors->first('passport_no', '<span class="help-block">:message</span>') !!}
                                           <span class="text-danger pss-error"></span>
                                       </div>
                                   </div>

                                   {{-- Surname --}}
                                   <div
                                       class="form-group  row {{ $errors->has('passport_surname') ? 'has-error' : '' }}">
                                       <label for="passport_surname" class="col-md-5">{!! trans('Signup::messages.sure_name') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::text('passport_surname', null, $attributes = ['class' => 'form-control textOnly input-sm required', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your surname', 'id' => 'passport_surname']) !!}
                                           {!! $errors->first('passport_surname', '<span class="help-block">:message</span>') !!}
                                       </div>
                                   </div>

                                   {{-- Given Name --}}
                                   <div
                                       class="form-group row {{ $errors->has('passport_given_name') ? 'has-error' : '' }}">
                                       <label for="passport_given_name" class="col-md-5">{!! trans('Signup::messages.name') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::text('passport_given_name', null, $attributes = ['class' => 'form-control textOnly input-sm required', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your given name', 'id' => 'passport_given_name']) !!}
                                           {!! $errors->first('passport_given_name', '<span class="help-block">:message</span>') !!}
                                       </div>
                                   </div>

                                   {{-- Personal No --}}
                                   <div
                                       class="form-group row {{ $errors->has('passport_personal_no') ? 'has-error' : '' }}">
                                       <label for="passport_personal_no" class="col-md-5">{!! trans('Signup::messages.personal_no') !!}</label>
                                       <div class="col-md-7">
                                           {!! Form::text('passport_personal_no', null, $attributes = ['class' => 'form-control alphaNumeric input-sm', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your personal number', 'id' => 'passport_personal_no']) !!}
                                           {!! $errors->first('passport_personal_no', '<span class="help-block">:message</span>') !!}
                                       </div>
                                   </div>

                                   {{-- Date of Birth --}}

                                   <div class="form-group row">
                                       {!! Form::label('passport_DOB', trans('Signup::messages.date_of_birth'), ['class' => 'col-md-5 inputLabel']) !!}
                                       <div class="col-sm-7">
                                           <div class="input-group date" id="datetimepicker5" data-target-input="nearest">
                                               {!! Form::text('passport_DOB', '', ['class' => 'input-sm form-control inputBox datetimepicker-input required','data-target' => '#datetimepicker5']) !!}
                                               <div class="input-group-append" data-target="#datetimepicker5"
                                                    data-toggle="datetimepicker">
                                                   <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>

                                   {{-- Date of Expire --}}

                                   <div class="form-group row">
                                       {!! Form::label('passport_date_of_expire', trans('Signup::messages.expiry_date'), ['class' => 'col-md-5 inputLabel']) !!}
                                       <div class="col-sm-7">
                                           <div class="input-group date" id="datetimepicker6" data-target-input="nearest">
                                               {!! Form::text('passport_date_of_expire', '', ['class' => 'input-sm form-control inputBox datetimepicker-input required','data-target' => '#datetimepicker6', 'id' => 'passport_date_of_expire']) !!}
                                               <div class="input-group-append" data-target="#datetimepicker6"
                                                    data-toggle="datetimepicker">
                                                   <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="magnify">
                                       <div class="large" id="magnify_image_large"></div>
                                       <small>File name: <span id="passport_file_name_show" style="font-size: 10px"></span></small>
                                       <img class="small img-responsive" id="magnify_image_small"
                                            alt="Investor passport copy" src="" style="height: 200pxs">
                                   </div>
                               </div>
                           </div>
                        </div>
                    </div>

                    <br>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="button" id="btn_back" class="btn btn_back"
                                onclick="btnBackToSetNationality()" style="display: none;">
                                <i class="fa fa-arrow-left" style="color: #538FF3;"> ফিরে যান</i>
                            </button>
                            <button type="submit" title="You must fill in all of the fields"
                                class="btn btn-md btn_verify" id="nid_tin_verify" style="display: none;">
                                <i class="fa fa-check"></i>
                                <strong>যাচাই করুন</strong>
                            </button>
                            <button type="button" class="btn btn-md btn_verify" id="passport_verify"
                                style="display: none">
                                <i class="fa fa-check"></i>
                                <strong>Verify</strong>
                            </button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

            </div>
        </div>

    </div>

@endsection

@section('footer-script')
    <script src="{{ asset('assets/scripts/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset("assets/plugins/toastr/toastr.min.js") }}"></script>
    <script src="{{ asset('assets/scripts/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/scripts/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/modules/signup/identity_verify.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            @if (!empty($getPreviousVerificationData))
                $('#previousVerificationDataModal').modal('show');
            @endif
        });

        $(document).on('click', 'a', function() {
            return confirm('Do you want to leave this page?');
        });

        function btnBackToSetNationality() {
            $('#bd_nationality_fields').addClass('d-none');
            $('#foreign_nationality_fields').addClass('d-none');
            $('#btn_back').hide();

            document.getElementById('nid_field').classList.add('d-none');

            $('#etin_number_field').addClass('d-none');

            $('#user_dob_field').addClass('d-none');

            $('#nid_tin_verify').hide();

            $('#passport_div').addClass('d-none');

            $('#passport_verify').hide();
            $('#select_nationality').show();
            $('#identity_type_nid').prop('checked', false);
            $('#identity_type_passport').prop('checked', false);

            // Passport section reset
            let passport_reset_btn = document.getElementById('passport_reset_btn');
            passport_reset_btn.dispatchEvent(new Event("click"));
        }

        /**
         * Show nationality wise fields
         * @param nationality
         */
        function setUserNationality(nationality) {
            $("#nationality_types").val(nationality);

            if (nationality === 'bangladeshi') {
                $('#bd_nationality_fields').removeClass('d-none');
                $('#btn_back').show();
                $('#nid_tin_verify').show();
                $('#passport_verify').hide();

                $('#foreign_nationality_fields').addClass('d-none');

                document.getElementById('select_nationality').style.display = 'none';
                $('input[name="identity_type_foreign"]').prop('checked', false);
            } else if (nationality === 'foreign') {
                $('#foreign_nationality_fields').removeClass('d-none');

                $('#nid_tin_verify').hide();
                $('#passport_verify').show();
                $('#btn_back').show();

                $('#bd_nationality_fields').addClass('d-none');

                document.getElementById('select_nationality').style.display = 'none';
                $('input[name="identity_type_bd"]').prop('checked', false);
            } else {
                $('#foreign_nationality_fields').addClass('d-none');
                $('#bd_nationality_fields').addClass('d-none');
            }

            // Trigger on user identity
            setUserIdentity();
        }

        /**
         * Show identity type wise fields
         * @param identity
         */
        function setUserIdentity(identity) {
            if (identity === 'nid') {
                document.getElementById('nid_field').classList.remove('d-none');
                document.getElementById('user_nid').value = '';

                $('#user_dob_field').removeClass('d-none');
                document.getElementById('user_DOB').value = '';

                document.getElementById('etin_number_field').classList.add('d-none');

                document.getElementById('passport_div').classList.add('d-none');
                $('#passport_verify').hide();
                $('#nid_tin_verify').show();
            } else if (identity === 'tin') {
                $('#etin_number_field').removeClass('d-none');

                document.getElementById('etin_number').value = '';

                document.getElementById('nid_field').classList.add('d-none');

                $('#user_dob_field').removeClass('d-none');

                document.getElementById('user_DOB').value = '';

                document.getElementById('passport_div').classList.add('d-none');

                $('#passport_verify').hide();
                $('#nid_tin_verify').show();
            } else if (identity === 'passport') {

                $('#passport_div').removeClass('d-none');

                document.getElementById('nid_field').classList.add('d-none');


                document.getElementById('user_dob_field').classList.add('d-none');


                document.getElementById('etin_number_field').classList.add('d-none');
                $('#passport_verify').hide();
                $('#nid_tin_verify').hide();
                var passport_sample_height = $("#sample_passport").height() + 'px';
                $("#sample_passport_text").css("height", passport_sample_height);

            } else {
                document.getElementById('passport_div').classList.add('d-none');

                document.getElementById('nid_field').classList.add('d-none');

                document.getElementById('user_dob_field').classList.add('none');

                document.getElementById('etin_number_field').classList.add('d-none');
                $('#passport_verify').hide();
                $('#nid_tin_verify').show();
                $('#passport_logout').show();
            }
        }
    </script>
@endsection
