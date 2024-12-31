<style>
    .payment-info {
        font-weight: normal;
    }
    .pr-20 {
        padding-right: 1.25rem;
    }
    .pl-20 {
        padding-left: 1.25rem;
    }
    .pr-0 {
        padding-right: 0;
    }
    .pl-0 {
        padding-left: 0;
    }
</style>
<?php $annualPaymentHeading = [
    1 => 'First Year Annual Payment',
    2 => 'Second Year Annual Payment',
    3 => 'Third Year Annual Payment',
    4 => 'Fourth Year Annual Payment',
    5 => 'Fifth Year Annual Payment',
    6 => 'Sixth Year Annual Payment',
    7 => 'Seventh Year Annual Payment',
    8 => 'Eighth Year Annual Payment',
    9 => 'Ninth Year Annual Payment',
    10 => 'Tenth Year Annual Payment',
    11 => 'Eleventh Year Annual Payment',
    12 => 'Twelfth Year Annual Payment',
    13 => 'Thirteenth Year Annual Payment',
    14 => 'Fourteenth Year Annual Payment',
    15 => 'Fifteenth Year Annual Payment',
]?>
<div class="card">
    <div>
        <h5 class="card-header bg-success"><strong>Payment Information</strong></h5>
    </div>
    <div class="card-body payment-info">

@if($is_special==1)

<!-- Dynamic Payment -->

@foreach($payment_info as $index => $payment_info)
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        Pay Order Information
                                    </div>
                                    <div class="card-body" style="padding: 15px 25px;">
                                        <div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Pay Order Number</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->pay_order_number }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($payment_info->pay_order_date)
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Pay Order Date</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->pay_order_date }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                            </div>
                                            <div class="row form-group">
                                            @if($payment_info->name)
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Bank Name</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Branch Name</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->branch_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <span class="v_label">Pay Order Copy</span>
                                                                <span class="float-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                @if(isset($payment_info->pay_order_copy))
                                                                    <a target="_blank" href="{{url($payment_info->pay_order_copy)}}">View Pay Order Copy</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

