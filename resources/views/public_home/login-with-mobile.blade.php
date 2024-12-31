@extends('layouts.btrc-layout')
@section('inner_content')
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form onsubmit="loginWithOtp(event)" method="post">
                    <div class="login-container">
                        <div class="login-head">
                            <div class="logo">
                                <a href="{{URL::to('/')}}"><img
                                            src="{{asset('assets/images/new_images_v2/logo-btrc-big.svg')}}" alt="Logo"></a>
                            </div>
                            <h3>Login</h3>
                        </div>

                        <div class="login-form">
                            <div class="form-group">
                                <label for="login_mobile_num">Mobile Number</label>
                                <div class="input-w-icon">
                                    <span class="input-icon"><img
                                                src="{{asset('assets/images/new_images_v2/icon-input-user.svg')}}"
                                                alt="Icon"></span>
                                    <input type="tel" class="form-control" id="login_mobile_num" name="login_mobile_num"
                                           placeholder="Enter Your Mobile Number" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-btrc btn-primary">Next</button>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

                        <div class="input-link-group">
                            <a href="{{URL::to('/login')}}" class="input-link">
                                <span><img src="{{asset('assets/images/new_images_v2/icon-envelope-color.svg')}}"
                                           alt="Icon"></span>
                                <span>Login With Email</span>
                            </a>
                        </div>

                        <div class="login-footer">
                            <p>Don't have an account? <a href="{{URL::to('/user-signup')}}">Sign Up</a></p>

                            @if(!env('IS_MOBILE'))
                                <div class="login-back-to-home">
                                    <a class="flex-center" href="{{URL::to('/')}}"><span class="btn-icon"><img
                                                    src="{{asset('assets/images/new_images_v2/icon-home.svg')}}"
                                                    alt="Icon"></span> Back to Home</a>
                                </div>
                            @endif
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(env('IS_MOBILE'))
        <style>
            .login-body {
                padding: 0px !important;
            }

            .login-white-box {
                border-radius: 0px !important;
            }
        </style>
    @endif

    <script>
        const base_url = "{{ url('/') }}";

        function loginWithOtp(event) {
            event.preventDefault();
            const mobile = document.getElementById('login_mobile_num').value;
            let formData = new FormData();
            formData.append('mobile', mobile);
            axios.post('/send-otp', formData).then((res) => {
                if (res.data?.status === 200) {
                    localStorage.setItem('mobile', mobile);
                    window.location = base_url + '/otp-phone';
                } else {
                    // window.location = '/login-with-mobile';
                    alert(res.data?.message);
                }
            }).catch((e) => {
                // window.location = '/login-with-mobile';
                alert(e.response?.data?.message);
            });
        }
    </script>
@endsection
