@extends('layouts.btrc-layout')
@section('inner_content')
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form onsubmit="loginObj.login(event)" type="post">
                    <div class="login-container">
                        <div class="login-head">
                            <div class="logo">
                                <a href="{{URL::to('/')}}"><img
                                        src="{{asset('assets/images/new_images_v2/logo-btrc-big.svg')}}" alt="Logo"></a>
                            </div>
                            <h3>Login</h3>
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                        </div>

                        <div class="login-form">
                            <div class="form-group">
                                <label for="login_email">Email</label>
                                <div class="input-w-icon">
                                    <span class="input-icon"><img
                                            src="{{asset('assets/images/new_images_v2/icon-input-user.svg')}}"
                                            alt="Icon"></span>
                                    <input type="email" class="form-control !text-lower" id="login_email"
                                           name="login_email" placeholder="Enter Your Email" required>
                                </div>
                            </div>
                            <div class="form-group input-bottom-text">
                                <label for="login_pass">Password</label>
                                <div class="input-w-icon">
                                    <span class="input-icon">
{{--                                        <img src="{{asset('assets/images/new_images_v2/icon-input-password.svg')}}" alt="Icon">--}}
                                            <i class="login fa fa-eye cursor-pointer" id="eye"
                                               onclick="loginObj.changeFieldType()"></i>
                                            <i class="login fa fa-eye-slash cursor-pointer hidden" id="eyeSlash"
                                               onclick="loginObj.changeFieldType()"></i>
                                    </span>
                                    <input type="password" class="form-control" id="login_pass" name="login_pass"
                                           placeholder="Enter Your Password" required>
                                </div>
                                <span class="bottom-text"><a href="{{URL::to('/forget-password')}}">Forgot Password?</a></span>
                            </div>
                            <button class="btn-btrc btn-primary">Log in</button>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

                        <div class="input-link-group">
                            <a href="{{URL::to('/login-with-mobile')}}" class="input-link">
                                <span><img src="{{asset('assets/images/new_images_v2/icon-otp-phone.svg')}}" alt="Icon"></span>
                                <span>Login With Mobile</span>
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
        class loginPage {
            base_url = "{{ url('/') }}";
            currentType = 'password';

            login(event) {
                event.preventDefault();
                const email = document.getElementById('login_email').value;
                const password = document.getElementById('login_pass').value;
                // const g_recaptcha_response = document.getElementById('g-recaptcha-response').value;
                // const _token = document.getElementsByName('_token')
                let formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                axios.post('/login/check', formData).then((res) => {
                    console.log(res);
                    if (res.data?.responseCode === 1) {
                        window.location = this.base_url + res.data?.redirect_to;
                    } else {
                        window.location = '/login';
                        // alert(res.data?.msg);
                        // document.body.innerHTML = res.data?.msg;
                    }
                }).catch((e) => {
                    window.location = '/login';
                    // alert(e.message);
                });
            }

            changeFieldType() {
                const inputField = this.getElementWithtId('login_pass');
                this.getElementWithtId('eye').classList.toggle('hidden');
                this.getElementWithtId('eyeSlash').classList.toggle('hidden');
                if (this.currentType === 'password') {
                    this.currentType = 'text';
                    inputField.type = 'text';
                } else {
                    this.currentType = 'password';
                    inputField.type = 'password';
                }
            }

            getElementWithtId(id) {
                return document.getElementById(id);
            }
        }

        const loginObj = new loginPage();
    </script>
@endsection
