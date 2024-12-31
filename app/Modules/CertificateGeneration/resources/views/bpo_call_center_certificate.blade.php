<html>

<body>
<div class="row">
    <div style="text-align: center;">
{{--        <strong style="font-size: 18px;">REGISTRATION CERTIFICATE</strong><br>--}}
{{--        <strong style="font-size: 18px;">FOR</strong><br>--}}
{{--        <strong style="font-size: 18px;" class="text-uppercase">BPO-Call Center, BPO</strong><br><br>--}}

        <table class="table-responsive" width="100%">
            <tr>
                <td style="width: 20%"><span style="font-size: 18px;">Registration No:</span></td>
                <td style="width: 50%"><span style="float:left;font-size: 18px;border: 1px solid black">{{$appInfo->license_no ?? ''}}</span></td>
                <td style="width: 10%;text-align:right;padding: 10px;"><span style="font-size: 18px;">Date : </span></td>
                <td style="width: 20%;float: left"><span style="font-size: 18px;">{{date('d-m-Y')}}</span></td>
            </tr>
        </table>
        <br>
        <table style="line-height: 1.5rem;width: 100%">
            <tr>
                <td style="width: 30%">
                    <span>Name of the Registered Entity : </span>
                    <strong style="font-size: 15px"><i>{{$appInfo->company_name ?? ''}}</i></strong>
                </td>
                {{-- <td style="width: 70%">

                </td> --}}
            </tr>
            <tr>
                <td>
                    <span>Address of the Registered Entity : </span>
                    <strong style="font-size: 15px"><i>{{$appInfo->reg_office_address ?? ''}}, {{$appInfo->reg_office_thana_name ?? ''}}, {{$appInfo->reg_office_district_name ?? ''}}</i></strong>
                </td>
                {{-- <td style="padding-top: 2px;">

                </td> --}}
            </tr>

            <tr>
                <td>
                    <span>Category : </span>
                    <strong style="font-size: 15px"><i>BPO/Call Center (Domestic & International)</i></strong>
                </td>
            </tr>

            <tr>
                <td>
                    <span>Duration of the Registration : </span>
                    <strong style="font-size: 15px"><i>From
                        @if($appInfo->process_type_id==6)
                        <span>{{date('d',strtotime($appInfo->license_renew_date))}}<sup> {{date('S',strtotime($appInfo->license_renew_date))}}</sup></span> <span >{{date('F',strtotime($appInfo->license_renew_date)).' '.date('Y',strtotime($appInfo->license_renew_date))}} </span>
                        @else
                        <span>{{date('d',strtotime($appInfo->license_issue_date))}}<sup> {{date('S',strtotime($appInfo->license_issue_date))}}</sup></span> <span >{{date('F',strtotime($appInfo->license_issue_date)).' '.date('Y',strtotime($appInfo->license_issue_date))}} </span>
                        @endif
                        to
                        <span>{{date('d',strtotime($appInfo->expiry_date))}}<sup> {{date('S',strtotime($appInfo->expiry_date))}}</sup></span> <span >{{date('F',strtotime($appInfo->expiry_date)).' '.date('Y',strtotime($appInfo->expiry_date))}} </span>
                        .</i></strong>
                </td>
                {{-- <td>

                </td> --}}
            </tr>
        </table>
        <br>
        <strong style="font-weight: bold;font-size: 25px;margin: auto;text-align: center"><u>Registration Certificate of Call Center (CC), BPO</u></strong><br>
        <br>
        @if($appInfo->process_type_id==6)
        <p style="text-align: justify">
            In exercise of the powers conferred by the Bangladesh Telecommunication Regulation Act, 2001
            the Bangladesh Telecommunication Regulatory Commission (BTRC) upon consideration of the
            application dated:
            <strong style="">{{!empty($appInfo->submitted_at) ? date('d-m-Y',strtotime($appInfo->submitted_at)) : date('d-m-Y',strtotime($appInfo->submitted_at))}}</strong>
            is pleased to issue Registration Certificate in favour of
            <strong style="font-size: 15px">{{$companyInfo->org_nm ?? ''}}</strong>. The registered entity has to abide by all the terms and conditions mentioned
            in the Registration Instructions enclosed herewith. This Registration Certificate is issued
            with the approval of the appropriate authority.and it is a continuation of the rights and responsibilities under
            your previous Call Center Registration
            Certificate bearing No.: {{$appInfo->license_no}}, Date: {{$appInfo->license_renew_date}}.
        </p>
        @else
        <p style="text-align: justify">
            In exercise of the powers conferred by the Bangladesh Telecommunication Regulation Act, 2001
            the Bangladesh Telecommunication Regulatory Commission (BTRC) upon consideration of the
            application dated:
            <strong style="">{{!empty($appInfo->submitted_at) ? date('d-m-Y',strtotime($appInfo->submitted_at)) : date('d-m-Y',strtotime($appInfo->submitted_at))}}</strong>
            is pleased to issue Registration Certificate in favour of
            <strong style="font-size: 15px">{{$companyInfo->org_nm ?? ''}}</strong>. The registered entity has to abide by all the terms and conditions mentioned
            in the Registration Instructions enclosed herewith. This Registration Certificate is issued
            with the approval of the appropriate authority.
        </p>
        @endif
        <br>
        <table style="width: 100%">
            <tr>
                <td style="width: 12%;margin-top: 0px"><strong>Enclosure : </strong></td>
                <td style="width: 88%;padding-top: 15px; "> <span> Call Centre (CC)/BPO Registration Instructions bearing No.: BTRC/LL/Call Centre/Licensing Procedure (268)/2008-967, dated: 18-09-2013.</span></td>
            </tr>
        </table>
        <br><br>
        <div style="width: 100%;">
            <div style="width: 70%;float: left">&nbsp;</div>
            <div style="width: 28%;float: right;text-align: center">
                @if($signatory->signature_encode)
                <img src="{{'data:image/jpeg;base64,'.$signatory->signature_encode}}" width="100px" alt="img"><br>
                <span>{{$signatory->user_first_name.''.$signatory->user_middle_name. '' .$signatory->user_last_name}}</span> <br>
                <span>{{$signatory->designation ?? ''}}</span> <br>
                @endif
{{--               @if($signatory->user_mobile)<span>Mobile: {{$signatory->user_mobile ?? ''}}</span> <br>@endif--}}
{{--               @if($signatory->user_email)<span>Email: {{$signatory->user_email ?? ''}}</span> <br>@endif--}}
                <span>Legal and Licensing Division</span> <br>
                <span>BTRC</span>
            </div>

        </div>


</div>

</body>
</html>
