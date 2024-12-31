<link rel="stylesheet"
      href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">

<style>
    .font-normal {
        font-weight: normal;
    }

    .\!font-normal {
        font-weight: normal !important;
    }

    .section_head {
        font-size: 20px;
        font-weight: 400;
        margin-top: 25px;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
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
    .cross-button {
        float: right;
        padding: 0rem .250rem !important;
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

    .border-header-box {
        padding: 25px 10px 15px;
        margin-bottom: 30px;
    }

    .border-header-txt {
        margin-top: -36px;
        position: absolute;
        background: #fff;
        padding: 0px 15px;
        font-weight: 600;
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

    .wizard > .steps > ul > li {
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
        background: #1C9D50 !important;
        color: #ffffff;
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
                <h4 class="card-header" style="font-size: 24px; font-weight: 400">Application for TVAS Certificate Issue</h4>
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
                <div style="display: none;" id="pcsubmitadd">
                    <input type="hidden" id="openMode" name="openMode" value="edit">
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                    {!! Form::hidden('status_id', $appInfo->status_id, ['class' => 'form-control input-md required', 'id' => 'status_id']) !!}
                </div>
                <h3>Basic Information</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Company Informaiton --}}
                    @includeIf('common.subviews.CompanyInfo', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Applicant Profile --}}
                    @includeIf('common.subviews.ApplicantProfile', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Contact Person Profile --}}
                    @includeIf('common.subviews.ContactPerson', ['mode' => 'edit', 'extra' => ['address2'], 'selected' => 1 ])

                    {{-- Shareholder/partner/proprietor Details --}}
                    @includeIf('common.subviews.Shareholder', ['mode' => 'edit'])


                </fieldset>

                <h3>Attachment & Declaration</h3>
                {{--                <br>--}}
                <fieldset>
                    {{-- Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box): --}}
                    {{--                    <div class="card card-magenta border border-magenta">--}}
                    {{--                        <div class="card-header">--}}
                    {{--                            Documents to be enclosed for New License and Renewal (Use tick [√] mark in the appropriate box):--}}
                    {{--                        </div>--}}
                    {{--                        <div class="card-body" style="padding: 15px 25px;">--}}

                    {{--                            <div class="row">--}}
                    {{--                                <div class="col-md-12">--}}
                    {{--                                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">--}}
                    {{--                                        <thead>--}}
                    {{--                                        <tr>--}}
                    {{--                                            <th style="text-align: center; width: 5%;">Serial</th>--}}
                    {{--                                            <th style="text-align: center; width: 70%;">Items</th>--}}
                    {{--                                            <th style="text-align: center; width: 30%;">Attached</th>--}}
                    {{--                                        </tr>--}}
                    {{--                                        </thead>--}}
                    {{--                                        <tbody>--}}
                    {{--                                        <tr>--}}
                    {{--                                            <td style="text-align: center; padding: 5px;">1</td>--}}
                    {{--                                            <td style="padding: 5px;"> Application in a Letter Head Pad </td>--}}
                    {{--                                            <td style="padding: 5px;">--}}
                    {{--                                                {!! Form::file('new_license_document[]', ['class' => 'form-control','id' => 'new_license_document']) !!}--}}
                    {{--                                            </td>--}}
                    {{--                                        </tr>--}}
                    {{--                                        </tbody>--}}
                    {{--                                    </table>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}

                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{-- Necessary attachment --}}
                    <div class="card card-magenta border border-magenta">
                        <div class="card-header">
                            Required Documents for Attachment
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">
                            <input type="hidden" id="doc_type_key" name="doc_type_key">
                            <div id="docListDiv"></div>
                        </div>
                    </div>


                    {{-- Declaration --}}
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
                                                Has any application for any license of the applicant/ any share
                                                holder/ partner been rejected before?
                                            </label>

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
                                                    {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input', 'id'=>'declaration_q1_text', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                                </div>
                                            </div>


                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Do the Applicant/ any Share Holder/ Partner hold any other Operator
                                                License/ Registration Certificate from the Commission?
                                            </label>

                                            <div style="margin: 5px 0; margin-bottom: 10px;" id="declaration_q2">
                                                {{ Form::radio('declaration_q2', 'Yes',$appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                                {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                                {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                                {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                            </div>
                                            <div style="margin: 5px;display: none" id="if_declaration_q2_yes" >
                                                {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input required', 'id'=>'declaration_q2_text', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5,))}}
                                            </div>

                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Has any other License/ Registration Certificate of the Applicans/ any
                                                Share Holder/ Partner been rejected before?
                                            </label>

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
                                                    {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input required','placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                                </div>
                                            </div>


                                        </li>
                                        <li>
                                            <label class="required-star !font-normal">
                                                Were the Applicants/ its owner(s)/ any of its director(s)/ partner(s)
                                                involved in any illegal call termination?
                                            </label>

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
                                            guidelines/ terms and conditions, for the license and <span
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

                </fieldset>

                <h3>Payment & Submit</h3>
                {{--                <br>--}}
                <fieldset>
                    @if($appInfo->status_id != 5)
                        <div id="payment_panel"></div>
                    @endif
                    @if($appInfo->status_id === 5)
                        <div style="margin-bottom: 40px;"></div>
                    @endif
                    {{-- Terms and Conditions --}}
                    <div class="card card-magenta border border-magenta" style="{{ $appInfo->status_id == 5 ? 'margin-top: -45px;' : '' }} ">
                        <div class="card-header">
                            Terms and Conditions
                        </div>
                        <div class="card-body" style="padding: 15px 25px;">

                            <div class="row">
                                <div class="col-md-12">

                                    {{ Form::checkbox('accept_terms', 1, null,['class'=>'form-check-input ', 'style'=>'display: inline', 'id' => 'accept_terms']) }}
                                    {{ Form::label('accept_terms', ' I agree with the Terms and Conditions', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}
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
                    <a href="{{ url('client/tvas-license-issue/list/'. Encryption::encodeId(25)) }}"
                       class="btn btn-default btn-md cancel" value="close" name="closeBtn"
                       id="save_as_draft">Close
                    </a>
                </div>

                <div class="float-right">
                    <button type="button" id="submitForm"
                            style="margin-right: 180px; position: relative; z-index: 99999; display: none;"
                            class="btn btn-success btn-md"
                            value="submit" name="actionBtn"
                            onclick="openPreviewV2()">{{ ($appInfo->status_id == 5)? 'Re-submit':'Submit'  }}
                    </button>
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
                                    <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh
                                        Telecommunication
                                        Regulatory Commission (BTRC).
                                    </li>
                                    <li>Fees and charges are not refundable.</li>
                                    <li>The Commission is entitled to change this from time to time if necessary.</li>
                                    <li>Updated documents shall be submitted during application.</li>
                                    <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
{{--                                    <li>For New Applicant only A, B and E will be applicable.</li>--}}
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
    // function enablePath() {
    //     document.getElementById('company_type_id').disabled = "";
    //     document.getElementById('company_office_division_id').disabled = "";
    //     document.getElementById('company_office_district_id').disabled = "";
    //     document.getElementById('company_office_thana_id').disabled = "";
    // }

    var selectCountry = '';

    $(document).ready(function () {

        @isset($appInfo->reg_office_district)
        getThanaByDistrictId('reg_office_district', {{ $appInfo->reg_office_district ?? '' }},
            'reg_office_thana', {{ $appInfo->reg_office_thana ?? '' }});
        @endisset

        @isset($appInfo->op_office_district)
        getThanaByDistrictId('op_office_district', {{ $appInfo->op_office_district ?? '' }},
            'op_office_thana', {{ $appInfo->op_office_thana ?? '' }});
        @endisset

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


        // setIntlTelInput('.shareholder_mobile');
        // setIntlTelInput('.contact_person_mobile');
        // setIntlTelInput('.applicant-mobile');


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
        // form.find('#save_as_draft').css('display','none');
        // form.find('#submitForm').css('display', 'none');
        form.find('.next').addClass('btn-primary');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                if (newIndex == 1) {
                    //return true;

                    var total = 0;
                    $('.shareholder_share_of', 'tr').each(function () {
                        total += Number($(this).val()) || 0;
                    });
                    if (total != 100) {
                        new swal({
                            type: 'error',
                            text: 'The value of the "% of share field" should be a total of 100.',
                        });
                        SetErrorInShareOfInputField();
                        return false;
                    }
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }

                }

                if (newIndex == 2) {
                    let errorStatus = false;
                    if ($("#declaration_q1_yes").is(":checked") || $("#declaration_q1_no").is(":checked")) {
                        if ($("#declaration_q2_yes").is(":checked") || $("#declaration_q2_no").is(":checked")) {
                            if ($("#declaration_q3_yes").is(":checked") || $("#declaration_q3_no").is(":checked")) {
                                if ($("#declaration_q4_yes").is(":checked") || $("#declaration_q4_no").is(":checked")) {
                                    errorStatus = false;
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
                    } else {
                        new swal({
                            type: 'error',
                            text: 'Please answer the Declaration section all question.',
                        });
                        errorStatus = true;
                    }

                    if (errorStatus) return false;

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

                if (newIndex < currentIndex) return true;

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
                } else {
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

        $('.finish').on('click', function (e) {
            if (!$('#accept_terms').prop('checked')) {
                $('#accept_terms').addClass('error');
                return false;
            }
            window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
        });


        function attachmentLoad() {
            var reg_type_id = parseInt($("#reg_type_id").val()); //order 1
            var company_type_id = parseInt($("#company_type_id").val()); //order 2
            var industrial_category_id = parseInt($("#industrial_category_id").val()); //order 3
            var investment_type_id = parseInt($("#investment_type_id").val()); //order 4

            var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' +
                investment_type_id;

            $("#doc_type_key").val(key);
            loadApplicationDocs('docListDiv', null);
        }

        attachmentLoad();
        // loadApplicationDocs('docListDiv', '1-1-1-1');
    });

    $(document).ready(function () {

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


<script type="text/javascript">
    $(function () {
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

    $(document).ready(function () {


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
    $(document).ready(function () {


        $(document).on('change', '.nationality', function () {
            let id = $(this).attr('id');
            let lastRowId = id.split('_')[2];

            if (this.value == 18) {
                $('#nidBlock_' + lastRowId).show();
                $('#shareholder_nid_' + lastRowId).addClass('required');

                $('#passportBlock_' + lastRowId).hide();
                $('#shareholder_passport_' + lastRowId).removeClass('required');

                $('#passportCopyBlock_' + lastRowId).hide();
                $('#shareholder_passport_copy_' + lastRowId).removeClass('required');

            } else {
                $('#nidBlock_' + lastRowId).hide();
                $('#shareholder_nid_' + lastRowId).removeClass('required');

                $('#passportBlock_' + lastRowId).show();
                $('#shareholder_passport_' + lastRowId).addClass('required');

                $('#passportCopyBlock_'+lastRowId).show();
                $('#shareholder_passport_copy_' + lastRowId).addClass('required');
            }
        });

        $(document).on('change', '#permanentSameAsRegisterdAddress', function (e) {
            if (this.checked === true) {
                let office_district = $("#reg_office_district").val();
                let office_upazilla_thana = $("#reg_office_thana").val();
                $("#permanent_office_district").val(office_district);
                getThanaByDistrictId('permanent_office_district', office_district, 'permanent_office_thana', office_upazilla_thana.trim());
                $("#permanent_office_address").val($("#reg_office_address").val());

            } else {
                $("#permanent_office_thana").val('');
                $("#permanent_office_address").val('');
                $("#permanent_office_district").val('');
            }
        })


        var old_value = $('#company_type :selected').val();
        $('#company_type').change(function () {
            $('#company_type').val(old_value);
        });

        var company_type = "{{$companyInfo->org_type}}";

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
            var btn = $(this);
            btn.after('&nbsp;<i class="fa fa-spinner fa-spin"></i>');
            let tblId = $(this).closest("table").attr('id');

            let tableType = $(`#${tblId} tr:last`).attr('row_id').split('_')[0];
            let lastRowId = parseInt($(`#${tblId} tr:last`).attr('row_id').split('_')[1]);

            $.ajax({
                type: "POST",
                url: "{{ url('isp-license-issue/add-row') }}",
                data: {
                    tableType: tableType,
                    lastRowId: lastRowId,
                },
                success: function (response) {
                    $(`#${tblId} tbody`).append(response.html);
                    $(btn).next().hide();
                }
            });
        });

        $(document).on('click', '.remove_row', function () {
            $(this).closest("tr").remove();
        });

    });
    @if($appInfo->status_id != 5)
    // load payment panel
    const fixed_amounts = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0
    };
    loadPaymentPanelV2('', '{{ $process_type_id }}', '1',
        'payment_panel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}",
        fixed_amounts);
    @endif

    function openPreview() {

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            return false;
        }

        window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function openPreviewV2() {
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
        // const fields = document.getElementsByClassName('required');
        const fields = document.querySelectorAll('#payment_panel .required');
        for (const element of fields) {
            if (!element.value) {
                element.classList.add('error');
                preview *= 0;
            } else preview *= 1;
        }

        if (!$('#accept_terms').prop('checked')) {
            $('#accept_terms').addClass('error');
            return false;
        }

        if (preview) window.open('<?php echo URL::to('process/license/preview/'.\App\Libraries\Encryption::encodeId($process_type_id)); ?>');
    }

    function imagePreview(input, img_preview_id) {

        if (input.files && input.files[0]) {
            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                input.value = '';
                alert('Image format is not valid. Only PNG or JPEG or JPG type images are allowed.');
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(img_preview_id).setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById(base64_value_target).value = '';
            document.getElementById(img_preview_id).setAttribute('src', '<?php echo e( url( 'assets/images/no_image.png' ) ); ?>');
        }
    }

    function SetErrorInShareOfInputField() {
        $(".shareholder_share_of").each(function (index) {
            $(this).addClass('error');
        });
    }


</script>
