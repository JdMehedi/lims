<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }

    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .p-r-0 {
        padding-right: 0;
    }

    .m-l-auto {
        margin-left: auto;
    }

    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
    }

    /*.card-magenta:not(.card-outline) > .card-header {*/
    /*    display: inherit;*/
    /*}*/

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 33.33% !important;
    }

    .card-header::after {
        display: none;
    }

    .d\:flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    mr-2 {
        margin-right: 0.5rem;
    }

    .wizard {
        overflow: visible;
    }

    .wizard > .content {
        overflow: visible;
    }

    .wizard > .actions {
        width: 70% !important;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }

    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }

    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }

    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    /*.wizard > .actions {*/
    /*top: -15px;*/
    /*}*/

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

    /*form label {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*.table>thead:first-child>tr:first-child>th {*/
    /*    font-weight: normal;*/
    /*    font-size: 14px;*/
    /*}*/

    /*td {*/
    /*    font-size: 14px;*/
    /*}*/

    .signature-upload-input {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 5;
        background-color: blue;
    }

    .sign_div {
        position: relative;
    }

    .signature-upload {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        padding: 5px 10px;
        font-size: 1rem;
        text-align: center;
        color: #000;
        background-color: #F5FAFE;
        border-radius: 0 !important;
        border: 0;
        height: 160px;
        /*box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);*/
        font-weight: 400;
        outline: 1px dashed #ccc;
        margin-bottom: 5px;
    }

    .custom_error {
        outline: 1px dashed red;
    }

    .email {
        font-family: Arial !important;
    }

    @media (min-width: 481px) {
        .panel-body {
            padding: 0 15px;
        }
    }

    @media (max-width: 480px) {
        .wizard > .actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard > .content > .body label {
            margin-top: .5em;
            margin-bottom: 0;
        }

        .tabInput {
            width: 120px;
        }

        .tabInput_sm {
            width: 75px;
        }

        .tabInputDate {
            width: 150px;
        }

        .table_responsive {
            overflow-x: auto;
        }

        /*.bootstrap-datetimepicker-widget {*/
        /*    position: relative !important;*/
        /*    top: 0 !important;*/
        /*}*/
        .prevMob {
            margin-top: 45px;
        }

        /*.wizard > .actions {*/
        /*display: block !important;*/
        /*width: 100% !important;*/
        /*text-align: center;*/
        /*}*/
    }

    wizard>.steps>ul>li {
      width: 33.2% !important;
    }

    @media (min-width: 576px) {
        .modal-dialog-for-condition {
            max-width: 1200px !important;
            margin: 1.75rem auto;
        }
    }

    .tbl-custom-header {
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }

    .card-header {
        border-bottom: 0px;
    }

</style>

