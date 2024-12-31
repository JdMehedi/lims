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
        বিষয়:<b> ৫ বছর মেয়াদী {{$service_name}} রেজিস্ট্রেশন সার্টিফিকেট ফি জমা প্রদান প্রসঙ্গে। </b>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সূত্র: {{ $appInfo->tracking_no }}, তারিখঃ {{\Carbon\Carbon::parse($appInfo->submitted_at)->format('d/m/Y')}} খ্রিঃ।<br>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        উপর্যুক্ত বিষয় ও সূত্রস্থ পত্রের প্রেক্ষিতে জানানো যাচ্ছে যে, আপনার প্রতিষ্ঠান হতে একটি নতুন {{$service_name}} রেজিস্ট্রেশন সার্টিফিকেট প্রাপ্তির নিমিত্ত সূত্রস্থ পত্রের মাধ্যমে দাখিলকৃত আবেদনটি কমিশন কর্তৃক অনুমোদিত হয়েছে। সে লক্ষ্যে ফি/চার্জ সংক্রান্ত সার্কুলার অনুযায়ী ৫ বছর মেয়াদী {{$service_name}} রেজিস্ট্রেশন সার্টিফিকেট ফি বাবদ ১৫,০০০/- (পনের হাজার) টাকা ও উহার উপর ১৫% ভ্যাট বাবদ ২,২৫০/- (দুই হাজার দুইশত পঞ্চাশ) টাকাসহ মোট ১৭,২৫০/- (সতের হাজার দুইশত পঞ্চাশ) টাকা অনলাইন পেমেন্ট অথবা পে-অর্ডার এর মাধ্যমে কমিশনে জমা প্রদানের বাধ্যবাধকতা রয়েছে।
    </div>

    <div style="width: 100%; text-align: left; margin-top: 2%;">
        ০২। এমতাবস্থায়, উপরিল্লিখিত ৫ বছর মেয়াদী বিপিও/কলসেন্টার রেজিস্ট্রেশন সার্টিফিকেট ফি অনলাইন পেমেন্ট অথবা পে-অর্ডার এর মাধ্যমে অনতিবিলম্বে কমিশনে জমা প্রদানের জন্য নির্দেশক্রমে অনুরোধ করা হলো।
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
        <span>ফোন: {{$signatory->user_mobile ?? ''}}</span> <br>
        <span>ইমেইল: {{$signatory->user_email ?? ''}}</span> <br>
        @endif
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সদয় অবগতি ও প্রয়োজনীয় কার্যার্থে প্রেরণ করা হল (জ্যেষ্ঠতার ক্রমানুসারে নয়):
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        <ul style="list-style: none;">
            <li style="margin: -10px 0px 0px 0px">১) পরিচালক, অর্থ, হিসাব ও রাজস্ব বিভাগ, বিটিআরসি।</li>
            <li style="margin: 0px 0px 0px 0px">২) চেয়ারম্যান এর একান্ত সচিব, চেয়ারম্যান এর দপ্তর, বিটিআরসি (মহোদয়ের সদয় অবগতির জন্য)।</li>
            <li style="margin: 0px 0px 0px 0px">৩) অফিস কপি, বিটিআরসি।</li>
        </ul>
    </div>

</div>

</body>
</html>
