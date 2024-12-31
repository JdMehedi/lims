@extends('public_home.front')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}" />
    <link rel="stylesheet" href="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/signup/identity_verify.css') }}">
    <style>
        /*CSS For Mobile Screen*/
        @media (max-width: 767px) {
            form label {
                font-size: 18px;
            }
            .col-centered{
                float: none;
                margin: 0 auto;
            }
            .heading{
                font-weight: 700;
                font-size: 15px;
                margin-top: 40px;
            }
            .box-content{
                margin-top: 55px;
                margin-bottom: 65px;
            }
            .select-nationality{
                font-size: 21px;
                font-weight: 600;
                color: #575656;
            }
            .btn-nationality{
                font-size: 16px;
                font-weight: 600;
                color: #3E2C28;
                background: white;
                border: none;
                border-radius: 5px;
                margin: 12px;
                width: 220px;
                height: 45px;
            }
            .identityRadio {
                width: 20px;
                height: 20px;
            }
            .identityRadio:checked {
                color: red !important;
                width: 20px;
                height: 20px;
            }
            .foreignRadio {
                width: 20px;
                height: 20px;
            }
            .inputLabel{
                font-size: 13px;
            }
            .identityBd{
                font-size: 18px;
                color: #575656;
            }
            .identityForeign{
                font-size: 18px;
                color: #575656;
            }
            .inputBox{
                height: 40px;
                border: none;
                border-radius: 3px;
            }
            .radio_label{
                margin-left: 10px;
                font-size: 15px;
                vertical-align: bottom;
            }
            .btn_back{
                border: 1px solid #538FF3;
                border-radius: 3px;
                background: #EEEEEE;
            }
            .btn_verify{
                color: #048B04;
                border: 1px solid #048B04;
                border-radius: 3px;
                background: #EEEEEE;
            }
            .passport-upload-input {
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
            }
            .pass_div{
                position: relative;
            }
            .passport-upload{
                height: 90px;
            }
        }
    </style>
@endsection

