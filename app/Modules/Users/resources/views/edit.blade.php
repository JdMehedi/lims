<?php

use Illuminate\Support\Str;

$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')){
    abort('400', 'You have no access right!. Contact with system admin for more information.');
}


$user_type_explode = explode('x', $users->user_type);
$random_number = Str::random(30);
?>

@extends('layouts.admin')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.min.css") }}"/>
@endsection

@section("content")

    @include('partials.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    <h5 class="card-title"><strong><i class="fa fa-user" aria-hidden="true"></i> {{trans('Users::messages.user_edit_form_title')}}</strong></h5>
                </div>
                {!! Form::open(array('url' => '/users/update/'.Encryption::encodeId($users->id),'method' => 'patch', 'class' => 'form-horizontal',
                        'id'=> 'user_edit_form')) !!}

                {!! Form::hidden('selected_file', '', array('id' => 'selected_file')) !!}
                {!! Form::hidden('validateFieldName', '', array('id' => 'validateFieldName')) !!}
                {!! Form::hidden('isRequired', '', array('id' => 'isRequired')) !!}
                {!! Form::hidden('isRequired', '', array('id' => 'isRequired')) !!}
                {!! Form::hidden('identity_type', 'nid') !!}

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">

                            @if($users->user_nid != null)
                            <div class="form-group row {{ $errors->has('user_nid') ? 'has-error' : ''}}">
                                <label class="col-md-4 text-left required-star">{{ trans('Users::messages.user_nid') }}</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        {!! Form::text('user_nid', $users->user_nid, $attributes = array('class'=>'form-control',  'data-rule-maxlength'=>'40',
                                        'placeholder'=>'Enter the NID', 'id'=>"user_nid", 'readonly')) !!}
                                        <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-credit-card"></i></span>
                                        </div>
                                    </div>
                                    {!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            @elseif($users->passport_no != null)
                                <div class="form-group row ">
                                    <label class="col-md-4 text-left required-star">{{ trans('Users::messages.passport_no') }}</label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            {!! Form::text('user_nid', $users->passport_no, $attributes = array('class'=>'form-control',  'data-rule-maxlength'=>'40',
                                            'placeholder'=>'Enter the NID', 'id'=>"user_nid", 'readonly')) !!}
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-credit-card"></i></span>
                                            </div>
                                        </div>
                                        {!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            @endif


                            <div class="form-group row {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                                <label class="col-md-4 text-left required-star">{{trans('Users::messages.user_name')}}</label>
                                <div class="col-md-7">
                                    <div class="input-group ">
                                        {!! Form::text('user_first_name', $value = $users->user_first_name, $attributes = array('class'=>'form-control',
                                        'id'=>"user_first_name", 'data-rule-maxlength'=>'50')) !!}
                                        <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-user"></i></span>
                                        </div>
                                    </div>

                                    @if($errors->first('user_first_name'))
                                        <span class="text-danger">
                                        <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_first_name','') }}</em>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('designation') ? 'has-error' : ''}}">
                                <label class="col-md-4 required-star">{{trans('Users::messages.user_designation')}}</label>
                                <div class="col-md-7">
                                    {!! Form::text('designation', $value = $users->designation, $attributes = array('class'=>'form-control required',
                                    'data-rule-maxlength'=>'40', 'placeholder'=>'Enter Designation','id'=>"designation")) !!}
                                    {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                            <div class="form-group row {{ $errors->has('user_type') ? 'has-error' : ''}}">
                                <label class="col-md-4 text-left required-star">{{trans('Users::messages.user_type')}}</label>

                                <div class="col-md-7">
                                    {!! Form::select('user_type', $user_types, $users->user_type, $attributes = array('class'=>'form-control required',
                                    'id'=>"user_type",'readonly' => "readonly")) !!}
                                    @if($errors->first('user_type'))
                                        <span class="help-block">
                                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_type','') }}</em>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($users->user_type == '4x404')
                                <div class="form-group row {{ $errors->has('working_office_id') ? 'has-error' : ''}}">
                                    <label class="col-md-4 required-star">{{trans('Users::messages.working_office')}}</label>
                                    <div class="col-md-7">
                                        {!! Form::select('working_office_id[]', $working_office, $users->office_ids, $attributes = array('class'=>'form-control limitedNumbSelect2 required','data-rule-maxlength'=>'40',
                                         'id'=>"working_office_id","multiple"=>true)) !!}
                                        {!! $errors->first('working_office_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            @endif


                                <div class="form-group row {{ $errors->has('designation') ? 'has-error' : ''}}">
                                    <label class="col-md-4">{{trans('Users::messages.security_profile_id')}}</label>
                                    <div class="col-md-7">
                                        {!! Form::select('security_profile_id', $securityProfile, $users->security_profile_id, $attributes = array('class'=>'form-control required',
                                  'id'=>"security_profile_id")) !!}
                                        {!! $errors->first('security_profile_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                    </div>

                    {{--Right Side--}}
                    <div class="col-md-6">
                        <div class="form-group row {{$errors->has('user_DOB') ? 'has-error' : ''}}">
                            {!! Form::label('user_DOB',trans('Users::messages.user_dob'),['class'=>'col-md-5 required-star']) !!}
                            <div class="col-md-7">
                                <div class="input-group">
                                    {!! Form::text('user_DOB', date('d-M-Y', strtotime($users->user_DOB)), ['class'=>'form-control required datepicker', 'placeholder' => 'Pick from calender', 'readonly']) !!}
                                    <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2"><i
                                                    class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{$errors->has('user_gender') ? 'has-error': ''}}">
                            {!! Form::label('user_gender',trans('Users::messages.user_gender'),['class'=>'text-left required-star col-md-4', 'id' => 'user_gender']) !!}
                            <div class="col-md-7">
                                <label class="identity_hover">
                                    {!! Form::radio('user_gender', 'Male', ($users->user_gender == 'Male') ?  true : false, ['class'=>'required']) !!} Male
                                </label>
                                &nbsp;&nbsp;
                                <label class="identity_hover">
                                    {!! Form::radio('user_gender', 'Female', ($users->user_gender == 'Female') ?  true : false, ['class'=>'required']) !!} Female
                                </label>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('user_mobile') ? 'has-error' : ''}}">
                            <label class="col-md-5 required-star">{{trans('Users::messages.user_mobile')}}</label>
                            <div class="col-md-7">
                                <div class="input-group ">
                                    {!! Form::text('user_mobile', $users->user_mobile, $attributes = array('class'=>'form-control digits required phone', 'maxlength'=>"20",
                                    'minlength'=>"8", 'placeholder'=>'Enter your Number','id'=>"user_mobile")) !!}
                                    <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2"><i
                                                    class="fa fa-mobile"></i></span>
                                    </div>
                                </div>
                                {!! $errors->first('user_mobile','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                            <label class="col-md-5 text-left">{!! trans('Users::messages.user_email') !!}</label>
                            <div class="col-md-7">
                                {{ $users->user_email }}
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="pull-left">
                        {{--                    {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}--}}
                    </div>
                    <div class="float-right">
                        <a href="/users/lists" class="btn btn-default "><i class="fa fa-times"></i><b>
                                Close</b></a>
                        @if(ACL::getAccsessRight('user','E'))
                            <button type="submit" class="btn  btn-primary" id='submit_btn' onclick="this.disabled=true;this.value='Sending';this.form.submit();"><b>Save</b>
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
    </div>
@endsection

@section('footer-script')
    <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/select2/js/select2.full.min.js") }}"></script>
    <script>
        $(".limitedNumbSelect2").select2({
            maximumSelectionLength: 1
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {

            $('#submit_btn').click(function(){
                var _token = $('input[name="_token"]').val();
                $("#user_edit_form").validate({
                    errorPlacement: function () {
                        return false;
                    },
                    submitHandler: function(form) { // <- pass 'form' argument in
                        $("#submit_btn").attr("disabled", true);
                        form.submit(); // <- use 'form' argument here.
                    }
                });
            })

            // remove laravel error message start
            @if($errors->any())
            $('form input[type=text]').on('keyup', function (e) {
                if ($(this).val() && e.which != 32) {
                    $(this).siblings(".help-block").hide();
                    $(this).parent().parent().removeClass('has-error');
                }
            });

            $('form select').on('change', function (e) {
                if ($(this).val()) {
                    $(this).siblings(".help-block").hide();
                    $(this).parent().parent().removeClass('has-error');
                }
            });
            @endif
        });

        $("#code").blur(function () {
            var code = $(this).val().trim();
            if (code.length > 0 && code.length < 12) {
                $('.code-error').html('');
                $('#submit_btn').attr("disabled", false);
            } else {
                $('.code-error').html('Code number should be at least 1 character to maximum  11 characters!');
                $('#submit_btn').attr("disabled", true);
            }
        });
    </script>
@endsection <!--- footer-script--->
