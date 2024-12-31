<html>

<body>
<div class="row">
    <div style="text-align: center;">
        <strong style="font-size: 20px;">OPERATOR LICENSE</strong>
        <br>
        <br>
        <strong style="font-size: 20px;">FOR</strong>
        <br>
        <br>
        <strong style="font-size: 20px;">{{$service_name}}</strong>
        <br>
        <br>
        <strong style="font-size: 20px;">ISSUED</strong>
        <br>
        <br>
        <strong style="font-size: 20px;">TO</strong>

        <br>
        <br>
        <br>
        <span style="font-size: 20px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$companyInfo->org_nm}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
        <br>
        <br>
        <br>
        <strong style="font-size: 20px;">UNDER</strong>

        <br>
        <br>
        <strong style="font-size: 20px;">THE BANGLADESH TELECOMMUNICATION</strong><br>
        <strong style="font-size: 20px;">REGULATION ACT, 2001</strong>

        <br>
        <br>
        <strong style="font-size: 20px;">ON</strong>

        <br>
        <br>
        {{--        <span style="font-size: 20px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('jS')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><strong style="font-size: 20px; margin-top: 50px;">DAY OF</strong><span style="font-size: 20px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('M').', '.date('Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>--}}
        <span style="font-size: 20px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('j')}}<sup>{{date('S')}}</sup>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><strong
            style="font-size: 20px; margin-top: 50px;">DAY OF</strong><span
            style="font-size: 20px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('F').', '.date('Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

        {{--        page 2--}}

        <br>
        <br>
        <br>
        <br>


        <strong style="font-size: 18px;">OPERATOR LICENSE</strong><br>
        <strong style="font-size: 18px;">FOR</strong><br>
        <strong style="font-size: 18px;">{{$service_name}}</strong><br><br>

        <table class="table-responsive" width="100%" border="1">
            <tr>
                <td style="padding: 10px;"><strong style="font-size: 18px;">LICENSE NO:</strong></td>
                <td style="padding: 10px;"><strong style="font-size: 18px;">{{$appInfo->license_no}}</strong></td>
                <td style="padding: 10px;"><strong style="font-size: 18px;">DATE</strong></td>
                <td style="padding: 10px;"><strong
                        style="font-size: 18px;">{{ !empty($appInfo->license_issue_date)? date('d.M.Y',strtotime( $appInfo->license_issue_date)) : date('d.M.Y')  }}</strong>
                </td>
            </tr>
        </table>
        <br>


        <strong style="font-size: 18px;">In Exercise of the Powers</strong><br>
        <strong style="font-size: 18px;">under section 36 of the Bangladesh Telecommunication Regulation Act,
            2001</strong><br>
        <strong style="font-size: 18px;">(Act No. XVIII of 2001)</strong><br>

        <strong style="font-size: 18px;">BANGLADESH TELECOMMUNICATION REGULATORY COMMISSION</strong><br>
        <strong style="font-size: 18px;">is pleased to grant the license in favour of</strong><br><br>
        <span style="font-size: 18px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{!empty($companyInfo->org_nm) ? $companyInfo->org_nm : ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br><br>

        <strong style="font-size: 18px;">represented by its Proprietor/Partner/Managing Director/Chairman/CEO having
            registered office at</strong><br><br>
        <span style="font-size: 18px; border-bottom: 4px dotted #0a001f; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{!empty($companyInfo->office_location) ? $companyInfo->office_location : ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br><br>


        <strong style="font-size: 18px;">as an Operator of<br>
            Internet Services<br>
            in Bangladesh<br>
            whereby it is authorized<br>
            to estabilsh, maintain and operate the associated systems and<br>
            ON NON-EXCLUSIVE BASIS<br>
            under the terms and conditions given in the following pages<br>
            including the schedules annexed hereto.</strong>


    </div>
</div>



</body>
</html>
