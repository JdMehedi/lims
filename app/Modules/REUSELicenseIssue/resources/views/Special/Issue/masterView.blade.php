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

@if($appInfo->status_id==15)
{!! Form::open([
        'url' => url('special_payment/store'),
        'method' => 'post',
        'class' => 'form-horizontal',
        'id' => 'application_form',
        'enctype' => 'multipart/form-data',
        'files' => 'true',
    ]) !!}
    @csrf
<div id="paymentPanel">

<h3>Payment & Submit</h3>
                <fieldset>
                    {{-- Service Fee Payment --}}
                    {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id), ['class' => 'form-control input-md required', 'id' => 'app_id']) !!}
                    <input type="hidden" id="process_type" name="process_type" value="{{$process_type_id}}">
                    <div id="pay_order_information" class="pay_order_fields">
                    
                        <div class="card card-magenta border border-magenta single_pay_order" id="single_pay_order_0">
                            <div class="card-header">
                                Pay Order Information
                                
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col-md-6 pay_order_fields">
                                        <div class="row">
                                            {!! Form::label('pay_order_copy', 'Pay Order Copy', ['class' => 'col-md-5 text-left required-star']) !!}
                                            
                                                <div class="col-md-7" id="pay_order_copy_preview">
                                                    <input type="file" style="border: none;"
                                                           class="form-control input-md require_check"
                                                           name="pay_order_copy" id="pay_order_copy"
                                                           onchange="createObjUrl(event, 'pay_order_copy_url_base64', true)"/>
                                                           
                                                          
                                                    <input type="hidden" id="pay_order_copy_url_base64"/>
                                                </div>
                                            
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('pay_order_number', 'Pay Order Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('pay_order_number', '', ['class' => 'form-control input-md require_check', 'id' => 'pay_order_number']) !!}
                                            </div>
                                        </div>
                                    </div>
                                

                                </div>
                               <div class ="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        {!! Form::label('bank_name', 'Bank Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::select('bank_name', [0=>'Select Bank Name'] + $bank_list, '', ['class' => 'form-control input-md require_check','id' => 'bank_name', 'onchange' => "getBranchByBankId('bank_name', this.value, 'branch_name')"]) !!}
                                        </div>
                                    </div>
                                </div>
                               
                                    <div class="col-md-6">
                                        <div class="row">
                                            {!! Form::label('branch_name', 'Branch Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('branch_name', [], '', ['class' => 'form-control input-md require_check', 'id' => 'branch_name']) !!}
                                            </div>
                                        </div>
                                    </div>
                               </div>
                                
                                    <div class="pay_order_fields">
                                       

                                       
                                    </div>

                                    <div class="float-right">
                        <button type="submit" id="submitPayment" style=" position: relative; z-index: 99999; " class="btn btn-success btn-md mt-2"
                                value="submit" name="actionBtn">Submit
                        </button>
                        </div>
                                
                            </div>

                            

                        </div>

                        

                        
                </div>

                    

                </fieldset>
                

</div>
{!! Form::close() !!}
@endif
<!--  -->



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

    @if(in_array(Auth::user()->user_type, ['4x404']))
    <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Related Reports
        </div>
        <div class="card-body" style="padding: 15px 25px;">
            
            @if(isset($appInfo->shortfall_reason))
                <a class="btn btn-primary" data-toggle="modal" data-target="#shortFallModal" href="#">View Shortfall Reason</a>
            @endif
        </div>
    </div>
    @endif   

        {{-- Company Informaiton --}}
        <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Company/ Organization Information
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>

            {{-- Registered Office Address --}}
            <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Registered Office Address
                </div>
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div class="col-md-8 ">
                                    <span>: {{ $appInfo->reg_office_district_en }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8 ">
                                    <span>: {{ $appInfo->reg_office_thana_en }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('reg_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->reg_office_address }}</span>
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
                <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_district', 'District', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_district_en }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_thana', 'Upazila/ Thana', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_thana_en }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                {!! Form::label('op_office_address', 'Address', ['class' => 'col-md-4 ']) !!}
                                <div
                                        class="col-md-8">
                                    <span>: {{ $appInfo->op_office_address }}</span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Applicant Profile --}}
        @includeIf('common.subviews.ApplicantProfile', ['mode' => 'view'])

        <div class="card card-magenta border border-magenta">
                <div class="card-header">
                    Other Inputs
                    
                </div>
                <div class="" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>


                   
                        @foreach($input_array as $key1=>$val1)
                        <div class="card card-magenta border border-magenta">

                        @foreach($val1 as $key2=>$val2)
                        <div class="col-md-12 card-header">
                        
                        
                        {{$val2['section_name']}}

                        </div>
                        <div class="row col-md-12">

                        @foreach($val2['section_value'] as $key3=>$val3)

                        <div class="card-body col-md-6" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
                            <div class="col-md-12">
                                <div class="form-group row">
                                <label  class="col-md-4">{{$val3['input_label']}}: </label>
                                @foreach($application_data as $key_appdata=>$appdata)
                                
                                @if( $key_appdata =='dynamic_'.str_replace(' ', '_', $val3['input_label']))
                                <div class="col-md-8 ">
                                    @if(is_array($appdata))
                                    <?php  $check_data=implode(",",$appdata);
                                    ?>
                                    {{ $check_data }}
                                    @else
                                    {{ $appdata }}
                                    @endif
                                    
                                    </div>
                                @endif
                                @endforeach
                                    
                                </div>
                            </div>
                        </div>

                        @endforeach
                        </div>
                        @endforeach

                        </div>
                        @endforeach
                    

                    
                </div>
            </div>


        
    </fieldset>
    <fieldset>
        {{-- Necessary attachment --}}
        <div class="card card-magenta border border-magenta">
        <div class="card-header">
            Required Documents
        </div>
        <div class="card-body" <?php echo !env('IS_MOBILE') ? 'style="padding: 15px 25px;"' : '' ?>>
            <div id="">
                <table class="table-bordered table-responsive table-striped" style="width: 100%;">
                    <thead>
                    <th style="padding: 10px;">Document Name</th>
                    <th style="padding: 10px;">File</th>    
                    </thead>
                    <tbody>
                        <?php $doc_index=0; ?>
                    @if(count($appDynamicDocInfo) > 0)
                        @foreach($appDynamicDocInfo as $key=>$docInfo)

                            @if(!empty($docInfo))
                                <tr>
                                    <td style="padding: 10px;">{{$doc_labels[$doc_index]}}</td>
                                    <td style="padding: 10px;" class="text-center" ><a target="_blank" href="{{url('/').'/'.$docInfo}}">View</a> </td>
                                </tr>
                            @endif
                            <?php $doc_index++; ?>
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
    //     @if (in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 54, 55, 56, 65, 60]))
    //     const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    //     const status_id = {{$appInfo->status_id}};
    //     let paymentInfo = {paymentName: 0, LicenseOrAnnualFee: 0, withOneYearAnnualFee: 0,annualFeeCurrentYear : 0, annualFeeYearCounting: 0};
    //     if (status_id == 15) {
    //         paymentInfo = {
    //             paymentName: 'License Fee With One Year Annual Fee',
    //             LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 1,
    //             annualFeeCurrentYear: 1, // numeric year
    //             annualFeeYearCounting: 5
    //         }
    //     } else if (status_id == 65) {
    //         paymentInfo = {
    //             paymentName: 'Second Year Annual Fee',
    //             LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 2, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     } else if (status_id == 54) {
    //         paymentInfo = {
    //             paymentName: 'Third Year Annual Fee',
    //             LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 3, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     } else if (status_id == 55) {
    //         paymentInfo = {
    //             paymentName: 'Fourth Year Annual Fee',
    //             LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 4, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     } else if (status_id == 56) {
    //         paymentInfo = {
    //             paymentName: 'Fifth Year Annual Fee',
    //             LicenseOrAnnualFee: 2, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 5, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     }  else if (status_id == 60) {
    //         paymentInfo = {
    //             paymentName: '4 Year Annual Fee or BG',
    //             LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 0, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     }
    //     loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
    //     'paymentPanel',
    //     "{{ CommonFunction::getUserFullName() }}",
    //     "{{ Auth::user()->user_email }}",
    //     "{{ Auth::user()->user_mobile }}",
    //     "{{ Auth::user()->contact_address }}"
    //     , unfixed_amounts, '', JSON.stringify(paymentInfo));
    // @elseif(in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [46, 64]))
    //     let payOrderInfo = {};
    //     @if($appInfo->status_id == 46)
    //         let paymentInfo = {
    //             paymentName: 'License Fee With One Year Annual Fee',
    //             LicenseOrAnnualFee: 1, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 1,
    //             annualFeeCurrentYear: 1, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //         payOrderInfo = @json($pay_order_info);
    //     @elseif($appInfo->status_id == 64)
    //         paymentInfo = {
    //             paymentName: 'Shortfall for BG payment',
    //             LicenseOrAnnualFee: 0, // license fee=1; annual fee=2
    //             withOneYearAnnualFee: 0,
    //             annualFeeCurrentYear: 0, // numeric year
    //             annualFeeYearCounting: 0
    //         }
    //     @endif
    //     const currentPayment = @json($payment_info);
    //     const unfixed_amounts = <?php echo json_encode($unfixed_amounts ); ?>;
    //     loadPaymentPanelV2('{{ $appInfo->id }}', '{{ $process_type_id }}', '{{ $payment_step_id }}',
    //         'paymentPanel',
    //         "{{ CommonFunction::getUserFullName() }}",
    //         "{{ Auth::user()->user_email }}",
    //         "{{ Auth::user()->user_mobile }}",
    //         "{{ Auth::user()->contact_address }}"
    //         , unfixed_amounts, JSON.stringify(payOrderInfo), JSON.stringify(paymentInfo), JSON.stringify(currentPayment));
    // @endif
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
