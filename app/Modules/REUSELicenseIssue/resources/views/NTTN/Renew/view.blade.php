<div id="paymentPanel"></div>

<div class="card" style="border-radius: 10px;" id="applicationForm">
    {!! Form::open([
        'url' => url('isp-license-issue/store'),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
        'onSubmit' => 'enablePath()',
    ]) !!}
    @csrf
    <fieldset>
        {{--Addons form data--}}
        @if(in_array($appInfo->status_id, [2,5,17,18,19,20,22,30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Related Reports
                </div>
                <div class="card-body" style="padding: 15px 25px;">
                    @if(isset($appInfo->dd_file_1))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_1)}}">View Evaluation Report</a>
                    @endif
                    @if(isset($appInfo->dd_file_2))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_2)}}">View Commission Meeting Minutes</a>
                    @endif
                    @if(isset($appInfo->dd_file_3))
                        <a class="btn btn-primary m-1" target="_blank" href="{{url($appInfo->dd_file_3)}}">View Ministry Approval  </a>
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
                    @if(isset($latter[4]))
                    <a class="btn btn-primary m-1
                    " target="_blank" href="{{url($latter[4])}}">BG Payment Letter</a>
                    @endif
                </div>
            </div>
        @endif


        {{-- Company Informaiton --}}
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view', 'selected' => 1])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view', 'selected' => 1])

        {{-- Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view', 'selected' => 1])

        {{-- Shareholder/partner/proprietor Details --}}
        @includeIf('common.subviews.Shareholder', ['mode' => 'view', 'selected' => 1])
        @if($appInfo->trade_license_number)
            @includeIf('common.subviews.ResubmitApplicationDetails', ['mode' => 'view'])
        @endif
    </fieldset>
    <fieldset>
        {{-- Necessary attachment --}}
        @includeIf('common.subviews.RequiredDocuments', ['mode' => 'view', 'selected' => 1])

        {{-- Note --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Declaration <span style="float: right;"></span>
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-12">
                        <ol type="1">
                            <li><span class="i_we_dynamic">I/we</span> declare that all the information furnished in this application form is true and correct. <span class="i_we_dynamic">I/we</span> understand that approval from the Commission for this application is based on information as declared in this application. Should any of the information as declared be incorrect, then any License granted by the Commission may be cancelled.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/we</span> also declare that <span class="i_we_dynamic">I/we</span> have read, understood and undertake to comply, with all the terms and conditions outlined or referred to in the Commission document entitled Regulatory and Licensing Guidelines for invitation of application for granting of Vehicle Tracking Service License/Approval in the country, and those terms and conditions included in the License/Approval to be issued to us/me, if this application is approved by the Commission.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="float-left">
        <a href="{{ url('client/isp-license-issue/list/'. Encryption::encodeId(51)) }}" class="btn btn-default btn-md cancel"
           value="close" name="closeBtn"
           id="save_as_draft">Close
        </a>
    </div>
    {!! Form::close() !!}
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
    let company_type = "{{$appInfo->org_type}}";

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
    $(document).on('click', '.cancelcounterpayment', function () {
        return confirm('Are you sure?');
    });

    @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 25, 54, 55, 56, 57, 69, 70, 71, 72, 73, 74, 75, 76, 77]))
    const unfixed_amounts = {
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
            paymentName: 'License Fee With One Year Annual Fee',
            LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
            withOneYearAnnualFee: 1,
            annualFeeCurrentYear: 1, // numeric year
            annualFeeYearCounting: 15
        }
        paymentStepId = 2;
    } else if (status_id == 25) {
        paymentInfo = {
            paymentName: 'Second Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 2, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 3
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
    } else if (status_id == 56) {
        paymentInfo = {
            paymentName: 'Fifth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 5, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 6;
    } else if (status_id == 57) {
        paymentInfo = {
            paymentName: 'Sixth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 6, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 7;
    } else if (status_id == 69) {
        paymentInfo = {
            paymentName: 'Seventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 7, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 8;
    } else if (status_id == 70) {
        paymentInfo = {
            paymentName: 'Eighth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 8, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 9;
    } else if (status_id == 71) {
        paymentInfo = {
            paymentName: 'Nineth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 9, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 10;
    } else if (status_id == 72) {
        paymentInfo = {
            paymentName: 'Tenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 10, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 11;
    } else if (status_id == 73) {
        paymentInfo = {
            paymentName: 'Eleventh Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 11, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 12;
    } else if (status_id == 74) {
        paymentInfo = {
            paymentName: 'Twelveth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 12, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 13;
    } else if (status_id == 75) {
        paymentInfo = {
            paymentName: 'Thirteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 13, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 14;
    } else if (status_id == 76) {
        paymentInfo = {
            paymentName: 'Fourteenth Year Annual Fee',
            LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
            withOneYearAnnualFee: 0,
            annualFeeCurrentYear: 14, // numeric year
            annualFeeYearCounting: 0
        }
        paymentStepId = 15;
    } else if (status_id == 77) {
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
        , unfixed_amounts, '', JSON.stringify(paymentInfo));

    @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46]))
    const payOrderInfo = @json($pay_order_info);
    const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , unfixed_amounts, JSON.stringify(payOrderInfo));
    @endif

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
