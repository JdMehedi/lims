@extends('public_home.front')

<?php
$userData = session('oauth_data');
$nationality_type = session('nationality_type');

$user_email = '';
$user_mobile = '';

if (!empty($userData)) {
    $user_email = $userData->user_email ?? '';
    $user_mobile = $userData->mobile ?? '';
}


$identity_type = session('identity_type');
$passport_info = Session::has('passport_info') ? json_decode(Encryption::decode(Session::get('passport_info')), true) : '';
$eTin_info = Session::has('eTin_info') ? json_decode(Encryption::decode(Session::get('eTin_info')), true) : '';
$nid_info = Session::has('nid_info') ? json_decode(Encryption::decode(Session::get('nid_info')), true) : '';
$user_pic = Session::get('passport_image');

if ($identity_type === 'nid') {
    $user_pic = isset($nid_info['photo']) ? (empty($nid_info['photo']) ? '' : $nid_info['photo']) : '';
} elseif ($identity_type === 'passport') {
    $passport_nationality = \App\Modules\Users\Models\Countries::where('id', $passport_info['passport_nationality'])->value('nationality');
}
?>
@section('header-resources')
    <style>
        .radio_hover {
            cursor: pointer;
        }

        fieldset.scheduler-border {
            border: 1px solid #afa3a3 !important;
            padding: 0 1.4em 0 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }

        input[type="radio"] {
            -webkit-appearance: checkbox;
            /* Chrome, Safari, Opera */
            -moz-appearance: checkbox;
            /* Firefox */
            -ms-appearance: checkbox;
            /* not currently supported */
        }

        #userPicViewer,
        #investorPhotoViewer {
            width: 150px;
            height: 150px;
            /border-radius: 50%;/ border-radius: 3px;
        }

        .user_pic_area .img-thumbnail {
            display: inline-block;
            max-width: 100%;
            height: auto;
            padding: 2px;
            line-height: 1.42857143;
            background-color: #8eb8ca;
            border: none;
        }

        .iti {
            display: block;
            width: 100%;
        }

        .g-recaptcha {
            transform: scale(0.97);
            transform-origin: 0 0;
        }

        #registrationForm .form-group {
            margin-bottom: 5px;
            margin-right: -10px;
            margin-left: -10px;
        }

        legend {
            font-size: 17px !important;
            border-bottom: 1px solid #828282 !important;
            font-weight: bold;
        }

        @media (max-width: 767px) {
            label {
                color: gray;
                font-size: 11px;
            }

            .col-xs-7 {
                font-weight: 700;
            }
        }
    </style>
@endsection

