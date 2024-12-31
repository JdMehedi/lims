<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/stylesheets/custom.min.css') }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/jquery-steps/jquery.steps.css") }}">
<link rel="stylesheet" href="{{ asset("assets/plugins/intlTelInput/css/intlTelInput.css") }}"/>
<style>
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
        display: inline-table!important;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .tbl-custom-header{
        border: 1px solid;
        padding: 5px;
        text-align: center;
        font-weight: 600;
    }
</style>


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
        @if(in_array($appInfo->status_id, [2,5,15, 16, 50, 51, 52, 53, 54, 55, 56, 57, 60, 62, 63, 64, 65, 25, 17, 18, 19, 20, 22, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 47, 48, 49]))
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
                <a class="btn btn-primary" target="_blank" href="{{url($latter[1])}}">Shortfall Letter  </a>
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
        @includeIf('common.subviews.CompanyInfo', ['mode' => 'view', 'extra' => ['address2']])

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view', 'extra' => ['address2']])

        {{-- Contact Person --}}
        @includeIf('common.subviews.ContactPerson', ['mode' => 'view', 'extra' => ['address2']])

        {{-- Types of ISP License Applied for --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Types Of ISP License Applied for
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {!! Form::label('type_of_isp_licensese', 'Types Of ISP License', ['class' => 'col-md-4 ']) !!}
                            <div class="col-md-8">
                                @if($appInfo->isp_license_type == 1)
                                    <span>: Nationwide</span>
                                @elseif($appInfo->isp_license_type == 2)
                                    <span>: Divisional</span>
                                @elseif($appInfo->isp_license_type == 3)
                                    <span>: District</span>
                                @elseif($appInfo->isp_license_type == 4)
                                    <span>: Thana/Upazila</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($appInfo->isp_license_type > 1)
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('isp_licensese_area_division', 'Division', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->isp_license_division }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    @if($appInfo->isp_license_type > 2)
                        <div class="col-md-6" id="district" style="display: none;">
                            <div class="form-group row">
                                {!! Form::label('isp_license_area_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->isp_license_district }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($appInfo->isp_license_type > 3)
                        <div class="col-md-6" id="thana">
                            <div class="form-group row">
                                {!! Form::label('isp_license_area_thana', 'Thana', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8">
                                    <span>: {{ $appInfo->isp_license_upazila }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Shareholder/partner/proprietor Details --}}
        @includeIf('common.subviews.Shareholder', ['mode' => 'view'])

        {{-- Equipment List --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Proposed Equipment List
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <table class="table table-bordered" id="equipment_tbl">
                    <thead>
                    <tr class="text-center">
                        <th width="7%" class="text-center">SL No.</th>
                        <th width="25%">Equipment Name</th>
                        <th width="20%">Brand & Model</th>
                        <th width="20%">Quantity</th>
                        <th width="20%">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($isp_equipment_list) && count($isp_equipment_list) > 0)
                        @foreach($isp_equipment_list as $index=>$value)
                            <tr row_id="equipment_{{$index+1}}">
                                <td style="text-align: center;">{{$index+1}}</td>
                                <td style="text-align: center;"> {{ $value->name }} </td>
                                <td style="text-align: center;"> {{ $value->brand_model }} </td>
                                <td style="text-align: center;"> {{ $value->quantity }} </td>
                                <td style="text-align: center;"> {{ $value->remarks }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No available data.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Proposed Tariff Chart --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Proposed Tariff Chart
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <table class="table table-bordered" id="tariffChart_tbl">
                    <thead>
                    <tr class="text-center">
                        <th width="3%" class="text-center">SL No.</th>
                        <th width="23%">Packages Name/Plan</th>
                        <th width="22%">Internet Bandwidth Package <br> Speed (Kbps/Mbps)</th>
                        <th width="14%">Price(BDT)</th>
                        <th width="15%">Duration(in Days)</th>
                        <th width="15%">Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($isp_tariff_chart_list) && count($isp_tariff_chart_list) > 0)
                        @foreach($isp_tariff_chart_list as $index=>$value)
                            <tr row_id="tariffChart_{{$index+1}}">
                                <td style="text-align: center;">{{$index+1}}</td>
                                <td style="text-align: center;"> {{ $value->package_name_plan }} </td>
                                <td style="text-align: center;"> {{ $value->bandwidth_package }} </td>
                                <td style="text-align: center;"> {{ $value->price }} </td>
                                <td style="text-align: center;"> {{ $value->duration }} </td>
                                <td style="text-align: center;"> {{ $value->remarks }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No available data.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </fieldset>
    <fieldset>
        {{-- Necessary attachment --}}
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
                                Has any Application for License of ISP been rejected before?
                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q1', 'Yes', $appInfo->declaration_q1=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q1_yes','disabled']) }}
                                    {{ Form::label('declaration_q1_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q1', 'No', $appInfo->declaration_q1=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q1_no','disabled']) }}
                                    {{ Form::label('declaration_q1_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q1_text', $appInfo->declaration_q1_text, array('class' =>'form-control input ', 'id'=>'if_declaration_q1_yes', 'style'=>'display:none;', 'placeholder'=>'', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>
                            </li>
                            <li>
                                Has any License of ISP issued previously to the Applicant/any Share Holder/Partner been
                                cancelled?

                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q2', 'Yes', $appInfo->declaration_q2=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q2_yes','disabled']) }}
                                    {{ Form::label('declaration_q2_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q2', 'No', $appInfo->declaration_q2=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q2_no','disabled']) }}
                                    {{ Form::label('declaration_q2_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 20px;">
                                    {{ Form::textarea('declaration_q2_text', $appInfo->declaration_q2_text, array('class' =>'form-control input', 'id'=>'if_declaration_q2_yes', 'style'=>'display:none;', 'placeholder'=>'', 'cols' => 5, 'rows' =>5, '' => '','readonly'))}}
                                </div>
                            </li>
                            <li>
                                Do the Applicant/any Share Holder/Partner hold any other Operator Licenses from the
                                Commission?
                                <div style="margin-top: 20px;">
                                    {{ Form::radio('declaration_q3', 'Yes', $appInfo->declaration_q3=='Yes'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline', 'id' => 'declaration_q3_yes','disabled']) }}
                                    {{ Form::label('declaration_q3_yes', 'Yes', ['class' => 'form-check-label','style'=>'display: inline;  margin-left:10px;']) }}

                                    {{ Form::radio('declaration_q3', 'No', $appInfo->declaration_q3=='No'?'checked':'', ['class'=>'form-check-input', 'style'=>'display: inline;  margin-left:10px;', 'id' => 'declaration_q3_no','disabled']) }}
                                    {{ Form::label('declaration_q3_no', 'No', ['class' => 'form-check-label','style'=>'display: inline; margin-left:35px;']) }}
                                </div>
                                <div style="margin-top: 60px; margin-left: 5px; display: none;" id="if_declaration_q3_yes">
                                    <a class="btn btn-file" target="_blank" href="{{asset($appInfo->declaration_q3_doc)}}"><i class="fa fa-file"></i> View Document</a>
                                </div>
                            </li>
                            <li ><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the guidelines/terms and conditions, for the license and <span class="i_we_dynamic">I/We</span> undertake to comply with the terms and conditions therein.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> hereby certify that <span class="i_we_dynamic">I/We</span> have carefully read the section 36 of Bangladesh Telecommunication Regulation Act, 2001 and <span class="i_we_dynamic">I/We</span> are not disqualified from obtaining the license.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that any information furnished in this application are found fake or false or this application form is not duly filled up, the Commission, at any time without any reason whatsoever, may reject the whole application.</li>
                            <li style="margin-top: 20px;"><span class="i_we_dynamic">I/We</span> understand that if at any time any information furnished for obtaining the license is found incorrect then the license if granted on the basis of such application shall deemed to be cancelled and shall be liable for action as per Bangladesh Telecommunication Regulation Act, 2001.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Note --}}
        <div class="card card-magenta border border-magenta">
            <div class="card-header">
                Note <span style="float: right;"></span>
            </div>
            <div class="card-body" style="padding: 15px 25px;">
                <div class="row">
                    <div class="col-md-12">
                        <ol style="list-style-type:disc;">
                            <li>The licensee shall have to apply before 180 (one hundred and eighty) days of the expiration of duration of its license or else the license shall be cancelled as per law and penal action shall follow, if the licensee continues its business thereafter without valid license. The late fees/ fines shall be recoverable under the Public Demand Recovery Act, 1913 (PDR Act, 1913) if the licensee fails to submit the fees and charges to the Commission in due time.</li>
                            <li>Application without the submission of complete documents and information will not be accepted.</li>
                            <li>Payment should be made by a Pay order/ Demand Draft in favour of Bangladesh Telecommunication Regulatory Commission (BTRC).</li>
                            <li>Fees and charges are not refundable.</li>
                            <li>The Commission is entitled to change this from time to time if necessary.</li>
                            <li>Updated documents shall be submitted during application.</li>
                            <li>Submitted documents shall be duly sealed and signed by the applicant.</li>
                            {{--                                        <li>For New Applicant only A, B and E will be applicable.</li>--}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="float-left">
        <a href="{{ url('client/isp-license-issue/list/'. Encryption::encodeId(1)) }}" class="btn btn-default btn-md cancel"
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

    @if(empty($appInfo->bulk_status))
        @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 54, 55, 56, 65, 60]))
        const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
        const status_id = {{$appInfo->status_id}};
        let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};
        if (status_id == 15) {
            paymentInfo = {
                paymentName: 'License Fee With One Year Annual Fee',
                LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
                withOneYearAnnualFee: 1,
                annualFeeCurrentYear: 1, // numeric year
                annualFeeYearCounting: 5
            }
        } else if (status_id == 65) {
            paymentInfo = {
                paymentName: 'Second Year Annual Fee',
                LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 2, // numeric year
                annualFeeYearCounting: 0
            }
        } else if (status_id == 54) {
            paymentInfo = {
                paymentName: 'Third Year Annual Fee',
                LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 3, // numeric year
                annualFeeYearCounting: 0
            }
        } else if (status_id == 55) {
            paymentInfo = {
                paymentName: 'Fourth Year Annual Fee',
                LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 4, // numeric year
                annualFeeYearCounting: 0
            }
        } else if (status_id == 56) {
            paymentInfo = {
                paymentName: 'Fifth Year Annual Fee',
                LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 5, // numeric year
                annualFeeYearCounting: 0
            }
        }  else if (status_id == 60) {
            paymentInfo = {
                paymentName: '4 Year Annual Fee or BG',
                LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 0, // numeric year
                annualFeeYearCounting: 0
            }
        }
        loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
        'paymentPanel',
        "{{ CommonFunction::getUserFullName() }}",
        "{{ Auth::user()->user_email }}",
        "{{ Auth::user()->user_mobile }}",
        "{{ Auth::user()->contact_address }}"
        , unfixed_amounts, '', JSON.stringify(paymentInfo));
    @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46, 64]))
        let payOrderInfo = {};
        @if($appInfo->status_id == 46)
            let paymentInfo = {
                paymentName: 'License Fee With One Year Annual Fee',
                LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
                withOneYearAnnualFee: 1,
                annualFeeCurrentYear: 1, // numeric year
                annualFeeYearCounting: 0
            }
            payOrderInfo = @json($pay_order_info);
        @elseif($appInfo->status_id == 64)
            paymentInfo = {
                paymentName: 'Shortfall for BG payment',
                LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
                withOneYearAnnualFee: 0,
                annualFeeCurrentYear: 0, // numeric year
                annualFeeYearCounting: 0
            }
        @endif
        const currentPayment = @json($payment_info);
        const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
        loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
            'paymentPanel',
            "{{ CommonFunction::getUserFullName() }}",
            "{{ Auth::user()->user_email }}",
            "{{ Auth::user()->user_mobile }}",
            "{{ Auth::user()->contact_address }}"
            , unfixed_amounts, JSON.stringify(payOrderInfo), JSON.stringify(paymentInfo), JSON.stringify(currentPayment));
    @endif
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

<script>
    setTimeout(function() {
        let annualPayment = document.getElementById('annual_fee');
        if(annualPayment.checked){
            let payOrderPayment = document.getElementById('pay_order_payment');
            if(payOrderPayment.checked){
                const bgPaymentFields = document.getElementsByClassName('bg_payment_fields');
                for(const elem of bgPaymentFields) {
                    elem.style.display = 'none';
                    for(const inputField of elem.querySelectorAll("input, select")) {
                        inputField.classList.remove('required');
                        inputField.classList.remove('require_check');
                        inputField.classList.remove('error');
                        inputField.required = false;
                    }
                }
            }
        }
    },8000);
</script>
