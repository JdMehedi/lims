@extends('layouts.btrc-layout')
@section('inner_content')
    <style>
        .select2-container {
            display: block !important;
            width: 100% !important;
        }
        .text-red {
            color: red;
        }
    </style>
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form action="{{ url('client/signup/mobile-registration') }}" id="signupFrm" method="post">
                    @csrf
                    <div class="login-container">
                        <div class="login-head">
                            <div class="logo">
                                <a href="{{URL::to('/')}}"><img src="{{asset('assets/images/new_images_v2/logo-btrc-big.svg')}}" alt="Logo"></a>
                            </div>
                            <h3>Signup</h3>
                        </div>

                        <div class="login-form">
                            <div class="form-group">
                                <label for="login_mobile">Mobile Number <span class="clr-red">*</span></label>
                                <div class="signup_phone">
                                    <span class="cnt-code"><span class="cnt-flag"><img src="{{asset('assets/images/new_images_v2/icon-flag-ban.svg')}}" alt="Icon"></span> +88</span>
                                    <input type="text" required="required" class="form-control @error('login_mobile') is-invalid @enderror"
                                           value="{{ old('login_mobile') }}"
                                           id="login_mobile" name="login_mobile" placeholder="Enter Your Mobile Number">
                                </div>
                                @error('login_mobile')
                                <p class="text-danger"><small>{{ $message }}</small></p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login_name">Name <span class="clr-red">*</span></label>
                                <input type="text" class="form-control @error('login_name') is-invalid @enderror"
                                       value="{{ old('login_name') }}"
                                       id="login_name" required="required" name="login_name" placeholder="Enter Your Full Name">
                                @error('login_name')
                                <p class="text-danger"><small>{{ $message }}</small></p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login_email">Email <span class="clr-red">*</span></label>
                                <input type="email" class="form-control @error('login_email') is-invalid @enderror"
                                       value="{{ old('login_email') }}"
                                       id="login_email" name="login_email" required="required" placeholder="Enter Your Email">
                                @error('login_email')
                                <p class="text-danger"><small>{{ $message }}</small></p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Company / Organization Type <span class="clr-red">*</span></label>
                                <div class="input_block mt-2">
                                    <div class="bs_radio">
                                        <input type="radio" onclick="selectNewCompany()" id="new_company" name="signup_company" value="new" required>
                                        <label for="new_company">New</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" onclick="selectExistingCompany()" id="exiting_company" name="signup_company" value="existing">
                                        <label for="exiting_company">Existing</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group hidden" id="new_company_part">
                                <label for="company_name">Company / Organization Name <span class="clr-red">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{old('company_name')}}" placeholder="Enter Company / Organization Name">
                                @error('company_name')
                                <p class="text-danger"><small>{{ $message }}</small></p>
                                @enderror
                            </div>
                            <div class="form-group hidden" id="existing_company_part">
                                <label for="company_name">Company / Organization Name <span class="clr-red">*</span></label>
                                <select class="form-control btrc-select" name="existing_company_id" id="existing_company_name"></select>
                            </div>


                            <!--
                            <div class="form-group">
                                <label>Company / Organization Name <span class="clr-red">*</span></label>
                                <div class="input_block mt-2">
                                    <div class="bs_radio">
                                        <input type="radio" onclick="signUpWithMobileObj.selectNewCompany()" id="new_company" name="signup_company" value="new">
                                        <label for="new_company">New</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" onclick="signUpWithMobileObj.selectExistingCompany()" id="exiting_company" name="signup_company" value="existing">
                                        <label for="exiting_company">Existing</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group hidden" id="new_company_part">
                                <label for="company_name">Company / Organization Name <span class="clr-red">*</span></label>
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company / Organization Name">
                            </div>

                            <div class="form-group hidden" id="existing_company_part">
                                <label for="company_name">Company / Organization Name <span class="clr-red">*</span></label>
                                <select class="form-control btrc-select" name="" id="">
                                    <option value="0">Select Company / Organization Name</option>
                                    <option value="ba">Business Automation</option>
                                </select>
                            </div>
                            -->

                        <!--
                            <div class="form-group">
                                <label>Gender</label>
                                <div class="input_block mt-2">
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_male" name="signup_gender"
                                               value="male" {{ old('signup_gender')=="male" ? 'checked='.'"'.'checked'.'"' : '' }}>
                                        <label for="gender_male">Male</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_female" name="signup_gender"
                                               value="female" {{ old('signup_gender')=="female" ? 'checked='.'"'.'checked'.'"' : '' }}>
                                        <label for="gender_female">Female</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_others" name="signup_gender"
                                               value="others" {{ old('signup_gender')=="others" ? 'checked='.'"'.'checked'.'"' : '' }}>
                                        <label for="gender_others">Others</label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group input-link-group">
                                <div class="input_block">
                                    <label>Authorization Letter <span class="clr-red">*</span></label>
                                </div>
                                <div class="input-link mt-0 mb-1">
                                    <input type="file" name="pdf_file" id="btrcSignup_atl" accept="pdf">
                                </div>
                                <div class="code-info text-right"><code>[File Format: .pdf Maximum 5 MB ]</code></div>
                            </div>
                            -->

                            <!--
                            <div class="form-group text-center">
                                <div class="google-reCaptcha">
                                    <iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6Ldds8QhAAAAAAWlBJzqxlLBgDJWB55axED4kxyh&amp;co=aHR0cHM6Ly9kZXYuYmEtc3lzdGVtcy5jb206NDQz&amp;hl=en&amp;v=vP4jQKq0YJFzU6e21-BGy3GP&amp;size=normal&amp;cb=egyf66130vj9" width="304" height="78" role="presentation" name="a-npcc2gmbqt3k" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe>
                                </div>
                            </div>
                            -->

                            <!-- button class="btn-btrc btn-primary" type="submit">Signup</button-->
                            <button class="btn-btrc btn-primary" type="button" onclick="submitForm();">Signup</button>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

