<style>

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

    label {
        font-weight: normal;
        font-size: 14px;
    }

    span {
        font-size: 14px;
    }

    .section_head {
        font-size: 24px;
        font-weight: 400;
        margin-top: 25px;
    }

    @media (min-width: 767px) {
        .addressField {
            width: 79.5%;
            float: right
        }
    }

    @media (max-width: 480px) {
        .section_head {
            font-size: 20px;
            font-weight: 400;
            margin-top: 5px;
        }

        label {
            font-weight: normal;
            font-size: 13px;
        }

        span {
            font-size: 13px;
        }

        .panel-body {
            padding: 10px 0 !important;
        }

        .form-group {
            margin: 0;
        }

        .image_mobile {
            width: 100%;
        }
    }

    .table-responsive {
        display: inline-table !important;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

@if(in_array($appInfo->status_id, [2,5,17,18,19,20,22,30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44,45, 47, 48, 49]))
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Related Reports
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            @if(isset($appInfo->dd_file_1))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation
                    Report</a>
            @endif
            @if(isset($appInfo->dd_file_2))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission Meeting
                    Agenda</a>
            @endif
            @if(isset($appInfo->dd_file_3))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry Approval  </a>
            @endif
            @if(isset($appInfo->dd_file_4))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_4)}}">View Inspection
                    Report</a>
            @endif
            @if(isset($appInfo->shortfall_reason))
                <a class="btn btn-primary" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
            @endif
            @if(isset($latter[1]))
            <a class="btn btn-primary" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
            @endif 
            @if(isset($latter[2]))
            <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
            @endif 
        </div>
    </div>
@endif

<div id="paymentPanel"></div>

