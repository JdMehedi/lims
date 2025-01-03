<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
//    $totalAmount= $paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_amount;
$totalAmount = ($paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_on_transaction_charge + $paymentInfo->vat_on_pay_amount);

?>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">

                <!--Applicent Copy-->
                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="30%" class="text-center">
                            <img src="{{ $voucher_logo_path }}" style="width: 100px"/>
                        </td>
{{--                        <td width="70%" class="text-right">--}}
{{--                            <h4 style="font-size: 18px; color: #092270;">{{ $voucher_title }}</h4>--}}
{{--                            <strong style="font-size: 12px;">{{ $voucher_subtitle }}</strong>--}}
{{--                        </td>--}}
                    </tr>
                </table>


                <table class="" cellspacing="0" width="100%" style="margin-top: 10px;">
                    <tr>
                        <td class="text-center">
                            <h4 class="text-center" style="font-weight: bold;">License Issuance And Management System (LIMS)</h4>
                        </td>
                    </tr>
                </table>


                <table class="" cellspacing="0" width="100%" style="margin-top: 10px;">
                    <tr>
{{--                        <td width="65%" class="text-right">--}}
{{--                            <img src="{{ $barcode_url }}" width="200px" height="30px"/>--}}
{{--                        </td>--}}
                        <td width="35%" class="text-right">
                            <p style="border: 1px solid black;">&nbsp;&nbsp; Applicant Copy &nbsp;&nbsp;</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td class="text-left">
                            <b style="padding: 5px;font-size: 18px;">Payment Information:
                            </b>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td class="text-left">
                            <p style="padding: 5px;">Payment ID : {{$paymentInfo->request_id}}</p>
                        </td>
                        <td class="text-right">
                            <p style="padding: 5px;">Date: {{date('d-M-Y', strtotime($paymentInfo->payment_date))}}</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td class="text-left">
                            <p style="padding: 5px;">Bank Name : Sonali Bank Limited</p>
                        </td>
                        <td class="text-right">
                            <p style="padding: 5px;">Payment Mode: {{ $paymentInfo->pay_mode }}</p>
                        </td>
                    </tr>
                </table>

                <br/>
                <table class="table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th style="padding: 5px;">Item</th>
                        <th class="text-right" style="padding: 5px;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left" style="padding: 5px;">
                            <span style="padding: 5px">Pay Amount</span>
                        </td>
                        <td style="padding: 5px" class="text-right">
                            <span style="padding: 5px"> {{$paymentInfo->pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="padding: 5px;">
                            <span style="padding: 5px">Bank Charge</span>
                        </td>
                        <td style="padding: 5px" class="text-right">
                            <span style="padding: 5px"> {{$paymentInfo->transaction_charge_amount + $paymentInfo->vat_on_transaction_charge}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="padding: 5px;">
                            <span style="padding: 5px">VAT/ TAX</span>
                        </td>
                        <td style="padding: 5px" class="text-right">
                            <span style="padding: 5px"> {{$paymentInfo->vat_on_pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="padding: 5px;">
                            <span style="padding: 5px; font-weight: bold;">Total Fees</span>
                        </td>
                        <td style="padding: 5px" class="text-right">
                            <span style="padding: 5px; font-weight: bold;"> {{$totalAmount}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="" cellspacing="0" width="100%" style="margin-top: 15px;">
                    <tr>
                        <td class="text-left">
                            <p style="padding: 5px;">Amount in words(Taka)
                                : {{ ucfirst(convert_number_to_words($totalAmount)) }} only
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%" style="margin-top: 5px;">
                    <tr>
                        <td class="text-left">
                            <p style="padding: 5px;">Organization Name
                                : {{ $companyName  }}
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%" style="margin-top: 5px;">
                    <tr>
                        <td width="50%" class="text-left">
                            <p style="padding: 5px;">Depositor Name
                                : {{$paymentInfo->contact_name}}
                            </p>
                        </td>
                        <td width="50%" class="text-right">
                            <p style="padding: 5px;">Depositor Mobile Number
                                : {{$paymentInfo->contact_no}}
                            </p>
                        </td>
                    </tr>
                </table>
                <br/>

{{--                <table class="" cellspacing="0" width="100%">--}}
{{--                    <tr>--}}
{{--                        <td width="45%">--}}
{{--                            <span>_____________________</span><br/>--}}
{{--                            <span>Depositor signature</span>--}}
{{--                        </td>--}}
{{--                        <td width="40%">--}}
{{--                            <span>____________</span><br/>--}}
{{--                            <span>Received By</span>--}}
{{--                        </td>--}}
{{--                        <td width="15%">--}}
{{--                            <span class="text-right">______________</span><br/>--}}
{{--                            <span class="text-right">Approved By</span>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td class="text-left">
                            <b style="padding: 5px;">Note: Payment could only be made through any branches of Sonali
                                Bank Ltd.(Online Branch).</b>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td class="text-center">
                            <small>Voucher generated by {{ config('app.project_name')}} & Manage by Business Automation
                                Ltd.</small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
</body>
</html>
