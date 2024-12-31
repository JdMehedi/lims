@extends('layouts.btrc-layout')
@section('inner_content')
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session()->get('error') }}
        </div>
    @endif
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form  onsubmit="otpPhoneObj.loginWithOtp(event)" method="post">
                    <div class="login-container">
                        <div class="login-head">
                            <div class="logo">
                                <a href="{{URL::to('/')}}"><img src="{{asset('assets/images/new_images_v2/logo-btrc-big.svg')}}" alt="Logo"></a>
                            </div>
                            <h3>Get a verification code</h3>
                        </div>

                        <div class="login-form">
                            <div class="form-group">
                                <label for="otp_code" id="mobile_num"></label>
                                <input type="text" class="form-control" id="otp_code" name="otp_code" placeholder="Enter OTP Code" required>
                            </div>
                            <button type="submit" class="btn-btrc btn-primary">Verify & Login</button>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

                        <div class="login-footer">
                            <p>Didn't receive OTP Code? <a href="javascript:void(0)" onclick="otpPhoneObj.resend()">Resend Code </a></p>
                            <div class="login-back-to-home">
                                <a class="flex-center" href="{{URL::to('/')}}"><span class="btn-icon"><img src="{{asset('assets/images/new_images_v2/icon-home.svg')}}" alt="Icon"></span> Back to Home</a>
                            </div>
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
        class otpPhone {
            static base_url = "{{ url('/') }}";
            static mobile = localStorage.getItem('mobile');
            showMobileNo() {
                let mobileLabel = document.getElementById('mobile_num');
                mobileLabel.innerHTML = `Enter OTP Code sent to ${otpPhone.mobile}`;
            }
            removeMobileNo() {
                localStorage.removeItem('mobile');
            }
            resend() {
                // this.removeMobileNo();
                // window.location = otpPhone.base_url + '/login-with-mobile';
                let formData = new FormData();
                formData.append('mobile', otpPhone.mobile);
                axios.post('/send-otp', formData).then((res) => {
                    if (res.data?.status === 200) {
                        window.location = '/otp-phone';
                        // alert('OTP sent');
                    } else {
                        window.location = '/otp-phone';
                        // alert(res.data?.message);
                    }
                }).catch((e) => {
                    alert(e.message);
                });
            }
            loginWithOtp(event) {
                event.preventDefault();
                let formData = new FormData();
                formData.append('otp', document.getElementById('otp_code').value);
                formData.append('mobile', otpPhone.mobile);
                axios.post('/login/otp-login-check', formData).then((res) => {
                    console.log(res);
                    if (res.data?.responseCode == 1) {
                        this.removeMobileNo();
                        window.location = otpPhone.base_url + res.data?.redirect_to;
                    } else {
                        // window.location = '/otp-phone';
                        alert(res.data?.msg);
                    }
                }).catch((e) => {
                    // window.location = '/otp-phone';
                    alert(e.message);
                });
            }
        }

        const otpPhoneObj = new otpPhone();
        otpPhoneObj.showMobileNo();
    </script>
@endsection