<div class="card-body" style="padding: 0px!important;" id="applicationForm">

    <fieldset>
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Company/ Organization Information
            </div>
            <div class="card-body" style="padding: 15px 25px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('company_name', 'Company Name', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->company_name}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('company_type', 'Company Type', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <?php
                                $companyType = \App\Modules\CompanyProfile\Models\CompanyType::where('status', 1)->where('is_archive', 0)->orderBy('name_en')->pluck('name_en', 'id')->toArray();
                                ?>
                                {{ $companyType[$appInfo->company_type] ?? null }}
                            </div>
                        </div>
                    </div>


                </div>

                <br>

                {{-- Registered Office Address --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Registered Office Address
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('reg_office_district', 'District:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->reg_off_dis_nm}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('reg_office_thana', 'Upazila / Thana:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->reg_off_thana_nm}}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('reg_office_address', 'Address:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->reg_office_address}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Operational Office Address --}}
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Operational Office Address
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('per_district', 'District:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->op_office_district_nm}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('per_thana', 'Upazila / Thana:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->op_office_thana_nm}}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    {!! Form::label('per_address', 'Address:', ['class' => 'col-md-4 ']) !!}
                                    <div class="col-md-8">
                                        <span>{{$appInfo->op_office_address}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Applicant Profile
            </div>
            <div class="card-body" style="padding: 15px 25px;">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_name', 'Name of Entity:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_name}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_district', 'District:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_dis_nm}}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_thana', 'Upazila / Thana:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_thana_nm}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_address', 'Address:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_address}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_email', 'Email:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_email}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="form-group row">
                            {!! Form::label('applicant_mobile', 'Mobile Number:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_mobile}}</span>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_telephone', 'Telephone No.:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_telephone}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('applicant_website', 'Website:', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                <span>{{$appInfo->applicant_website}}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])

        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])
    </fieldset>
    <fieldset>

        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Required Documents for attachment
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div id="">
                    <table class="table-bordered table-responsive table-striped" style="width: 100%;">
                        <thead>
                        <th style="padding: 10px;">Document Name</th>
                        <th style="padding: 10px;">File</th>
                        </thead>
                        <tbody>

                        @if(count($appDynamicDocInfo) > 0)
                            @foreach($appDynamicDocInfo as $docInfo)
                                <tr>
                                    @if($docInfo->uploaded_path)
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                        <td style="padding: 10px;" class="text-center" ><a target="_blank" href="{{url('/').'/uploads/'.$docInfo->uploaded_path}}">View</a> </td>
                                    @else
                                        <td style="padding: 10px;">{{$docInfo->doc_name}}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align: center;">No Data found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </fieldset>

    <fieldset>
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
                                Has any application for any license of the applicant/any share holder/partner been
                                rejected before?
                                <div style="margin: 5px 0;">
                                    {{ Form::radio('declaration_q1', 'Yes',$appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline', 'id' => 'declaration_q1_yes']) }}
                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no']) }}
                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div id="if_declaration_q1_yes" style="display: none">
                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                        <div
                                            class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">
                                            <div class="input-group date datetimepicker4"
                                                 id="declaration_q1_application_date" data-target-input="nearest">
                                                {!! Form::text('declaration_q1_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q1_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q1_application_date):'', ['class' => 'form-control','disabled', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y') ] ) !!}
                                                {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input', 'disabled', 'id'=>'if_declaration_q1_yes', 'style'=>($appInfo->declaration_q1 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => ''))}}
                                    </div>
                                </div>


                            </li>
                            <li>
                                Do the Applicant/any Share Holder/ Partner hold any other Operator License/ Registration
                                Certificate from the Commission?

                                <div style="margin: 5px 0;">
                                    {{ Form::radio('declaration_q2', 'Yes',$appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline', 'id' => 'declaration_q2_yes']) }}
                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input','disabled','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no']) }}
                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin: 5px 0;">
                                    {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input required','disabled', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                </div>

                            </li>
                            <li>
                                Has any other License/ Registration Certificate of the Applicans/ any Share Holder/
                                Partner been rejected before?
                                <div style="margin: 5px 0;">
                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline', 'id' => 'declaration_q3_yes']) }}
                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input','disabled','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no']) }}
                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div id="if_declaration_q3_yes" style="display: none">
                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('declaration_q3_application_date', 'Date of Application', ['class' => 'col-md-2 required-star','disabled', 'style' => 'font-weight:400' ]) !!}
                                        <div
                                            class="col-md-4 {{ $errors->has('declaration_q3_application_date') ? 'has-error' : '' }}">
                                            <div class="input-group date datetimepicker4"
                                                 id="declaration_q3_application_date" data-target-input="nearest">
                                                {!! Form::text('declaration_q3_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q3_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q3_application_date):'', ['class' => 'form-control','disabled', 'id' => 'declaration_q3_application_date','placeholder'=> date('d-M-Y') ]) !!}

                                                {!! $errors->first('declaration_q3_application_date', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin: 5px 0;">
                                        {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input required','disabled', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'required'))}}
                                    </div>
                                </div>


                            </li>
                            <li>
                                Were the Applicants/ its owner(s)/ any of its director(s)/ partner(s) involved in any
                                illegal call termination?

                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q4', 'Yes', $appInfo->declaration_q4 == 'Yes' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline', 'id' => 'declaration_q4_yes']) }}
                                    {{ Form::label('declaration_q4_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q4', 'No', $appInfo->declaration_q4 == 'No' ? true : false, ['class'=>'form-check-input','disabled','style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q4_no']) }}
                                    {{ Form::label('declaration_q4_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div id="if_declaration_q4_yes" style="display: none">
                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('q4_license_number', 'Period of Involvement in illegal activities', ['class' => 'col-md-4 required-star', 'style' => 'font-weight:400' ]) !!}
                                        <div
                                            class="col-md-3 {{ $errors->has('q4_license_number') ? 'has-error' : '' }}">
                                            {!! Form::text('q4_license_number',  $appInfo->q4_license_number, ['class' => 'form-control','disabled', 'id' => 'q4_license_number', 'placeholder'=> 'Enter License Number']) !!}
                                            {!! $errors->first('q4_license_number', '<span class="help-block">:message</span>') !!}
                                        </div>

                                        {!! Form::label('q4_case_no', 'Case No', ['class' => 'col-md-2 required-star', 'style' => 'font-weight:400' ]) !!}
                                        <div class="col-md-3 {{ $errors->has('q4_case_no') ? 'has-error' : '' }}">
                                            {!! Form::text('q4_case_no',  $appInfo->q4_case_no, ['class' => 'form-control','disabled', 'id' => 'q4_case_no']) !!}
                                            {!! $errors->first('q4_case_no', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 border border-header-box">
                                            <span
                                                class="border-header-txt">Administrative fine paid to the Commission</span>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('q4_amount', 'Amount', ['class' => 'col-md-4 required-star','style'=> 'font-weight: 400']) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('q4_amount') ? 'has-error' : '' }}">
                                                            {!! Form::text('q4_amount',  $appInfo->q4_amount, ['class' => 'form-control','disabled', 'id' => 'q4_amount']) !!}
                                                            {!! $errors->first('q4_amount', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        {!! Form::label('q4_bank_draft_no', 'Cheque No./ Bank Draft No', ['class' => 'col-md-4 required-star' ,'style'=> 'font-weight: 400' ]) !!}
                                                        <div
                                                            class="col-md-8 {{ $errors->has('q4_bank_draft_no') ? 'has-error' : '' }}">
                                                            {!! Form::text('q4_bank_draft_no', $appInfo->q4_bank_draft_no, ['class' => 'form-control','disabled', 'id' => 'q4_bank_draft_no']) !!}
                                                            {!! $errors->first('q4_bank_draft_no', '<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('q4_given_comission', 'Undertaking given to the Commission', ['class' => 'col-md-5 required-star', 'style' => 'font-weight:400' ]) !!}
                                        <div class="col-md-6 {{ $errors->has('given_comission') ? 'has-error' : '' }}">
                                            {{ Form::radio('q4_given_comission', 'Yes', $appInfo->q4_given_comission == 'Yes' ? true : false, ['class'=>'form-check-input','disabled', 'style'=>'display: inline', ]) }}
                                            {{ Form::label('given_comission_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                            {{ Form::radio('q4_given_comission', 'No', $appInfo->q4_given_comission == 'No' ? true : false, ['class'=>'form-check-input','disabled','style'=>'display: inline;  margin-left:10px;']) }}
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
    </fieldset>


    <div class="float-left">
        <a href="{{ url('client/tvas-license-issue/list/'. Encryption::encodeId(27)) }}"
           class="btn btn-default btn-md cancel" value="close" name="closeBtn"
           id="save_as_draft">Close
        </a>
    </div>
</div>

<div class="modal fade" id="shortFallModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Shortfall Reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $appInfo->shortfall_reason !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/scripts/custom.min.js') }}"></script>
<script>


    var reg_type_id = "{{ $appInfo->regist_type }}";
    var company_type_id = "{{ $appInfo->org_type }}";
    var industrial_category_id = "{{ $appInfo->ind_category_id }}";
    var investment_type_id = "{{ $appInfo->invest_type }}";

    var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

    loadApplicationDocs('docListDiv', key);




{{--    @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15]))--}}
{{--    var unfixed_amounts = <?php echo json_encode( $unfixed_amounts ); ?>;--}}

{{--    loadPaymentPanel('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',--}}
{{--        'paymentPanel',--}}
{{--        "{{ $appInfo->applicant_name }}",--}}
{{--        "{{ $appInfo->applicant_email }}",--}}
{{--        "{{ $appInfo->applicant_mobile }}",--}}
{{--        "{{ $appInfo->applicant_address }}"--}}
{{--        , unfixed_amounts);--}}
{{--    @endif--}}
</script>

<script>
    $(document).ready(function () {

        var company_type = "{{$appInfo->company_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 1){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }


        var declaration_q1 = "{{$appInfo->declaration_q1}}";
        var declaration_q2 = "{{$appInfo->declaration_q2}}";
        var declaration_q3 = "{{$appInfo->declaration_q3}}";
        var declaration_q4 = "{{$appInfo->declaration_q4}}";

        if (declaration_q1 == 'Yes') {
            $('#if_declaration_q1_yes').css('display', 'inline');
        }

        if (declaration_q2 == 'Yes') {
            $('#if_declaration_q2_yes').css('display', 'inline');
        }

        if (declaration_q3 == 'Yes') {
            $('#if_declaration_q3_yes').css('display', 'inline');
        }

        if (declaration_q4 == 'Yes') {
            $('#if_declaration_q4_yes').css('display', 'inline');
        }


    });
</script>