<div class="row">
    <div class="col-md-12 col-lg-12" id="inputForm">
        @if (in_array($appInfo->status_id, [5, 6]))
            @include('ProcessPath::remarks-modal')
        @endif
        {{-- Industry registration --}}
        <div class="card border-magenta" style="border-radius: 10px; ">
            <div class="card-header d:flex justify-between">
            <h4 class="card-header">Application for ITC License Renew</h4>

                <div class="clearfix">
                    @if (in_array($appInfo->status_id, [5]))
                        <div class="col-md-12">
                            <div class="float-right" style="margin-right: 1%;">
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye mr-2"></i>Reason of ' . $appInfo->status_name . '', ['type' => 'button', 'class' => 'btn btn-md btn-secondary', 'style'=>'white-space: nowrap;']) !!}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                {!! Form::open([
                        'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                        'method' => 'post',
                        'class' => 'form-horizontal',
                        'id' => 'application_form',
                        'enctype' => 'multipart/form-data',
                        'files' => 'true'
                    ])
                !!}
                @csrf
                <div style="display: none;" id="pcsubmitadd"></div>
{{--                <input type="hidden" id="openMode" name="openMode" value="edit">--}}
{{--                {!! Form::hidden('app_id','', ['class' => 'form-control input-md', 'id' => 'app_id']) !!}--}}
{{--                {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}--}}

                {!! Form::hidden('issue_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}

                {{--Basic Information--}}
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.licenseInfo', [ 'mode' => 'renew-serarch', 'url' => 'itc-license-renew/fetchAppData']){{--                    @includeIf('common.subviews.licenseInfo', ['mode' => 'default'])--}}

                    {{-- Company info --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1 ])

                    @includeIf('common.subviews.ContactPerson', ['mode' => 'renew', 'extra' => ['address2'], 'selected' => 1])

                    @includeIf('common.subviews.Shareholder', ['mode' => 'renew'])

                </fieldset>


                {{--Attachment Declration--}}
                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    @includeIf('common.subviews.RequiredDocuments', ['mode' => 'edit'])
                    {{--Required Documents--}}
                    {{--                    <div class="card card-magenta border border-magenta">--}}
                    {{--                        <div class="card-header" id="reqDoc">--}}
                    {{--                            Required Documents for attachment--}}
                    {{--                        </div>--}}
                    {{--                        <div class="card-body" style="padding: 15px 25px;">--}}
                    {{--                            <input type="hidden" id="doc_type_key" name="doc_type_key">--}}
                    {{--                            <div id="docListDiv"></div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--Declaration--}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Declaration
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <ol>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any application for any license of the applicant/any share holder/partner been rejected before?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q1', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q1', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div style="margin-top: 20px;">
                                                <div class="row" id="declaration_q1_date_row" style="display: none;">
                                                    <div class="col-md-6">
                                                        <div class="row form-group">
                                                            {!! Form::label('declaration_q1_date', 'Date of Application', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-6{{ $errors->has('declaration_q1_date') ? 'has-error' : '' }}">
                                                                {!! Form::date('declaration_date_of_application', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                                                                {!! $errors->first('declaration_q1_date', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="if_declaration_q1_yes" style="display: none;">
                                                    <div class="col-md-12">
                                                        <div class="row form-group">
                                                            {{ Form::textarea('declaration_q1_textarea', null, array('class' =>'form-control input required', 'placeholder'=>'Please give details reasons for rejection', 'rows' =>3, '' => '' ,'required'))}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <br/>
                                        <li class="required-star !font-normal">
                                            <label class="!font-normal">
                                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the Commission?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q2', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div class="row">
                                                {!! Form::textarea('declaration_q2__textarea',null, ['class' =>'form-control input required', 'rows' => '5', 'placeholder' => 'Please give detail reasons for rejection.', 'id'=>'if_declaration_q2_yes']) !!}
                                            </div>
                                        </li>
                                        <li>
                                            <label class="!font-normal">
                                                Has any other License of the applicant/any shareholder has been rcjected before?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q3', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q3', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div style="margin-top: 20px;">
                                                <div class="row" id="declaration_q3_date_row" style="display: none;">
                                                    <div class="col-md-6">
                                                        <div class="row form-group">
                                                            {!! Form::label('declaration_q3_date', 'Date of Application', ['class' => 'col-md-4']) !!}
                                                            <div class="col-md-6{{ $errors->has('declaration_q3_date') ? 'has-error' : '' }}">
                                                                {!! Form::date('declaration_q3__date_of_application', '', ['class' => 'form-control', 'placeholder' => '']) !!}
                                                                {!! $errors->first('declaration_q3_date', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="if_declaration_q3_yes" style="display: none;">
                                                    <div class="col-md-12">
                                                        <div class="row form-group">
                                                            {{ Form::textarea('declaration_q3_textarea', null, array('class' =>'form-control input required', 'placeholder'=>'Please give details reasons for rejection', 'rows' =>3, '' => '' ,'required'))}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <br/>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant/ its owner(s)/ any of its director(s)/ partner(s) were involved in any illegal call termination?
                                            </label>
                                            <div style="margin-top: 20px; margin-left: 20px;">
                                                {{ Form::radio('declaration_q4', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}
                                                {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q4', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}
                                                {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <br/>
                                            <div style="margin-top: 20px;">
                                                <div id="if_declaration_q4_yes" style="display: none;">
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q4_period_of_indestment', 'Period of Tnvolvement in illegal activitics', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q4_period_of_indestment') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q4_period_of_involvement', '', ['class' => 'form-control', 'placeholder' => 'Enter License Number']) !!}
                                                                    {!! $errors->first('declaration_q4_period_of_indestment', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('declaration_q5_case_no', 'Case No', ['class' => 'col-md-4']) !!}
                                                                <div class="col-md-8{{ $errors->has('declaration_q4_case_no') ? 'has-error' : '' }}">
                                                                    {!! Form::text('declaration_q5_case_no', '', ['class' => 'form-control', 'placeholder' => 'Enter License Number']) !!}
                                                                    {!! $errors->first('declaration_q4_case_no', '<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border">Administrative fine paid to the Commission</legend>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row form-group">
                                                                    {!! Form::label('declaration_q4_amount', 'Amount', ['class' => 'col-md-4']) !!}
                                                                    <div class="col-md-8{{ $errors->has('declaration_q4_amount') ? 'has-error' : '' }}">
                                                                        {!! Form::number('declaration_q4_amount', '', ['class' => 'form-control', 'placeholder' => 'Enter amount']) !!}
                                                                        {!! $errors->first('declaration_q4_amount', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row form-group">
                                                                    {!! Form::label('declaration_q4_cheque_or_bank_draft', 'Cheque No./ Bank Draft No', ['class' => 'col-md-4']) !!}
                                                                    <div class="col-md-8{{ $errors->has('declaration_q4_cheque_or_bank_draft') ? 'has-error' : '' }}">
                                                                        {!! Form::number('declaration_q4_cheque_or_bank_draft', '', ['class' => 'form-control', 'placeholder' => 'Enter check or draft no.']) !!}
                                                                        {!! $errors->first('declaration_q4_cheque_or_bank_draft', '<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <div class="row" >
                                                        <div class="col-md-6">
                                                            <div class="row form-group">
                                                                {!! Form::label('', 'Undertaking given to the Commission', ['class' => 'col-md-8']) !!}
                                                                <div class="col-md-4">
                                                                    {{ Form::radio('declaration_q4_given_commision', 'Yes', '', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_given_commision_yes']) }}
                                                                    {{ Form::label('declaration_q4_given_commision_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                                    {{ Form::radio('declaration_q4_given_commision', 'No', '', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_given_commision_no']) }}
                                                                    {{ Form::label('declaration_q4_given_commision_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                        </li>
                                        <br/>
                                        <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>

                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                                        <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                {{--Payment and Submit--}}
                <h3> {{ ($appInfo->status_id == 5 )? 'Re-Submit' : 'Payment & Submit' }}</h3>
                <fieldset>
                    {{-- Service Fee Payment --}}
                    @if($appInfo->status_id != 5)
                        <div id="payment_panel"></div>
                    @elseif(($appInfo->status_id == 5) && (isset($appInfo->is_pay_order_verified) && $appInfo->is_pay_order_verified === 0))
                        <div id="payment_panel"></div>
                    @endif

                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms','required']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="float-left">
                    <a href="{{ url('client/itc-license-issue/list/'. Encryption::encodeId(55)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    @if(!in_array($appInfo->status_id,[5]))
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                class="btn btn-success btn-md"
                                value="submit" name="actionBtn" onclick="openPreviewV2()">Submit
                        </button>
                    @endif

                    @if($appInfo->status_id == 5)
                        <button type="button" id="submitForm"
                                style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                class="btn btn-info btn-md" onclick="ReSubmitForm()"
                                value="Re-submit" name="actionBtn">Re-submit
                        </button>
                    @endif
                </div>

                @if(!in_array($appInfo->status_id,[5]))
                    <button type="submit" class="btn btn-info btn-md cancel" style="margin-left: 20px;" value="draft"
                            name="actionBtn"
                            id="save_as_draft">Save as Draft
                    </button>
                @endif
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

<div id="terms_and_conditions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">

        <!-- Modal content for terms_and_conditions_modal-->
        <div class="modal-content">
            <div class="modal-dialog-for-condition">
                {{-- Terms and Conditions --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Terms and Conditions <span style="float: right;"><button type="button" class="close"
                                                                                 data-dismiss="modal"
                                                                                 style="color: #fff; font-size: 16px;">&times;</button></span>
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>
                                        The licensee shall have to apply before 180 (one hundred and eighty) days of the
                                        expiration of duration of its license or else the license shall be cancelled as
                                        per law and penal action shall follow, if the licensee continues its business
                                        thereafter without valid license. The late fees/fines shall be recoverable under
                                        the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to
                                        submit the fees and charges to the Commission in due time.
                                    </li>
                                    <li>
                                        Application without the submission of complete documents and information will
                                        not be accepted.
                                    </li>
                                    <li>Payment should be made by a Pay order/Demand Draft in favour of Bangladesh
                                        Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                                    <li>For New Applicant only A, B and E will be applicable.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>
    if (typeof company_type === 'undefined') {
            let company_type = "{{$companyInfo->org_type}}";
        }

    if (company_type == "") {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('I/We');
    } else if (company_type == 3) {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('I');
    } else {
        $('.i_we_dynamic').text('');
        $('.i_we_dynamic').text('We');
    }


    // Registered Office Address
    @isset($appInfo->reg_office_district)
    getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
        'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
    @endisset
    // Operational Office Address
    @isset($appInfo->op_office_district)
    getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
        'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
    @endisset

    // Applicant Profile
    @isset($appInfo->applicant_district)
    getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
        'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
    @endisset

    // Contact Information
    @isset($contact_person)
    @foreach($contact_person as $index => $person)
    @if(empty($person->district) || empty($person->upazila))
    @continue
    @endif
    getThanaByDistrictId('contact_district_{{$index}}', {{ $person->district ?? ''}},
        'contact_thana_{{$index}}', {{ $person->upazila ?? '' }});
    @endforeach
    @endisset

    @if($appInfo->declaration_q1 == 'Yes')
    $('#if_declaration_q1_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q2 == 'Yes')
    $('#if_declaration_q2_yes').css('display', 'block');
    @endif

    @if($appInfo->declaration_q3 == 'Yes')
    $('#if_declaration_q3_yes').css('display', 'block');
    @endif

    let selectCountry = '';
    $(document).ready(function () {
        // jquery step functionality
        let form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                let errorStatus = false;
                if (newIndex == 1) {
                    let total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        // alert("The value of the '% of share field' should be a total of 100.");
                        new swal({
                            type: 'error',
                            text: 'The value of the % of share field should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        errorStatus = true;
                    }
                }



                $(".shareholder_nationality").each(function (){
                    let id = $(this).attr('id');
                    let countryCode = $(this).val();
                    console.log(countryCode);
                    let lastId = id.split('_')[2];
                    if(countryCode == 18){
                        let nidValue = $("#shareholder_nid_"+lastId).val();
                        if(nidValue.length == 10 || nidValue.length == 13 || nidValue.length == 17){
                            errorStatus = false;
                        }else{
                            new swal({
                                type: 'error',
                                text: 'Please provide valid NID number.',
                            });
                            $(this).addClass('error')
                            errorStatus = true;
                        }
                    }

                });

                if (newIndex == 3) {
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {

                            } else {
                                new swal({
                                    type: 'error',
                                    text: 'Please answer the Declaration section all question.',
                                });
                                errorStatus = true;
                            }
                        } else {
                            new swal({
                                type: 'error',
                                text: 'Please answer the Declaration section all question.',
                            });
                            errorStatus = true;
                        }
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }
                }

                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                if (currentIndex > newIndex) return true;
                form.validate().settings.ignore = ":disabled,:hidden";
                if (!form.valid()) errorStatus = true;
                if (errorStatus) return false;
                return true;
            },
            onStepChanged: function (event, currentIndex, priorIndex) {

            if (currentIndex > 0) {
                form.find('#submitForm').css('display', 'none');
                $('.actions > ul > li:first-child').attr('style', '');
            } else {
                $('.actions > ul > li:first-child').attr('style', 'display:none');
            }
            if (currentIndex === 1) {
                form.find('#submitForm').css('display', 'none');
            }
            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2) {
                form.find('#submitForm').css('display', 'inline');
                form.steps("next");
            }
            else {
                form.find('#submitForm').css('display', 'none');
                $('ul[aria-label=Pagination] input[class="btn"]').remove();
                form.find('.previous').removeClass('prevMob');
            }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
                confirm: {
                    equalTo: "#password-2"
                }
            }
        });

        let popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")) {
                $('#accaccept_termseptTerms').removeClass('error');
                $('#accept_terms').next('label').css('color', 'black');
                $('body').css({"display": "none"});
                $("form").submit();
            } else {
                $('#acceptTerms').addClass('error');
                return false;
            }
        });

        $('.finish').on('click', function (e) {
            if (isCheckedAcceptTerms()) return false;
            openPreviewV2();
        });

        $("#equipment_edit").click(function () {
            if (this.checked) makeReadWriteByDivId('equipment_tbl');
            else makeReadOnlyByDivId('equipment_tbl');

            if(this.checked)
            {
                $("#equipment_tbl button").removeClass('input_disabled')
            }else{
                $("#equipment_tbl button").addClass('input_disabled')
            }
        });


        $("#tariff_edit").click(function () {
            if (this.checked) makeReadWriteByDivId('tariffChart_tbl');
            else makeReadOnlyByDivId('tariffChart_tbl');

            if(this.checked)
            {
                $("#tariffChart_tbl button").removeClass('input_disabled')
            }else{
                $("#tariffChart_tbl button").addClass('input_disabled')
            }
        });

        $('#type_of_isp_licensese').on('change', function () {

            if(this.value == 1 || this.value == ""){
                $('#division').css('display','none');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }
            if(this.value == 2){
                $('#division').css('display','inline');
                $('#district').css('display','none');
                $('#thana').css('display','none');
            }

            if(this.value == 3){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#thana').css('display','none');
            }

            if(this.value == 4){
                $('#division').css('display','none');
                $('#district').css('display','inline');
                $('#thana').css('display','inline');
            }
        });

        function attachmentLoad() {
            let reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            let company_type_id = parseInt($("#company_type_id").val()); //order 2
            let industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            let investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            let key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id

            $("#doc_type_key").val(key);

            loadApplicationDocs('docListDiv', key);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');

        let today = new Date();
        let yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerProject').datetimepicker({
            viewMode: 'years',
            format: 'DD-MM-YYYY',
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: '01/01/' + (yyyy + 20),
            minDate: '01/01/1905'
        })

        // Bangla step number
        $(".wizard>.steps .number").addClass('input_ban');


        $(function () {
            let today = new Date();
            let yyyy = today.getFullYear();

            $('.datetimepicker4').datetimepicker({
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110),
                ignoreReadonly: true,
            });
        });

        //form & field operation
        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
        });
        $("#declaration_q1_no").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'none');
        });

        $("#declaration_q2_yes").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'inline');
        });
        $("#declaration_q2_no").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'none');
        });

        $("#declaration_q3_yes").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'inline');
        });
        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
        });

        // nationality
        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];
            console.log(lastRowId);
            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#passportBlock_' + lastRowId).hide();
                $('#shareholder_nid_' + lastRowId).addClass('required');
                $('#shareholder_passport_' + lastRowId).removeClass('required');
            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#passportBlock_' + lastRowId).show();
                $('#shareholder_passport_' + lastRowId).addClass('required');
                $('#shareholder_nid_' + lastRowId).removeClass('required');
            }
        });

        let old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$appInfo->org_type}}";
        if (company_type == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (company_type == 1) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        $('.add_row').click(function () {
            let btn = $(this);
            btn.prop("disabled", true);
            btn.after('<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');
            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);
            $.ajax({
                async: false,
                type: "POST",
                url: "{{ url('itc-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                    btn.prop("disabled", false);
                }
            });
            getHelpText();

        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });


    });

    function mobile_no_validation(id) {
        var onlyNumber = $("#" + id).val();
        var countryCode = $("#" + id).intlTelInput("getSelectedCountryData").dialCode ?? "880";
        var regex = /^01[0-9]{9}$/;
        var result = regex.test(onlyNumber);

        if (countryCode === '880') {
            if (onlyNumber === "") {
                console.log('label:', 0, `#${id}`)
                $(`#${id}`).addClass('error');
            } else if (result === false) {
                console.log('label:', 1)
                $(`#${id}`).addClass('error');
            } else {
                console.log('label:', 2)
                $(`#${id}`).removeClass('error');
            }
        }
    }

    function generateBase64String(source, destination) {
        const inputfile = document.getElementById(source);
        const file = inputfile.files[0];
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            document.getElementById(destination).value = reader.result;
        };
        reader.onerror = function (error) {
            console.log(error);
        };
    }

    // display payment panel
    if (typeof fixed_amounts == 'undefined') {
        const fixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
}
    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0
        });

    function openPreview() {
        if (isCheckedAcceptTerms()) return false;
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function openPreviewV2() {
        if (isCheckedAcceptTerms()) return false;

        let preview = 1;
        // If select online payment
        const onlinePayment = "{{config('payment.online_payment')}}";
        if ($("#online_payment").is(':checked') && !onlinePayment) {
            new swal({
                title: 'Online Payment',
                text: 'Online Payment is not available now. Please proceed to Pay Order.',
            });
            return;
        }
        // field validation
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if(!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }
        if(preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function isCheckedAcceptTerms() {
        var checkbox = document.querySelector('#accept_terms');
        var errorStatus = false;
        if (!checkbox.checked) {
            checkbox.classList.add('error');
            errorStatus = true;
        } else {
            checkbox.classList.remove('error');
            errorStatus = false;
        }
        return errorStatus;
    }

    function deleteContactRow(element) {
        element.remove();
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }

    function ReSubmitForm(){
        if (isCheckedAcceptTerms()) return false;
        document.querySelector("#application_form").submit();
    }

</script>

