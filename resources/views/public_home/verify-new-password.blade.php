@extends('public_home.front')

@section('header-resources')
    <link rel="stylesheet" href="{{ asset("assets/plugins/password-strength/password_strength.css") }}">
    <style>
        .form-group{
            margin-bottom: 0;
        }
        a:hover{
            text-decoration: none;
        }
        .card-body{
            padding-bottom: 0;
        }
    </style>
@endsection


@section ('body')
    <div class="row my-5">
        <div class="col-md-12">

            <div class="wrapper">
                {{--    <div class="container header-image"></div>--}}
                <div class="container container-bg"><br>
                    {!! Form::open(['url' => 'store-forgotten-password','method' => 'post', 'class' =>'form-horizontal', 'id' => 'forgetPassword']) !!}
                    <div class="offset-2 col-md-4 login-panel m-auto">
                        <div class="card card-info">
                            <div class="card-header text-center">
                               Set your new password
                            </div>
                            <input type="hidden" value="{{$token_no}}" name="token">
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="text-danger">{{$error}}<br></div>
                                    @endforeach
                                @endif
                                <div class="form-group row">
                                    <p style="padding: 0 25px 10px; text-align: center; color: rgba(0,0,0,.7);">You are only one step a way from your new password, recover your password now.</p>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div id="myPassword">
                                            <div class="col-md-12">
                                                <div class="input-group mb-3">
                                                {!! Form::password('user_new_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                                'placeholder'=>'New password','onkeyup'=>"enableSavePassBtn()",'id'=>"user_new_password", 'data-rule-maxlength'=>'120')) !!}
                                                    <input type="text" class="form-control" id="enable_show" style="display:none"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--                                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
                                                {!! $errors->first('user_new_password','<span class="help-block">:message</span>') !!}
                                                <a href="" class="button_strength ">Show</a>
                                                <div class="strength_meter">
                                                    <div class=""><p></p></div>
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
                                    <div class="col-sm-12">
                                        <div class="col-md-12">
                                            <div class="input-group mb-3">
                                            {!! Form::password('user_confirm_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",
                                            'placeholder'=>'Confirm new password','id'=>"user_confirm_password", 'data-rule-maxlength'=>'120')) !!}
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-lock"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" style="display:none"/>
                                            {!! $errors->first('user_new_confirm_password','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <button style="margin: 0 40px;" type="submit" class="btn btn-primary form-control "  disabled  id="update_pass_btn">Change password</button>
                                <div class="col-md-12 mt-2">
                                    <a class="mx-4 text-primary my-2" href="{{ url('/') }}"> &nbsp; Login</a>

                                </div>
                            </div>

                        </div>
                </div>
                {!! Form::close() !!}
            </div>
            </div>


        </div>
    </div>
    </div>
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

        $('#myPassword').strength_meter();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // const btnSubmit = document.querySelector('#btnSignIn');
        // btnSubmit.addEventListener('click', function (e) {
        //     $("#forgetPassword").validate({
        //         rules: {
        //             user_confirm_password: {
        //                 equalTo: "#user_new_password"
        //             }
        //         },
        //         submitHandler: function (form) {
        //             form.submit();
        //         }
        //     });
        // });

    </script>
@endsection
