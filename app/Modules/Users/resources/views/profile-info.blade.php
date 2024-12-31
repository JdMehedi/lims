@extends('layouts.admin')

@section('header-resources')

    <style>
        .analog-clock {
            width: 250px;
            height: 250px;
        }

        #clock-face {
            stroke: black;
            stroke-width: 2px;
            fill: white;
        }

        #clock-face-db {
            stroke: black;
            stroke-width: 2px;
            fill: white;
        }

        #h-hand, #m-hand, #s-hand, #s-tail, #db-h-hand, #db-m-hand, #db-s-hand, #db-s-tail {
            stroke: black;
            stroke-linecap: round;
        }

        #h-hand, #db-h-hand {
            stroke-width: 3px;
        }

        #m-hand, #db-m-hand {
            stroke-width: 2px;
        }

        #s-hand, #db-s-hand {
            stroke-width: 1px;
        }

        .time-text {
            text-align: center;
        }

        #accessList {
            height: 100px !important;
            overflow: scroll;
        }

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .profileinfo-table {
            width: 100% !important;
        }

        .sorting {
            background-image: url(../images/sort_both_oss.png);
        }
    </style>
    <link rel="stylesheet"
          href="{{ asset("assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.min.css") }}"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="{{ asset("assets/plugins/password-strength/password_strength.css") }}">
    @include('partials.datatable-css')
@endsection