<!-- Dynamic payment end -->
@else
        @foreach ($payment_info as $key => $payment)
            @if($payment->is_bg != 1)
                <div class="card">
                @if($payment->payment_type === 'pay_order')
                    <h5 class="card-header bg-info">

                        @if($payment->payment_step_id === 1)
                            <b>Application Submission Payment</b>
                        @elseif($payment->payment_step_id === 2)
                            <b>Application License Payment</b>
                        @elseif(in_array($payment->payment_step_id, [3, 4, 5, 6, 7, 8, 9, 10]))
                            @if($payment->payment_step_id == 7 && $payment->year == 0 && in_array($payment->process_type_id, [1, 2]))
                                <b>Four Years Annual Fee</b>
                            @else
                                <b>{{$annualPaymentHeading[$payment->year]}}</b>
                            @endif
                        @endif
                    </h5>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Contact name</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->contact_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Contact email</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->contact_email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Contact phone</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->contact_no }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Contact address</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->address }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{$payment->pay_amount - intval($payment->first_annual_fee) - intval($payment->delay_fee)}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">VAT on pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->vat_on_pay_amount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty(intval($payment->first_annual_fee)) || !empty(intval($payment->delay_fee)))
                            <div class="row form-group">
                                @if(!empty(intval($payment->first_annual_fee)))
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="v_label">1st year annual fee</span>
                                                <span class="float-right">&#58;</span>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $payment->first_annual_fee }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty(intval($payment->delay_fee)))
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="v_label">Delay fee</span>
                                                <span class="float-right">&#58;</span>
                                            </div>
                                            <div class="col-md-6">
                                                {{ $payment->delay_fee }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->transaction_charge_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">VAT on transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->vat_on_transaction_charge }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--pay order & bank guarantee info--}}
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Payment Type</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if($payment->payment_type === 'online_payment')
                                            Online Payment
                                        @elseif($payment->payment_type === 'pay_order')
                                            Pay Order
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($payment->payment_type === 'pay_order' && $payment->multiple_pay_order_info)
                            @foreach($payment->multiple_pay_order_info as $index => $payment_info)
                                <div class="card card-magenta border border-magenta">
                                    <div class="card-header">
                                        Pay Order Information
                                    </div>
                                    <div class="card-body" style="padding: 15px 25px;">
                                        <div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Pay Order Number</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->pay_order_num }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Pay Order Date</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->pay_order_date }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Bank Name</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->bank_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="v_label">Branch Name</span>
                                                            <span class="float-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{ $payment_info->branch_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    @if($payment->payment_type === 'pay_order')
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <span class="v_label">Pay Order Copy</span>
                                                                <span class="float-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                @if(isset($payment_info->pay_order_copy))
                                                                    <a target="_blank" href="{{url($payment_info->pay_order_copy)}}">View Pay Order Copy</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        {{--bank guarantee--}}
                        @if(!empty($payment->bg_bank_name))
                            <div class="card card-magenta border border-magenta">
                                <div class="card-header">
                                    Bank Guarantee
                                </div>
                                <div class="card-body">
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 pr-20">
                                                    <span class="v_label">Bank Name</span>
                                                    <span class="float-right">&#58;</span>
                                                </div>
                                                <div class="col-md-6 pl-0">
                                                    {{ $payment->bg_bank_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 pr-0">
                                                    <span class="v_label">Branch Name</span>
                                                    <span class="float-right">&#58;</span>
                                                </div>
                                                <div class="col-md-6 pl-20">
                                                    {{ $payment->bg_branch_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 pr-20">
                                                    <span class="v_label">Expire Date</span>
                                                    <span class="float-right">&#58;</span>
                                                </div>
                                                <div class="col-md-6 pl-0">
                                                    {{ $payment->bg_expire_date }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 pr-0">
                                                    <span class="v_label">Bank Guarantee Copy</span>
                                                    <span class="float-right">&#58;</span>
                                                </div>
                                                <div class="col-md-6 pl-20">
                                                    @if(isset($payment->bg_copy))
                                                        <a target="_blank" href="{{url($payment->bg_copy)}}">View Bank Guarantee</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif
                        {{--pay total & payment status--}}
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Total Amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $payment->total_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="v_label">Payment status</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($payment->payment_status == 0)
                                            <span class="label label-warning">Pending</span>
                                        @elseif($payment->payment_status == -1)
                                            <span class="label label-info">In-progress</span>
                                        @elseif($payment->payment_status == 1)
                                            <span class="label label-success">Paid</span>
                                        @elseif($payment->payment_status == 2)
                                            <span class="label label-danger">Exception</span>
                                        @elseif($payment->payment_status == 3)
                                            <span class="label label-warning">Waiting for payment confirmation</span>
                                        @elseif($payment->payment_status == -3)
                                            <span class="label label-warning">Payment cancelled</span>
                                        @else
                                            <span class="label label-warning">Invalid status</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div >
                        <h5 class="card-header bg-info"><b>{{ $payment->payment_name }}</b></h5>
                    </div>
                    <div class="card-body">
                        @if($payment->process_type_id != '1' || $payment->process_type_id != '2' )
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact name</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact email</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_email }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact phone</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_no }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact address</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->pay_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">VAT on pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->vat_on_pay_amount }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->transaction_charge_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">VAT on transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->vat_on_transaction_charge }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Total Amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->total_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Payment status</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        @if ($payment->payment_status == 0)
                                            <span class="label label-warning">Pending</span>
                                        @elseif($payment->payment_status == -1)
                                            <span class="label label-info">In-progress</span>
                                        @elseif($payment->payment_status == 1)
                                            <span class="label label-success">Paid</span>
                                        @elseif($payment->payment_status == 2)
                                            <span class="label label-danger">Exception</span>
                                        @elseif($payment->payment_status == 3)
                                            <span class="label label-warning">Waiting for payment confirmation</span>
                                        @elseif($payment->payment_status == -3)
                                            <span class="label label-warning">Payment cancelled</span>
                                        @else
                                            <span class="label label-warning">Invalid status</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Ref transaction no</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$payment->ref_tran_no}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($payment->process_type_id != '1' )
                            <div class="row form-group">
                                <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="v_label">Payment mode</span>
                                    <span class="float-right">&#58;</span>
                                </div>
                                <div class="col-md-7">
                                    {{ $payment->pay_mode }}
                                </div>
                            </div>
                        </div>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="collapse" id="multiCollapseExample{{ $payment->id }}">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0 no-margin">
                                            <thead>
                                                <tr>
                                                    <th>Account No.</th>
                                                    <th>Fees Type</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (explode(';', $payment->payment_details) as $distribution)
                                                    @php
                                                        $distribution_info = explode(',', $distribution);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ isset($distribution_info[0]) ? $distribution_info[0] : 'N/A' }}
                                                        </td>
                                                        <td>{{ isset($distribution_info[1]) ? $payment_distribution_types[trim($distribution_info[1])] : 'N/A' }}
                                                        </td>
                                                        <td>{{ isset($distribution_info[2]) ? $distribution_info[2] : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            <a href="/spg/{{ $payment->pay_mode_code == 'A01' ? 'counter-' : '' }}payment-voucher/{{ Encryption::encodeId($payment->id) }}"
                                target="_blank" class="btn btn-info btn-sm">
                                <strong> Download voucher</strong>
                            </a>
                        </div>

                        {{-- Counter payment, 3 = Waiting for Payment Confirmation --}}
                        @if ($payment->payment_status == 3 && $app_status == 3 && in_array(Auth::user()->user_type, ['5x505', '6x606']))
                            <div class="float-right">
                                <a href="/spg/counter-payment-check/{{ Encryption::encodeId($payment->id) }}/{{ Encryption::encodeId(0) }}"
                                    class="btn btn-danger btn-sm cancelcounterpayment">
                                    <strong> Cancel payment request</strong>
                                </a>
                                <a href="/spg/counter-payment-check/{{ Encryption::encodeId($payment->id) }}/{{ Encryption::encodeId(1) }}"
                                    class="btn btn-primary btn-sm">
                                    <strong> Confirm payment request</strong>
                                </a>
                            </div>
                        @endif
                        <div class="clearfix"></div>
                    </div>
                @endif
            </div>
            @else
                <div class="card">
                    <div >
                        <h5 class="card-header bg-info"><b>Bank Guarantee</b></h5>
                    </div>
                    <div class="card-body">
                        @if(!in_array($payment->process_type_id, [1, 2]))
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact name</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact email</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_email }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact phone</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->contact_no }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Contact address</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->pay_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">VAT on pay amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->vat_on_pay_amount }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->transaction_charge_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">VAT on transaction charge</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->vat_on_transaction_charge }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Total Amount</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ $payment->total_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="v_label">Payment status</span>
                                        <span class="float-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        @if ($payment->payment_status == 0)
                                            <span class="label label-warning">Pending</span>
                                        @elseif($payment->payment_status == -1)
                                            <span class="label label-info">In-progress</span>
                                        @elseif($payment->payment_status == 1)
                                            <span class="label label-success">Paid</span>
                                        @elseif($payment->payment_status == 2)
                                            <span class="label label-danger">Exception</span>
                                        @elseif($payment->payment_status == 3)
                                            <span class="label label-warning">Waiting for payment confirmation</span>
                                        @elseif($payment->payment_status == -3)
                                            <span class="label label-warning">Payment cancelled</span>
                                        @else
                                            <span class="label label-warning">Invalid status</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!in_array($payment->process_type_id, [1, 2]))
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <span class="v_label">Payment mode</span>
                                            <span class="float-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{ $payment->pay_mode }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="collapse" id="multiCollapseExample{{ $payment->id }}">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0 no-margin">
                                            <thead>
                                            <tr>
                                                <th>Account No.</th>
                                                <th>Fees Type</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach (explode(';', $payment->payment_details) as $distribution)
                                                @php
                                                    $distribution_info = explode(',', $distribution);
                                                @endphp
                                                <tr>
                                                    <td>{{ isset($distribution_info[0]) ? $distribution_info[0] : 'N/A' }}
                                                    </td>
                                                    <td>{{ isset($distribution_info[1]) ? $payment_distribution_types[trim($distribution_info[1])] : 'N/A' }}
                                                    </td>
                                                    <td>{{ isset($distribution_info[2]) ? $distribution_info[2] : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
@endif

    </div>
</div>
