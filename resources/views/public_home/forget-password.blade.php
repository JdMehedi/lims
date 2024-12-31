@extends('public_home.front_old')

@section('header-resources')
    <style>
        .form-group{
            margin-bottom: 0;
        }
        a:hover{
            text-decoration: none;
        }
    </style>
@endsection

@section ('body')
    <div class="row my-5">
        <div class="col-md-12">
            <div class="wrapper">
                {{--    <div class="container header-image"></div>--}}
                <div class="container container-bg"><br>
                    <div class="offset-2 col-md-4 login-panel m-auto">

                        <div class="card card-info">
                            {!! Form::open(['url' => 'reset-forgotten-password','method' => 'post', 'class' =>'form-horizontal', 'id' => 'forgetPassword']) !!}
                            {{--                            <form class="form-horizontal">--}}
                            <div class="card-body">
                                <div class="form-group row">
                                    <p style="padding: 0 25px 10px; text-align: center; color: rgba(0,0,0,.7);">You forgot your password? Here you can easily retrieve a new password.</p>
                                    @include('partials.messages')
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                        {!! Form::email('user_email', '',['class' => 'form-control required','placeholder' => 'Email','id'=>'user_email']) !!}
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-envelope"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="text-danger error-msg"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
{{--                                    <div class="col-sm-12">--}}
{{--                                        {!! NoCaptcha::renderJs() !!}--}}
{{--                                        {!! NoCaptcha::display() !!}--}}
{{--                                        <small--}}
{{--                                            class="form-text text-danger">{!! $errors->first('g-recaptcha-response') !!}</small>--}}
{{--                                    </div>--}}
                                </div>
                            </div>

                            <div class="form-group row py-2">
                                <button style="margin: 0 28px;" type="submit" class="btn btn-primary float-right form-control"  id="btnSignIn">Request new password</button>
                                <br>
                                <div class="col-md-12">
                                    <a class="mx-4 text-primary my-1" href="{{ url('/login') }}" style="display: block;">Login</a>
                                </div>
                                <div class="col-md-12">
{{--                                    <a class="mx-4 text-primary my-1" href="{{ url('/') }}">Register a new membership</a>--}}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endsection

        @section('footer-script')
            <script src="{{ asset("assets/scripts/jquery.validate.min.js") }}"></script>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const btnSubmit = document.querySelector('#btnSignIn');
                btnSubmit.addEventListener('click', function (e) {
                    $("#forgetPassword").validate({
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });
            </script>
@endsection