@section('content')
    @include('partials.messages')
    @if(Session::has('checkProfile'))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <strong>Dear user</strong><br><br>
                    <p>We noticed that your profile setting does not complete yet 100%.<br>Please upload your <b>profile
                            picture</b>,<b>signature</b> And other required information <br>Without required filed you
                        can't
                        apply for any kind of Registration.<br><br>Thanks<br>BHTPA Authority </p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-warning">
                    {{session('message')}}
                </div>
            @endif

        </div>

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <div class="card card-info">
                    <div class="card-headerd">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab_1">
                                    {!! trans('Users::messages.profile') !!}
                                </a>
                            </li>
                            @if(!env('IS_MOBILE'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       data-toggle="tab"
                                       href="#tab_2"
                                        {{--                                   href="{{ url( config('osspid.osspid_base_url').'/user/profile-setting#change_password') }}"--}}
                                    ><strong>{!! trans('Users::messages.change_password') !!}</strong></a>
                                </li>

                                @if(in_array(Auth::user()->user_type, ['4x404']))

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab"
                                           href="#tab_3"><b>{!! trans('Users::messages.delegation') !!}</b></a>
                                    </li>

                                @endif

                                <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab"
                                                        id="50Activities"
                                                        aria-expanded="false"><b>{!! trans('Users::messages.last_50_activities') !!}</b></a>
                                </li>

                                <li class="nav-item"><a class="nav-link" href="#tab_5" data-toggle="tab" id="accessLog"
                                                        aria-expanded="false"><b>{!! trans('Users::messages.access_log') !!}</b></a>
                                </li>

                                <li class="nav-item"><a href="#tab_6" class="nav-link" data-toggle="tab"
                                                        id="accessLogFailed"
                                                        aria-expanded="false"><b>{!! trans('Users::messages.access_log_failed') !!}</b></a>
                                </li>

                                @if(in_array(Auth::user()->user_type, ['5x505']))
                                    <li class="nav-item d-none"><a href="#tab_7" class="nav-link" data-toggle="tab"
                                                            id="companyAssociation"
                                                            aria-expanded="false"><b>{!! trans('Users::messages.companyAssociation') !!}</b></a>
                                    </li>
                                @endif
                            @endif


                        </ul>


                    </div>

                    <div class="card-body">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="tab_1" class=" tab-pane active">
                                {!! Form::open(array('url' => '/users/profile_updates/'.$id,'method' =>'post','id'=>'update_form', 'class' => 'form-horizontal',
                                                                            'enctype'=>'multipart/form-data')) !!}
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset>
                                            {!! Form::hidden('Uid', $id) !!}
                                            <div class="alert d-none" role="alert" id="responseMsgSec">
                                                <span></span>
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-lg-4 text-left">{!! trans('Users::messages.user_type') !!}</label>
                                                <div class="col-lg-8">
                                                    {{ $user_type_info->type_name }}
                                                </div>
                                            </div>
                                            @if($users->desk_id = '0')
                                                <div class="form-group row">
                                                    <label
                                                        class="col-lg-4 text-left">{!! trans('Users::messages.user_desk_name') !!}</label>
                                                    <div class="col-lg-7">
                                                        {{$user_desk->desk_name}}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group row">

                                                <label
                                                    class="col-lg-4 text-left">{!! trans('Users::messages.user_email') !!}</label>
                                                <div class="{{ (int)$users->email_verified ? 'col-lg-8':'col-lg-5'  }}">
                                                    {!! Form::text('user_email',$users->user_email, $attributes = array('class'=>'form-control required',
                                                       'placeholder'=>'Enter Email','id'=>"user_email",'readonly' => true)) !!}
                                                </div>
                                                @if(!(int)$users->email_verified)
                                                    <div class="col-lg-3">
                                                        <button class="btn btn-primary btn-md" id="verity_btn"
                                                                onclick="SendEmailVerificationLink()">
                                                            <span>Verify</span>
                                                            <i class="fa fa-spinner fa-spin d-none"
                                                               id="verity_btn_loader"></i>
                                                        </button>
                                                    </div>
                                                @endif

                                            </div>

                                            <div
                                                class="form-group row {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                                                <label
                                                    class="col-lg-4 text-left ">{!! trans('Users::messages.user_name') !!}</label>
                                                <div class="col-lg-8">
                                                    <div class="input-group ">
                                                        {!! Form::text('user_first_name',$users->user_first_name, $attributes = array('class'=>'form-control required',
                                                        'placeholder'=>'Enter Name','id'=>"user_first_name", 'data-rule-maxlength'=>'50', 'readonly' => true)) !!}
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon2"><i
                                                                    class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                    {!! $errors->first('user_first_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group row {{ $errors->has('user_gender') ? 'has-error' : '' }}">
                                                <label
                                                    class="col-lg-4 text-left required-star">{!! trans('Users::messages.user_gender') !!}</label>
                                                <div class="col-md-7">
                                                    @if($users->user_gender ==  'Male')
                                                        <label
                                                            class="identity_hover">{!! Form::radio('user_gender', 'Male',  ($users->user_gender ==  'Male') ?  true : false, ['class'=>'user_gender']) !!}
                                                            Male
                                                        </label>
                                                    @elseif($users->user_gender ==  'Female')
                                                        <label
                                                            class="identity_hover">{!! Form::radio('user_gender', 'Female',  ($users->user_gender ==  'Female') ?  true : false, ['class'=>'user_gender']) !!}
                                                            Female
                                                        </label>
                                                    @elseif($users->user_gender ==  'Not defined')
                                                        <label
                                                            class="identity_hover">{!! Form::radio('user_gender', 'Not defined',  ($users->user_gender ==  'Not defined') ?  true : false, ['class'=>'user_gender']) !!}
                                                            Others
                                                        </label>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(Auth::user()->user_type == '4x404')
                                                <div
                                                    class="form-group row {{ $errors->has('designation') ? 'has-error' : '' }}">
                                                    <label
                                                        class="col-lg-4 text-left required-star">{!! trans('Users::messages.user_designation') !!}</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::text('designation',$users->designation, ['class'=>'form-control required','data-rule-maxlength'=>'40',
                                                        'placeholder'=>'Enter your Designation']) !!}
                                                        {!! $errors->first('designation','<span class="help-block">:message</span>')
                                                        !!}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group row">
                                                <label
                                                    class="col-lg-4 text-left required-star">{!! trans('Users::messages.user_dob') !!}</label>
                                                <div class="col-lg-8">

                                                    <div class="input-group date datetimepicker4"
                                                         id="datepicker0" data-target-input="nearest">
                                                        <?php
                                                        $dob = '';
                                                        if ( $users->user_DOB ) {
                                                            $dob = App\Libraries\CommonFunction::changeDateFormat( $users->user_DOB );
                                                        }
                                                        ?>
                                                        {!! Form::text('user_DOB', $dob, ['class' => 'form-control', 'id' => 'user_DOB']) !!}
                                                        <div class="input-group-append"
                                                             data-target="#datepicker0"
                                                             data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                        {!! $errors->first('user_DOB', '<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="form-group row {{ $errors->has('user_mobile') ? 'has-error' : ''}}">
                                                <label
                                                    class="col-lg-4 text-left required-star">{!! trans('Users::messages.user_mobile') !!}</label>
                                                <div class="col-lg-8 input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <span class="iti-flag bd"></span>
                                                            <span>+88</span>
                                                        </span>
                                                    </div>
                                                    {!! Form::text('user_mobile',$users->user_mobile, $attributes = array('class'=>'form-control required mobile_number_validation',
                                                    'placeholder'=>'Enter your Mobile Number','id'=>"user_mobile")) !!}

                                                    {!! $errors->first('user_mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div
                                                class="form-group row {{ $errors->has('contact_address') ? 'has-error' : ''}}">
                                                <label
                                                    class="col-lg-4 text-left">Contact Address</label>
                                                <div class="col-lg-8">

                                                    {!! Form::text('contact_address',$users->contact_address, $attributes = array('class'=>'form-control',
                                                    'placeholder'=>'Enter your contact address','id'=>"contact_address")) !!}

                                                    {!! $errors->first('contact_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>


                                            @if(Auth::user()->user_type != '1x101')
                                                <div
                                                    class="form-group row {{$errors->has('identity_type') ? 'has-error': ''}}">
                                                    {!! Form::label('identity_type',trans('Users::messages.identity_type'),['class'=>'text-left col-md-4', 'id' => 'identity_type_label']) !!}
                                                    <div class="col-md-7">
                                                        @if($users->identity_type ==  'passport')
                                                            <label
                                                                class="identity_hover">{!! Form::radio('identity_type', 'passport',  ($users->identity_type ==  'passport') ?  true : false, ['class'=>'identity_type', 'onclick' => 'selectIdentityType(this.value)']) !!}
                                                                {!! trans('Users::messages.passport') !!}
                                                            </label>
                                                        @elseif($users->identity_type ==  'nid')
                                                            <label
                                                                class="identity_hover">{!! Form::radio('identity_type', 'nid',  ($users->identity_type ==  'nid') ?  true : false, ['class'=>'identity_type', 'onclick' => 'selectIdentityType(this.value)']) !!}
                                                                {!! trans('Users::messages.user_nid') !!}
                                                            </label>
                                                        @elseif($users->identity_type ==  'tin')
                                                            <label
                                                                class="identity_hover">{!! Form::radio('identity_type', 'tin',  ($users->identity_type ==  'tin') ?  true : false, ['class'=>'identity_type', 'onclick' => 'selectIdentityType(this.value)']) !!}
                                                                {!! trans('Users::messages.tin') !!}</label>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div
                                                    class="form-group row  {{ $errors->has('passport_no') ? 'has-error' : ''}}"
                                                    id="passport_div">
                                                    <label
                                                        class="col-lg-4 text-left">{!! trans('Users::messages.passport_no') !!}</label>
                                                    <div class="col-lg-8">
                                                        <div class="input-group ">
                                                            {!! Form::text('passport_no',$users->passport_no, ['class'=>'form-control required','data-rule-maxlength'=>'40',
                                                           'placeholder'=>'Enter your Passport (if any)','id'=>"passport_no", 'readonly' => true]) !!}

                                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2"><i
                                                            class="fa fa-book"></i></span>
                                                            </div>
                                                        </div>
                                                        {!! $errors->first('passport_no','<span class="help-block">:message</span>')!!}
                                                    </div>
                                                </div>

                                                <div
                                                    class="form-group row {{ $errors->has('user_nid') ? 'has-error' : '' }}"
                                                    id="nid_div">
                                                    <label
                                                        class="col-lg-4 text-left required-star">{!! trans('Users::messages.user_nid_no') !!}</label>
                                                    <div class="col-lg-8">
                                                        <div class="input-group ">
                                                            {!! Form::text('user_nid',$users->user_nid, ['class'=>'form-control required','data-rule-maxlength'=>'40',
                                                            'placeholder'=>'Enter your NID (if any)','id'=>"user_nid", 'readonly' => true]) !!}
                                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2"><i
                                                            class="fa fa-book"></i></span>
                                                            </div>
                                                        </div>
                                                        {!! $errors->first('user_nid','<span class="help-block">:message</span>')
                                                        !!}
                                                    </div>
                                                </div>

                                                {{--                                                <div--}}
                                                {{--                                                    class="form-group row {{ $errors->has('user_tin') ? 'has-error' : '' }}"--}}
                                                {{--                                                    id="tin_div">--}}
                                                {{--                                                    <label--}}
                                                {{--                                                        class="col-lg-4 text-left required-star">{!! trans('Users::messages.tin_no') !!}</label>--}}
                                                {{--                                                    <div class="col-lg-8">--}}
                                                {{--                                                        <div class="input-group ">--}}
                                                {{--                                                            {!! Form::text('user_tin',$users->user_tin, ['class'=>'form-control required','data-rule-maxlength'=>'40',--}}
                                                {{--                                                            'placeholder'=>'Enter your TIN (if any)','id'=>"user_tin"]) !!}--}}
                                                {{--                                                            <div class="input-group-append">--}}
                                                {{--                                                    <span class="input-group-text" id="basic-addon2"><i--}}
                                                {{--                                                            class="fa fa-credit-card"></i></span>--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                        {!! $errors->first('user_tin','<span class="help-block">:message</span>')--}}
                                                {{--                                                        !!}--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}
                                            @endif


                                            <div
                                                class="form-group row {{$errors->has('identity_type') ? 'has-error': ''}}">
                                                {!! Form::label('identity_type',trans('Users::messages.to_step'),['class'=>'text-left col-md-4', 'id' => 'identity_type_label']) !!}
                                                <div class="col-md-7">
                                                    <label
                                                        class="sd">{!! Form::radio('auth_token_allow', 1,  ($users->auth_token_allow ==  '1') ?  true : false) !!}
                                                        {!! trans('Users::messages.yes') !!}&nbsp;&nbsp;
                                                    </label>
                                                    <label
                                                        class="sd">{!! Form::radio('auth_token_allow', 0,  ($users->auth_token_allow ==  '0') ?  true : false) !!}
                                                        {!! trans('Users::messages.no') !!}
                                                    </label>
                                                </div>
                                            </div>


                                            @if($users->user_status == "rejected")
                                                <div class="form-group row">
                                                    <label class="col-lg-4 text-left">Reject Reason</label>
                                                    <div class="col-lg-8">
                                                        {{ $users->user_status_comment }}
                                                    </div>
                                                </div>
                                            @endif


                                        </fieldset>
                                    </div>

                                    <div class="col-md-1 col-sm-1"></div>

                                    <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                        <div class="card card-default" id="browseimagepp">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 addImages" style="max-height:300px;">
                                                    <label class="center-block image-upload" for="user_pic"
                                                           style="margin: 0px">
                                                        <figure>
                                                            <img
                                                                src="{{ (!empty($users->user_pic) ?  url('uploads/users/profile-pic/' . $users->user_pic) : CommonComponent()->fileUrlEncode('assets/images/user_profile.jpg')) }}"
                                                                class="img-responsive img-thumbnail"
                                                                id="user_pic_preview"/>
                                                        </figure>
                                                        <input type="hidden" id="user_pic_base64"
                                                               name="user_pic_base64"/>
                                                        @if(!empty($users->user_pic))
                                                            <input type="hidden" name="user_pic"
                                                                   value="{{$users->user_pic}}"/>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 col-md-8">
                                                    <h4 id="profile_image">
                                                        {!! Form::label('user_pic',trans('Users::messages.profile_image'), ['class'=>'text-left required-star']) !!}
                                                    </h4>
                                                    <span class="text-success col-lg-8 text-left"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>

                                                    <span id="user_err" class="text-danger col-lg-8 text-left"
                                                          style="font-size: 10px;"> {!! $errors->first('applicant_photo','<span class="help-block">:message</span>') !!}</span>
                                                    <div class="clearfix"><br/></div>
                                                    <label class="btn btn-primary btn-file">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        {!! trans('Users::messages.browse') !!}<input type="file"
                                                                                                      class="custom-file-input input-sm {{!empty($users->user_pic) ? '' : 'required'}}"
                                                                                                      name="user_pic"
                                                                                                      id="user_pic"
                                                                                                      onchange="imageUploadWithCroppingAndDetect(this, 'user_pic_preview', 'user_pic_base64')"
                                                                                                      size="300x300"/>


                                                    </label>

{{--                                                    <label class="btn btn-primary btn-file" id="cameraclick">--}}
{{--                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>--}}
{{--                                                        Camera <span class="btn btn-primary"></span>--}}
{{--                                                    </label>--}}
                                                </div>


                                            </div>
                                        </div>
                                        <div class="card card-default" style="display: none" id="camera">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-8">


                                                </div>

                                                <div class="col-md-6">

                                                    <h4>
                                                        {!! Form::label('user_pic',trans('Users::messages.profile_image'), ['class'=>'text-left required-star']) !!}
                                                    </h4>
                                                    <span class="text-success col-lg-12 text-left"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>

                                                    <div id="my_camera"></div>
                                                    <div id="results">Your captured image will appear here...</div>

                                                    <br/>

                                                    <input type=button id="ts" value="Take Snapshot"
                                                           onClick="take_snapshot()">

                                                    <input type="hidden" name="image" class="image-tag">

                                                    <button type="button" id="reset_image_from_webcamera"
                                                            class="btn btn-warning btn-xs" value="">Reset image
                                                    </button>

                                                </div>

                                            </div>
                                        </div>

                                        @if(Auth::user()->user_type == '4x404')
                                            <br>
                                            <div class="card card-default">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-4 addImages" style="max-height:300px;">
                                                        <label class="center-block image-upload" for="signature">
                                                            <figure>
                                                                <img
                                                                    src="{{ (!empty($users->signature) ? url('uploads/users/signature/' . $users->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"
                                                                    class="img-responsive img-thumbnail"
                                                                    id="signature_preview"/>
                                                            </figure>
                                                            <input type="hidden" id="signature_base64"
                                                                   name="signature_base64"/>
                                                            @if(!empty($users->signature))
                                                                <input type="hidden" name="signature"
                                                                       value="{{$users->signature}}"/>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-6 col-md-8">
                                                        <h4 id="profile_image">
                                                            {!! Form::label('signature','Signature:', ['class'=>'text-left required-star']) !!}
                                                        </h4>
                                                        <span class="text-success col-lg-8 text-left"
                                                              style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 80PX]</span>
                                                        <div class="clearfix"><br/></div>
                                                        <span id="user_err" class="text-danger col-lg-8 text-left"
                                                              style="font-size: 10px;"> {!! $errors->first('signature','<span class="help-block">:message</span>') !!}</span>
                                                        <label class="btn btn-primary btn-file">
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                            {!! trans('Users::messages.browse') !!}<input type="file"
                                                                                                          class="custom-file-input input-sm {{!empty($users->signature) ? '' : 'required'}}"
                                                                                                          name="signature"
                                                                                                          id="signature"
                                                                                                          onchange="imageUploadWithCropping(this, 'signature_preview', 'signature_base64')"
                                                                                                          size="300x80"/>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Asigned Desk</legend>
                                                <div class="control-group">
                                                    <?php $i = 1;?>
                                                    @foreach($desk as $desk_name)
                                                        <dd>{{$i++}}. {!!$desk_name->desk_name!!}</dd>
                                                    @endforeach
                                                </div>
                                            </fieldset>

                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Office</legend>
                                                <div class="control-group">
                                                    <?php $i = 1;?>
                                                    @foreach($park as $park_name)
                                                        <dd>{{$i++}}. {!!$park_name->park_name!!}</dd>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        @endif
                                    </div>

                                    <div class="col-md-12"><br>
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info btn-lg "
                                                    id='update_info_btn'><b><i
                                                        class="fa fa-save"></i> {!! trans('Users::messages.save') !!}
                                                </b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}

                            </div>

                            <div class="container tab-pane fade" id="tab_2">
                                <div class="row">
                                    <div class="col-sm-9">
                                        {!! Form::open(array('url' => '/users/update-password-from-profile','method' => 'patch', 'class' => 'form-horizontal',
                                        'id'=> 'password_change_form')) !!}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        {{--<fieldset>--}}
                                        <div class="clearfix"><br/></div>
                                        <input type="hidden" class="form-control" name="Uid" value="{{$id}}">

                                        <div
                                            class="form-group row {{ $errors->has('user_old_password') ? 'has-error' : ''}}">
                                            <label class="col-lg-3 text-left">Old Password</label>
                                            <div class="col-lg-5">
                                                {!! Form::password('user_old_password', $attributes = array('class'=>'form-control required',
                                                'placeholder'=>'Enter your old password','id'=>"user_old_password", 'data-rule-maxlength'=>'120')) !!}
                                                {!! $errors->first('user_old_password','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        {{--      New code with validation --}}
                                        <div
                                            class="form-group row {{ $errors->has('user_new_password') ? 'has-error' : ''}}">
                                            <label class="col-lg-3 text-left">New Password</label>
                                            <div id="myPassword" class="col-lg-5">
                                                <div class="col-lg-12 p-0">
                                                    {!! Form::password('user_new_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                                    'placeholder'=>'Enter your new password','onkeyup'=>"enableSavePassBtn()",'id'=>"user_new_password", 'data-rule-maxlength'=>'120')) !!}
                                                    <input type="text" id="enable_show" class="form-control"
                                                           style="display:none"/>
                                                    {!! $errors->first('user_new_password','<span class="help-block">:message</span>') !!}
                                                    <a href="" class="button_strength float-right"
                                                       onclick="enableSavePassBtn()">Show</a>
                                                    <div class="strength_meter mt-4">
                                                        <div class=""><p></p></div>
                                                    </div>
                                                </div>
                                                <div class="pswd_info">
                                                    <h4>Password must include:</h4>
                                                    <ul>
                                                        <li data-criterion="length" class="invalid">At least <strong>
                                                                06 Characters</strong></li>
                                                        <li data-criterion="capital" class="invalid">At least <strong>one
                                                                capital
                                                                letter</strong></li>
                                                        <li data-criterion="number" class="invalid">At least <strong>one
                                                                number</strong></li>
                                                        <li data-criterion="specialchar" class="invalid">At least
                                                            <strong>one
                                                                special character</strong></li>
                                                        <li data-criterion="letter" class="valid">No spaces</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('user_confirm_password') ? 'has-error' : ''}}">
                                            <label class="col-lg-3 text-left">Confirm New Password</label>
                                            <div class="col-lg-5">
                                                {!! Form::password('user_confirm_password', $attributes = array('class'=>'form-control required', 'minlength' => "6",
                                                'placeholder'=>'Confirm your new password','id'=>"user_confirm_password", 'data-rule-maxlength'=>'120')) !!}
                                                {{--                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
                                                {!! $errors->first('user_confirm_password','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-2 col-lg-offset-6">
                                                <div class="clearfix"><br></div>
                                                <button type="submit" class="btn btn-block disabled btn-primary"
                                                        id="update_pass_btn"><b>Save</b></button>
                                            </div>
                                            <div class="col-lg-4"></div>
                                        </div>
                                        {{--</fieldset>--}}
                                        {!! Form::close() !!}
                                    </div>

                                    <!--<div class="col-sm-3">
                                        <div class="clearfix"><br/></div>
                                        <a target="_blank"
                                           href="{{url( config('osspid.osspid_base_url').'/user/profile-setting#change_password')}}"
                                           class="btn btn-primary btn-block btn-danger">OSSPID Password change</a>

                                        <a target="_blank"
                                           href="https://myaccount.google.com/intro/signinoptions/password?hl=bn"
                                           class="btn btn-primary btn-block btn-warning">Google Password change</a>

                                        {{--                                       <button class="btn btn-primary btn-block btn-warning">Google Password change</button>--}}
                                    </div>-->

                                </div>
                            </div>

                            <div id="tab_3" class="container tab-pane fade"><br>

                                {!! Form::open(array('url' => '/users/process-delegation','method' =>
                                          'patch','id'=>'delegation', 'class' => '','enctype'
                                          =>'multipart/form-data')) !!}
                                <div class="form-group row col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">User Type</label></div>
                                    <div class="col-lg-6">
                                        <?php $userDesignation = ( $delegate_to_types ? $delegate_to_types : '' ) ?>
                                        {!! Form::select('designation', $userDesignation, '', $attributes =
                                        array('class'=>'form-control required', 'onchange'=>'getUserDeligate()',
                                        'placeholder' => 'Select Type', 'id'=>"designation_2")) !!}
                                    </div>
                                </div>

                                <div class="form-group row  col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">Delegated User</label>
                                    </div>
                                    <div class="col-lg-6">
                                        {!! Form::select('delegated_user', [] , '', $attributes =
                                        array('class'=>'form-control required',
                                        'placeholder' => 'Select User', 'id'=>"delegated_user")) !!}
                                    </div>
                                </div>

                                <div class="form-group row  col-lg-8">
                                    <div class="col-lg-3"><label>Remarks</label></div>
                                    <div class="col-lg-6">
                                        {!! Form::text('remarks','', $attributes = array('class'=>'form-control',
                                        'placeholder'=>'Enter your Remarks','id'=>"remarks")) !!}
                                    </div>
                                </div>


                                <div class="form-group  col-lg-4">
                                    <div class="float-right m-auto">
                                        <button type="submit" class="btn btn-primary " id='update_info_btn'>
                                            <b>Deligate</b>
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>


                            <div id="tab_4" class="container tab-pane fade">
                                <table id="last50activities"
                                       class="table table-striped table-bordered"
                                       width="100%" cellspacing="0" style="font-size: 14px;">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{!! trans('Users::messages.action_taken') !!}</th>
                                        <th>{!! trans('Users::messages.ip') !!}</th>
                                        <th>{!! trans('Users::messages.date_n_time') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div id="tab_5" class="container tab-pane fade">
                                <table id="accessList" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{!! trans('Users::messages.remote_address') !!}</th>
                                        <th>{!! trans('Users::messages.login_type') !!}</th>
                                        <th>{!! trans('Users::messages.login_time') !!}</th>
                                        <th>{!! trans('Users::messages.logout_time') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div id="tab_6" class="container tab-pane fade">
                                <table id="accessLogFailedList" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{!! trans('Users::messages.remote_address') !!}</th>
                                        <th>{!! trans('Users::messages.failed_login_time') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div id="tab_7" class="container tab-pane fade">
                                @include("CompanyAssociation::associated-list")
                            </div>


                        </div>
                    </div>


                </div>
            </div>

            {{-- Company Association List --}}
            {{--            @include("CompanyAssociation::associated-list")--}}
        </div>

    </div>

@endsection


@section('footer-script')
    @include('partials.datatable-js')
    @include('partials.image-upload')
    <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>
    {{--initial- input plugin--}}
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{{ asset("assets/plugins/password-strength/password_strength.js") }}"></script>

    <script>


        // Show password validation check
        $(document).ready(function () {

            $("#enable_show").on("input", function () {
                var show_pass_value = document.getElementById('enable_show').value;
                checkRegularExp(show_pass_value);
            });


            // $(".delegated_user").select2({
            //     maximumSelectionLength: 1
            // });
        });

        function enableSavePassBtn() {
            var password_input_value = document.getElementById('user_new_password').value;
            checkRegularExp(password_input_value);
        }

        function checkRegularExp(password) {
            var submitbtn = $('#update_pass_btn');
            var user_password = $('#user_new_password');
            var enable_show = $('#enable_show');
            var regularExp = /^(?!\S*\s)(?=.*\d)(?=.*[~`!@#$%^&*()--+={}\[\]|\\:;"'<>,.?/_₹])(?=.*[A-Z]).{6,20}$/;

            if (regularExp.test(password) == true) {
                user_password.removeClass('is-invalid');
                user_password.addClass('is-valid');
                enable_show.removeClass('is-invalid');
                submitbtn.prop("disabled", false);
                submitbtn.removeClass("disabled");
            } else {
                enable_show.addClass('is-invalid');
                user_password.addClass('is-invalid');
                submitbtn.prop("disabled", true);
                submitbtn.addClass("disabled");
            }

        }

        $(document).ready(function ($) {
            $('#myPassword').strength_meter();
        });

        $('#myPassword').strength_meter({

            //  CSS selectors
            strengthWrapperClass: 'strength_wrapper',
            inputClass: 'strength_input',
            strengthMeterClass: 'strength_meter',
            toggleButtonClass: 'button_strength',

            // text for show / hide password links
            showPasswordText: 'Show Password',
            hidePasswordText: 'Hide Password'

        });

        function togglePasswordInfo() {
            $(".pswd_infos").toggle();
        }

        @if(Auth::user()->user_type == '5x505')
        function getCompanyAssociationList() {
            /**
             * table desk script
             * @type {jQuery}
             */
            $('#companyAssociationList').DataTable({
                processing: true,
                // serverSide: true,
                searching: false,

                ajax: {
                    url: '{{url("/client/company-association/get-company-list")}}',
                    method: 'get',
                },
                columns: [
                    {data: 'email', name: 'email'},
                    {data: 'org_nm', name: 'org_nm'},
                    {data: 'action', name: 'action'},

                ],
                "aaSorting": []
            });
        }

        function RequestCompany() {
            $.ajax({
                type: "GET",
                url: "{{ url('client/company-association/create') }}",
                dataType: "json",
                success: function (response) {
                    $('#load_content').html(response.html);
                }
            })
        }

        function deleteAssocCompany(e, key) {
            var r = confirm("Are you sure?");
            if (r !== true) {
                return false;
            }
            const button_text = e.innerText;
            const loading_sign = '...<i class="fa fa-spinner fa-spin"></i>';

            var companyAssocId = e.value;
            $.ajax({
                url: "{{ url('client/company-association/approve-reject') }}",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    companyAssocId: companyAssocId,
                    key: key
                },
                beforeSend: function () {
                    e.innerHTML = button_text + loading_sign;
                },
                success: function (response) {
                    toastr.success('Deleted successfully!');
                    $('#companyAssociationList').DataTable().destroy();
                    getCompanyAssociationList()
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.warning('Delete not successful');
                    console.log(errorThrown);
                },
            });
        }

        $(document).ready(function () {
            getCompanyAssociationList()
        });
        @endif

        $(document).ready(function () {
            const url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            $("#vreg_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            $('#password_change_form').validate({
                rules: {
                    user_confirm_password: {
                        equalTo: "#user_new_password"
                    }
                },
                errorPlacement: function () {
                    return false;
                }
            });


            $("#delegation").validate({
                errorPlacement: function () {
                    return false;
                }

            });
            $("#update_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            $("#division").change(function () {
                $(this).after('<span class="loading_data">Loading...</span>');
                const self = $(this);
                const districtSelectedCode = '{{ $users->district }}';
                const divisionId = $('#division').val();
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url( '/public/assets/images/ajax-loader.gif' ); ?>' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url( '/users/get-district-by-division' ); ?>",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        let option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                if (districtSelectedCode == id.split('@')[0]) {
                                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                                } else {
                                    option += '<option value="' + id + '">' + value + '</option>';
                                }
                            });
                        }
                        $("#district").html(option);
                        $("#district").trigger('change');
                        self.next().hide();
                        $("#district").next().hide();
                    }
                });
            });
            $("#division").trigger('change');

            // get district by dstrictID
            $("#district").change(function () {
                $(this).after('<span class="loading_data">Loading...</span>');
                const self = $(this);
                const thanaSelectedCode = '{{ $users->thana }}';
                const districtId = $('#district').val();
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url( '/public/assets/images/ajax-loader.gif' ); ?>' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url( '/users/get-thana-by-district-id' ); ?>",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        let option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                if (thanaSelectedCode == id.split('@')[0]) {
                                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                                } else {
                                    option += '<option value="' + id + '">' + value + '</option>';
                                }

                            });
                        }
                        $("#thana").html(option);
                        $("#thana").trigger('change');
                        self.next().hide();
                    }
                });
            });


            selectIdentityType('<?php  echo $users->identity_type; ?>');

            $("#country").change(function () {
                if (this.value == 'BD') { // 001 is country_code of Bangladesh
                    $('#division_div').removeClass('d-none');
                    $('#division').addClass('required');
                    $('#district_div').removeClass('d-none');
                    $('#district').addClass('required');
                    $('#thana_div').removeClass('d-none');
                    $('#thana').addClass('required');

                    $('#state_div').addClass('d-none');
                    $('#state').removeClass('required');
                    $('#province_div').addClass('d-none');
                    $('#province').removeClass('required');

                    $("#division").prop("disabled", false);
                    $("#district").prop("disabled", false);
                    $("#thana").prop("disabled", false);
                    $("#state").prop("disabled", true);
                    $("#province").prop("disabled", true);

                } else {
                    $('#state_div').removeClass('d-none');
                    $('#state').addClass('required');
                    $('#province_div').removeClass('d-none');
                    $('#province').addClass('required');

                    $('#division_div').addClass('d-none');
                    $('#division').removeClass('required');
                    $('#district_div').addClass('d-none');
                    $('#district').removeClass('required');
                    $('#thana_div').addClass('d-none');
                    $('#thana').removeClass('required');

                    $("#division").prop("disabled", true);
                    $("#district").prop("disabled", true);
                    $("#thana").prop("disabled", true);
                    $("#state").prop("disabled", false);
                    $("#province").prop("disabled", false);
                }
            });
            $('#country').trigger('change');

            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                // maxDate: (new Date()),
                // minDate: '01/01/1916'
            });

            let accessLogClick = 0;
            $('#accessLog').click(function () {
                accessLogClick++;
                console.log(accessLogClick);
                if (accessLogClick == 1) {
                    $('#accessList').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{url("users/get-access-log-data-for-self")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        },
                        columns: [
                            {data: 'ip_address', name: 'ip_address'},
                            {data: 'user_login_type', name: 'user_login_type'},
                            {data: 'login_dt', name: 'login_dt'},
                            {data: 'logout_dt', name: 'logout_dt'},

                        ],
                        "aaSorting": []
                    });
                }
            });

            var url1 = document.location.toString();
            if (url1.match('#')) {
                if (url1.split('#')[1] == 'tab_5') {
                    $('#accessLog').trigger('click')
                }
            }

            let accessLogFailedClick = 0;
            $('#accessLogFailed').click(function () {
                accessLogFailedClick++;
                if (accessLogFailedClick == 1) {
                    $('#accessLogFailedList').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{url("users/get-access-log-failed")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        },
                        columns: [
                            {data: 'remote_address', name: 'remote_address'},
                            {data: 'created_at', name: 'created_at'}

                        ],
                        "aaSorting": []
                    });
                }
            });

            let activitiesClick = 0;
            $('#50Activities').click(function () {
                activitiesClick++;
                if (activitiesClick == 1) {
                    $('#last50activities').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {

                            url: '{{url("users/get-last-50-actions")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        },
                        columns: [
                            {data: 'rownum', name: 'rownum'},
                            {data: 'action', name: 'action'},
                            {data: 'ip_address', name: 'ip_address'},
                            {data: 'created_at', name: 'created_at'}

                        ],
                        "aaSorting": []
                    });
                }
            });

            let flag = 0;
            $('.server_date_time').on('click', function () {
                flag++;
                if (flag == 1) {
                    getAppTimeDate();
                    getTimeDate();
                }
            });

            function getTimeDate() {
                $.ajax({
                    type: 'POST',
                    url: '{{url("users/get-server-time")}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        const options = {weekday: "long", year: "numeric", month: "long", day: "numeric"}
                        $('#db_date').html(data.db_date);
                        $('#db_time').html(data.db_time);
                        $('#app_date').html(d.toLocaleDateString("en-US", options));

                        getDbTimeDate(data.db_hour, data.db_min, data.db_sec);
                    }
                });
            }
        });

        function selectIdentityType(identity_type) {
            if (identity_type === "passport") { // 1 is for passport
                $('#passport_div').removeClass('d-none');
                $('#passport_no').addClass('required');
                $('#nid_div').addClass('d-none');
                $('#user_nid').removeClass('required');
                $('#tin_div').addClass('d-none');
                $('#user_tin').removeClass('required');

                $("#passport_no").prop("disabled", false);
                $("#user_nid").prop("disabled", true);
                $("#user_tin").prop("disabled", true);
            } else if (identity_type === "nid") { // 2 is for NID
                $('#passport_div').addClass('d-none');
                $('#passport_no').removeClass('required');
                $('#tin_div').addClass('d-none');
                $('#user_tin').removeClass('required');
                $('#nid_div').removeClass('d-none');
                $('#user_nid').addClass('required');

                $("#passport_no").prop("disabled", true);
                $("#user_tin").prop("disabled", true);
                $("#user_nid").prop("disabled", false);
            } else {
                $('#passport_div').addClass('d-none');
                $('#passport_no').removeClass('required');
                $('#nid_div').addClass('d-none');
                $('#user_nid').removeClass('required');
                $('#tin_div').removeClass('d-none');
                $('#user_tin').addClass('required');

                $("#passport_no").prop("disabled", true);
                $("#user_tin").prop("disabled", false);
                $("#user_nid").prop("disabled", true);
            }
        }

        function getUserDeligate() {
            const designation = $('#designation_2').val();
            $.ajax({
                url: '{{url("users/get-delegate-userinfo")}}',
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    designation: designation
                },
                dataType: 'json',
                success: function (response) {
                    let html = '<option value="">Select User</option>';
                    $.each(response, function (index, value) {
                        html += '<option value="' + value.id + '" >' + value.user_full_name + '</option>';
                    });
                    $('#delegated_user').html(html);
                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        }

        // analog app clock
        let d = new Date();
        let hour = d.getHours();
        let min = d.getMinutes();
        let sec = d.getSeconds();

        function getAppTimeDate() {
            //calculate angle
            let h = 30 * (parseInt(hour) + parseFloat(min / 60));
            let m = 6 * min;
            let s = 6 * sec;

            //move hands
            setAttr('h-hand', h);
            setAttr('m-hand', m);
            setAttr('s-hand', s);
            setAttr('s-tail', s + 180);

            sec++;
            if (sec == 60) {
                sec = 0;
                min++;

                if (min == 60) {
                    min = 0;
                    hour++;
                }
            }

            //call every second
            setTimeout(getAppTimeDate, 1000);

        };

        //analog database clock
        function getDbTimeDate(db_hour, db_min, db_sec) {

            //calculate angle
            let db_h = 30 * (parseInt(db_hour) + parseFloat(db_min / 60));
            let db_m = 6 * db_min;
            let db_s = 6 * db_sec;
            //move hands
            setAttr('db-h-hand', db_h);
            setAttr('db-m-hand', db_m);
            setAttr('db-s-hand', db_s);
            setAttr('db-s-tail', db_s + 180);

            db_sec++;
            if (db_sec == 60) {
                db_sec = 0;
                db_min++;

                if (db_min == 60) {
                    db_min = 0;
                    db_hour++;
                }
            }

            //call every second
            //setTimeout(getDbTimeDate(db_h, db_m, db_s), 1000);
            setTimeout(function () {
                getDbTimeDate(db_hour, db_min, db_sec);
            }, 1000);

        };

        function setAttr(id, val) {
            const v = 'rotate(' + val + ', 70, 70)';
            document.getElementById(id).setAttribute('transform', v);
        }

        function setText(id, val) {
            if (val < 10) {
                val = '0' + val;
            }
            document.getElementById(id).innerHTML = val;
        }
    </script>


    <script>
        // $("#user_mobile").intlTelInput({
        //     hiddenInput: "user_mobile",
        //     onlyCountries: ["bd"],
        //     initialCountry: "BD",
        //     placeholderNumberType: "MOBILE",
        //     separateDialCode: true,
        // });
    </script>
    <script language="JavaScript">


        function take_snapshot() {

            Webcam.snap(function (data_uri) {

                $(".image-tag").val(data_uri);

                $("#my_camera").hide();

                $("#ts").hide();

                document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
                $("#results").show();
            });

        }

        $(document).ready(function () {
            $('#myPassword').strength_meter();
            $("#cameraclick").click(function () {
                Webcam.on('error', function (err) {
                    toastr.options.preventDuplicates = true;
                    toastr.error('Web camera not available on your device!');
                    $("#reset_image_from_webcamera").trigger('click');
                });

                $("#reset_image_from_webcamera").show();

                $("#camera").show();
                $("#browseimagepp").hide();

                $("#my_camera").show();

                $("#ts").show();
                $("#results").hide();

                Webcam.set({

                    width: 300,

                    height: 300,

                    image_format: 'jpeg',

                    jpeg_quality: 90

                });


                Webcam.attach('#my_camera');
            });

            $("#reset_image_from_webcamera").click(function () {
                $("#camera").hide();
                $("#browseimagepp").show();
                $("#reset_image_from_webcamera").hide();

            });


        });

    </script>

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

        function SendEmailVerificationLink() {
            const user_email = document.getElementById('user_email').value;
            const user_mobile = document.getElementById('user_mobile').value;
            const responseMsgElement = document.getElementById('responseMsgSec');
            const url = "{{ url('users/email-verification') }}";
            const verifyBtn = document.getElementById('verity_btn');
            const verifyBtnLoader = document.getElementById('verity_btn_loader');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    user_email,
                    user_mobile,
                },
                dataType: "json",
                beforeSend: function () {
                    verifyBtn.disabled = true;
                    verifyBtnLoader.classList.remove('d-none')
                },
                success: function (response) {
                    verifyBtn.disabled = false;
                    verifyBtnLoader.classList.add('d-none')
                    responseMsgElement.classList.remove('d-none');
                    const {statusCode, message} = response;
                    const alertClass = statusCode == 1 ? "alert-success" : "alert-danger";
                    responseMsgElement.classList.add(alertClass);
                    document.querySelector('#responseMsgSec span').innerText = message;
                }
            })
        }

    </script>
@endsection