@section ('body')
    <!-- NID data verification Modal -->
    <div class="modal fade" id="NIDVerifyModal" role="dialog" aria-labelledby="NIDVerifyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="NIDVerifyModalTitle">
                        <div class="pull-left">
                            <img src="{{ url('/assets/images/ec.png') }}" width="40px" height="40px"
                                 alt="election commision"/>
                            <span class="text-success">বাংলাদেশ নির্বাচন কমিশন থেকে জাতীয় পরিচয়পত্র যাচাই হচ্ছে</span>
                        </div>
                        <div class="clearfix"></div>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="errorMsgNID alert alert-danger alert-dismissible hidden">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>
                    <div class="successMsgNID alert alert-success alert-dismissible hidden">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>

                    <!-- NID Verification response div -->
                    <div class="alert alert-info" id="NIDVerificationResponse"></div>

                    <!-- Total spent time to NID verification -->
                    <div id="NIDVerificationTimeCounting" class="text-success">
                        Waiting for the connection to the national ID server, <span id="NIDVerifyTimeSpent">0</span>
                        seconds
                        passed.
                    </div>

                    <!-- NID data show after verification -->
                    <div id="VerifiedNIDInfo" class="clearfix" hidden>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-sm-4 text-center">
                                    <img id="nid_image" class="img-circle" src=""
                                         width="100px" height="100px">
                                </div>
                                <div class="col-sm-8">
                                    <br/>
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>{!!  trans('Signup::messages.name') !!}:</label>
                                            <span id="nid_name"></span>
                                        </div>
                                        <br/>
                                        <div class="form-group">
                                            <label>{!!  trans('Signup::messages.date_of_birth') !!}:</label>
                                            <span id="nid_dob"></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End NID data show after verification -->
                </div>
                <div class="modal-footer" id="NIDVerifyModalFooter">
                    <div class="pull-left">
                        {!! Form::button('<i class="fa fa-times"></i> বাতিল ', array('type' => 'button', 'class' => 'btn btn-danger round-btn', 'data-dismiss' => 'modal')) !!}
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-success round-btn hidden" id="SaveContinueBtn"
                                onclick="submitIdentityVerifyForm('identityVerifyForm')">
                            <i class="fa fa-save"></i> Save & continue
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End NID data verification Modal -->

    <!-- ETIN data verification Modal -->
    <div class="modal fade" id="ETINVerifyModal" role="dialog" aria-labelledby="ETINVerifyModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ETINVerifyModalTitle">
                        <div class="pull-left">
                            <img src="{{ url('/assets/images/nbrlogo.jpg') }}" width="180px" height="35px"
                                 alt="election commision"/>
                        </div>
                        <div class="pull-right">
                            <span class="text-success">ETIN Verification</span>
                        </div>
                        <div class="clearfix"></div>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="errorMsgTIN alert alert-danger alert-dismissible hidden">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>
                    <div class="successMsgTIN alert alert-success alert-dismissible hidden">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    </div>

                    <div class="alert alert-info" id="ETINResponseCountMsg"></div>

                    <div id="ETINVerifySuccessMsg" class="text-danger"></div>

                    <!-- ETIN data show after verification -->
                    <div id="VerifiedETINInfo" class="clearfix" hidden>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form>
                                        <div class="form-group">
                                            <label>{!!  trans('Signup::messages.name') !!}:</label>
                                            <span id="etin_name"></span>
                                        </div>
                                        <div class="form-group">
                                            <label>{!!  trans('Signup::messages.father_name') !!}:</label>
                                            <span id="etin_father_name"></span>
                                        </div>
                                        <div class="form-group">
                                            <label>{!!  trans('Signup::messages.date_of_birth') !!}:</label>
                                            <span id="etin_dob"></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End ETIN data show after verification -->
                </div>
                <div class="modal-footer" id="ETINVerifyModalFooter">
                    <div class="pull-left">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger round-btn', 'data-dismiss' => 'modal')) !!}
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-success round-btn hidden" id="etinSaveContinueBtn"
                                onclick="submitIdentityVerifyForm('identityVerifyForm')">
                            <i class="fa fa-save"></i> Save & continue
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- End ETIN data verification Modal -->

    <!-- Passport error modal -->
    <div class="modal fade" id="PassportErrorModal" role="dialog" aria-labelledby="PassportErrorModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title text-danger" id="PassportErrorModalTitle">
                        <strong>Oh snap!</strong> Your passport verification failed.
                    </h4>
                </div>
                <div class="modal-body">
                    <div style="padding: 20px; text-align: center;">
                        <button type="button" id="passport_error_retry" class="btn btn-primary">Retry
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
    @if(!empty($getPreviousVerificationData))
        <div id="previousVerificationDataModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <p class="text-success"><i>You tried previously to register by verifying <span class="text-uppercase">{{ $getPreviousVerificationData->identity_type }}</span>.</i></p>
                        <h4 class="modal-title">Do you want to continue with the previous verification information?</h4>
                    </div>
                    {!! Form::open(array('url' => 'client/signup/identity-verify-previous/' . \App\Libraries\Encryption::encodeId($getPreviousVerificationData->id) ,'method' => 'post', 'class' => 'form-horizontal','enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3">
                                                <span class="v_label">{!!  trans('Signup::messages.nationality_type') !!}</span>
                                                <span class="pull-right">&#58;</span>
                                            </label>
                                            <span class="col-sm-9">{{ ucfirst($getPreviousVerificationData->nationality_type) }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">
                                                <span class="v_label">{!!  trans('Signup::messages.identity_type') !!}</span>
                                                <span class="pull-right">&#58;</span>
                                            </label>
                                            <span class="col-sm-9">{{ ucfirst($getPreviousVerificationData->identity_type) }}</span>
                                        </div>

                                        @if($getPreviousVerificationData->identity_type == 'tin')
                                            <?php
                                            $previous_tin_info = json_decode(Encryption::decode($getPreviousVerificationData->eTin_info), true);
                                            ?>
                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.tin_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_tin_info['etin_number'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_tin_info['assesName'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.father_name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_tin_info['fathersName'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ date('d-M-Y', strtotime($previous_tin_info['dob'])) }}</span>
                                            </div>
                                        @elseif($getPreviousVerificationData->identity_type == 'nid')
                                            <?php
                                            $previous_nid_info = json_decode(Encryption::decode($getPreviousVerificationData->nid_info), true);
                                            ?>
                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.nid_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_nid_info['return'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_nid_info['return']['voterInfo']['voterInfo']['nameEnglish'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ date('d-M-Y', strtotime($previous_nid_info['return']['voterInfo']['voterInfo']['dateOfBirth'])) }}</span>
                                            </div>
                                        @elseif($getPreviousVerificationData->identity_type == 'passport')
                                            <?php
                                            $previous_passport_info = json_decode(Encryption::decode($getPreviousVerificationData->passport_info), true);
                                            ?>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.passport_type') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_passport_info['passport_type'] }}</span>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.passport_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_passport_info['passport_no'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.sure_name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_passport_info['passport_surname'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.name') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_passport_info['passport_given_name'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.personal_no') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ $previous_passport_info['passport_personal_no'] }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.date_of_birth') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ date('d-M-Y', strtotime($previous_passport_info['passport_DOB'])) }}</span>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3">
                                                    <span class="v_label">{!!  trans('Signup::messages.expiry_date') !!}</span>
                                                    <span class="pull-right">&#58;</span>
                                                </label>
                                                <span class="col-sm-9">{{ date('d-M-Y', strtotime($previous_passport_info['passport_date_of_expire'])) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="pull-left">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Skip</button>
                        </div>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-success">Continue</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    @endif

    <div class="container">
        {{--Heading--}}
        <div class="row">
            <div class="col-xs-12 text-center" style="margin-top: 15px; margin-bottom: 10px; color: #603433">

                <h2 class="heading">
                    {!!trans('Signup::messages.reg_title')!!}
                </h2>
            </div>
        </div>
        <div class="jumbotron col-xs-12 col-centered" style="margin-bottom: 30px;">

            {!! Form::open(array('url' => 'client/signup/identity-verify','method' => 'post', 'class' => 'form-horizontal', 'id' => 'identityVerifyForm', 'name' => 'identityVerifyForm',
                            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

            <div class="row" id="select_nationality">
                <div class="col-xs-12 col-centered">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <h2 class="select-nationality">
                                    {!!trans('Signup::messages.nationality')!!}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div class="col-xs-12 bd-btn"><button type="button" class="btn-nationality" id="bangladeshi" value="bangladeshi" onclick="setUserNationality(this.value)"> {!!trans('Signup::messages.bangladeshi')!!}</button></div>
                                <div class="col-xs-12 frn-btn"><button type="button" class="btn-nationality" id="foreign" value="foreign" onclick="setUserNationality(this.value)"> {!!trans('Signup::messages.foreign')!!}</button></div>
                                <input type="hidden" name="nationality_type" id="nationality_types">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <a href="/" class="btn btn_back"><i class="fa fa-arrow-left" style="color: #538FF3;"> ফিরে যান</i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="bd_nationality_fields" hidden>
                <div class="col-xs-12 col-centered">

                    {!! Form::label('identity_type_bd',trans('Signup::messages.identity'),['class'=>'col-xs-12', 'id' => 'nationality']) !!}
                    <div class="col-xs-6">
                        {!! Form::radio('identity_type_bd', 'nid', false, ['class'=>'required identityRadio', 'id' => 'identity_type_nid', 'onclick' => 'setUserIdentity(this.value)']) !!}
                        <label class="radio_label" for="identity_type_nid">
                            {!! trans('Signup::messages.nid') !!}
                        </label>
                    </div>
                </div>
            </div>

            <div id="foreign_nationality_fields"
                 class="row form-group has-feedback {{$errors->has('identity_type_foreign') ? 'has-error': ''}}"
                 hidden>
                <div class="col-xs-12" id="passport_radio">
                    {!! Form::label('identity_type_foreign',trans('Signup::messages.identity'),['class'=>'col-xs-12', 'id' => 'nationality']) !!}
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-6">
                                    {!! Form::radio('identity_type_foreign', 'passport', false, ['class'=>'required identityRadio', 'id' => 'identity_type_passport', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                <label class="radio_label" for="identity_type_passport">
                                    {!! trans('Signup::messages.passport') !!}
                                </label>
                            </div>
                        </div>
                        {!! $errors->first('identity_type_foreign','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>

            {{--National ID No--}}
            <div class="row" id="nid_field" style="margin-top: 15px;" hidden>
                <div class="col-xs-12">
                    {!! Form::label('user_nid',trans('Signup::messages.nid_no'),['class'=>'col-xs-12 inputLabel', 'id' => 'nid_number']) !!}
                    <div class="col-xs-12 {{$errors->has('nid_number') ? 'has-error': ''}}">
                        {!! Form::text('user_nid','', ['placeholder' => 'Enter your NID number', 'class' => 'form-control inputBox','id'=>'user_nid']) !!}
                        {!! Form::hidden('verified_nid_data', null, $attributes = array('id'=>"verified_nid_data")) !!}
                        <label style="font-size: 11px; color: #F2A3A3;">
                            You need to enter 13 or 17 digits NID or 10 digits smart cart ID
                        </label>
                        {!! $errors->first('user_nid','<span
                            class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>

            {{-- TIN Number --}}
            <div class="row" id="etin_number_field" style="margin-top: 15px;" hidden>
                <div class="col-xs-12">
                    {!! Form::label('etin_number',trans('Signup::messages.tin_no'),['class'=>'col-xs-12 inputLabel', 'id' => 'tin_number']) !!}
                    <div class="col-xs-12 {{$errors->has('nid_number') ? 'has-error': ''}}">
                        {!! Form::text('etin_number','', ['placeholder' => 'Enter your TIN number', 'class' => 'form-control inputBox onlyNumber','id'=>'etin_number']) !!}
                        {!! Form::hidden('verified_etin_data', null, $attributes = array('id'=>"verified_etin_data")) !!}
                        <label style="font-size: 10px; color: #F2A3A3;">
{{--                            You need to enter 13 or 17 digits NID or 10 digits smart cart ID--}}
                        </label>
                        {!! $errors->first('etin_number','<span
                            class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>

            {{-- Date of Birth --}}
            <div class="row" id="user_dob_field" hidden>
                <div class="col-xs-12">
                    {!! Form::label('applicant_dob',trans('Signup::messages.date_of_birth'),['class'=>'col-xs-12 inputLabel', 'id' => 'applicant_dob']) !!}
                    <div class="col-xs-12 {{$errors->has('applicant_dob') ? 'has-error': ''}}">
                        <div class="userDP input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                            {!! Form::text('user_DOB', '', ['class'=>'form-control inputBox dateMbl', 'placeholder' => 'Pick from Calendar', 'data-rule-maxlength'=>'40', 'id'=>'user_DOB']) !!}
                            <span class="input-group-addon inputBox">

                                    <span class="fa fa-calendar"></span>
                                </span>
                            {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>

            <div id="passport_div" style="margin-top: 5px;" hidden>

                <div id="passport_upload_wrapper" class="passport-upload-wrapper">
                    <div class="row">
                            <span id="passport_upload_error" class="text-danger text-left"></span>

                            <div class="panel-body" id="passport_capture_div" type="mobile" style="background: white; margin: 15px 20px">

                                <div style="text-align: center;" id="passport_capture">
                                    <div class="passport-capture">
                                        <img class="col-centered" src="/assets/images/camera.png" alt="" style="margin-top: 15px;">
                                        <p style="font-size: 12px; font-weight: 600; color: #7D7C7C">
                                            Capture your passport
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body pass_div" id="passport_upload_div" style="background: white; margin: 0 20px">

                                <div style="text-align: center;" >
                                    <div class="passport-upload">
                                        <div class="passport-upload-message">
                                            {{--<i class="fas fa-cloud-upload-alt fa-3x passport-upload-icon"></i>--}}
                                            <img src="/assets/images/cloud_upload.png" alt="" >
                                            <p style="font-size: 12px; font-weight: 600; color: #7D7C7C">
                                                Browse Image
                                            </p>
                                        </div>
                                        <input accept="image/*" type="file" name="passport_upload"
                                               id="passport_upload" class="passport-upload-input"
                                               onchange="getPassportImage(this);">
                                    </div>
                                </div>
                            </div>

                            <div id="passport_preloader" class="text-center fa-3x"
                                 style="display: none; padding: 10px 0;"><i class="fa fa-spinner fa-pulse"></i>
                            </div>
                    </div>
                    <div id="passport_upload_view_wrapper" hidden>
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="passport_upload_view_div">
                                    <img class="img-thumbnail" id="passport_upload_view" src="#"
                                         alt="Investor passport upload copy">
                                    <input type="hidden" name="passport_upload_base_code"
                                           id="passport_upload_base_code">
                                    <input type="hidden" name="passport_upload_manual_file"
                                           id="passport_upload_manual_file">
                                    <input type="hidden" name="passport_file_name" id="passport_file_name">
                                </div>

                                <div id="passport_cropped_result" class="col-xs-12 panel panel-info">

                                </div>

                                <div style="margin-top: 15px;">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <button style="margin-left: 15px; display: none; background: #EEEEEE; color: #1673E6; border: 1px solid #1673E6;" type="button" id="passport_edit_btn"
                                                    class="btn btn-sm pull-left">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>

                                            <button type="button" id="passport_crop_btn"
                                                    class="btn btn-sm pull-left" style="background: #1673E6; color:white; margin-left: 15px;">
                                                Done
                                            </button>
                                        </div>
                                        <div class="col-xs-6 text-center">
                                            {{--                                                Data: <span id="crop_data_info"></span>--}}
                                        </div>
                                        <div class="col-xs-3">
                                            <button type="button" id="passport_reset_btn"
                                                    class="btn btn-link pull-right">
                                                <i class="fa fa-undo"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel" style="border: none;">
                            <div class="panel-heading text-center" style="padding-left: 5px; background-color: #EEEEEE">
                                <h3 class="panel-title" style="color: #6793C3">FAQ</h3>
                            </div>
                            <div class="panel-body" style="border: none; background-color: #EEEEEE">
                                <ul class="list-group" style="border: none; color: #7FA4CC">
                                    <li class="list-group-item">
                                        <strong style="color: #6793C3">Ques: How to zoom in or zoom out?</strong> <br>
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
                                        <strong style="color: #6793C3">Ques: How to passport verify? </strong> <br>
                                        Ans: After done your image you will get a
                                        green color verify button. Just click and wait for
                                        result.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="preloader" class="text-center fa-3x" style="display: none; padding: 10px 0"></div>
                <div id="passport_div_verification" hidden>
                    <div class="col-xs-12" style="margin-bottom: 15px;">
                        {{-- Passport Nationality --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_nationality') ? 'has-error' : ''}}">
                            <label for="passport_nationality"
                                   class="col-xs-12 small text-left">{!!  trans('Signup::messages.passport_nationality') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::select('passport_nationality', $passport_nationalities, '', $attributes = array('class'=>'form-control input-md required',
                                    'placeholder' => 'Select one', 'id'=>"passport_nationality")) !!}
                                {!! $errors->first('passport_nationality','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Passport Type --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_type') ? 'has-error' : ''}}">
                            <label for="passport_type"
                                   class="col-xs-12 small">{!!  trans('Signup::messages.passport_type') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::select('passport_type', $passport_types, '', $attributes = array('class'=>'form-control input-md required',
                                    'placeholder' => 'Select one', 'id'=>"passport_type")) !!}
                                {!! $errors->first('passport_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Passport No --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">
                            <label for="passport_no" class="col-xs-12 small">{!!  trans('Signup::messages.passport_no') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::text('passport_no', null, $attributes = array('class'=>'form-control required alphaNumeric input-md', 'data-rule-maxlength'=>'20',
                                'placeholder'=>'Enter your passport number', 'id'=>"passport_no")) !!}
                                {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                <span class="text-danger pss-error"></span>
                            </div>
                        </div>

                        {{-- Surname --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_surname') ? 'has-error' : ''}}">
                            <label for="passport_surname" class="col-xs-12 small">{!!  trans('Signup::messages.sure_name') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::text('passport_surname', null, $attributes = array('class'=>'form-control textOnly input-md required',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your surname', 'id'=>"passport_surname")) !!}
                                {!! $errors->first('passport_surname','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Given Name	 --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_given_name') ? 'has-error' : ''}}">
                            <label for="passport_given_name" class="col-xs-12 small">{!!  trans('Signup::messages.name') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::text('passport_given_name', null, $attributes = array('class'=>'form-control textOnly input-md required',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your given name', 'id'=>"passport_given_name")) !!}
                                {!! $errors->first('passport_given_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Personal No --}}
                        <div class="form-group has-feedback {{ $errors->has('passport_personal_no') ? 'has-error' : ''}}">
                            <label for="passport_personal_no" class="col-xs-12 small">{!!  trans('Signup::messages.personal_no') !!}</label>
                            <div class="col-xs-12">
                                {!! Form::text('passport_personal_no', null, $attributes = array('class'=>'form-control alphaNumeric input-md',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your personal number', 'id'=>"passport_personal_no")) !!}
                                {!! $errors->first('passport_personal_no','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="form-group has-feedback {{$errors->has('passport_DOB') ? 'has-error' : ''}}">
                            <label for="passport_DOB" class="col-xs-12 small">{!!  trans('Signup::messages.date_of_birth') !!}</label>
                            <div class="col-xs-12">
                                <div class="passportDP input-group date">
                                    {!! Form::text('passport_DOB', '', ['class'=>'form-control input-md required', 'id'=>'passport_DOB', 'placeholder' => 'Pick from calendar']) !!}
                                    <span class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                </div>
                                {!! $errors->first('passport_DOB','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Date of Expire --}}
                        <div class="form-group has-feedback {{$errors->has('passport_date_of_expire') ? 'has-error' : ''}}">
                            <label for="passport_date_of_expire" class="col-xs-12 small">{!!  trans('Signup::messages.expiry_date') !!}</label>
                            <div class="col-xs-12">
                                <div class="passExpiryDate input-group date">
                                    {!! Form::text('passport_date_of_expire', '', ['class'=>'form-control input-md required','id'=>'passport_date_of_expire',  'placeholder' => 'Pick from calendar']) !!}
                                    <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                </div>
                                {!! $errors->first('passport_date_of_expire','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br/>
            <div class="form-group">
                <div class="col-xs-12 text-center">
                    <button type="button" id="btn_back" class="btn btn_back" onclick="btnBackToSetNationality()" style="display: none;">
                        <i class="fa fa-arrow-left" style="color: #538FF3;"> Back</i>
                    </button>
                    <button type="submit" title="You must fill in all of the fields"
                            class="btn btn-md btn_verify" id="nid_tin_verify" disabled style="display: none;">
                        <i class="fa fa-check"></i>
                        <strong>Verify</strong>
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
@endsection

@section('footer-script')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
    <script src="{{ asset('vendor/cropperjs_v1.5.7/cropper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset("assets/modules/signup/identity_verify.js") }}" type="text/javascript"></script>

    <script>

        $(document).ready(function () {

            @if(!empty($getPreviousVerificationData))
            $('#previousVerificationDataModal').modal('show');
            @endif
        });
        $(document).on('click','a',function () {
            return confirm('Do you want to leave this page?');
        });

        function btnBackToSetNationality(){
            $('#bd_nationality_fields').hide();
            $('#foreign_nationality_fields').hide();
            $('#btn_back').hide();
            $('#nid_field').hide();
            $('#etin_number_field').hide();
            $('#user_dob_field').hide();
            $('#nid_tin_verify').hide();
            $('#passport_div').hide();
            $('#passport_verify').hide();
            $('#select_nationality').show();
            $('#identity_type_nid').prop('checked', false);
            $('#identity_type_passport').prop('checked', false);
        }

    </script>
@endsection
