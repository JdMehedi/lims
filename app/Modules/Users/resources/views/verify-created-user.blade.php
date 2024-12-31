@extends('layouts.btrc-layout')
@section('inner_content')


    <link rel="stylesheet" href="{{ asset("assets/plugins/password-strength/password_strength.css") }}">
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                @include('partials.messages')
                <div class="col-md-12 col-sm-12">
                    {!! Form::open(array('url' => '/users/created-user-verification/'.$encrypted_token,'method' => 'patch', 'class' => 'form-horizontal',
                    'id'=> 'vreg_form')) !!}
                    <div class="card border-0">
                        <div class="card-header" style="background-color: transparent;">
                            <h3 class="card-title text-center">Please set your password</h3>
                        </div>
                        <div class="card-body">

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="text-danger">{{$error}}<br>
                                    </div>
                                @endforeach
                            @endif
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">New Password</label>
                                <div class="col-sm-8">
                                    <div id="myPassword">
                                        <div class="col-lg-12">
                                            {!! Form::password('user_new_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                            'placeholder'=>'Enter your new password','onkeyup'=>"enableSavePassBtn()",'id'=>"user_new_password", 'data-rule-maxlength'=>'120')) !!}
                                            <input type="text" class="form-control" id="enable_show" style="display:none"/>
                                            {!! $errors->first('user_new_password','<span class="help-block">:message</span>') !!}
                                            <a href="javascript:void(0)" class="button_strength" onclick="showPass()">Show</a>
                                            <div class="strength_meter">
                                                <div><p></p></div>
                                            </div>
                                        </div>
                                        <div class="pswd_info">
                                            <h4>Password must include:</h4>
                                            <ul>
                                                <li data-criterion="length" class="invalid">06-20<strong>
                                                        Characters</strong></li>
                                                <li data-criterion="capital" class="invalid">At least <strong>one capital
                                                        letter</strong></li>
                                                <li data-criterion="number" class="invalid">At least <strong>one
                                                        number</strong></li>
                                                <li data-criterion="specialchar" class="invalid">At least <strong>one
                                                        special character</strong></li>
                                                <li data-criterion="letter" class="valid">No spaces</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Confirm New password</label>
                                <div class="col-sm-8">
                                    <div class="col-lg-12">
                                        {!! Form::password('user_confirm_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                        'placeholder'=>'Enter your confirm password','id'=>"user_confirm_password", 'data-rule-maxlength'=>'120')) !!}
                                        <input type="text" class="form-control" style="display:none"/>
                                        {!! $errors->first('user_new_confirm_password','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8" style="color: red;">
                                    [*Minimum 6 Characters at least 1 Alphabet, 1 Number and 1 Special Character ]
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label>
                                        {!! Form::checkbox('user_agreement', 1, null,  ['class'=>'required']) !!}
                                        &nbsp;
                                        I have read and agree to terms and conditions.
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row" style="margin-top: 40px;">
                                <div class="col-lg-7">
                                    Already have an account? <b>{!! link_to('users/login', 'Login', array('class' => '')) !!}</b>
                                </div>
                                <div class="col-lg-5 col-lg-offset-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-sm" id="update_pass_btn"><b>Save and Continue</b></button>
                                </div>
                            </div>
                            <div class="form-group">

                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {!! Form::close() !!}
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            console.log('hello');
        }, 2000);
        let initialMode = 'password';
        function showPass() {
            let passField = document.getElementById('user_new_password');
            if (initialMode === 'password') {
                initialMode = 'text';
                passField.type = 'text';
            } else {
                initialMode = 'password';
                passField.type = 'password';
            }
        }
    </script>
@endsection
@section('footer-script')
    <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{{ asset("assets/plugins/password-strength/password_strength.js") }}" type="text/javascript"></script>

    <script>


        // Show password validation check
        $(document).ready(function(){
            $("#enable_show").on("input", function(){
                var show_pass_value= document.getElementById('enable_show').value;
                checkRegularExp(show_pass_value);
            });
        });

        function enableSavePassBtn(){
            var password_input_value= document.getElementById('user_new_password').value;
            checkRegularExp(password_input_value);
        }

        function checkRegularExp(password){
            var submitbtn=$('#update_pass_btn');
            var user_password= $('#user_new_password');
            var enable_show = $('#enable_show');
            var regularExp = /^(?!\S*\s)(?=.*\d)(?=.*[~`!@#$%^&*()--+={}\[\]|\\:;"'<>,.?/_₹])(?=.*[A-Z]).{6,20}$/;

            if(regularExp.test(password)==true ) {
                user_password.removeClass('is-invalid');
                user_password.addClass('is-valid');
                enable_show.removeClass('is-invalid');
                submitbtn.prop("disabled", false);
                submitbtn.removeClass("disabled");
            }
            else {
                enable_show.addClass('is-invalid');
                user_password.addClass('is-invalid');
                submitbtn.prop("disabled", true);
                submitbtn.addClass("disabled");
            }

        }

        $(document).ready(function($) {
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

        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#vreg_form").validate({
                rules: {
                    user_confirm_password: {
                        equalTo: "#user_new_password"
                    }
                },
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>

    <style>
        input[type="checkbox"].error{
            outline: 1px solid red
        }
    </style>
@endsection
