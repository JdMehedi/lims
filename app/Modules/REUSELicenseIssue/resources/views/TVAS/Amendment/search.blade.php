<link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" />
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .input_disabled {
        background-color: #E9ECEF;
        pointer-events: none;
    }
    .cross-button {
        float:right;
        padding: 0rem .250rem !important;
    }
    .areaAddress::after {
        display: none;
    }
    .addAreaAddressRow {
        pointer-events: none;
    }
    .section_head{
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }
    .wizard>.steps>ul>li {
        width: 25% !important;
    }

    .wizard {
        overflow: visible;
    }

    .wizard>.content {
        overflow: visible;
    }

    .wizard>.actions {
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

    #total_fixed_ivst_million {
        pointer-events: none;
    }

    .col-centered {
        float: none;
        margin: 0 auto;
    }

    .radio {
        cursor: pointer;
    }

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
        .wizard>.actions {
            width: 55% !important;
            position: inherit;
        }

        .panel-body {
            padding: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .wizard>.content>.body label {
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

    .wizard>.steps>ul>li {
        width: 33.33% !important;
    }

    @media (min-width: 576px){
        .modal-dialog-for-condition {
            max-width: 1200px!important;
            margin: 1.75rem auto;
        }
    }

    .border-header-box{
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }
    .border-header-txt{
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
    }

    /*#renewForm input[type=checkbox]{*/
    /*    vertical-align: bottom;*/
    /*    width: 15px;*/
    /*    height: 15px;*/
    /*}*/
    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-md-12 col-lg-12" id="AmendmentForm">
        <div class="card border-magenta" style="border-radius: 10px; ">
            <h4 class="card-header">Application for TVAS License Amendment</h4>
            <div class="card-body">
                    {!! Form::open([
                                     'url' => url('process/license/store/'.\App\Libraries\Encryption::encodeId($process_type_id)),
                                     'method' => 'post',
                                     'class' => 'form-horizontal',
                                     'id' => 'application_form',
                                     'enctype' => 'multipart/form-data',
                                     'files' => 'true',
                                 ]) !!}
                    @csrf
                    <div style="display: none;" id="pcsubmitadd">

                        <input type="hidden" id="openMode" name="openMode" value="edit">
                        {{--        {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md', 'id' => 'app_id']) !!}--}}
                        {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}

                        {!! Form::hidden('license_no', $license_no, ['class' => 'form-control input-md required', 'id' => 'license_no']) !!}

                        {!! Form::hidden('issue_tracking_no', \App\Libraries\Encryption::encodeId($appInfo->tracking_no), ['class' => 'form-control input-md', 'id' => 'issue_tracking_no']) !!}


                    </div>
                    <h3>Basic Information</h3>
                    <fieldset>
                        {{-- Company Informaiton --}}
                        @includeIf('common.subviews.CompanyInfo', ['mode' => 'amendment', 'selected' => 1])

                        {{-- Applicant Profile --}}
                        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'amendment', 'selected' => 1])

                        {{-- Name of Authorized Signatory and Contact Person --}}
                        @includeIf('common.subviews.ContactPerson', ['mode' => 'amendment', 'selected' => 1])

                        {{-- Shareholder --}}
                        @includeIf('common.subviews.Shareholder', ['mode' => 'amendment', 'selected' => 1])

                    </fieldset>

                    <h3>Attachment, Declaration</h3>
                    <br>
                    <fieldset>
                        {{-- Necessary attachment --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Required Documents for attachment
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">
                                <input type="hidden" id="doc_type_key" name="doc_type_key">
                                <div id="docListDiv"></div>
                            </div>
                        </div>

                        {{-- Declaration --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header d-flex justify-content-between areaAddress">
                                <div>Declaration</div>
                                <div>
                                    <label class="amendmentEditBtn">
                                        <input type="checkbox" id="declarations"/>
                                        EDIT
                                    </label>
                                </div>
                            </div>

                            <div class="card-body" id="declarationFields" style="padding: 15px 25px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ol>
                                            <li>
                                                <div class="required-star !font-normal">
                                                    Has any application for any license of the applicant/any share
                                                    holder/partner been rejected before?
                                                </div>

                                                <div style="margin: 5px 0; margin-bottom: 10px;" id="declaration_q1">
                                                    {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div id="if_declaration_q1_yes" style="display: none">
                                                    <div class="form-group row" style="margin-top:10px;">
                                                        {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                                        <div
                                                            class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">
                                                            <div class="input-group date datetimepicker4"
                                                                 id="declaration_q1_application_date_picker"
                                                                 data-target-input="nearest">
                                                                {!! Form::text('declaration_q1_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q1_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q1_application_date):'', ['class' => 'form-control', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#declaration_q1_application_date_picker"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="margin-top: 20px;">
                                                        {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input input_disabled', 'id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                                    </div>
                                                </div>


                                            </li>
                                            <li>
                                                <div class="required-star !font-normal">
                                                    Do the Applicant/any Share Holder/ Partner hold any other Operator
                                                    License/ Registration Certificate from the Commission?
                                                </div>

                                                <div style="margin: 5px 0; margin-bottom: 10px;" id="declaration_q2">
                                                    {{ Form::radio('declaration_q2', 'Yes',$appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div style="margin: 5px;display: none" id="if_declaration_q2_yes" >
                                                    {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input required input_disabled', 'id'=>'declaration_q2_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5,))}}
                                                </div>

                                            </li>
                                            <li>
                                                <div class="required-star !font-normal">
                                                    Has any other License/ Registration Certificate of the Applicans/ any
                                                    Share Holder/ Partner been rejected before?
                                                </div>

                                                <div style="margin: 5px 0;" id="declaration_q3">
                                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div id="if_declaration_q3_yes" style="display: none">
                                                    <div class="form-group row" style="margin-top:10px;">
                                                        {!! Form::label('declaration_q3_application_date', 'Date of Application', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                                        <div
                                                            class="col-md-4 {{ $errors->has('declaration_q3_application_date') ? 'has-error' : '' }}">
                                                            <div class="input-group date datetimepicker4"
                                                                 id="declaration_q3_application_date_picker"
                                                                 data-target-input="nearest">
                                                                {!! Form::text('declaration_q3_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q3_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q3_application_date):'', ['class' => 'form-control', 'id' => 'declaration_q3_application_date','placeholder'=> date('d-M-Y') ]) !!}
                                                                <div class="input-group-append"
                                                                     data-target="#declaration_q3_application_date_picker"
                                                                     data-toggle="datetimepicker">
                                                                    <div class="input-group-text"><i
                                                                            class="fa fa-calendar"></i></div>
                                                                </div>
                                                                {!! $errors->first('declaration_q3_application_date', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="margin: 5px 0;">
                                                        {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input required input_disabled','placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                                    </div>
                                                </div>


                                            </li>
                                            <li>
                                                <div class="required-star !font-normal">
                                                    Were the Applicants/ its owner(s)/ any of its director(s)/ partner(s)
                                                    involved in any illegal call termination?
                                                </div>

                                                <div style="margin-top: 20px;" id="declaration_q4">
                                                    {{ Form::radio('declaration_q4', 'Yes', $appInfo->declaration_q4 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}
                                                    {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                    {{ Form::radio('declaration_q4', 'No', $appInfo->declaration_q4 == 'No' ? true : false, ['class'=>'form-check-input','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}
                                                    {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                </div>
                                                <div id="if_declaration_q4_yes" style="display: none">
                                                    <div class="form-group row" style="margin-top:10px;">
                                                        {!! Form::label('q4_license_number', 'Period of Involvement in illegal activities', ['class' => 'col-md-4 required-star', 'style' => 'font-weight:400' ]) !!}
                                                        <div
                                                            class="col-md-3 {{ $errors->has('q4_license_number') ? 'has-error' : '' }}">
                                                            {!! Form::text('q4_license_number',  $appInfo->q4_license_number, ['class' => 'form-control', 'id' => 'q4_license_number', 'placeholder'=> 'Enter License Number']) !!}
                                                            {!! $errors->first('q4_license_number', '<span class="help-block">:message</span>') !!}
                                                        </div>

                                                        {!! Form::label('q4_case_no', 'Case No', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                                        <div
                                                            class="col-md-3 {{ $errors->has('q4_case_no') ? 'has-error' : '' }}">
                                                            {!! Form::text('q4_case_no',  $appInfo->q4_case_no, ['class' => 'form-control', 'id' => 'q4_case_no']) !!}
                                                            {!! $errors->first('q4_case_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 border border-header-box">
                                                            <span class="border-header-txt">Administrative fine paid to the Commission</span>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('q4_amount', 'Amount', ['class' => 'col-md-4 required-star','style'=> 'font-weight: 400']) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('q4_amount') ? 'has-error' : '' }}">
                                                                            {!! Form::number('q4_amount',  $appInfo->q4_amount, ['class' => 'form-control', 'id' => 'q4_amount']) !!}
                                                                            {!! $errors->first('q4_amount', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {!! Form::label('q4_bank_draft_no', 'Cheque No./ Bank Draft No', ['class' => 'col-md-4 required-star' ,'style'=> 'font-weight: 400' ]) !!}
                                                                        <div
                                                                            class="col-md-8 {{ $errors->has('q4_bank_draft_no') ? 'has-error' : '' }}">
                                                                            {!! Form::text('q4_bank_draft_no', $appInfo->q4_bank_draft_no, ['class' => 'form-control', 'id' => 'q4_bank_draft_no']) !!}
                                                                            {!! $errors->first('q4_bank_draft_no', '<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" style="margin-top:10px;"
                                                    >
                                                        {!! Form::label('q4_given_comission', 'Undertaking given to the Commission', ['class' => 'col-md-5 required-star', 'style' => 'font-weight:400' ]) !!}
                                                        <div
                                                            class="col-md-6 {{ $errors->has('given_comission') ? 'has-error' : '' }}" id="q4_given_comission">
                                                            {{ Form::radio('q4_given_comission', 'Yes', $appInfo->q4_given_comission == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id'=> '' ]) }}
                                                            {{ Form::label('given_comission_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                            {{ Form::radio('q4_given_comission', 'No', $appInfo->q4_given_comission == 'No' ? true : false, ['class'=>'form-check-input','style'=>'display: inline;  margin-left:10px;', 'id'=> '' ]) }}
                                                            {{ Form::label('given_comission_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                                            {!! $errors->first('q4_given_comission', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>


                                                </div>


                                            </li>


                                            <li>
                                                <span class="i_we_dynamic">I/We</span> hereby certify that <span
                                                    class="i_we_dynamic">I/We</span> have carefully read the
                                                guidelines/terms and conditions, for the license and <span
                                                    class="i_we_dynamic">I/We</span> undertake to comply with the terms and
                                                conditions therein.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby
                                                certify that <span class="i_we_dynamic">I/We</span> have carefully read the
                                                section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span
                                                    class="i_we_dynamic">I/We</span> are not disqualified from obtaining the
                                                license.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                                that any information furnished in this application are found fake or false
                                                or this application form is not duly filled up, the Commission, at any time
                                                without any reason whatsoever, may reject the whole application.
                                            </li>
                                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand
                                                that if at any time any information furnished for obtaining the license is
                                                found incorrect then the license if granted on the basis of such application
                                                shall deemed to be cancelled and shall be liable for action as per
                                                Bangladesh Telecommunication Regulation Act, 2001.
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Terms and Conditions --}}
                        {{--        <div class="card card-magenta border border-magenta">--}}
                        {{--            <div class="card-header">--}}
                        {{--                Terms and Conditions--}}
                        {{--            </div>--}}
                        {{--            <div class="card-body" style="padding: 15px 25px;">--}}

                        {{--                <div class="row">--}}
                        {{--                    <div class="col-md-12">--}}
                        {{--                        {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'accept_terms','required']) }}--}}
                        {{--                        {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:25px;']) }}--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--        </div>--}}
                    </fieldset>

                    <h3>Submit</h3>
                    <fieldset>
                        {{-- Service Fee Payment --}}
                        {{--        <div id="payment_panel"></div>--}}

                        {{-- Terms and Conditions --}}
                        <div class="card card-magenta border border-magenta">
                            <div class="card-header">
                                Terms and Conditions
                            </div>
                            <div class="card-body" style="padding: 15px 25px;">

                                <div class="row">
                                    <div class="col-md-12">

                                        {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                        {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:20px;']) }}
                                        {{--                                    <a href="#" data-toggle="modal" data-target="#terms_and_conditions_modal">Terms and Conditions.</a>--}}
                                        {{--                                    <a href="#" data-toggle="modal" data-target="#otp_modal" class="btn btn-info btn-block btn-md"><strong><i--}}
                                        {{--                                                class=" fa fa-lock "></i> Login with--}}
                                        {{--                                            OTP</strong></a>--}}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </fieldset>

                    <div class="float-left">
                        <a href="{{ url('client/tvas-license-ammendment/list/'. Encryption::encodeId(27)) }}"
                           class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                           id="save_as_draft">Close
                        </a>
                    </div>

                    <div class="float-right">
                        @if(!in_array($appInfo->status_id,[5]))
                            <button type="button" id="submitForm"
                                    style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                                    class="btn btn-success btn-md"
                                    value="submit" onclick="openPreview()" name="actionBtn">Submit
                            </button>
                        @elseif($appInfo->status_id == 5)
                            <button type="button" id="submitForm"
                                    style="margin-right: 180px; position: relative; z-index: 99999; display: none; cursor: pointer;"
                                    class="btn btn-info btn-md"
                                    value="Re-submit" onclick="ReSubmitForm()" name="actionBtn">Re-submit
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


<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset("assets/plugins/jquery-steps/jquery.steps.js") }}"></script>

@include('partials.image-upload')

<script>

    var selectCountry = '';
    $(document).ready(function() {

        // Registered Office Address
{{--        @isset($appInfo->reg_office_district)--}}
{{--        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},--}}
{{--            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});--}}
{{--        @endisset--}}
{{--        // Operational Office Address--}}
{{--        @isset($appInfo->noc_district)--}}
{{--        getThanaByDistrictId('noc_district', {{ $appInfo->noc_district ?? '' }},--}}
{{--            'noc_thana', {{ $appInfo->noc_thana ?? '' }});--}}
{{--        @endisset--}}

        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset

        // Operational Office Address
        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset

        @isset($appInfo->applicant_district)
        getThanaByDistrictId('applicant_district', {{ $appInfo->applicant_district ?? '' }},
            'applicant_thana', {{ $appInfo->applicant_thana ?? '' }});
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

        @if($appInfo->declaration_q4 == 'Yes')
        $('#if_declaration_q4_yes').css('display', 'block');
        @endif


        var form = $("#application_form").show();
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 1) {
                    //return true;
                    var total=0;
                    $('.shareholder_share_of', 'tr').each(function() {
                        total += Number($(this).val()) || 0;
                    });
                    if(total != 100){
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }


                    // Allways allow previous action even if the current form is not valid!
                    console.log(currentIndex,newIndex)
                    if (currentIndex > newIndex) {
                        return true;
                    }

                }

                if (newIndex == 2) {
                    let errorStatus = false;
                    // if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                    //     if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                    //         if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                    //             if ($("#declaration_q4_yes").is(":checked") || $("#declaration_q4_no").is(":checked")) {
                    //                 errorStatus = false;
                    //             }else{
                    //                 new swal({
                    //                     type: 'error',
                    //                     text: 'Please answer the Declaration section all question.',
                    //                 });
                    //                 errorStatus = true;
                    //             }
                    //         }else{
                    //             new swal({
                    //                 type: 'error',
                    //                 text: 'Please answer the Declaration section all question.',
                    //             });
                    //             errorStatus = true;
                    //         }
                    //     }else{
                    //         new swal({
                    //             type: 'error',
                    //             text: 'Please answer the Declaration section all question.',
                    //         });
                    //         errorStatus = true;
                    //     }
                    // }else{
                    //     new swal({
                    //         type: 'error',
                    //         text: 'Please answer the Declaration section all question.',
                    //     });
                    //     errorStatus = true;
                    // }

                    // if(errorStatus) return false;

                    if (currentIndex > newIndex) {
                        return true;
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

                if(newIndex < currentIndex) return true;

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
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
                console.log('finished');
                return false;
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

        var popupWindow = null;
        $('.submitForm').on('click', function (e) {

            e.preventDefault();

            if ($('#accept_terms').is(":checked")){
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
            if(!$('#accept_terms').prop('checked')){
                $('#accept_terms').focus()
                $('#accept_terms').addClass('error');
                return false;
            }
            $('#accept_terms').removeClass('error');
            popupWindow = window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>', 'Sample', '');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;

            $("#doc_type_key").val(key);

            // loadApplicationDocs('docListDiv', key);
            loadApplicationDocs('docListDiv', null);
        }
        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function() {

        var today = new Date();
        var yyyy = today.getFullYear();

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

        {{-- initail -input mobile plugin script start --}}
        $("#company_office_mobile").intlTelInput({
            hiddenInput: "company_office_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#company_factory_mobile").intlTelInput({
            hiddenInput: "company_factory_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#company_ceo_mobile").intlTelInput({
            hiddenInput: "company_ceo_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#auth_person_mobile").intlTelInput({
            hiddenInput: "auth_person_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        {{-- initail -input mobile plugin script end --}}

    })

</script>

<script>
    var selectCountry = '';

    $(document).ready(function() {

        var check = $('#same_address').prop('checked');
        if ("{{ isset($companyInfo) && $companyInfo->is_same_address === 0 }}") {
            $('#company_factory_div').removeClass('hidden');
        }
        if (check == false) {
            $('#company_factory_div').removeClass('hidden');
        }

        LoadListOfDirectors();
    })
</script>
<script>
    function openModal(btn) {
        //e.preventDefault();
        var this_action = btn.getAttribute('data-action');
        if (this_action != '') {
            $.get(this_action, function(data, success) {
                if (success === 'success') {
                    $('#myModal .load_modal').html(data);
                } else {
                    $('#myModal .load_modal').html('Unknown Error!');
                }
                $('#myModal').modal('show', {
                    backdrop: 'static'
                });
            });
        }
    }

    $(document).ready(function() {
        if ("{{ $companyInfo->nid ?? '' }}") {
            $("#company_ceo_passport_section").addClass("hidden");
            $("#company_ceo_nid_section").removeClass("hidden");
        } else {
            $("#company_ceo_passport_section").removeClass("hidden");
            $("#company_ceo_nid_section").addClass("hidden");
        }
    })

    //Load list of directors
    function LoadListOfDirectors() {
        $.ajax({
            url: "{{ url('client/company-profile/load-listof-directors-session') }}",
            type: "POST",
            data: {
                {{-- app_id: "{{ Encryption::encodeId($appInfo->id) }}", --}}
                    {{-- process_type_id: "{{ Encryption::encodeId($appInfo->process_type_id) }}", --}}
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                var html = '';
                if (response.responseCode == 1) {

                    var edit_url = "{{ url('/client/company-profile/edit-director') }}";
                    var delete_url = "{{ url('/client/company-profile/delete-director-session') }}";

                    var count = 1;
                    $.each(response.data, function(id, value) {
                        var sl = count++;
                        html += '<tr>';
                        html += '<td>' + sl + '</td>';
                        html += '<td>' + value.l_director_name + '</td>';
                        html += '<td>' + value.l_director_designation + '</td>';
                        html += '<td>' + value.nationality + '</td>';
                        html += '<td>' + value.nid_etin_passport + '</td>';
                        html += '<td>' +
                            '<a data-toggle="modal" data-target="#directorModel" onclick="openModal(this)" data-action="' +
                            edit_url + '/' + id +
                            '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                        if (sl != 1) {
                            html += '<a data-action="' + delete_url + '/' + id +
                                '" onclick="ConfirmDelete(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });

                    if (response.ceoInfo != null) {
                        $("#ceoInfoDIV").removeClass('hidden');

                        $("#company_ceo_designation_id").val(response.ceoInfo.designation);
                        var date_of_birth = moment(response.ceoInfo.date_of_birth).format("DD-MM-YYYY");
                        $("#company_ceo_dob").val(date_of_birth);
                        $("#company_ceo_name").val(response.ceoInfo.l_director_name);
                        $("#company_ceo_fatherName").val(response.ceoInfo.l_father_name);


                        if (response.ceoInfo.nationality == 'Bangladeshi') {
                            $("#company_ceo_nationality").attr('readonly', true);
                            $("#company_ceo_nationality").val(18);
                        }

                        if (response.ceoInfo.identity_type == 'passport') {
                            $("#company_ceo_passport_section").removeClass("hidden");
                            $("#company_ceo_nid_section").addClass("hidden");
                            $("#company_ceo_passport").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_nationality").attr('readonly', false);
                            $("#company_ceo_nationality").val('');
                            $("#company_ceo_nid").val('');
                        } else {
                            $("#company_ceo_passport_section").addClass("hidden");
                            $("#company_ceo_nid_section").removeClass("hidden");
                            $("#company_ceo_nid").val(response.ceoInfo.nid_etin_passport);
                            $("#company_ceo_passport").val('');
                        }
                    } else {
                        $(".ceoInfoDirector").removeClass('hidden');
                        // $("#ceoInfoDIV").addClass('hidden');
                    }

                } else {
                    html += '<tr>';
                    html += '<td colspan="5" class="text-center">' +
                        '<span class="text-danger">No data available!</span>' + '</td>';
                    html += '</tr>';
                }
                $('#directorList tbody').html(html);
            }
        });
    }

    //confirm delete alert
    function ConfirmDelete(btn) {
        var sure_delete = confirm("Are you sure you want to delete?");
        if (sure_delete) {
            var url = btn.getAttribute('data-action');
            $.ajax({
                url: url,
                type: "get",
                success: function(response) {
                    if (response.responseCode == 1) {
                        toastr.success(response.msg);
                    }

                    LoadListOfDirectors();
                }
            });

        } else {
            return false;
        }
    }
</script>

<script type="text/javascript">
    $(function() {
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datetimepicker4').datetimepicker({
            format: 'DD-MMM-YYYY',
            maxDate: 'now',
            minDate: '01/01/' + (yyyy - 110),
            ignoreReadonly: true,
        });
    });
</script>

<script>
    //form & field operation

    $(document).ready(function() {
        // $("#declaration_q1_yes").on('click', function() {
        //     $('#if_declaration_q1_yes').css('display','inline');
        // });
        // $("#declaration_q1_no").on('click', function() {
        //     $('#if_declaration_q1_yes').css('display','none');
        // });
        //
        // $("#declaration_q2_yes").on('click', function() {
        //     $('#if_declaration_q2_yes').css('display','inline');
        // });
        // $("#declaration_q2_no").on('click', function() {
        //     $('#if_declaration_q2_yes').css('display','none');
        // });
        //
        // $("#declaration_q3_yes").on('click', function() {
        //     $('#if_declaration_q3_yes').css('display','inline');
        // });
        // $("#declaration_q3_no").on('click', function() {
        //     $('#if_declaration_q3_yes').css('display','none');
        // });
        //
        // $("#declaration_q4_yes").on('click', function() {
        //     $('#if_declaration_q4_yes').css('display','inline');
        //     getHelpText();
        // });
        // $("#declaration_q4_no").on('click', function() {
        //     $('#if_declaration_q4_yes').css('display','none');
        // });

        $("#declaration_q1_yes").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'inline');
            $('#declaration_q1_application_date').addClass('required date');
            $('#declaration_q1_text').addClass('required');
        });
        $("#declaration_q1_no").on('click', function () {
            $('#if_declaration_q1_yes').css('display', 'none');
        });

        $("#declaration_q2_yes").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'inline');
            $('#declaration_q2_text').addClass('required');
        });
        $("#declaration_q2_no").on('click', function () {
            $('#if_declaration_q2_yes').css('display', 'none');
        });

        $("#declaration_q3_yes").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'inline');
            $('#declaration_q3_application_date').addClass('required date');
            $('#declaration_q3_text').addClass('required');
        });
        $("#declaration_q3_no").on('click', function () {
            $('#if_declaration_q3_yes').css('display', 'none');
        });

        $("#declaration_q4_yes").on('click', function () {
            $('#if_declaration_q4_yes').css('display', 'inline');
            getHelpText();
        });
        $("#declaration_q4_no").on('click', function () {
            $('#if_declaration_q4_yes').css('display', 'none');
        });



    });
</script>

<script>
    $(document).ready(function() {
        getHelpText();
        @isset($contact_person)
        @foreach($contact_person as $index => $person)
        getThanaByDistrictId('contact_district_{{$index+1}}', {{ $person->district ?? ''}},
            'contact_thana_{{$index+1}}', {{ $person->upazila ?? '' }});
        @endforeach
        @endisset

        $('#company_type').on('change', function () {
            if (this.value == "") {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I/We');
            } else if (this.value == 3) {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('I');
            } else {
                $('.i_we_dynamic').text('');
                $('.i_we_dynamic').text('We');
            }
        });

        // default I/We
        const companyType = $('#company_type').val();
        if (companyType == "") {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        } else if (companyType == 1) {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        } else {
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }


        $("#areaAddressEditBtn").click(function() {
            if (this.checked) {
                makeReadWriteByDivId('amendmentProposal');
                document.getElementById('addAreaAddressRowId').style.pointerEvents = 'auto';
            } else {
                makeReadOnlyByDivId('amendmentProposal');
                document.getElementById('addAreaAddressRowId').style.pointerEvents = 'none';
            }
        });

        $("#declarations").click(function() {
            const elements = document.querySelectorAll('#declarationFields input, #declarationFields label');
            if (this.checked) {
                elements.forEach(item => {
                    item.classList.remove('input_disabled');
                })
            } else {
                elements.forEach(item => {
                    item.classList.add('input_disabled');
                })
            };
        });

        //initially input disabled declaration fields
        const elements = document.querySelectorAll('#declarationFields input, #declarationFields label');
        if (this.checked) {
            elements.forEach(item => {
                item.classList.remove('input_disabled');
            })
        } else {
            elements.forEach(item => {
                item.classList.add('input_disabled');
            })
        };



        $(document).on('click','.remove_row',function(){
            $(this).closest("tr").remove();
        });

        // load payment panel
        {{--const fixed_amounts = {--}}
        {{--    1: 0,--}}
        {{--    2: 0,--}}
        {{--    3: 0,--}}
        {{--    4: 0,--}}
        {{--    5: 0,--}}
        {{--    6: 0--}}
        {{--};--}}

        {{--loadPaymentPanel('', '{{ $process_type_id }}', '1',--}}
        {{--    'payment_panel',--}}
        {{--    "{{ CommonFunction::getUserFullName() }}",--}}
        {{--    "{{ Auth::user()->user_email }}",--}}
        {{--    "{{ Auth::user()->user_mobile }}",--}}
        {{--    "{{ Auth::user()->contact_address }}",--}}
        {{--    fixed_amounts);--}}
    });

    function generateBase64String(source, destination){
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

    function openPreview() {

        if(!$('#accept_terms').prop('checked')){
            $('#accept_terms').focus()
            $('#accept_terms').addClass('error');
            return false;
        }
        $('#accept_terms').removeClass('error');
        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function declarationSectionValidation(){
        let error_status = false ;
        if($("#declaration_q1_yes").is(":checked") && $("#if_declaration_q1_yes").val() === ""){
            $("#if_declaration_q1_yes").addClass('error');
            error_status = true;
        }

        if($("#declaration_q2_yes").is(":checked") && $("#if_declaration_q2_yes").val() === ""){
            $("#if_declaration_q2_yes").addClass('error');
            error_status = true;
        }

        let declaration_q3_images_preview = $("#declaration_q3_images_preview").attr('href');
        if($("#declaration_q3_yes").is(":checked") && ( $("#if_declaration_q3_yes").val() === "" &&  !Boolean(declaration_q3_images_preview) )){
            console.log($("#if_declaration_q3_yes").val());
            $("#if_declaration_q3_yes").addClass('error');
            error_status = true;
        }

        return error_status;
    }

    function SectionValidation(selector) {
        const requiredClientFields = document.querySelectorAll(selector);
        let errorStatus = false;
        for (const elem of requiredClientFields) {
            if (elem.classList.contains('required') && !elem.value) {
                elem.classList.add('error');
                errorStatus = true;
            }
        }
        return errorStatus;
    }

    function deleteAddressRow(element) {
        element.remove();
    }
</script>

