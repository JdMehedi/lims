@extends('public_home.front')

@section ('body')

<div class="container" style="margin-top:65px;">
    <div class="row">
        <div class="col-md-10 offset-1 p-4" style="background: snow; opacity:0.88; border-radius:8px;">

            {!! Form::open(array('url' => 'users/verify-two-step/','method' => 'patch', 'class' => 'form-horizontal',
             'id' => 'verifyForm')) !!}

                @if(isset($req_dta))
                    {!! Form::hidden('req_dta',$req_dta) !!}
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="container-fluid">
                            <h3 class="text-left">Verification Code</h3>


                            <input type="hidden" id="smsTrackingCode" value="-1">
                            <input type="hidden" id="OTPExpireTime" value="-1">

                            <div class="col-md-8 col-md-offset-1">
                                @if($steps == 'email')
                                    <img src="{{url('assets/images/email_icon.png')}}" class= img-responsive"  alt="Two-step verification by email"
                                         id="email_verification_img"  width="150" /><br/>
                                    An email has been sent to your given address
                                    (<?php echo substr($user_email, 0, 3) . '***************' . substr($user_email, -9); ?>).
                                @else
                                    <img src="{{url('assets/images/sms.png')}}" class= img-responsive"  alt="Two-step verification by SMS"
                                         id="sms_verification_img"  width="150" /><br/>
                                    An SMS has been sent to your given mobile number
                                    (<?php echo substr($user_phone, 0, 6) . '************' . substr($user_phone, -2); ?>).
                                @endif
                                Please enter the 4 digit code that you have got.
                            </div>

                            <div class="col-md-12"><br></div>

                            <div class="col-md-7 col-md-offset-1">
                                <div class="form-group row ">
                                    {!! Form::text('security_code','', ['class' => 'form-control required','placeholder'=>'Enter your security code']) !!}
                                    {!! $errors->first('security_code','<span class="help-block">:message</span>') !!}

                                </div>
                            </div>

                            <div class="col-md-12">
                                <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                &nbsp;  &nbsp; <b>or</b> &nbsp;  &nbsp;
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-chevron-circle-right"></i>
                                    Submit
                                </button>

                            </div><!-- /.box-footer -->

                        </div>
                        <!-- /.form end -->
                    </div>

                    <div class="col-md-4">
                        <h4>ভেরিফিকেশন কোড না পেলে করণীয় কী?</h4>
                        <ul>
                            <li>সিস্টেমে ভেরিফিকেশন কোড প্রেরণের জন্য মোবাইলে এস.এম.এস এবং ইমেইল এই দুইটি মাধ্যম ব্যবহার করা হয়। </li>
                            <li>ভেরিফিকেশন কোড না পেলে আপনি বিকল্প মাধ্যমটিতে চেষ্টা করতে পারেন।</li>
                        </ul>
                        <h4>একাধিক ভেরিফিকেশন কোড পেলে কোনটি গ্রহণযোগ্য হবে?</h4>
                        <ul>
                            <li>আপনি যদি সিস্টেম থেকে একাধিক ভেরিফিকেশন কোড পেয়ে থাকেন, তাহলে সর্বশেষে প্রেরিত কোডটিই সিস্টেম গ্রহণ করবে। </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <h4>What to do if you do not get the verification code?</h4>
                        <ul>
                            <li>Mobile SMS and email are used to send the verification code to the system.</li>
                            <li>If you don't get the verification code, you can try alternative way.</li>
                        </ul>
                        <h4>If multiple verification codes are available, which one will be accepted?</h4>
                        <ul>
                            <li>If you receive multiple verification codes from the system, the last code sent will be accepted by the system. </li>
                        </ul>
                    </div>

                </div>

            {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>



        @endsection

        @section ('footer-script')
        <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
        <script>
            $(document).ready(
                    function () {
                        $("#verifyForm").validate({
                            errorPlacement: function () {
                                return false;
                            }
                        });
                    });
        </script>
        <style>
            ul li {
                list-style-type: none;
            }
        </style>
        @endsection
