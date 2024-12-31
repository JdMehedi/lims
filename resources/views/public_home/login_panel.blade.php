<?php
$osspid = new \App\Libraries\Osspid([
    'client_id' => config('osspid.osspid_client_id'),
    'client_secret_key' => config('osspid.osspid_client_secret_key'),
    'callback_url' => config('app.PROJECT_ROOT') . '/osspid-callback',
]);
$redirect_url = $osspid->getRedirectURL();
?>
<div class="col-md-4">
    <div class="card " style="background: #C9F7DB">
        <div class="card-body ">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="text-center">
                        <h4><b> User Access </b></h4>
                        @include('partials.messages')
                        <a href="<?php echo @$redirect_url; ?>" class="btn btn-danger btn-block btn-md"
                            style="background-color: #EC1D23"><b><i class="fa fa-chevron-circle-right"></i> Login with
                                OSSPID</b></a>
                        <a href="#" data-toggle="modal" data-target="#otp_modal" class="btn btn-info btn-block btn-md"><strong><i
                                    class=" fa fa-lock "></i> Login with
                                OTP</strong></a>
                        <a href="/auth/google" class="btn btn-warning btn-block btn-md"><strong><i
                                    class=" fa fa-user "></i> Login with
                                Google</strong></a>
                    </div>
                    <div class="row">
                        <div class="col">
                            <hr>
                        </div>
                        <div class="col-auto">OR</div>
                        <div class="col">
                            <hr>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <div class="input-padding">
                            <label class="text-danger error-msg"></label>
                        </div>
                        <input type="email" class="form-control input-md required" id="email" name="email"
                            placeholder="User Email"><br>
                        <input type="password" class="form-control input-md required" id="password" name="password"
                            placeholder="User Password">
                        <p></p>

                        <div class="input-padding" id="captchaDiv" style="display: none;">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>


                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-success btn-login btn-md" id="btnSignIn"
                                    onclick="checkUserInformation('loginForm')">Sign In
                                </button>
                            </div>
                            <div>
                                <a class="black-color fs-18" href="{{ url('forget-password') }}">Forgot password ?</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between float-right">
                            <div>
                                <a class="black-color fs-18" href="{{ url('/articles/support') }}">Need help? Contact
                                    us</a>

                            </div>
                        </div>



                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <span style="font-size: smaller">Powered by</span>
                            <br>
                            <a href="https://www.ba-systems.com/product/ossp-one-stop-service-platform" target="_blank"
                                rel="noopener">
                                <img style="background: none;" src="https://bidaquickserv.org/assets/images/ossp.png"
                                    alt="One Stop Service Platform">
                            </a>
                        </div>
                        <div>
                            <span style="font-size: smaller">Managed by</span>
                            <br>
                            <a href="https://www.ba-systems.com" target="_blank" rel="noopener">
                                <img style="background: none;"
                                    src="https://bidaquickserv.org/assets/images/business_automation_sm.png"
                                    alt="Business Automation Ltd.">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div id="otp_modal" class="modal fade" role="dialog">
    <div class="modal-dialog user-login-modal-container">

        <!-- Modal content for OTP Login-->
        <div class="modal-content user-login-modal-body">
            <div class="modal-header user-login-modal-title">
                <div class="modal-title" style="font-size: 15px;">
                    লগিন অপশনে ক্লিক করলে oss প্রদানকৃত আপনার ইমেইলে একটি One Time Password (OTP) প্রেরণ করা হবে। OTP দিয়ে Submit অপশনে ক্লিক করুন। </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body login-otp user-login-modal-content">
                {!! Form::open(array('url' => '', 'method' => '' ,'id'=>'otpForm')) !!}
                <div style="display: none" class="error-message-message-login alert alert-danger"></div>
                <div style="display: none" class="success-message-message-login alert alert-success"></div>
                <div id="loading_send_sms" class="text-center" style="font-size: 16px;">
                </div>

                <div class="row">
                    <div class="col-md-12 otp_step_1" id="otp_step_1">
                        <div class="form-group row otp_receiver">
                            {!! Form::label('otp_by', 'Email ID:', array('class' => 'col-sm-3 col-form-label', 'id' => '')) !!}

                            <div class="col-sm-9">
                                {!! Form::text('email', '', array('class' => 'form-control email', 'placeholder' => 'Enter email address.','id'=>'email_address')) !!}
                                <span class="error-message-nid" style="color: red"></span>
                            </div>
                        </div>
                    </div>

                    <div id="otp_step_2" class="col-md-12" style="display:none;">
                        <div class="form-group row">
                            <div style="display: none" class="alert alert-danger error-message alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><span class="error-message"></span></div>

                            {!! Form::label('login_token', 'OTP:', array('class' => 'col-md-3')) !!}
                            <div class="col-md-9">
                                {!! Form::text('login_token', '', array('class' => 'form-control', 'placeholder' => 'Enter Your OTP','id'=>'login_token')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger float-right  Next1" id="otpnext1" style="background-color:rgba(255, 101, 132, 1) ">Login</button>
                        <div id="otpnext2" style="display:none;">
                            <button type="button" id="otpnext3" style="float:right; background-color: rgba(113, 217, 181, 1)" class="btn btn-info float-right Next2 btn-block">Submit</button>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div id="resend_link" style="display: none; font-size: 16px; text-align: center;" class="login-need-help col-md-12">
                            <br>
                            Don't receive the OTP?
                            <b>
                                <a   href="javascript:void(0)"  class="resend_otp" style="color: red;">Resend OTP</a>
                            </b>

                        </div>
                        <br>
                        <p id="display_before" style="font-size: 16px;display: none; text-align: center;"  >The OTP will be expired in <span id="show_cowndown"></span></p>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer user-login-modal-footer" style="font-size: 15px;">OTP না পেলে নির্বাহী প্রকৌশলীর কার্যালয়,  যোগাযোগ করুন। অথবা +8809639434343  নম্বরে কল করুন।

            </div>
        </div>

    </div>
</div>

<script>

    const base_url = "{{ url('/') }}";
    const errorMsg = $('.error-msg');
    const captchaDiv = $('#captchaDiv');


    let hit = "{{ Session::get('hit') }}";
    if (hit >= 3) {
        captchaDiv.css('display', 'block');
        grecaptcha.reset();
    }

    $(document).bind('keypress', function(e) {
        if (e.keyCode == 13) {
            checkUserInformation()
        }
    });

    function checkUserInformation() {
        if ($("#email").val() == '' || $("#passowrd").val() == '') {
            errorMsg.html("Please enter your email and password properly!");
            return false;
        }

        $("#btnSignIn").prop('disabled', true); // disable button
        $("#btnSignIn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
        errorMsg.html("");
        $.ajax({
            url: '/login/check',
            type: 'POST',
            data: {
                email: $('input[name="email"]').val(),
                password: $('input[name="password"]').val(),
                g_recaptcha_response: $('#g-recaptcha-response').val(),
                _token: $('input[name="_token"]').val()
            },
            datatype: 'json',
            success: function(response) {
                if (response.responseCode === 1) {
                    window.location = base_url + response.redirect_to;
                } else {
                    if (response.hit >= 3) {
                        captchaDiv.css('display', 'block');
                        grecaptcha.reset();
                    }
                    errorMsg.html(response.msg);
                }
                $("#btnSignIn").prop('disabled', false); // disable button
                $("#btnSignIn").html('Sign In');
            },
            error: function(jqHR, textStatus, errorThrown) {
                // Reset error message div and put the message inside
                errorMsg.html(jqHR.responseJSON.message);
                // console.log(jqHR.responseJSON.message)
                console.log(jqHR, textStatus, errorThrown);
                $("#btnSignIn").prop('disabled', false); // disable button
                $("#btnSignIn").html('Sign In');
            }
        });
    }

    $(document).on('click','.Next1',function(e){

        // var project_id = $('#project_name').val();
        var email_address = $('#email_address').val();
        // if( project_id == '')
        // {
        //     $("#project_name").addClass('error');
        //     $(".error-message").text("Please select your project");
        //     return false;
        // }
        // else {
        //     $("#project_name").removeClass('error');
        //     $(".error-message").text("");
        // }

        if( email_address == '')
        {
            $("#email_address").addClass('error');
            $(".error-message-nid").text("Please enter your email address");
            return false;
        }
        else {
            $("#email_address").removeClass('error');
            $(".error-message-nid").text("");
        }

        if( !validateEmail(email_address)) {
            $("#email_address").addClass('error');
            $(".error-message-nid").text("Please enter valid email address");
            return false;
        }
        // if(email_address.length < 10 ){
        //     $(".error-message-nid").text("Please enter your 10 or 13 digit nid number.");
        //     return  false
        // }else{
        //     $(".error-message-nid").text("");
        // }

        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
        // btn.prop('disabled', true);

        $("#otpnext1").prop('disabled', true); // disable button
        $.ajax({
            url: '/login/otp-login-validation-with-token-provide',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                'email_address': email_address,
                // 'project_id': project_id,
                // 'otp' : $('#otpForm').find('input[name=otp]:checked').val()
            },
            success: function (response) {
                btn.prop('disabled', false);
                btn.html(btn_content);

                if(response.responseCode == 1)
                {
                    timerCounter= setInterval('secondPassed()', 1000);
                    seconds.value = seconds.defaultValue;
                    $(".error-message-message-login").hide();
                    // $("#email_address").prop("disabled", true)
                    $('#otp_step_1').css("display", "none");
                    $('#otpnext1').css("display", "none");
                    $('#otp_step_2').css("display", "block");
                    $('#otpnext2').css("display", "block");
                    $('#otpnext3').css("display", "block");

                    $('#loading_send_sms').html('Sending OTP <i class="fa fa-spinner fa-spin"></i>');
                    ///// ajax call
                    $('.modal-title').html('<span class="text-bold">Your email Address:  '+response.user_email+'</span>');
                    $('.modal-title').addClass('text-center');
                    checksmsStatus(response.queue_id);
                }
                else
                {
                    $(".error-message-message-login").show();
                    $(".error-message-message-login").text(response.msg);
                    // alert('Invalid Credentials');
                    return false;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                //completed
            }
        });

    });
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
    }
    $(document).on('click','.Next2',function(e){
        btn.prop('disabled', true);
        var login_token = $('#login_token').val();
        var project_id = $('#project_name').val();
        var email_address = $('#email_address').val();
        if(!login_token)
        {
            alert('OTP should be given');
            return false;
        }

        if(!email_address)
        {
            alert('Data has mismatch');
            return false;
        }


        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

        $.ajax({
            url: '/login/otp-login-check',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                'email_address': email_address,
                'login_token' : login_token,
                'project_id' : project_id
            },
            success: function (response) {

                btn.html(btn_content);
                btn.prop('disabled', false);

                if(response.responseCode == 1)
                {
                    window.location.href = response.redirect_to;
                }
                else if(response.msg == 'OTP Time Expired!.Please Try again'){
                    $('#resend_link').css("display", "block");
                }
                else
                {
                    $(".error-message-message-login").show();
                    $(".error-message-message-login").text(response.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                //completed
            }
        });
    });

    $(document).on('click','.resend_otp',function(e){
        // btn.prop('disabled', true);
        $('#login_token').val('');
        var project_id = $('#project_name').val();

        var email_address = $('#email_address').val();

        if(!email_address)
        {
            alert('Data has mismatch');
            return false;
        }


        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);

        $.ajax({
            url: '/login/otp-resent',
            type: 'post',
            data: {
                _token: $('input[name="_token"]').val(),
                'email_address': email_address,
            },
            success: function (response) {

                btn.html(btn_content);
                // btn.prop('disabled', false);

                //console.log(response);
                //alert(response);


                if(response.responseCode == 1)
                {
                    $('#resend_link').css("display", "none");
                    $('#loading_send_sms').css("display", "block");
                    $('#loading_send_sms').html('Sending OTP <i class="fa fa-spinner fa-spin"></i>');
                    $('#resend_link').css("display", "none");
                    checksmsStatus(response.queue_id);

                    // checksmsStatus(response.sms_id,response.otp_expired);
                }
                else
                {
                    $(".success-message-message-login").hide();
                    $(".error-message-message-login").show();
                    $(".error-message-message-login").text(response.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                //completed
                if(seconds == 0){
                    seconds = 180;
                }

                timerCounter= setInterval('secondPassed()', 1000);
            }
        });
    });


    $('.onlyNumber').on('keydown', function (e) {
        //period decimal
        if ((e.which >= 48 && e.which <= 57)
            //numpad decimal
            || (e.which >= 96 && e.which <= 105)
            // Allow: backspace, delete, tab, escape, enter and .
            || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
            // Allow: Ctrl+A
            || (e.keyCode == 65 && e.ctrlKey === true)
            // Allow: Ctrl+C
            || (e.keyCode == 67 && e.ctrlKey === true)
            // Allow: Ctrl+V
            || (e.keyCode == 86 && e.ctrlKey === true)
            // Allow: Ctrl+X
            || (e.keyCode == 88 && e.ctrlKey === true)
            // Allow: home, end, left, right
            || (e.keyCode >= 35 && e.keyCode <= 39))
        {

            var thisVal = $(this).val();
            if (thisVal.indexOf(".") != -1 && e.key == '.') {
                return false;
            }
            $(this).removeClass('error');
            return true;
        }
        else
        {
            $(this).addClass('error');
            return false;
        }
    }).on('paste', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 4);
    }).on('keyup', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 4);
    });
    $(document).on('keypress',  function (e) {
        if($('#otp_step_1').is(':visible')) {
            var key = e.which;
            if (key == 13) { //This is an ENTER
                $('#otpnext1').click();
            }
        }
    });
    $(document).on('keypress',  function (e) {
        if($('#otp_step_2').is(':visible')) {
            var key = e.which;
            if (key == 13) { //This is an ENTER
                $('#otpnext3').click();
            }
        }
    });


