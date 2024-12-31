@extends('public_home.front')
@section('header-resources')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap4.min.css') }}">
@endsection


@section ('body')

    <div class="container" style="background: #d8eff7 none repeat scroll 0 0"><br>

        <div class="card card-info" style="background:  rgba(231, 247, 234, 1)">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4">
                                        <img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <span>Support from home will be ensure</span><br>
                                        <span>Sunday to Thursday: 9:00am-5:00pm</span><br>
                                        <span>Friday & Saturday: Closed</span><br>
                                        <span>All Govt. Holiday: Closed</span><br>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> More Info
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column  mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4 ">
                                        <img style="width: 70%"
                                             src="https://bidaquickserv.org/assets/images/need_help/oss_help_desk.png"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <b>Oss Help Desk</b><br>
                                        <span>Hi! We're here to answer any questions you may have.</span><br>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> More Info
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column  mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4 ">
                                        <img style="width: 70%"
                                             src="https://bidaquickserv.org/assets/images/need_help/call.png"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <br>
                                        <b><u>Please contact to Call Center</u></b><br>
                                        <span>{{$configuration[1]['value'] ?? ""}}</span><br>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column  mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4 ">
                                        <img style="width: 70%"
                                             src="https://bidaquickserv.org/assets/images/need_help/email.png"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <br>
                                        <b><u>Email to</u></b><br>
                                        <span>{{$configuration[0]['value'] ?? ""}}</span>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column  mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4 ">
                                        <img style="width: 70%"
                                             src="https://bidaquickserv.org/assets/images/need_help/anydesk.png"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <br>
                                        <span>
                                            <a href="https://download.anydesk.com/AnyDesk.exe"> Use Anydesk Software to show your actual problem to help desk officer
                                        </a>
                                        </span>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 d-flex align-items-stretch flex-column  mt-3">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body pt-2">
                                <div class="row">
                                    <div class="col-4 ">
                                        <img style="width: 70%"
                                             src="https://bidaquickserv.org/assets/images/need_help/complain.png"
                                             alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                    <div class="col-8">
                                        <br>
                                        <span><u>Support related complaint email</u></span><br>
                                        <span>sohana@ba-systems.com</span>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <h3> How can I get a verification link again?</h3>
        Please enter your registered email address and submit.<br><br>

        <div class="card card-default p-4">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="text-danger">{{$error}}<br></div>
                @endforeach
            @endif

            {!! Session::has('success') ? '
                <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
            {!! Session::has('error') ? '
            <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
            ' : '' !!}

            {!! Form::open(array('url' => 'users/resend-email-verification','method' => 'post','enctype'=>'multipart/form-data', 'id' => 'verification', 'role'=>'form', 'class'=>'form-inline')) !!}
                <div class="form-group mb-2">
                    Resend Verification Email
{{--                    <label for="staticEmail2" class="sr-only">Email</label>--}}
{{--                    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="email@example.com">--}}
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="inputPassword2" class="sr-only">email</label>
                    <input type="email" name="email" class="form-control" id="inputPassword2" placeholder="email">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                </div>


                <button type="submit" class="btn btn-primary mb-2">Submit</button>
            {!! Form::close()!!}
        </div>
<br>
<br>
        <div class="item-s">
            <h3 id="">Why I need to Sign-up to submit application?</h3>
            <p>
                The One Stop Service (OSS) system is web-based application for investors. This
                service is entirely automated, paperless and cashless. Specifically, this
                service
                shall be requested and rendered electronically via the OSS online platform; all
                required supporting documents shall be transmitted, stored, and processed in
                electronic format; all applications shall be signed electronically; and all
                required
                official payments shall be made electronically in real time in that cases the
                sign
                up mandatory.
            </p>

            <p style="margin-bottom: 0; padding-bottom: 5px;">
                <span>Video tutorial for using the system :</span>
                <a href="https://youtu.be/jsdi3MO__hU">https://youtu.be/jsdi3MO__hU</a>
            </p>
        </div>

        <div class="item-s">
            <h3 id="">How can I get updates on the application status?</h3>
            <p>
                After successfully submission of any application, user can get the current
                status of
                duly submitted application. This action can be seen from the status option:
                <br/><span>Submitted: </span> The stage means that you have submitted
                successfully.
                <br/><span>Verified:</span> The stage belongs that your application has been
                successfully reviewed by BIDA.
                <br/><span>Approved:</span> The stage belongs that you submission has been
                already
                got the approval. You will get an auto generated e‐mail to your authorize
                person’s
                e‐mail address also it to be send to concern stockholder with approval copy.
                <br/><span>Shortfall:</span> The stage means that you have submitted your
                application with required information and BIDA have already reviewed the
                application
                but considered the application as shortfall due to some information or documents
                mismatch or not uploaded correctly. In this stage you will also get an e‐mail
                from
                BIDA as shortfall. You can see the remarks of shortfall the top side of the
                application. If it is information mismatch you are able to edit the application
                and
                re-submit.
                <br/><span>Discard:</span> The stage belongs that you have submitted your
                application with such type of information that cannot be considered as realistic
                or
                have not existence of your provided information or completed your application
                with
                garbage data. BIDA has right to drop your submission request if found any thing
                seems like that.
                <br/><span>Rejected:</span> The stage belongs that you have submitted your
                application with such type of information that cannot be considered as
                realistic.
                BIDA has right to reject your application for any false submission.
            </p>
        </div>

        <div class="item-s">
            <h3 id="technical_support">To whom should I contact for technical support?</h3>
            <p>
                <strong>Business Automation Ltd.</strong> provides technical support for this
                project.
                You can contact with the respective officer for your necessary technical support
                during office hour. <br/>
                <span><span>Call center no.:</span> +8809678771353</span><br/>
                <span><span>Email:</span> support@batworld.com </span><br/>
                <span>Online Support portal:</span>
                <a href="http://support.batworld.com">http://support.batworld.com</a>
            </p>
        </div>
        <div class="item-s">
            <h3 id="supervising_officer">Supervising Officer of One Stop Service (OSS) System
            </h3>

            <span>Jibon Krishna Saha Roy</span><br/>
            <span>Director</span><br/>
            <span>One Stop Service (OSS)</span><br/>
            <span>Bangladesh Investment Development Authority</span><br/>
            <span><span>Phone :</span> +880255007217</span><br/>
            <span><span>Mobile :</span> +8801846740822</span><br/>
            <span><span>Email :</span> dir5.osss@bida.gov.bd</span><br/>
        </div>
        <br>
        <br>
    </div>
@endsection

@section('footer-script')
@endsection


