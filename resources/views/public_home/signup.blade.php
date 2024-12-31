@extends('layouts.btrc-layout')
@section('inner_content')
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form action="">
                    <div class="login-container">
                        <div class="login-head">
                            <div class="logo">
                                <a href="{{URL::to('/')}}"><img src="{{asset('assets/images/new_images_v2/logo-btrc-big.svg')}}" alt="Logo"></a>
                            </div>
                            <h3>Signup</h3>
                        </div>

                        <div class="input-link-group">
                            <a href="{{URL::to('/signup-email')}}" class="input-link">
                                <span><img src="{{asset('assets/images/new_images_v2/icon-envelope-color.svg')}}" alt="Icon"></span>
                                <span>Signup With Email</span>
                            </a>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

                        <div class="input-link-group">
                            <a href="{{URL::to('/signup-mobile')}}" class="input-link">
                                <span><img src="{{asset('assets/images/new_images_v2/icon-otp-phone.svg')}}" alt="Icon"></span>
                                <span>Signup With Mobile</span>
                            </a>
                        </div>

                        <div class="login-footer mt-4">
                            <p>Alradey have an account? <a href="{{URL::to('/login-with-mobile')}}">Login</a></p>

                            <div class="login-back-to-home">
                                <a class="flex-center" href="{{URL::to('/')}}"><span class="btn-icon"><img src="{{asset('assets/images/new_images_v2/icon-home.svg')}}" alt="Icon"></span> Back to Home</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
