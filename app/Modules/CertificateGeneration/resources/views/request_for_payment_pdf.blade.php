<html>
<style>
</style>
<body>
<div style="width: 100%;">
    <div style="width: 100%; text-align: center;">
        <div style="float: left; width: 20%; text-align: left;">
            <img src="assets/images/digital_bangladesh_logo.png" alt="" style="object-fit: contain;" />
        </div>
        <div style="float: left; width: 60%; text-align: center;">
            <h4>বাংলাদেশ টেলিযোগাযোগ নিয়ন্ত্রণ কমিশন</h4>
            <h4 style="font-size:14px">প্লট: ই-৫/এ, আগারগাঁও প্রশাসনিক এলাকা, শেরে-বাংলা নগর, ঢাকা-১২০৭।</h4>
            <h4 style="font-size:14px">লাইসেন্সিং শাখা</h4>
        </div>
        <div style="float: left; width: 20%; text-align: right;">
            <img src="assets/images/new_images/logo.png" alt="" />
        </div>
    </div>
    <div style="width: 100%;  margin-top: 2%;">
        <div style="width: 50%; float: left; text-align: left;">
            <h5>স্মারক নম্বর: @if(!empty($appInfo->sharok_no)){{ $appInfo->sharok_no }}@endif</h5>
        </div>
        <div style="width: 50%; float: left; text-align: right;">
            <h5>তারিখ: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
        </div>
    </div>
    <div style="width: 100%;  text-align: left; margin-top: 2%;">
        বিষয়:<b> {{ $companyInfo->org_nm }} এর নতুন লাইসেন্স ফি জমা প্রদান প্রসঙ্গে।</b>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সূত্র: ক) {{ $companyInfo->org_nm }} এর পত্র নং-{{ $appInfo->tracking_no }}, তারিখঃ {{\Carbon\Carbon::parse($appInfo->submitted_at)->format('d/m/Y')}}।<br>
             খ) ডাক ও টেলিযোগাযোগ বিভাগের স্মারক নং- @if(!empty($appInfo->approval_memo_no_ministry)){{$appInfo->approval_memo_no_ministry}}@endif, তারিখ: @if(!empty($appInfo->approval_date_ministry)){{ \Carbon\Carbon::parse($appInfo->approval_date_ministry)->format('d/m/Y') }}@endif।
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        উপরোক্ত বিষয় ও সূত্রস্থ পত্রসমূহের প্রেক্ষিতে জানানো যাচ্ছে যে, {{ $companyInfo->org_nm }} এর সূত্রস্থ-(ক) নং পত্র বিবেচনায় সরকার কর্তৃক ০৫ (পাঁচ) বছর মেয়াদে নতুন @if(!empty($isp_license_type_area_info["type"])){{$isp_license_type_area_info["type"]}}@endif লাইসেন্সের আবেদন অনুমোদন প্রদান করা হয়েছে।
    </div>

    <div style="width: 100%; text-align: left; margin-top: 2%;">

    ০২। এমতাবস্থায়, ISP গাইডলাইনের ক্লজ নং-১৬ এর বিধান অনুযায়ী {{ $companyInfo->org_nm }} এর নতুন লাইসেন্স ফি বাবদ {{$pdfAmountArray["mainAmount"]}} টাকা ও প্রযোজ্য 15% ভ্যাট {{$pdfAmountArray["vatAmount"]}} টাকাসহ সর্বমোট {{$pdfAmountArray["totalAmount"]}} টাকা অনলাইন পেমেন্ট অথবা পে-অর্ডার পত্র জারির তারিখ হতে আগামী ১০ (দশ) দিনের মধ্যে কমিশনে জমা প্রদানের জন্য নির্দেশক্রমে অনুরোধ  করা হলো।

    </div>

    <div style="width: 100%; margin-top: 2%;  text-align: left;">
        <img src="data:image/png;base64,{{$qrCode}}" width="100px" height="100px" alt="barcode" />
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        {{ $ContactPersonData->designation }}<br>
        {{ $companyInfo->org_nm }}<br>
        {{ $registerOfficeAddress }}, {{ $registerOfficeThana }}<br>
        {{ $registerOfficeDistrict }}

    </div>

    <div style="width: 100%; text-align: right; margin-top: 2%;">
        @if($signatory->signature_encode)
        <img style="text-align: right;" src="{{'data:image/jpeg;base64,'.$signatory->signature_encode}}" width="100px" alt="img"><br>
        <span>{{$signatory->user_first_name.''.$signatory->user_middle_name. '' .$signatory->user_last_name}}</span> <br>
        <span>{{$signatory->designation ?? ''}}</span> <br>
        @endif
    </div>
    <div style="width: 100%;  margin-top: 2%;">
        <div style="width: 50%; float: left; text-align: left;">
            <h5>স্মারক নম্বর: @if(!empty($appInfo->sharok_no)){{ $appInfo->sharok_no }}@endif/1(2)</h5>
        </div>
        <div style="width: 50%; float: left; text-align: right;">
            <h5>তারিখ: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
        </div>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সদয় অবগতি ও কার্যার্থে প্রেরণ করা হল:
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        <ul style="list-style: none;">
            <li style="margin: -10px 0px 0px 0px">১) পরিচালক, অর্থ, হিসাব ও রাজস্ব বিভাগ, বাংলাদেশ টেলিযোগাযোগ নিয়ন্ত্রণ কমিশন।</li>
            <li style="margin: 0px 0px 0px 0px">২) চেয়ারম্যান মহোদয়ের একান্ত সচিব (মহোদয়ের সদয় অবগতির জন্য)।</li>
        </ul>
    </div>

</div>

</body>
</html>