//     function remainingtime(time) {
//         var countDownDate = new Date(time).getTime();
// // Update the count down every 1 second
//         var x = setInterval(function() {
//             console.log('timer');
//
//             // Get today's date and time
//             var now = new Date().getTime();
//
//             // Find the distance between now and the count down date
//             var distance = countDownDate - now;
//
//             // Time calculations for days, hours, minutes and seconds
//
//             var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
//             var seconds = Math.floor((distance % (1000 * 60)) / 1000);
//
//             // Output the result in an element with id="demo"
//             if(minutes <=0){
//                 document.getElementById("show_cowndown").innerHTML =seconds+' seconds';
//             }else{
//                 document.getElementById("show_cowndown").innerHTML = minutes + " minute:" + seconds+' seconds';
//             }
//
//
//             // If the count down is over, write some text
//             if (distance < 0) {
//                 clearInterval(x);
//                 $(".success-message-message-login").hide();
//                 $(".error-message-message-login").hide();
//                 $('#display_before').css("display", "none");
//                 $('#resend_link').css("display", "block");
//             }
//         }, 1000);
//     }
    var timerCounter;
    var seconds = 180; //**change 180 for any number you want, it's the seconds **//
    function secondPassed() {

            $('#resend_link').css("display", "none");
            var minutes = Math.round((seconds - 30)/60);
            var remainingSeconds = seconds % 60;
            if (remainingSeconds < 10) {
                remainingSeconds = "0" + remainingSeconds;
            }

            document.getElementById('show_cowndown').innerHTML = minutes + ":" + remainingSeconds;
            if (seconds == 0) {
                clearInterval(timerCounter);
                document.getElementById('show_cowndown').innerHTML = "Expired!";

                $(".success-message-message-login").hide();
                $(".error-message-message-login").hide();
                $('#display_before').css("display", "none");
                $('#resend_link').css("display", "block");

            } else {
                seconds--;
            }

    }



    function checksmsStatus(email_id,expiredtime=null) {
        var currenttime = new Date();
        var currenttimemilisecond = currenttime.getTime();
        var after10 = (currenttimemilisecond+(10*1000));
        var after20 = (currenttimemilisecond+(20*1000));
        var expiredtimemilisecond = new Date(expiredtime).getTime();

        var x = setInterval(function() {
            var currenttime2 = new Date().getTime();
            $.ajax({
                url: '/login/check-sms-send-status',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    'email_id': email_id,
                },
                success: function (response) {

                    if(response.responseCode == 1) {
                        if(response.sms_status ==1){
                            clearInterval(x);
                            $('#loading_send_sms').css("display", "none");
                            $(".error-message-message-login").hide();
                            $('.success-message-message-login').show().text(response.msg).delay(5000).fadeOut(300);
                            setTimeout("$('#display_before').css('display', 'block')", 5000);
                            $('#otp_step_1').css("display", "none");
                            $('#otpnext1').css("display", "none");
                             remainingtime(expiredtime);
                        }else{
                            if (currenttime2 >expiredtimemilisecond){
                                clearInterval(x);
                                $('#loading_send_sms').html('Please Try after some times');
                            }else
                                if(currenttime2 >after20){
                                $('#loading_send_sms').html('Please wait, sending SMS <i class="fa fa-spinner fa-spin"></i>');
                            }else if(currenttime2 > after10){
                                $('#loading_send_sms').html('Sending SMS <i class="fa fa-spinner fa-spin"></i>');
                            }


                        }
                    }
                    else
                    {
                        clearInterval(x);
                        $('#loading_send_sms').html('Please Try after some times');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                    timerCounter=  setInterval('secondPassed()', 1000);
                }
            });

        }, 2000);
    }

</script>
