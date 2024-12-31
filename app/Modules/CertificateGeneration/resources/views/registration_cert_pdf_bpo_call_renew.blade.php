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
        বিষয়:<b> {{ $companyInfo->org_nm }}  এর অনুকূলে নতুন বিপিও/কলসেন্টার রেজিস্ট্রেশন সার্টিফিকেট ইস্যু প্রসঙ্গে। </b>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সূত্র: {{ $appInfo->tracking_no }}, তারিখঃ {{\Carbon\Carbon::parse($appInfo->submitted_at)->format('d/m/Y')}} ।<br>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        উপর্যুক্ত বিষয় ও সূত্রস্থ পত্রের প্রেক্ষিতে জানানো যাচ্ছে যে, আপনার প্রতিষ্ঠান কর্তৃক দাখিলকৃত বিপিও/কলসেন্টার রেজিস্ট্রেশন সার্টিফিকেটটি {{\Carbon\Carbon::parse($appInfo->expiry_date)->format('d/m/Y')}} খ্রিঃ হতে {{ \Carbon\Carbon::parse($appInfo->expiry_date)->addYears(5)->subDay()->format('d/m/Y') }} খ্রিঃ তারিখ পর্যন্ত মেয়াদে নবায়নের আবেদনটি নিম্নোক্ত শর্তে অনুমোদন প্রদান করা হলোঃ <br>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        (ক) আপনার প্রতিষ্ঠানকে বিপিও/কলসেন্টার নির্দেশিকা যথাযথভাবে প্রতিপালন করতে হবে;<br>
        (খ) বিপিও/কলসেন্টার নির্দেশিকার ধারা-৫.১.২১ এ উল্লিখিত বিধান মোতাবেক নির্ধারিত সময়ের মধ্যে অপারেশন শুরু করতে হবে;<br>
        (গ) বিপিও/কলসেন্টার নির্দেশিকার ধারা-৩.১০ এবং ৫.১.২০ এ উল্লিখিত বিধান মোতাবেক অপারেশন শুরুর পূর্বে কমিশন (ইঞ্জিনিয়ারিং এন্ড অপারেশন বিভাগ) হতে প্রয়োজনীয় অনুমোদন গ্রহণ করতে হবে;<br>
        (ঘ) রেজিস্ট্রেশন সার্টিফিকেটের কোন শর্ত ভঙ্গ করা যাবে না; কোন শর্ত ভঙ্গ করলে কমিশন যে কোন সময় রেজিস্ট্রেশন সার্টিফিকেট বাতিল করার ক্ষমতা সংরক্ষণ করে;<br>
        (ঙ) কোন কারণ ব্যতিরেকে যে কোন সময় এ আদেশ বাতিলের এখতিয়ার কমিশন কর্তৃক সংরক্ষিত;<br>
    </div>

    <div style="width: 100%; text-align: left; margin-top: 2%;">
        ০২। এমতাবস্থায়, {{ $companyInfo->org_nm }} এর অনুকূলে নবায়নকৃত নতুন বিপিও/কলসেন্টার রেজিস্ট্রেশন সার্টিফিকেটটি অনতিবিলম্বে কমিশন হতে সংগ্রহের জন্য নির্দেশক্রমে অনুরোধ জানানো হলো। তবে যৌক্তিক কারণে মালিক/অংশীদারগণ অনুপস্থিত থাকলে রেজিস্ট্রেশন সার্টিফিকেট গ্রহণকালে অবশ্যই মালিক/অংশীদারগণ কর্তৃক মনোনীত প্রতিনিধিকে ক্ষমতাপত্র (Authorization Letter) প্রদান করতে হবে।
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সংযুক্তিঃ <a target="_blank" href="{{url($call_center_guideline_hyperlink)}}">Call Center Guideline Hyperlink</a>
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
            <li style="margin: -10px 0px 0px 0px">১) পরিচালক (১), ইঞ্জিনিয়ারিং এন্ড অপারেশন্স বিভাগ, বিটিআরসি।</li>
            <li style="margin: 0px 0px 0px 0px">২) পরিচালক, অর্থ, হিসাব ও রাজস্ব বিভাগ, বিটিআরসি।</li>
            <li style="margin: 0px 0px 0px 0px">৩) চেয়ারম্যান এর একান্ত সচিব, চেয়ারম্যান এর দপ্তর, বিটিআরসি (মহোদয়ের সদয় অবগতির জন্য)।</li>
            <li style="margin: 0px 0px 0px 0px">৪) অফিস কপি, বিটিআরসি।</li>
        </ul>
    </div>

</div>

</body>
</html>