{{--                        <div class="input-link-group">--}}
{{--                            <a href="{{URL::to('/signup-email')}}" class="input-link">--}}
{{--                                <span><img src="{{asset('assets/images/new_images_v2/icon-envelope-color.svg')}}" alt="Icon"></span>--}}
{{--                                <span>Signup With Email</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}

                        <div class="login-footer">
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
    @push('footer_script')
        <script>
            document.onreadystatechange = () => {
                if (document.readyState === "complete") {
                    @if(old('signup_company') && old('signup_company') == 'new')
                    document.getElementById('new_company').click();
                    @endif

                    @if(old('signup_company') && old('signup_company') == 'existing')
                    document.getElementById('exiting_company').click();
                    @endif
                }
            };

            function submitForm(){
                const login_mobile = document.getElementById('login_mobile').value;
                const login_email = document.getElementById('login_email').value;


                let formData = new FormData();
                formData.append('login_mobile', login_mobile);
                formData.append('login_email', login_email);
                axios.post('/client/signup/email-mobile-validation', formData).then((res) => {
                    // alert(res.data.statusCode);
                    // alert(res.data.message);
                    //
                    // console.log(res.data);
                    //
                    // return;

                    if (res.data.statusCode == 1) {
                        $("#signupFrm").submit();
                    } else {
                        alert(res.data.message);
                    }
                }).catch((e) => {
                    // alert(e.message);
                });
                return false;
            }

            $(document).ready(function() {
                $('#existing_company_name').select2();
            });

            const new_company = document.getElementById('new_company_part');
            const existing_company = document.getElementById('existing_company_part');
            function selectNewCompany() {
                new_company.classList.remove('hidden');
                existing_company.classList.add('hidden');
            }

            function selectExistingCompany() {
                new_company.classList.add('hidden');
                existing_company.classList.remove('hidden');
            }
            class signUpWithMobile {
                getCompanyInfos() {
                    axios.get('/company-info').then((response) => {
                        const existionCompanyInfo = response.data.data;
                        const companyInfoSelectBox = document.getElementById('existing_company_name');
                        let htmlOptions = '<option value="0">Enter Company / Organization Name</option>';
                        for (let i = 0; i < existionCompanyInfo.length; i++) {
                            const item = existionCompanyInfo[i];
                            htmlOptions += `
                            <option value="${item.id}">${item.org_nm}</option>
                        `;
                        }
                        companyInfoSelectBox.innerHTML = htmlOptions;
                    });
                }
            }

            //create class instance
            const signUpWithMobileObj = new signUpWithMobile();
            signUpWithMobileObj.getCompanyInfos();
        </script>
    @endpush
@endsection
