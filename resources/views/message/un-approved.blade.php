@if(Auth::user()->is_approved != 1 or Auth::user()->is_approved !=true)

    <div class="row">
        <div class="col-sm-9">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h5><strong>Please see this instruction</strong></h5>
                </div>
                <div class="panel-body">
                    <strong class="text-danger">
                        <br/>
                        Dear {!! \App\Libraries\CommonFunction::getUserFullName() !!},
                        <br/>
                        <br/>
                        <p>
                            Your account is awaiting approval by the {!! config('app.project_name') !!} system administrator.
                            You will not be able to fully interact
                            with all features of this system until your account is approved.
                            <br/>
                            Kindly contact to System Administrator or IT Help Desk officer to approve your account.
                            Once approved or denied you will received an email notice.
                            <br/>
                            You will get all the available functionality once your account is approved!
                        </p>
                        <br/>
                        Thank you!<br/>
                        {{config('app.project_name')}}
                    </strong>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <img width="200" src="{{ url('assets/images/alarm_clock_time_bell_wait-512.png') }}">
        </div>
    </div>
@endif