@section('body')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    @include('partials.messages')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mt-4 mb-4">
                    <h3 class="text-center">{!! trans('Signup::messages.reg_process') !!}</h3>
                    <hr />

                    {!! Form::open(['url' => 'client/signup/registration', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'registrationForm', 'name' => 'registrationForm', 'enctype' => 'multipart/form-data', 'files' => 'true']) !!}

                    <div class="row">
                        <div class="col-md-6">

                            <div
                                class="form-group has-feedback row {{ $errors->has('nationality_type') ? 'has-error' : '' }}">
                                <label for="nationality_type" class="col-md-5 col-xs-5">{!! trans('Signup::messages.nationality_n') !!}</label>
                                <div class="col-md-7 col-xs-7">
                                    {{ ': ' . ucfirst($nationality_type) }}
                                    {!! Form::hidden('nationality_type', \App\Libraries\Encryption::encode($nationality_type)) !!}
                                    {!! $errors->first('nationality_type', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div
                                class="form-group has-feedback row {{ $errors->has('identity_type') ? 'has-error' : '' }}">
                                <label for="identity_type" class="col-md-5 col-xs-5">{!! trans('Signup::messages.identity_type') !!} </label>
                                <div class="col-md-7 col-xs-7">
                                    {{ ': ' . strtoupper($identity_type) }}
                                    {!! Form::hidden('identity_type', \App\Libraries\Encryption::encode($identity_type)) !!}
                                    {!! $errors->first('identity_type', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            @if ($identity_type === 'nid')
                                <div id="NIDInfoArea">
                                    <div class="form-group has-feedback row">
                                        <label for="user_name_en" class="col-md-5 col-xs-5">{!! trans('Signup::messages.name') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $user_name_en = !empty($nid_info['nameEn']) ? $nid_info['nameEn'] : 'N/A'; ?>
                                            {{ ': ' . $user_name_en }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="user_DOB" class="col-md-5 col-xs-5">{!! trans('Signup::messages.date_of_birth') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $user_DOB = !empty($nid_info['dateOfBirth']) ? date('d-M-Y', strtotime($nid_info['dateOfBirth'])) : 'N/A'; ?>
                                            {{ ': ' . $user_DOB }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_copy"
                                            class="col-md-5 col-xs-7">{!! trans('Signup::messages.pic') !!}</label>
                                        <div class="col-md-7 col-xs-7">

                                            <div class="col-md-12">
                                                @if (empty($nid_info['photo']))

                                                        <input type="file" class="form-control input-sm required"
                                                            name="correspondent_photo" id="correspondent_photo"
                                                            onchange="imageUploadWithCropping(this, 'correspondent_photo_preview', 'correspondent_photo_base64')"
                                                            size="300x300" />
                                                        <span class="text-success"
                                                            style="font-size: 9px; font-weight: bold; display: block;">[File
                                                            Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
                                                @endif

                                            </div>
                                                <div class="col-md-12">
                                                    <label class="center-block image-upload" for="correspondent_photo" style="max-width: 50%">
                                                        <figure>
                                                            <img src="{{ !empty($nid_info['photo']) ? $nid_info['photo'] : url('assets/images/photo_default.png') }}"
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
                            @elseif($identity_type === 'tin')
                                <div id="ETINInfoArea">
                                    <div class="form-group has-feedback row">
                                        <label for="user_name_en" class="col-md-5 col-xs-5">{!! trans('Signup::messages.name') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $user_name_en = !empty($eTin_info['assesName']) ? ucfirst($eTin_info['assesName']) : 'N/A'; ?>
                                            {{ ': ' . $user_name_en }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="father_name" class="col-md-5 col-xs-5">{!! trans('Signup::messages.father_name') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $father_name = !empty($eTin_info['fathersName']) ? ucfirst($eTin_info['fathersName']) : 'N/A'; ?>
                                            {{ ': ' . $father_name }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="user_DOB" class="col-md-5 col-xs-5">{!! trans('Signup::messages.date_of_birth') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $user_DOB = $eTin_info['dob'] != '' ? date('d-M-Y', strtotime($eTin_info['dob'])) : 'N/A'; ?>
                                            {{ ': ' . $user_DOB }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_copy"
                                            class="col-md-5 col-xs-7">{!! trans('Signup::messages.pic') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="file" class="form-control input-sm required"
                                                           name="correspondent_photo" id="correspondent_photo"
                                                           onchange="imageUploadWithCropping(this, 'correspondent_photo_preview', 'correspondent_photo_base64')"
                                                           size="300x300" />
                                                    <span class="text-success"
                                                          style="font-size: 9px; font-weight: bold; display: block;">[File
                                                        Format:
                                                        *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
                                                </div>
                                                <div class="col-12">
                                                    <label class="center-block image-upload" for="correspondent_photo" style=" max-width: 50%">
                                                        <figure>
                                                            <img src="{{ url('assets/images/photo_default.png') }}"
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
                            @elseif($identity_type === 'passport')
                                <div id="PassportInfoArea">
                                    <div class="form-group has-feedback row">
                                        <label for="passport_nationality"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.passport_nationality') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_nationality = !empty($passport_nationality) ? ucfirst($passport_nationality) : 'N/A'; ?>
                                            {{ ': ' . $passport_nationality }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_type"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.passport_type') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_type = !empty($passport_info['passport_type']) ? ucfirst($passport_info['passport_type']) : 'N/A'; ?>
                                            {{ ': ' . $passport_type }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_no" class="col-md-5 col-xs-5">{!! trans('Signup::messages.passport_no') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_no = !empty($passport_info['passport_no']) ? $passport_info['passport_no'] : 'N/A'; ?>
                                            {{ ': ' . $passport_no }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_surname"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.sure_name') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_surname = !empty($passport_info['passport_surname']) ? $passport_info['passport_surname'] : 'N/A'; ?>
                                            {{ ': ' . $passport_surname }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_given_name"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.name') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_given_name = !empty($passport_info['passport_given_name']) ? $passport_info['passport_given_name'] : 'N/A'; ?>
                                            {{ ': ' . $passport_given_name }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_personal_no"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.personal_no') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_personal_no = !empty($passport_info['passport_personal_no']) ? $passport_info['passport_personal_no'] : 'N/A'; ?>
                                            {{ ': ' . $passport_personal_no }}
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback row">
                                        <label for="passport_DOB"
                                            class="col-md-5 col-xs-5">{!! trans('Signup::messages.date_of_birth') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_DOB = !empty($passport_info['passport_DOB']) ? date('d-M-Y', strtotime($passport_info['passport_DOB'])) : 'N/A'; ?>
                                            {{ ': ' . $passport_DOB }}
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row">
                                        <label for="passport_date_of_expire"
                                            class="col-md-5 col-xs-7">{!! trans('Signup::messages.expiry_date') !!}</label>
                                        <div class="col-md-7 col-xs-7">
                                            <?php $passport_date_of_expire = !empty($passport_info['passport_date_of_expire']) ? date('d-M-Y', strtotime($passport_info['passport_date_of_expire'])) : 'N/A'; ?>
                                            {{ ': ' . $passport_date_of_expire }}
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback row">
                                        <label for="passport_copy"
                                            class="col-md-5 col-xs-7">{!! trans('Signup::messages.pic') !!}</label>
                                        <div class="col-md-7 col-xs-7">

                                            <div class="col-md-12">
                                                @if (empty($user_pic))

                                                        <input type="file" class="form-control input-sm required"
                                                            name="correspondent_photo" id="correspondent_photo"
                                                            onchange="imageUploadWithCropping(this, 'correspondent_photo_preview', 'correspondent_photo_base64')"
                                                            size="300x300" />
                                                        <span class="text-success"
                                                            style="font-size: 9px; font-weight: bold; display: block;">[File
                                                            Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
                                                @endif
                                            </div>

                                                <div class="col-md-12">
                                                    <label class="center-block image-upload" for="correspondent_photo" style="max-width: 50%">
                                                        <figure>
                                                            <img src="{{ !empty($user_pic) ? 'data:image/jpg;base64,' . $user_pic : url('assets/images/photo_default.png') }}"
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
                            @endif

                            <div class="form-group">
                                <div class="col-md-offset-5 col-md-7 text-right">
                                    <button type="submit"
                                        class="btn btn-md btn-primary round-btn btn-block"><b>Submit</b></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div
                                class="form-group has-feedback row {{ $errors->has('user_email') ? 'has-error' : '' }}">
                                <label for="user_email" class="col-md-5 col-xs-5 required-star">Email </label>
                                <div class="col-md-7 col-xs-7">
                                    {!! Form::text('user_email', $user_email, ['class' => 'form-control email required', empty($user_email) ? '' : 'readonly']) !!}
                                    {!! $errors->first('user_email', '<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>

                            <div
                                class="form-group has-feedback row {{ $errors->has('user_mobile') ? 'has-error' : '' }}">
                                <label for="user_mobile" class="col-md-5 col-xs-5 required-star">Mobile Number </label>
                                <div class="col-md-7 col-xs-7">
                                    {!! Form::text('user_mobile', $user_mobile, ['class' => 'form-control required']) !!}
                                    {!! $errors->first('user_mobile', '<span class="help-block text-danger">:message</span>') !!}
                                </div>
                            </div>

                            <div
                                class="form-group row {{ $errors->has('user_gender') ? 'has-error' : '' }}">
                                <label
                                    class="col-lg-5 text-left required-star">{!! trans('Users::messages.user_gender') !!}</label>
                                <div class="col-md-7">

                                        <label
                                            class="identity_hover">{!! Form::radio('user_gender', 'Male','', ['class'=>'user_gender required']) !!}
                                            Male
                                        </label>

                                        <label
                                            class="identity_hover">{!! Form::radio('user_gender', 'Female', '',['class'=>'user_gender required']) !!}
                                            Female
                                        </label>

                                        <label
                                            class="identity_hover">{!! Form::radio('user_gender', 'others', '',['class'=>'user_gender required']) !!}
                                            Others
                                        </label>

                                </div>
                            </div>

                            <h5 style="margin-top:76px"><strong>ওয়ান স্টপ সার্ভিস সিস্টেম ব্যবহারের শর্তাবলী</strong></h5>
                            <ul>
                                <li>সেবা-সংক্রান্ত যেকোন নীতিমালা অবশ্যই অনুসরণ করতে হবে।</li>
                                <li>অসম্পূর্ন,ভুল ও ত্রুটিপূর্ন তথ্য প্রদানের দায়ভার ইউজারকে বহন করতে হবে।
                                </li>
                                <li>আপনার ইউজারের মাধ্যমে সংঘঠিত সকল কার্যক্রমের দায়ভার আপনাকে বহন করতে হবে বিধায় ইউজার
                                    এক্সেস
                                    কারো সঙ্গে শেয়ার করবেন না।
                                </li>
                                <li>এই তথ্যাবলি যেকোন সময় হালনাগাদ করতে পারে তাই প্রতিটি হালনাগাদ তথ্যের প্রতি নিয়মিত
                                    দৃষ্টি রাখুন।
                                </li>
                            </ul>
                            <br />

                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection



@section('footer-script')
    @include('partials.image-upload')
    <script>
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
        var user_image = $("#correspondent_photo_preview").attr('src');
        convertImageUrlToBase64(user_image, function(url) {
            $("#correspondent_photo_base64").val(url);
        });


        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // $("#registrationForm").validate({
            //     errorPlacement: function () {
            //         return false;
            //     }
            // });


        });
    </script>
@endsection
