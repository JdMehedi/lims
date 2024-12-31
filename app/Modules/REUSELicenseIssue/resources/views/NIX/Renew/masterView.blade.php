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

{{--Addons form data--}}
@if(in_array($appInfo->status_id, [2,5,17,18,19,20,22,30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Related Reports
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            @if(isset($appInfo->dd_file_1))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation Report</a>
            @endif
            @if(isset($appInfo->dd_file_2))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission Meeting Minutes</a>
            @endif
            @if(isset($appInfo->dd_file_3))
                <a class="btn btn-primary" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry Approval  </a>
            @endif
            @if(isset($appInfo->shortfall_reason))
                <a class="btn btn-primary" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
            @endif
            @if(isset($latter[1]))
            <a class="btn btn-primary" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
            @endif
            @if(isset($latter[2]))
            <a class="btn btn-primary" target="_blank" href="{{url($latter[2])}}">Request for Payment Letter</a>
            @endif
            @if(isset($latter[3]))
            <a class="btn btn-primary" target="_blank" href="{{url($latter[3])}}">BPO/Call Center Registration Certificate</a>
            @endif
            @if(isset($latter[4]))
            <a class="btn btn-primary mt-2
            " target="_blank" href="{{url($latter[4])}}">BG Payment Letter</a>
            @endif
        </div>
    </div>
@endif

<div id="paymentPanel"></div>

<div class="card-body" style="padding: 0px!important;" id="applicationForm">

    <fieldset>
        {{-- Company Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view'])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])

        {{-- Name of Authorized Signatory and Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view'])

        {{-- Shareholder/partner/proprietor Details --}}
        @includeIf('common.subviews.ShareHolder', ['mode' => 'view'])
        @if($appInfo->trade_license_number)
            @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'view'])
        @endif


    </fieldset>
    <fieldset>
        @includeIf('common.subviews.RequiredDocuments', ['mode' => 'view'])

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
                                Declaration Has any Application for License of ISP been rejected before?

                                <div style="margin-top: 20px;" id="declaration_q1">
                                    {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes', 'disabled']) }}
                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;','id' => 'declaration_q1_no','disabled']) }}
                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q1_text', ($appInfo->declaration_q1 == 'Yes') ? $appInfo->declaration_q1_text : null, array('class' =>'form-control input', 'id'=>'if_declaration_q1_yes', 'style'=>($appInfo->declaration_q1 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Please give data of application and reasons for rejection', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>

                            </li>
                            <li>
                                Has any License of ISP issued previously to the Applicant/any Share Holder/Partner been cancelled?

                                <div style="margin-top: 20px;" id="declaration_q2">
                                    {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline','id' => 'declaration_q2_yes','disabled']) }}
                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;','id' => 'declaration_q2_no','disabled']) }}
                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q2_text', ($appInfo->declaration_q2 == 'Yes') ? $appInfo->declaration_q2_text : null, array('class' =>'form-control input','id'=>'if_declaration_q2_yes', 'style'=>($appInfo->declaration_q2 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>

                            </li>

                            <li>
                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the Commission?

                                <div style="margin-top: 20px;" id="declaration_q3">
                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3 == 'Yes' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3 == 'No' ? true : false, ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q3_text', ($appInfo->declaration_q3 == 'Yes') ? $appInfo->declaration_q3_text : null, array('class' =>'form-control input','id'=>'if_declaration_q3_yes', 'style'=>($appInfo->declaration_q3 == 'Yes') ? 'display:block;' :'display:none;', 'placeholder'=>'Enter Please give details', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}

                                </div>

                            </li>
                            @php $subject = ($appInfo->company_type == 3) ? 'I' : 'We' @endphp
                            <li ><span class="i_we_dynamic">{{ $subject }}</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">{{ $subject }}</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>

    </fieldset>


    <div class="float-left">
        <a href="{{ url('client/nix-license-renew/list/'. Encryption::encodeId(10)) }}"
           class="btn btn-default btn-md cancel" value="close" name="closeBtn"
           id="save_as_draft">Close
        </a>
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




    @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 25, 80, 54, 55, 56, 57, 69, 70, 71, 72, 73, 74, 75, 76, 77]))
    const fixed_amounts = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0,
            7: 0,
            8: 0,
            9: 0,
            10: 0
        };
    const status_id = {{$appInfo->status_id}};
    let paymentStepId = 1;
    let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};
    if (status_id == 15) {
        paymentInfo = {
            paymentName: 'License Fee',
            LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 0, // numeric year
            annualFeeYearCounting: 15
        }
        paymentStepId = 1;
    } else if (status_id == 25) {
        paymentInfo = {
            paymentName: 'First Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 1, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 2
    } else if (status_id == 80) {

        paymentInfo = {
            paymentName: 'Second Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 2, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 3;
    } else if (status_id == 54) {
        paymentInfo = {
            paymentName: 'Third Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 3, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 4;
    } else if (status_id == 55) {
        paymentInfo = {
            paymentName: 'Fourth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 4, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 5;
    }else if (status_id == 56) {
        paymentInfo = {
            paymentName: 'Fifth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 5, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 6;
    }else if (status_id == 57) {
        paymentInfo = {
            paymentName: 'Sixth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 6, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 7;
    }else if (status_id == 69) {
        paymentInfo = {
            paymentName: 'Seventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 7, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 8;
    }else if (status_id == 70) {
        paymentInfo = {
            paymentName: 'Eighth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 8, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 9;
    }else if (status_id == 71) {
        paymentInfo = {
            paymentName: 'Nineth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 9, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 10;
    }else if (status_id == 72) {
        paymentInfo = {
            paymentName: 'Tenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 10, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 11;
    }else if (status_id == 73) {
        paymentInfo = {
            paymentName: 'Eleventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 11, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 12;
    }else if (status_id == 74) {
        paymentInfo = {
            paymentName: 'Twelveth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 12, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 13;
    }else if (status_id == 75) {
        paymentInfo = {
            paymentName: 'Thirteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 13, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 14;
    }else if (status_id == 76) {
        paymentInfo = {
            paymentName: 'Fourteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 14, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 15;
    }else if (status_id == 77) {
        paymentInfo = {
            paymentName: 'Fifteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 15, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 16;
    }

    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', paymentStepId,
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , fixed_amounts, '', JSON.stringify(paymentInfo));

            @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46]))
    const payOrderInfo = @json($pay_order_info);
    const fixed_amounts = <?php echo json_encode($fixed_amounts ); ?>;
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , fixed_amounts, JSON.stringify(payOrderInfo));
    @endif
</script>

<script>
    $(document).ready(function () {

        var type = "{{$appInfo->isp_license_type}}";
        if (type == 1) {
            $('#division').css('display', 'none');
            $('#district').css('display', 'none');
            $('#thana').css('display', 'none');
        }
        if (type == 2) {
            $('#division').css('display', 'inline');
            $('#district').css('display', 'none');
            $('#thana').css('display', 'none');
        }

        if (type == 3) {
            $('#division').css('display', 'none');
            $('#district').css('display', 'inline');
            $('#thana').css('display', 'none');
        }

        if (type == 4) {
            $('#division').css('display', 'none');
            $('#district').css('display', 'inline');
            $('#thana').css('display', 'inline');
        }


        var declaration_q1 = "{{$appInfo->declaration_q1}}";
        var declaration_q2 = "{{$appInfo->declaration_q2}}";
        var declaration_q3 = "{{$appInfo->declaration_q3}}";

        if (declaration_q1 == 'Yes') {
            $('#if_declaration_q1_yes').css('display', 'inline');
        }

        if (declaration_q2 == 'Yes') {
            $('#if_declaration_q2_yes').css('display', 'inline');
        }

        if (declaration_q3 == 'Yes') {
            $('#if_declaration_q3_yes').css('display', 'inline');
        }


    });
</script>
