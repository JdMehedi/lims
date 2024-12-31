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
{{--    @if($errors->any())--}}
{{--        @foreach ($errors->all() as $error)--}}
{{--            <div class="alert alert-danger">{{ $error }}</div>--}}
{{--        @endforeach--}}
{{--    @endif--}}
    <div class="site-main">
        <div class="login-body">
            <div class="login-white-box">
                <form action="/client/signup/registration" method="post">
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
                                <label for="login_email">Email <span class="clr-red">*</span></label>
                                <input type="email" class="form-control" id="login_email" value="{{ old('email') }}" name="email" placeholder="Enter Your Email" required>
                                @error('email')
                                <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login_name">Name <span class="clr-red">*</span></label>
                                <input type="text" class="form-control" id="login_name" name="name" value="{{ old('name') }}" placeholder="Enter Your Full Name" required>
                                @error('name')
                                <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="login_phone">Mobile Number <span class="clr-red">*</span></label>
                                <div class="signup_phone">
                                    <span class="cnt-code"><span class="cnt-flag"><img src="{{asset('assets/images/new_images_v2/icon-flag-ban.svg')}}" alt="Icon"></span> +88</span>
                                    <input type="text"
                                           class="form-control"
                                           name="mobile"
                                           value="{{ old('mobile') }}"
                                           id="login_phone"
                                           placeholder="Enter Your Mobile Number"
                                           pattern="^(?:\+88|88)?(01[3-9]\d{8})$"
                                           oninvalid="this.setCustomValidity('Please provide valid mobile number')"
                                           onchange="try{setCustomValidity('')}catch(e){}"
                                           oninput="setCustomValidity(' ')"
                                           required
                                    >
                                </div>
                                @error('mobile')
                                <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Company / Organization Name <span class="clr-red">*</span></label>
                                <div class="input_block mt-2">
                                    <div class="bs_radio">
                                        <input type="radio" onclick="signUpWithMailObj.selectNewCompany()" id="new_company" name="signup_company" value="new" required {{old('signup_company') == 'new' ? 'checked' : ''}}>
                                        <label for="new_company">New</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" onclick="signUpWithMailObj.selectExistingCompany()" id="exiting_company" name="signup_company" value="existing" {{old('signup_company') == 'existing' ? 'checked' : ''}}>
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
                                <select class="form-control btrc-select" name="existing_company_id" id="existing_company_name"></select>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="input_block mt-2">
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_male" name="gender" value="male" {{old('gender') == 'male' ? 'checked' : ''}}>
                                        <label for="gender_male">Male</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_female" name="gender" value="female" {{old('gender') == 'female' ? 'checked' : ''}}>
                                        <label for="gender_female">Female</label>
                                    </div>
                                    <div class="bs_radio">
                                        <input type="radio" id="gender_others" name="gender" value="others" {{old('gender') == 'others' ? 'checked' : ''}}>
                                        <label for="gender_others">Others</label>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="form-group input-link-group">--}}
{{--                                <div class="input_block">--}}
{{--                                    <label>Authorization Letter <span class="clr-red">*</span></label>--}}
{{--                                </div>--}}
{{--                                <div class="input-link mt-0 mb-1">--}}
{{--                                    <input type="file" onchange="signUpWithMailObj.setAuthLetterFile(event)" name="pdf_file" id="btrcSignup_atl" accept="application/pdf" required>--}}
{{--                                </div>--}}
{{--                                <div class="code-info text-right"><code>[File Format: .pdf Maximum 5 MB ]</code></div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group text-center">--}}
{{--                                <div class="google-reCaptcha">--}}
{{--                                    <iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6Ldds8QhAAAAAAWlBJzqxlLBgDJWB55axED4kxyh&amp;co=aHR0cHM6Ly9kZXYuYmEtc3lzdGVtcy5jb206NDQz&amp;hl=en&amp;v=vP4jQKq0YJFzU6e21-BGy3GP&amp;size=normal&amp;cb=egyf66130vj9" width="304" height="78" role="presentation" name="a-npcc2gmbqt3k" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <button class="btn-btrc btn-primary">Signup</button>
                        </div>

                        <div class="form-separator"><span>Or</span></div>

                        <div class="input-link-group">
                            <a href="{{URL::to('/signup-mobile')}}" class="input-link">
                                <span><img src="{{asset('assets/images/new_images_v2/icon-otp-phone.svg')}}" alt="Icon"></span>
                                <span>Signup With Mobile</span>
                            </a>
                        </div>

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
            $(document).ready(function() {
                $('#existing_company_name').select2();
            });

            class signUpWithMail {
                static new_company = document.getElementById('new_company_part');
                static existing_company = document.getElementById('existing_company_part');
                static authLetter = null;
                static base_url = "{{ url('/') }}";
                selectNewCompany() {
                    signUpWithMail.new_company.classList.remove('hidden');
                    signUpWithMail.existing_company.classList.add('hidden');
                }

                selectExistingCompany() {
                    signUpWithMail.new_company.classList.add('hidden');
                    signUpWithMail.existing_company.classList.remove('hidden');
                }

                setAuthLetterFile(event) {
                    signUpWithMail.authLetter = event.target.files[0];
                }

                signup(event) {
                    event.preventDefault();
                    const email = this.getElementValue('login_email');
                    const name = this.getElementValue('login_name');
                    const mobile = '0'+this.getElementValue('login_phone');
                    const company = document.querySelector('input[name="signup_company"]:checked').value;
                    const companyName = (company === 'new') ? this.getElementValue('company_name') : this.getElementValue('existing_company_name');
                    const gender = document.querySelector('input[name="signup_gender"]:checked') ? document.querySelector('input[name="signup_gender"]:checked').value : '';
                    const data = this.createFormData({email, name, mobile, gender});
                    axios.post('/client/signup/registration', data).then((res) => {
                        if (res.data?.responseCode === 1) {
                            window.location = signUpWithMail.base_url + res.data?.redirect_to;
                        } else {
                            alert(res.data?.msg);
                        }
                    }).catch((e) => {
                        // const error = e.response.data?.errors;
                        // if (error) {
                        //     for (const eKey in error) {
                        //         alert(error[eKey]);
                        //     }
                        // }
                        window.location.reload();
                    });
                }
                getElementValue(id) {
                    return document.getElementById(id).value;
                }
                createFormData(obj) {
                    let formData = new FormData();
                    for (const objKey in obj) {
                        formData.append(objKey, obj[objKey]);
                    }
                    return formData;
                }
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
            const signUpWithMailObj = new signUpWithMail();
            signUpWithMailObj.getCompanyInfos();
        </script>
    @endpush
@endsection
