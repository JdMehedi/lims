<?php
$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, 'V')) {
    abort('400', 'You have no access right!. Contact with system admin for more information.');
}

$nationality_type = session('nationality_type');
$identity_type = session('identity_type');

$passport_info1 = Session::has('passport_info') ? json_decode(Encryption::decode(Session::get('passport_info')), true) : '';
$passport_info = $passport_info1['data'] ?? "";
$eTin_info = Session::has('eTin_info') ? json_decode(Encryption::decode(Session::get('eTin_info')), true) : '';
$nid_info = Session::has('nid_info') ? json_decode(Encryption::decode(Session::get('nid_info')), true) : '';

$mobile_no = '';
$user_name_en = '';
$user_DOB = '';
$user_gender = '';
$user_pic = '';
$passportImage = '';

if ($identity_type == 'nid') {
    $user_pic = $nid_info['photo'] ?? '';

    $user_name_en = $nid_info['nameEn'];
    $user_DOB = date('d-M-Y', strtotime($nid_info['dateOfBirth']));
    $user_gender = $nid_info['gender'];
} elseif ($identity_type == 'passport') {
    $user_DOB = date('d-M-Y', strtotime($passport_info['birth_date']));
    $user_name_en = $passport_info['name'];
    $passportImage = Session::get('passport_image') ;
    $passport_nationality = \App\Modules\Users\Models\Countries::where('id', $passport_info1['nationality_id'])->value('nationality');
} elseif ($identity_type == 'tin') {
    $user_name_en = $eTin_info['assesName'];
    $user_DOB = date('d-M-Y', strtotime($eTin_info['dob']));
    $mobile_no = $eTin_info['mobile'];
}

?>

