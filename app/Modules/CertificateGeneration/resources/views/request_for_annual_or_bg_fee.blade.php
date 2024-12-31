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
        বিষয়:<b>  {{ $companyInfo->org_nm }} এর নতুন {{ $isp_license_type_area_info['type'] }} আইএসপি লাইসেন্স ইস্যু প্রসঙ্গে।</b>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সূত্র: {{ $companyInfo->org_nm }} এর পত্র নং-{{ $appInfo->tracking_no }}, তারিখ:- {{\Carbon\Carbon::parse($appInfo->submitted_at)->format('d/m/Y')}} খ্রিঃ।<br>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        উপর্যুক্ত বিষয় ও সূত্রস্থ পত্রের প্রেক্ষিতে নির্দেশিত হয়ে জানানো যাচ্ছে যে, {{ $companyInfo->org_nm }} কর্তৃক দাখিলকৃত আবেদন বিবেচনায় নিম্নোক্ত শর্তে প্রতিষ্ঠানটির {{ $isp_license_type_area_info['type'] }} আইএসপি লাইসেন্সটি {{ $isp_license_type_area_info['name'] }} এর জন্য পরবর্তী মেয়াদে অর্থাৎ {{ \Carbon\Carbon::now()->format('d/m/Y') }} খ্রিঃ থেকে {{\Carbon\Carbon::parse($appInfo->expiry_date)->format('d/m/Y')}} খ্রিঃ তারিখ পর্যন্ত নবায়নের অনুমোদন প্রদান করা হয়েছে।
    </div>
    <div>
        <br>
        শর্তঃ<br>
        (০১) আপনার প্রতিষ্ঠানকে প্যারেন্টাল গাইডেন্স প্রদানসহ তা প্রতিপালন করতে হবে;<br>
        (০২) লাইসেন্সপ্রাপ্ত প্রতিষ্ঠানকে বাধ্যতামূলকভাবে আইএসপিএবি (ISPAB)-এর সদস্য পদ গ্রহণ করতে হবে;<br>
        (০৩) আপনার প্রতিষ্ঠানকে প্রতি সপ্তাহে তাদের গ্রাহকদের হালনাগাদ তালিকা স্থানীয় থানা এবং বিটিআরসিতে প্রেরণ করতে হবে;<br>
        (০৪) আইএসপি লাইসেন্সধারী প্রতিষ্ঠান কেবলমাত্র তার লাইসেন্সের জন্য নির্ধারিত ভৌগোলিক সীমানাতেই আইনসঙ্গত কর্মকাণ্ড পরিচালনা করবে;<br>
        (০৫) আপনার প্রতিষ্ঠানকে সিসিটিভি (CCTV) এর আওতায় আনতে হবে এবং এ সংক্রান্ত তথ্য কমিশনে প্রদান করতে হবে;<br>
        (০৬) বিটিআরসি’র ডাটা ইনফরমেশন সিস্টেম (Data Information System) সহ অন্যান্য ডাটাবেইজসমূহে নিয়মিত তথ্য প্রদান করতে হবে;<br>
        (০৭) আইএসপি গাইডলাইনের ধারা ৩৮.১০ অনুযায়ী সাইবার ক্যাফেসহ সকল সেবা গ্রহণকারীদের আইপি লগ (IP Log) সংরক্ষণ করতে হবে এবং চাহিবা মাত্র প্রদান করতে হবে;<br>
        (০৮) সরকার কর্তৃক অনুমোদিত "এক দেশ এক রেট" সহ অন্যান্য ট্যারিফ প্ল্যান কমিশনের সিস্টেমস এন্ড সার্ভিসেস বিভাগ হতে লিখিতভাবে অনুমোদন গ্রহণ করতঃ গুনগত মান বজায় রেখে গ্রাহকদের সেবা প্রদান করতে হবে।;<br>
        (০৯) লাইসেন্স প্রাপ্ত প্রতিষ্ঠান কোন প্রকার এজেন্ট নিয়োগ করতে পারবে না;<br>
        (১০) আইএসপি গাইডলাইন এর ধারা ৩৬ অনুযায়ী ওয়েববেইজড একসেস (Web Based Access) এর জন্য কমিশনকে প্রয়োজনীয় অনলাইন মনিটরিং সিস্টেম ও কানেক্টিভিটি প্রদান করতে হবে;<br>
        (১১) লাইসেন্সের কোন শর্ত ভঙ্গ করা যাবে না; লাইসেন্সের কোন শর্ত ভঙ্গ করলে সরকার যে কোন সময় লাইসেন্স বাতিল করার ক্ষমতা সংরক্ষণ করে;<br>
        (১২) কোন কারণ ব্যতিরেকে যে কোন সময় এ আদেশ বাতিলের এখতিয়ার সরকার কর্তৃক সংরক্ষিত।<br>
        (১৩) ক্রম-৬ অনুযায়ী ডিআইএস (DIS) এ নিবন্ধন করতঃ প্রমাণক ইএন্ডও বিভাগের সংশ্লিষ্ট কর্মকর্তার নিকট থেকে গ্রহণ করতে হবে।<br>
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        এমতাবস্থায়, {{ $companyInfo->org_nm }} এর অনুকূলে ইস্যুকৃত {{ $isp_license_type_area_info['type'] }} আইএসপি লাইসেন্স (যার নম্বরঃ {{ $appInfo->license_no }}; Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}) এর নতুন বই লাইসেন্স ইস্যুর ৩০ (ত্রিশ) দিনের মধ্যে কমিশন হতে সংগ্রহের জন্য নির্দেশক্রমে অনুরোধ জানানো হলো। একইসাথে উক্ত সময়সীমার মধ্যে আইএসপি গাইডলাইনের ক্লজ নং-১৭ এর বিধান মোতাবেক নির্ধারিত ফরম্যাট অ্যাপেন্ডিক্স-৫ (Appendix-5) অনুযায়ী প্রযোজ্য ব্যাংক গ্যারান্টি (BG) ({{$pdfAmountArray["bank_guarantee_amount"]}}) অথবা লাইসেন্সের পূর্ণমেয়াদের বার্ষিক ফি ও ভ্যাট {({{$pdfAmountArray["mainAmount"]}} + {{$pdfAmountArray["vatAmount"]}}) * 4 =  {{$pdfAmountArray["totalAmount"]}}} কমিশনের অর্থ, হিসাব ও রাজস্ব বিভাগে জমা প্রদান পূর্বক রিসিভ কপি লাইসেন্স গ্রহণের সময় অত্র বিভাগে জমা প্রদান করতে হবে। উল্লেখ্য, লাইসেন্স গ্রহণকালে প্রতিষ্ঠানের মালিক/অংশীদারগণের অবর্তমানে অবশ্যই মালিক/অংশীদারগণ কর্তৃক মনোনীত প্রতিনিধিকে ক্ষমতাপত্র (Authorization Letter) প্রদান করতে হবে।
    </div>
    <br>
    <br>
    <br>
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

    <div style="width: 100%;  margin-top: 2%;">
        <div style="width: 50%; float: left; text-align: left;">
            <h5>স্মারক নম্বর: @if(!empty($appInfo->sharok_no)){{ $appInfo->sharok_no }}/1(3)@endif</h5>
        </div>
        <div style="width: 50%; float: left; text-align: right;">
            <h5>তারিখ: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
        </div>
    </div>

    <div style="width: 100%; text-align: left; margin-top: 2%;">
        সদয় অবগতি ও প্রয়োজনীয় কার্যার্থে প্রেরণ করা হল (জ্যেষ্ঠতার ক্রমানুসারে নয়):
    </div>
    <div style="width: 100%; text-align: left; margin-top: 2%;">
        <ul style="list-style: none;">
            <li style="margin: -10px 0px 0px 0px">১) মহা পরিচালক, ইঞ্জিনিয়ারিং এন্ড অপারেশন্স বিভাগ, বাংলাদেশ টেলিযোগাযোগ নিয়ন্ত্রণ কমিশন</li>
            <li style="margin: 0px 0px 0px 0px">২) পরিচালক, অর্থ, হিসাব ও রাজস্ব বিভাগ, বাংলাদেশ টেলিযোগাযোগ নিয়ন্ত্রণ কমিশন</li>
            <li style="margin: 0px 0px 0px 0px">৩) চেয়ারম্যান এর একান্ত সচিব, চেয়ারম্যান এর দপ্তর, বাংলাদেশ টেলিযোগাযোগ নিয়ন্ত্রণ কমিশন</li>
        </ul>
    </div>

</div>

</body>
</html>
