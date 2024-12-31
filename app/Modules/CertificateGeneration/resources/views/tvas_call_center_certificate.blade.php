<html>

<body>
<div class="row">
    <div style="text-align: center;">
        <div style="border: 1px solid black;">
            <strong style="font-size: 18px;">REGISTRATION CERTIFICATE</strong><br>
            <strong style="font-size: 18px;">FOR</strong><br>
            {{--        <strong style="font-size: 18px;" class="text-uppercase">Telecommunication Value Added Services Provider in Bangladesh</strong><br><br>--}}
            <strong style="font-size: 18px;" class="text-uppercase">{{$service_name}}</strong><br><br>
        </div>
        <br>
        <br>


        <table style="line-height: 2rem;width: 100%" border="1">
            <tr>
                <td style="padding-left: 5px">
                    <strong>Registration No. : </strong><span style="font-size: 15px">{{$appInfo->license_no ? $appInfo->license_no : ''}}</span>
                </td>
{{--                <td style="width: 70%;padding-left: 5px">--}}
{{--                    <span style="font-size: 15px">{{$appInfo->license_no ? $appInfo->license_no : ''}}</span>--}}
{{--                </td>--}}
            </tr>
            <tr>
                <td style="padding-left: 5px">
                    <strong>Date : </strong><span style="font-size: 15px">{{date('d F Y')}}</span>
                </td>
{{--                <td style="width: 70%;padding-left: 5px">--}}
{{--                    <span style="font-size: 15px">{{date('d M Y')}}</span>--}}
{{--                </td>--}}
            </tr>
            <tr>
                <td style="padding-left: 5px">
                    <strong>Name of the Registered Entity : </strong><span style="font-size: 15px">{{ !empty($companyInfo->org_nm)  ? $companyInfo->org_nm  : ''}}.</span>
                </td>
{{--                <td style="width: 70%;padding-left: 5px">--}}
{{--                    <span style="font-size: 15px">{{$companyInfo->org_nm ? $companyInfo->org_nm  : ''}}.</span>--}}
{{--                </td>--}}
            </tr>
            <tr>
                <td style="padding-left: 5px">
                    <strong>Address of the Registered Entity : </strong><span style="font-size: 15px">{{ !empty($companyInfo->office_location) ? $companyInfo->office_location : ''}}.</span>
                </td>
{{--                <td style="padding-left: 5px">--}}
{{--                    <span style="font-size: 15px">{{$companyInfo->office_location ? $companyInfo->office_location : ''}}.</span>--}}
{{--                </td>--}}
            </tr>
            <tr>
                <td style="padding-left: 5px">
                    <strong>Duration of the Registration : </strong><span style="font-size: 15px"> From {{date('d F Y',strtotime($appInfo->license_issue_date))}} To {{date('d F Y',strtotime($appInfo->expiry_date))}}   </span>
                </td>
{{--                <td style="padding-left: 5px">--}}
{{--                    <span style="font-size: 15px"> From {{$appInfo->license_issue_date}} To {{$appInfo->expiry_date}}   </span>--}}
{{--                </td>--}}
            </tr>
        </table>
        <br>
        <br>
        <div style="text-align: justify">
            In exercise of the powers conferred by the Bangladesh Telecommunication Regulation Act,
            2001 the Bangladesh Telecommunication Regulatory Commission (BTRC) upon consideration
            of the application dated:
            <span>{{date('d-m-Y',strtotime($appInfo->license_issue_date))}}</span>
            is pleased to issue Registration Certificate in
            favour of
            <span style="font-weight: bold;font-size: 15px">{{  $appInfo->org_nm ?? '' }}.</span> The registered entity has to abide by all
            the terms and conditions mentioned in the Regulatory Guidelines for Issuance of
            Registration Certificate for providing Telecommunication Value Added Services (TVAS)
            In Bangladesh vide No.:
            <span>{{$appInfo->license_no}}</span>, Dated: <span>{{date('d-m-Y',strtotime($appInfo->license_issue_date))}}</span> (enclosed herewith).
            The Registration Certificate is issued with the approval of the appropriate authority.
        </div>
        <br>
        <br>
        <br>
        <div style="width: 100%;">
            <div style="width: 70%;float: left">&nbsp;</div>
            <div style="width: 28%;float: right;text-align: center">
              @if(!empty($signatory->signature_encode))
                    <img src="{{'data:image/jpeg;base64,'.$signatory->signature_encode}}" width="100px" alt="img"><br>
                    <span>{{$signatory->user_first_name.''.$signatory->user_middle_name. '' .$signatory->user_last_name}}</span> <br>
                    <span>{{$signatory->designation ?? ''}}</span> <br>
              @endif
{{--                @if($signatory->user_mobile)<span>Mobile: {{$signatory->user_mobile}}</span> <br>@endif--}}
{{--                @if($signatory->user_email)<span>Email: {{$signatory->user_email}}</span> <br>@endif--}}
                <span>Legal and Licensing Division</span> <br>
                <span>BTRC</span>
            </div>

        </div>


</div>

</body>
</html>
