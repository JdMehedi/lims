<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>
<body class="body" style="position: relative; padding:0 !important; margin:0 !important; display:block !important; background:#ffffff; -webkit-text-size-adjust:none; font-family: kalpurush; color: #000;">

<?php
//    $totalAmount= $paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_amount;
$totalAmount = ($paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_on_transaction_charge + $paymentInfo->vat_on_pay_amount);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tbody>
    <tr>
        <td align="center" valign="top">
            <table border="0" cellspacing="0" cellpadding="0" class="mobile-shell" bgcolor="#ffffff" style="background: #ffffff; border: 1px solid #000000;">
                <tbody>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="pdf-header" style="width: 100%;">
                            <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <span>
                                        <a href="#"><img src="{{ $voucher_logo_path }}" alt="" style="width: 20%; margin: 10px 0px;"></a>
                                    </span>
                                    <br>

                                    <span style="display: block; text-align: center;font-size: 18px;line-height: 26px;text-transform: capitalize;margin-top: 10px;font-weight:bold;color: #000000;">License Issuance and Management System (LIMS) <br>Bangladesh Telecommunication Regulatory Commission</span>
                                    <div style="display:inline-block; text-align: center;">
                                        <?php if(!empty($barcode_url)) {?>
                                        <span style="display:inline-block;margin: 10px 0 5px;">
                                                            <img src="{{ $barcode_url  }}" alt="" style="margin: 10px 0px;">
                                                        </span>
{{--                                        <span style="display:block; font-size: 16px;text-align: center; margin: 10px 0px;">{{ $request_id  }}</span>--}}
                                        <?php }?>
                                    </div>
                                    <br>
                                    <span style="display: block; text-align: center;font-size: 24px;line-height: 30px;text-transform: uppercase;color: #000; font-weight: bold;margin: 20px 0;">APPLICANT COPY</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
                            <tbody>
                            <tr>
                                <td align="left" style="padding: 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Service Name: <strong>{{ $process_type  }}</strong></td>
                                <td align="right" style="padding: 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Tracking ID: <strong>{{ $trackingNo  }}</strong></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100%;text-align: left;">
                                        <tbody>
                                        <tr>
                                            <td valign="top" style="padding:0; width: 55%;">
                                                <table width="100%" border="1" cellspacing="0" cellpadding="0" style="width: 100%;text-align: left;border-collapse: collapse;">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight: bold; text-align: center;">Depositor Details</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Organization Name</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;background: #d2d2d2;">{{$companyName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Type of License</td>
                                                        @if(!empty($type_of_license))
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;">{{ $type_of_license }}</td>
                                                        @else
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;">{{ $paymentInfo->purpose_payment }}</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Purpose of Charge</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;background: #d2d2d2;">{{ $paymentInfo->purpose_payment   }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Depositor Name</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;">{{$paymentInfo->contact_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Depositor Mobile No</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight:bold;background: #d2d2d2;">{{$paymentInfo->contact_no}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>

                                            <td width="10" style="width:10px;padding: 0;">&nbsp;</td>

                                            <td valign="top" style="padding:0;">
                                                <table width="100%" border="1" cellspacing="0" cellpadding="0" style="width: 100%;text-align: left;border-collapse: collapse;">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight: bold; text-align: center;">Payment Details</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Date</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;font-weight:bold;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{date('d-M-Y', strtotime($paymentInfo->ref_tran_date_time))}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Time</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;font-weight:bold;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{date('H:i:s', strtotime($paymentInfo->ref_tran_date_time))}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Payment Mode</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;font-weight:bold;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{ $paymentInfo->pay_mode }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Payment ID</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;font-weight:bold;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{$paymentInfo->request_id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;text-align: right;">Bank Name</td>
                                                        <td style="padding: 7px 10px;font-size: 16px;font-weight:bold;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">
                                                            <?php if($paymentInfo->pay_mode_code == 'A01' || $paymentInfo->pay_mode_code == 'A02') {?>
                                                                Sonali Bank Limited
                                                            <?php } else {?>
                                                                N/A
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%;text-align: left;border-collapse: collapse; border: 1px solid #000000;">
                                        <tbody>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;font-weight: bold; text-align: center; width: 50%;">Payment Item</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush; font-weight: bold; text-align: center;">Amount</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Principle Amount</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{$paymentInfo->pay_amount}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Late Fee</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">{{$paymentInfo->delay_fee}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">VAT/Tax</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{$paymentInfo->vat_on_pay_amount}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Bank Charge</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">{{$paymentInfo->transaction_charge_amount + $paymentInfo->vat_on_transaction_charge}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;">Total</td>
                                            <td style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;background: #d2d2d2;">{{$totalAmount - $paymentInfo->delay_fee}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: 1px solid #000000; padding: 7px 10px;font-size: 16px;line-height: 24px;font-family: kalpurush;"><strong>Amount in words:</strong> Tk {{ ucfirst(convert_number_to_words($totalAmount)) }} Only</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100%; border: 0px">
                            <tbody>
                            <tr>
                                <td colspan="3" align="center">
                                    <span style="display:inline-block;width: 100px;margin:10px;"><img src="data:image/png;base64,{{$qrCode}}" alt="barcode" /></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:left;">Payment voucher generated by License Issuance and Management System (LIMS) & Payment information provided by Sonali Payment Gateway (SPG)</td>
                            </tr>
                            <tr>
                                <td align="left">Voucher Generated Time: {{ date('H:i:s') }}</td>
                                <td align="center"><a href="https://lims.btrc.gov.bd" style="color: #000; text-decoration: none;">https://lims.btrc.gov.bd</a></td>
                                <td align="right">SPG Help Line: 01920-000000</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