@extends('layouts.admin')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/intlTelInput/css/intlTelInput.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" />
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><strong><i class="fa fa-user-plus" aria-hidden="true"></i>
                            {{ trans('Users::messages.new_user_form_title') }}</strong>
                    </h5>
                </div>

                {!! Form::open(['url' => '/users/store-new-user', 'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'create_user_form', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}

                <div class="card-body">
                <!--
                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="row">
                                <label class="col-md-4 col-form-label required-star">{!! trans('Signup::messages.nationality_n') !!}</label>
                                <div class="col-md-8">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ ucfirst($nationality_type) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <label class="col-md-4 col-form-label required-star">{!! trans('Signup::messages.identity_type') !!}</label>
                                <div class="col-md-8">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ strtoupper($identity_type) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                -->


                    @if ($identity_type == 'nid')
                        <div id="NIDInfoArea">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-md-4 col-form-label required-star">NID Number</label>
                                        <div class="col-md-8">
                                            {!! Form::text('user_nid', $nid_info['nationalId'], $attributes = ['class' => 'form-control', 'placeholder' => 'Enter the NID', 'id' => 'user_nid', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        {!! Form::label('user_DOB', trans('Users::messages.user_dob'), ['class' => 'col-md-4 col-form-label']) !!}
                                        <div class="col-md-8">
                                            <div class="input-group-append">
                                                {!! Form::text('user_DOB', $user_DOB, ['class' => 'form-control required datepicker', 'placeholder' => 'Pick from calender', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        {!! $errors->first('user_DOB', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @elseif($identity_type == 'tin')
                        <div id="ETINInfoArea">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-md-4 col-form-label required-star">TIN Number</label>
                                        <div class="col-md-8">
                                            {!! Form::text('user_tin', $eTin_info['etin_number'], $attributes = ['class' => 'form-control', 'placeholder' => 'Enter the NID', 'id' => 'user_nid', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        {!! Form::label('user_DOB', trans('Users::messages.user_dob'), ['class' => 'col-md-4 col-form-label']) !!}
                                        <div class="col-md-8">
                                            <div class="input-group-append">
                                                {!! Form::text('user_DOB', $user_DOB, ['class' => 'form-control required datepicker', 'placeholder' => 'Pick from calender', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        {!! $errors->first('user_DOB', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @elseif($identity_type == 'passport')
                        <div id="PassportInfoArea">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="row">
                                        <label class="col-md-4 col-form-label required-star">TIN Number</label>
                                        <div class="col-md-8">
                                            {!! Form::text('user_passport', $passport_info['document_number'], $attributes = ['class' => 'form-control', 'placeholder' => 'Enter the NID', 'id' => 'user_nid', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row">
                                        {!! Form::label('user_DOB', trans('Users::messages.user_dob'), ['class' => 'col-md-4 col-form-label']) !!}
                                        <div class="col-md-8">
                                            <div class="input-group-append">
                                                {!! Form::text('user_DOB', $user_DOB, ['class' => 'form-control required datepicker', 'placeholder' => 'Pick from calender', !empty(Request::segment(3)) ? (Encryption::decodeId(Request::segment(3)) == 'skip' ? '' : 'readonly') : 'readonly']) !!}
                                                <span class="input-group-text" id="basic-addon2"><i
                                                        class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        {!! $errors->first('user_DOB', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 required-star">{{ trans('Users::messages.user_name') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group ">
                                        {!! Form::text('user_first_name', $user_name_en, $attributes = ['class' => 'form-control required', 'placeholder' => 'Enter Full Name', 'id' => 'user_first_name']) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2"><i
                                                    class="fa fa-user"></i></span>
                                        </div>
                                    </div>
                                    {!! $errors->first('user_first_name', '<span class="text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('user_gender', trans('Users::messages.user_gender'), ['class' => 'text-left required-star col-md-4', 'id' => 'user_gender']) !!}
                                <div class="col-sm-8">
                                    <label class="identity_hover">
                                        {!! Form::radio('user_gender', 'Male', $user_gender == 'male' ? true : false, ['class' => 'required', 'id' => 'user_gender_male']) !!}
                                        Male
                                    </label>
                                    &nbsp;&nbsp;
                                    <label class="identity_hover">
                                        {!! Form::radio('user_gender', 'Female', $user_gender == 'female' ? true : false, ['class' => 'required', 'id' => 'user_gender_female']) !!}
                                        Female
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label
                                    class="col-md-4 required-star">{{ trans('Users::messages.user_designation') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group ">
                                        {!! Form::text('designation', $value = null, $attributes = ['class' => 'form-control required bnEng', 'data-rule-maxlength' => '200', 'placeholder' => 'Enter Designation', 'id' => 'designation']) !!}

                                    </div>
                                    {!! $errors->first('designation', '<span class="text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 required-star">{{ trans('Users::messages.user_mobile') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('user_mobile', $mobile_no, $attributes = ['class' => 'form-control required bd_mobile', 'placeholder' => 'Enter your Number', 'id' => 'user_mobile']) !!}
                                    </div>
                                    {!! $errors->first('user_mobile', '<span class="text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if ($logged_user_type == '1x101' || $logged_user_type == '8x808')
                            {{-- For System Admin & IT cell --}}
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label
                                        class="col-md-4 required-star">{{ trans('Users::messages.user_type') }}</label>
                                    <div class="col-md-8">
                                        {!! Form::select('user_type', $user_types, '', $attributes = ['class' => 'form-control required', 'data-rule-maxlength' => '40', 'placeholder' => 'Select One', 'id' => 'user_type']) !!}
                                        {!! $errors->first('user_type', '<span class="text-danger">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div
                                class="form-group row has-feedback {{ $errors->has('user_email') ? 'needs-validation' : '' }}">
                                <label class="col-md-4 required-star">{{ trans('Users::messages.user_email') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group ">
                                        {!! Form::text('user_email', $value = null, $attributes = ['class' => 'form-control email required', 'data-rule-maxlength' => '40', 'placeholder' => 'Enter your Email Address', 'id' => 'user_email']) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2"><i
                                                    class="fa fa-envelope"></i></span>
                                        </div>
                                    </div>
                                    {!! $errors->first('user_email', '<span class="text-danger">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <div class="row">
                                {!! Form::label('user_DOB', trans('Users::messages.user_dob'), ['class' => 'col-md-4 col-form-label']) !!}
                                <div class="col-md-8">
                                    <div class="input-group-append">
                                        {!! Form::text('user_DOB', $user_DOB, ['class' => 'form-control required datepicker', 'placeholder' => 'Pick from calender']) !!}
                                        <span class="input-group-text" id="basic-addon2"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                {!! $errors->first('user_DOB', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group row has-feedback">

                                <div style="margin-left: 10px" class="card card-default" id="browseimagepp">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-4 addImages" style="max-height:300px;">
                                            <label class="center-block image-upload" for="user_pic" style="margin: 0px">
                                                <figure>
                                                    <img src="{{ !empty($user_pic) ? $user_pic : url('assets/images/photo_default.png') }}"

                                                         class="img-responsive img-thumbnail" id="user_pic_preview">
                                                </figure>
                                                <input type="hidden" id="user_pic_base64" name="user_pic_base64"  value="{{$user_pic}}">
                                            </label>
                                        </div>
                                        <div class="col-sm-6 col-md-8">
                                            <h4 id="profile_image">
                                                <label for="user_pic" class="text-left required-star">Profile image</label>
                                            </h4>
                                            <span class="text-success col-lg-8 text-left" style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>

                                            <span id="user_err" class="text-danger col-lg-8 text-left" style="font-size: 10px;"> </span>
                                            <div class="clearfix"><br></div>
                                            <label class="btn btn-primary btn-file">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                Browse<input type="file" class="custom-file-input input-sm " name="user_pic" id="user_pic" onchange="imageUploadWithCroppingAndDetect(this, 'user_pic_preview', 'user_pic_base64')" size="300x300">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>


                        <div class="col-md-6">
                            <div class="form-group row has-feedback">
                                <label for="user_signature" class="col-md-12">{!! trans('Users::messages.user_signature') !!}</label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control input-sm" name="user_signature"
                                                id="user_signature"
                                                onchange="imageUploadWithCropping(this, 'user_signature_preview', 'user_signature_base64')"
                                                size="300x80" />
                                            <span class="text-success"
                                                style="font-size: 9px; font-weight: bold; display: block;">[File
                                                Format:
                                                *.jpg/ .jpeg/ .png | Width 300PX, Height 80PX]</span>
                                        </div>


                                        <div class="col-md-3">
                                            <label class="center-block image-upload" for="user_signature">
                                                <figure>
                                                    <img src="{{ url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg') }}"
                                                        class="img-responsive img-thumbnail nidPhoto"
                                                        id="user_signature_preview" />
                                                    <figcaption><i class="fa fa-camera"></i></figcaption>
                                                </figure>
                                                <input type="hidden" id="user_signature_base64"
                                                    name="user_signature_base64" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="float-left">
                        <a href="{{ url('users/lists') }}" class="btn btn-default btn-sm"><i class="fa fa-times"></i>
                            <b>Close</b></a>
                    </div>
                    <div class="float-right">
                        @if (ACL::getAccsessRight('user', 'A'))
                            <button type="submit" class="btn btn-block btn-sm btn-primary" id="submit"><b>Submit</b>
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <!--/panel-body-->
    </div>
@endsection

@section('footer-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/intlTelInput.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/intlTelInput/js/utils.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}" type="text/javascript"></script>

    @include('partials.image-upload')

    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                sideBySide: true,
                format: "yyyy-mm-dd"
            });
        });
        $("#user_mobile").intlTelInput({
            hiddenInput: "user_mobile",
            onlyCountries: ["bd"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $(".limitedNumbSelect2").select2({
            maximumSelectionLength: 1
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit').click(function() {
            var _token = $('input[name="_token"]').val();
            $("#create_user_form").validate({
                errorPlacement: function() {
                    return false;
                },
                submitHandler: function(form) { // <- pass 'form' argument in
                    $("#submit").attr("disabled", true);
                    form.submit(); // <- use 'form' argument here.
                }
            });
        })

        // remove laravel error message start
        @if ($errors->any()) $('form input[type=text]').on('keyup', function (e) {
            if ($(this).val() && e.which != 32) {

                if ($(this).parent().parent().hasClass('has-error')) {
                    $(this).parent().parent().removeClass('has-error');
                    $(this).siblings(".help-block").hide();
                } else {
                    $(this).parent().parent().parent().removeClass('has-error');
                    $(this).parent().siblings(".help-block").hide();
                }

            }
        });

        $('form select').on('change', function (e) {
            if ($(this).val()) {
                $(this).siblings(".help-block").hide();
                $(this).parent().parent().removeClass('has-error');
            }
        }); @endif
        // remove laravel error message end


        /**
         * Convert an image to a base64 url
         * @param  {String}   url
         * @param  {String}   [outputFormat=image/png]
         */
        function convertImageToBase64(img, outputFormat) {
            var originalWidth = img.style.width;
            var originalHeight = img.style.height;

            img.style.width = "auto";
            img.style.height = "auto";
            img.crossOrigin = "Anonymous";

            var canvas = document.createElement("canvas");

            canvas.width = img.width;
            canvas.height = img.height;

            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);

            img.style.width = originalWidth;
            img.style.height = originalHeight;

            // Get the data-URL formatted image
            // Firefox supports PNG and JPEG. You could check img.src to
            // guess the original format, but be aware the using "image/jpg"
            // will re-encode the image.
            var dataUrl = canvas.toDataURL(outputFormat);

            //return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
            return dataUrl;
        }

        function convertImageUrlToBase64(url, callback, outputFormat) {
            var img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                callback(convertImageToBase64(this, outputFormat));
            };
            img.src = url;
        }


        // Convert NID image URL to base64 format
        var user_image = $("#user_pic_preview").attr('src');
        convertImageUrlToBase64(user_image, function(url) {
            $("#user_pic_base64").val(url);
        });
    </script>
@endsection
