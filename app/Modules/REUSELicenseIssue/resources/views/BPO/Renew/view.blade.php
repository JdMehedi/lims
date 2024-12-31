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
    .\!font-normal {
        font-weight: normal !important;
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
    @media (max-width: 1200px) {
        .responsive-row {
            display: block;
        }
    }
    .table-responsive {
        display: inline-table!important;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

@if(in_array($appInfo->status_id, [2,5,15, 16, 17, 18, 19, 20, 22, 25, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Related Reports
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            @if(isset($appInfo->dd_file_1))
                <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation Report</a>
            @endif
            @if(isset($appInfo->shortfall_reason))
                <a class="btn btn-primary m-1" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
            @endif
            @if(isset($latter[1]))
            <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
            @endif
            @if(isset($latter[2]))
            <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
            @endif
            @if(isset($latter[3]))
            <a class="btn btn-primary m-1" target="_blank" href="{{url($latter[3])}}">BPO/Call Center Registration Certificate</a>
            @endif
        </div>
    </div>
@endif

<div id="paymentPanel"></div>

<div class="card-body" id="applicationForm" style="padding: 0px!important;">

    <fieldset>
        {{-- Company Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view'])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])

        {{-- Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])

        {{-- Present Business Actives of the Applicant/Company/Group Company --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Present Business Actives of the Applicant/ Company/ Group Company
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <p>{{$appInfo->present_business_actives}}</p>
            </div>
        </div>

        {{-- Present Proposal (Please submit separate sheet for each centre) --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Present Proposal
            </div>
            <div class="card-body" style="padding: 15px 25px;">

{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        {!! Form::label('service', 'Service:', ['class' => 'col-md-4 ']) !!}--}}
{{--                        @if(in_array('Call Center',json_decode($appInfo->proposal_service)) && in_array('BPO',json_decode($appInfo->proposal_service)))--}}
{{--                            <span>: BPO, Call Center</span>--}}
{{--                        @elseif(in_array('BPO',json_decode($appInfo->proposal_service)))--}}
{{--                            <span>: BPO</span>--}}
{{--                        @elseif(in_array('Call Center',json_decode($appInfo->proposal_service)))--}}
{{--                            <span>: Call Center</span>--}}
{{--                        @endif--}}
{{--                    </div>--}}

{{--                    <div class="col-md-6">--}}
{{--                        {!! Form::label('service_type', 'Service Type:', ['class' => 'col-md-4 ']) !!}--}}
{{--                        <span>{{ implode(",",json_decode($appInfo->proposal_service_type))  }}</span>--}}
{{--                    </div>--}}

{{--                </div>--}}
                <br>
                <div class="card card-magenta border border-magenta">
                    <div class="card-header">
                        Area Address
                    </div>
                    <div class="card-body" style="padding: 15px 25px;">

                        <table class="table-responsive" style="width: 100%;     display: inline-table!important;"
                               id="contactPersonRow">
                            @foreach($proposal_area as $index=>$areaData)
                                <tr id="cp_r_{{$index+1}}">
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('proposal_district', 'District', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->proposal_district}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('proposal_thana', 'Upazila / Thana', ['class' => 'col-md-4 ']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->proposal_thana}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    {!! Form::label('proposal_address', 'Address', ['class' => 'col-md-2 ']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->proposal_address}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('proposal_no_of_seats', 'No.of Seats', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->proposal_no_of_seats}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('proposal_employee', 'Proposed no. of Employees', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->proposal_employee}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('local', 'Local', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->local}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    {!! Form::label('expatriate', 'Expatriate', ['class' => 'col-md-4']) !!}
                                                    <div class="col-md-8">
                                                        <span>: {{$areaData->expatriate}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

            </div>
        </div>

        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])
        @if($appInfo->trade_license_number)
            @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'view'])
        @endif



    </fieldset>
    <fieldset>
        @includeIf('REUSELicenseIssue::BPO.Renew.existing-call-center-details', ['mode' => 'view'])

        {{-- Necessary attachment --}}
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
                                <label class=" !font-normal">
                                    Has any Application for Registration Certificate of BPO/Call Center been rejected before?
                                </label>
                                <div style="margin-top: 20px;" id="declaration_q1">
                                    {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes','disabled']) }}
                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q1', 'No',  $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no','disabled']) }}
                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div id="if_declaration_q1_yes" style="display: none">
                                    <div class="form-group row" style="margin-top:10px;">
                                        {!! Form::label('declaration_q1_application_date', 'Date of Application', ['class' => 'col-md-2', 'style' => 'font-weight:400' ]) !!}
                                        <div
                                            class="col-md-4 {{ $errors->has('declaration_q1_application_date') ? 'has-error' : '' }}">
                                            <div class="input-group date datetimepicker4"
                                                 id="application_date_picker"
                                                 data-target-input="nearest">
                                                {!! Form::text('declaration_q1_application_date', ($appInfo->declaration_q1 == 'Yes' && !empty($appInfo->q1_application_date))? \App\Libraries\CommonFunction::changeDateFormat($appInfo->q1_application_date):'', ['class' => 'form-control', 'id' => 'declaration_q1_application_date', 'placeholder'=> date('d-M-Y'), 'readonly' ] ) !!}
                                                <div class="input-group-append"
                                                     data-target="#application_date_picker"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i
                                                            class="fa fa-calendar"></i></div>
                                                </div>
                                                {!! $errors->first('declaration_q1_application_date', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null , array('class' =>'form-control input','id' => 'declaration_q1_text', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '', 'readonly' ))}}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <label class=" !font-normal">
                                    Has any License/ Registration issued previously to the Applicant/ any
                                    Share Holder/ Partner been cancelled?
                                </label>

                                <div style="margin-top: 20px;" id="declaration_q2">
                                    {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes' , 'disabled']) }}
                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no', 'disabled']) }}
                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null , array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '','readonly' ))}}
                                </div>
                            </li>
                            <li>
                                <label class=" !font-normal">
                                    Do the Applicant/ any Share Holder/ Partner hold any other Operator
                                    Licenses from the Commission?
                                </label>

                                <div style="margin-top: 20px;" id="declaration_q3">
                                    {{ Form::radio('declaration_q3', 'Yes',  $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes' && !empty($appInfo->declaration_q3_text))? $appInfo->declaration_q3_text:'', array('class' =>'form-control input', 'id'=>'if_declaration_q3_yes', 'style'=>'display:none;', 'placeholder'=>'Please give details reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '' ,'readonly'))}}
                                </div>
                                @if(isset($appInfo->declaration_q3_images))
                                    <div style="margin-top: 20px; margin-left: 5px;" id="if_declaration_q3_images">
                                        <a class="btn btn-file" target="_blank" href="{{url('/').'/'.$appInfo->declaration_q3_images}}"><i class="fa fa-file"></i> View Document</a>
                                    </div>
                                @endif
                            </li>

                            <li style="margin-top: 20px;" ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the instructions/ terms and conditions, for the registration and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein. (Instructions for issuance of registration certificate for the operation of BPO/Call Center are available at www.btrc.gov.bd.)</li>
                            <li style="margin-top: 20px;" ><span class="i_we_dynamic">I/We</span> understand that this application if found incomplete in any respect and/ or if found with conditional compliance shall be summarily rejected.</li>
                            <li style="margin-top: 20px;" ><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the registration certificate isfound
                                incorrect then the registration certificate if granted on the basis of such application shall deemed to be
                                cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act,2001.</li>

                        </ol>
                    </div>
                </div>
            </div>
        </div>


    </fieldset>


    <div class="float-left">
        <a href="{{ url('client/bpo-or-call-center-new-app/list/'. Encryption::encodeId(6)) }}" class="btn btn-default btn-md cancel" value="close" name="closeBtn"
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
    $(document).on('click', '.cancelcounterpayment', function () {
        return confirm('Are you sure?');
    });

    var reg_type_id = "{{ $appInfo->regist_type }}";
    var company_type_id = "{{ $appInfo->org_type }}";
    var industrial_category_id = "{{ $appInfo->ind_category_id }}";
    var investment_type_id = "{{ $appInfo->invest_type }}";

    var key = reg_type_id + '-' + company_type_id + '-' + industrial_category_id + '-' + investment_type_id;

    loadApplicationDocs('docListDiv', key);



    @if (in_array(Auth::user()->user_type, ['5x505']) && $appInfo->status_id == 15)
    var unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>

    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ $appInfo->applicant_name }}",
        "{{ $appInfo->applicant_email }}",
        "{{ $appInfo->applicant_mobile }}",
        "{{ $appInfo->applicant_address }}"
        , unfixed_amounts);
    @endif
</script>

<script>
    $(document).ready(function () {
        var company_type = "{{$appInfo->company_type}}";

        if( company_type == ""){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I/We');
        }else if(company_type == 3){
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('I');
        }else{
            $('.i_we_dynamic').text('');
            $('.i_we_dynamic').text('We');
        }

        var declaration_q1 = "{{$appInfo->declaration_q1}}";
        var declaration_q2 = "{{$appInfo->declaration_q2}}";
        var declaration_q3 = "{{$appInfo->declaration_q3}}";

        if(declaration_q1 == 'Yes') {
            $('#if_declaration_q1_yes').css('display','inline');
        }

        if(declaration_q2 == 'Yes') {
            $('#if_declaration_q2_yes').css('display','inline');
        }

        if(declaration_q3 == 'Yes') {
            $('#if_declaration_q3_yes').css('display','inline');
        }


    });
</script>
