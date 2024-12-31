<html>

<body>
<div class="row">
    <div class="col-md-4 fontSize" style="float: left;width: 60%;">
        ম্যানুয়াল নিবন্ধন নং : {{@$appInfo->manual_reg_number}}<br>
        ম্যানুয়াল নিবন্ধন ইস্যুর তারিখ : {{\App\Libraries\CommonFunction::convert2Bangla($appInfo->manual_reg_date)}}<br>
        অনলাইন সিস্টেমে পুনঃনিবন্ধন নং : {{@$appInfo->regist_no}}<br><br>
    </div>
    <div class="col-md-8 " style="text-align: right">
        <span  style="font-size: 16px;" class="input_ban">তারিখ: {{\App\Libraries\CommonFunction::convert2Bangla(date('d-m-Y', strtotime($appInfo->completed_date))) }}</span><br>
    </div>
    <br>
    <br>
    <div class="col-lg-12 fontSize text-justify" style="width: 100%">
        <h4>বিষয়: উদ্যোক্তা প্রদত্ত নিম্নবর্ণিত তথ্যের ভিত্তিতে OSS সিস্টেমে শিল্প প্রকল্পের পুনঃনিবন্ধন। </h4>
        <br>
        প্রিয় মহোদয়,<br>

        উপর্যুক্ত বিষয়ে জানানো যাচ্ছে যে গত {{ \App\Libraries\CommonFunction::convert2Bangla(date('d/m/Y', strtotime($appInfo->submitted_at))) }} খ্রি. তারিখে আবেদনপত্রে প্রদত্ত তথ্যাদির ভিত্তিতে বিসিকে নিবন্ধিত শিল্প প্রতিষ্ঠানটি অত্র কার্যালয় কর্তৃক অনলাইন সিস্টেমে পুনঃ নিবন্ধিত হলো। আপনার প্রতিষ্ঠানের ম্যানুয়াল নিবন্ধন নং  {{$appInfo->manual_reg_number}}
        ইস্যুর তারিখ {{ \App\Libraries\CommonFunction::convert2Bangla(date('d/m/Y', strtotime($appInfo->completed_date))) }}
        OSS সিস্টেমে নিবন্ধন নম্বর {{@$appInfo->regist_no}} যা পরবর্তী পৃষ্ঠায় বর্ণিত শর্তসাপেক্ষে প্রদান করা হলো।
    </div>
    <br>
    <br>
    <br>
    <div class="col-md-4 fontSize" style="width: 55%; float: left;">
        {{$appInfo->ceo_name}}<br>
        {{$appInfo->designation}}<br>

        {{@$appInfo->org_nm_bn}}<br>
        {{@$appInfo->office_location}},  {{@$appInfo->thana_nm_ban}}, {{@$appInfo->dis_nm_ban}}, {{@$appInfo->div_nm_ban}}

    </div>
    <div class="col-md-5 fontSize" style="text-align: right;">
        <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
        <br>
        {{@$signatory->user_first_name}}<br>
        {{@$signatory->designation}}<br>
        ফোনঃ {{@\App\Libraries\CommonFunction::convert2Bangla(@$signatory->user_mobile) }}<br>
        ইমেইলঃ {{@$signatory->user_email}}<br>
    </div>

    <div class="col-md-12 fontSize" style="width: 100%;">
        <img src="data:image/png;base64,{{$qrCode}}" alt="barcode" />
    </div><br>

    <div class="col-md-12 fontSize" style="width: 100%;">

        অনুলিপি (সদয় জ্ঞাতার্থে): <br><br>
        ১. পরিচালক,দক্ষতা ও প্রযুক্তি/অর্থ/বিপণন,নকশা ও কারু শিল্প/পরিকল্পনা ও গবেষণা/শিল্প উন্নয়ন ও সম্প্রসারণ/প্রকৌশল ও প্রকল্প বাস্তবায়ন, বিসিক, ঢাকা।<br>
        ২. পরিচালক, পরিবেশ অধিদপ্তর, {{@$appInfo->industrial_city_dis_name}}।<br>
        ৩. মহা-ব্যবস্থাপক, পরিসংখ্যান পরিদপ্তর, বাংলাদেশ ব্যাংক, মতিঝিল বা/এ, ঢাকা।<br>
        ৪. উপ-প্রধান পরিদর্শক, কারখানা ও প্রতিষ্ঠানসমূহের কার্যালয়, {{@$appInfo->industrial_city_dis_name}}।<br>
        ৫. আমদানি ও রপ্তানি যুগ্ন নিয়ন্ত্রক, {{@$appInfo->industrial_city_dis_name}}।

    </div>
    <pagebreak></pagebreak>


    <div class="col-md-12" style="font-size: 15px">
       <div class="row">
           <div class="col-md-4" style="float: left;width: 60%;">
               উদ্যোক্তা কর্তৃক নিম্মোক্ত তথ্যের ভিত্তিতে প্রতিষ্ঠানটির নিবন্ধন প্রদান করা হলো।
           </div>
           <div class="col-md-8 " style="text-align: right">
               পুনঃ নিবন্ধন নং: <br> {{@$appInfo->regist_no}} <br>
               তারিখ: {{\App\Libraries\CommonFunction::convert2Bangla(date('d-m-Y', strtotime($appInfo->completed_date))) }}
           </div>
       </div>

        প্রতিষ্ঠানের নামঃ  {{@$appInfo->org_nm_bn}}
        <br>
        প্রকল্পের নামঃ {{@$appInfo->project_nm}}
        <br>
        কার্যালয়ের ঠিকানাঃ {{@$appInfo->office_location}}, {{@$appInfo->thana_nm_ban}} ,{{@$appInfo->dis_nm_ban}},{{@$appInfo->div_nm_ban}} মোবাইল নং-{{ \App\Libraries\CommonFunction::convert2Bangla(@$appInfo->office_mobile) }}.
        <br>
        কারখানার ঠিকানাঃ {{$appInfo->factory_location}}, {{@$appInfo->f_thana_nm_ban}} ,{{@$appInfo->f_dis_nm_ban}},{{@$appInfo->f_div_nm_ban}} মোবাইল নং-{{ \App\Libraries\CommonFunction::convert2Bangla(@$appInfo->factory_mobile) }}.
        <br>
        নিবন্ধনের প্রকৃতিঃ {{@$appInfo->regist_name_bn}}
        <br>
        প্রতিষ্ঠানের ধরনঃ {{@$appInfo->company_type_bn}}
        <br>
        শিল্পের খাতঃ {{@$appInfo->ind_sector_bn}}
    </div>
    <br>
    <div class="col-md-12" style="font-size: 15px">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td colspan="4"><h4>প্রতিষ্ঠানের বার্ষিক উৎপাদন ক্ষমতা</h4></td>
            </tr>
            <tr>
                <td><h4>নং</h4></td>
                <td><h4>পণ্য/সেবার নাম</h4></td>
                <td><h4>পরিমাণ</h4></td>
                <td><h4>পরিমাণ একক</h4></td>
                <td><h4>মূল্য (লক্ষ টাকায়)</h4></td>
            </tr>
            </thead>
            <tbody>
            @php
                $i = 1;
            @endphp
            @foreach($annualProductionCapacity as $item)
                <tr>
                    <td class="input_ban">{{ \App\Libraries\CommonFunction::convert2Bangla($i) }}</td>
                    <td>{{@$item->service_name}}</td>
                    <td class="input_ban">{{ \App\Libraries\CommonFunction::convert2Bangla(@$item->quantity)}}</td>
                    <td>{{ $item->name_bn}}</td>
                    <td class="input_ban">{{ \App\Libraries\CommonFunction::convert2Bangla(@$item->amount_bdt) }}</td>
                </tr>
                {{$i++}}
            @endforeach
            </tbody>
        </table>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <td></td>
                <td><h4>স্থানীয়</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->sales_local.'%') }}</td>
                <td><h4>বিদেশী</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->sales_foreign.'%') }}</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td colspan="3"><h4>বিনিয়োগ</h4> </td>
            </tr>
            <tr>
                <td colspan="2"><h4>স্থায়ী বিনিয়োগ</h4> </td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>১</td>
                <td><h4>ভূমি</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_land_ivst) }}</td>
            </tr>
            <tr>
                <td>২</td>
                <td><h4>ভবন/শেড</h4></td>
                <td>{{\App\Libraries\CommonFunction::convert2Bangla($appInfo->local_building_ivst)}}</td>
            </tr>
            <tr>
                <td>৩</td>
                <td><h4>যন্ত্রপাতি ও সরঞ্জামাদি</h4></td>
                <td>{{\App\Libraries\CommonFunction::convert2Bangla($appInfo->local_machinery_ivst)}}</td>
            </tr>
            <tr>
                <td>৪</td>
                <td><h4>অন্যান্য</h4></td>
                <td>{{\App\Libraries\CommonFunction::convert2Bangla($appInfo->local_others_ivst)}}</td>
            </tr>

            <tr>
                <td colspan="2"> <h4>চলতি মূলধন (৩ মাসের)</h4></td>
                <td>{{\App\Libraries\CommonFunction::convert2Bangla($appInfo->local_wc_ivst)}}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right"><h4>সর্বমোট বিনিয়োগ</h4></td>
                <td>{{\App\Libraries\CommonFunction::convert2Bangla($appInfo->total_fixed_ivst_million)}}</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td colspan="3"><h4> বিনিয়োগের উৎস</h4></td>
            </tr>
            <tr>
                <td></td>
                <td><h4>টাকা</h4> </td>
                <td><h4>ডলার</h4> </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><h4>উদ্যোক্তার সম-মূলধন</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->ceo_taka_invest) }}</td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->ceo_dollar_invest) }}</td>
            </tr>
            <tr>
                <td><h4>স্থানীয় ঋণ</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_loan_taka) }}</td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_loan_dollar) }}</td>
            </tr>
            <tr>
                <td><h4>বিদেশী ঋণ</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->foreign_loan_taka) }}</td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->foreign_loan_dollar) }}</td>
            </tr>
            <tr>
                <td><h4>সর্বমোট</h4></td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->total_inv_taka) }}</td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->total_inv_dollar) }}</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <td colspan="3"><h4>দেশভিত্তিক ঋণ</h4> </td>
            </tr>
            <tr>
                <td><h4>দেশের নাম</h4> </td>
                <td><h4>সংস্থার নাম</h4></td>
                <td><h4>ঋণের পরিমাণ(টাকায়)</h4></td>
            </tr>
            @foreach($loanSrcCountry as $item)
                <tr>
                    <td>
                        {{ @$item->country_name }}
                    </td>
                    <td>
                        {{ @$item->loan_org_nm }}
                    </td>
                    <td class="input_ban">
                        {{  \App\Libraries\CommonFunction::convert2Bangla($item->loan_amount) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        কর্মসংস্থানঃ স্থানীয় পুরুষ- {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_male) }}, স্থানীয় মহিলা- {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_female) }}, বিদেশী পুরুষ- {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->foreign_male) }},  বিদেশী মহিলা- {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->foreign_female) }}, মোট {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->manpower_total) }} জন
        <br>
        উদ্যোক্তাঃ {{ @$appInfo->ceo_name }}, জাতীয়তা-{{ @$appInfo->ceo_nationality }}, ফোন- {{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->ceo_mobile) }}
        <br>
        <div class="row">
            <div class="col-md-4" style="float: left;width: 55%;">
                <img src="data:image/png;base64,{{$qrCode}}" alt="Qrcode" />
            </div>
            <div class="col-md-8 " style="text-align: right">
                <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                {{@$signatory->user_first_name}}<br>
                {{@$signatory->designation}}<br>
                ফোনঃ {{@\App\Libraries\CommonFunction::convert2Bangla($signatory->user_phone) }}<br>
                ইমেইলঃ {{$signatory->user_email}}<br>
            </div>
        </div>
        <pagebreak></pagebreak>
        <br>
        <div class="text-center">
            <h3> উদ্যোক্তা/পরিচালকগনের তালিকা
            </h3>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr style="font-size: 16px">
                <td><h4>নং</h4> </td>
                <td><h4>নাম</h4> </td>
                <td><h4>পদবি</h4></td>
                <td><h4>NID</h4></td>
                <td><h4>জাতীয়তা</h4></td>
            </tr>
            </thead>
            <tbody>
            @php
                $i = 1;
            @endphp
            @foreach($investorInfo as $item)
                <tr class="text-center" id="" data-number="1">
                    <td> {{ \App\Libraries\CommonFunction::convert2Bangla($i) }}</td>
                    <td>{{ @$item->investor_nm }}</td>
                    <td>{{ @$item->designation }}</td>
                    <td class="input_ban">{{ \App\Libraries\CommonFunction::convert2Bangla(@$item->identity_no) }}</td>
                    <td>{{ @$item->nationality }}</td>
                </tr>
                {{$i++}}
            @endforeach
            </tbody>
        </table>

        <br>

        <div class="row">
            <div class="col-md-6" style="float: left;width: 55%;">
                <img style="width: 100px; height: 60px" src="{{ (!empty($appInfo->entrepreneur_signature) ? url('' . $appInfo->entrepreneur_signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                ({{@$appInfo->ceo_name}})<br>
                {{@$appInfo->designation}}<br>
                {{@$appInfo->org_nm_bn}}<br>
                <br>
                <img src="data:image/png;base64,{{$qrCode}}" alt="Qrcode" />
            </div>
            <div class="col-md-6 " style="text-align: right">
                <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                {{@$signatory->user_first_name}}<br>
                {{@$signatory->designation}}<br>
                ফোনঃ {{@\App\Libraries\CommonFunction::convert2Bangla($signatory->user_mobile) }}<br>
                ইমেইলঃ {{$signatory->user_email}}<br>
            </div>
        </div>
        <pagebreak></pagebreak>
        <br>

        <div class="text-center">
            <h3>আমদানিকৃত/আমদানিতব্য যন্ত্রপাতির তালিকা </h3>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td><h4>নং</h4> </td>
                <td><h4>যন্ত্রপাতির নাম</h4></td>
                <td><h4>মূল্য</h4></td>
            </tr>
            </thead>
            <tbody>
            @php
                $i = 1;
            @endphp
            @foreach($importedMachinery as $item)
                <tr class="text-center">
                    <td>{{ \App\Libraries\CommonFunction::convert2Bangla($i) }}</td>
                    <td>
                        {{ \App\Libraries\CommonFunction::convert2Bangla($item->machinery_nm) }}
                    </td>
                    <td>
                        {{ \App\Libraries\CommonFunction::convert2Bangla($item->machinery_price) }}
                    </td>
                </tr>
                {{$i++}}
            @endforeach
            </tbody>
            <tr>
                <td class="text-right" colspan="2" ><h4>মোট</h4> </td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->imported_machinery_total) }}</td>
            </tr>
        </table>
        <br>
        <div class="row">
            <div class="col-md-6" style="float: left;width: 55%; ">
                <img style="width: 100px; height: 60px" src="{{ (!empty($appInfo->entrepreneur_signature) ? url('' . $appInfo->entrepreneur_signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                ({{@$appInfo->ceo_name}})<br>
                {{@$appInfo->designation}}<br>
                {{@$appInfo->org_nm_bn}}<br>
                <br>
                <img src="data:image/png;base64,{{$qrCode}}" alt="Qrcode" />
            </div>
            <div class="col-md-6 " style="text-align: right">
                <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                {{@$signatory->user_first_name}}<br>
                {{@$signatory->designation}}<br>
                ফোনঃ {{@\App\Libraries\CommonFunction::convert2Bangla($signatory->user_mobile) }}<br>
                ইমেইলঃ {{@$signatory->user_email}}<br>
            </div>
        </div>
        <pagebreak></pagebreak>

        <div class="text-center">
            <h3>স্থানীয়ভাবে সংগৃহীত/সংগৃহীতব্য যন্ত্রপাতির তালিকা </h3>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <td><h4>নং</h4> </td>
                <td><h4>যন্ত্রপাতির নাম</h4></td>
                <td><h4>মূল্য</h4></td>
            </tr>
            </thead>
            <tbody>
            @php
                $i = 1;
            @endphp
            @foreach($localMachinery as $item)
                <tr class="text-center">
                    <td>{{ \App\Libraries\CommonFunction::convert2Bangla($i) }}</td>
                    <td>
                        {{ \App\Libraries\CommonFunction::convert2Bangla($item->machinery_nm) }}
                    </td>
                    <td>
                        {{ \App\Libraries\CommonFunction::convert2Bangla($item->machinery_price) }}
                    </td>
                </tr>
                {{$i++}}
            @endforeach
            </tbody>
            <tr>
                <td class="text-right" colspan="2" style="font-size: 16px">মোট</td>
                <td>{{ \App\Libraries\CommonFunction::convert2Bangla($appInfo->local_machinery_total) }}</td>
            </tr>
        </table>
        <br>
        <div class="row">
            <div class="col-md-6" style="float: left;width: 55%;">
                <img style="width: 100px; height: 60px" src="{{ (!empty($appInfo->entrepreneur_signature) ? url('' . $appInfo->entrepreneur_signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                ({{@$appInfo->ceo_name}})<br>
                {{@$appInfo->designation}}<br>
                {{@$appInfo->org_nm_bn}}<br>
                <br>
                <img src="data:image/png;base64,{{$qrCode}}" alt="Qrcode" />
            </div>
            <div class="col-md-6 " style="text-align: right">
                <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                {{@$signatory->user_first_name}}<br>
                {{@$signatory->designation}}<br>
                ফোনঃ {{\App\Libraries\CommonFunction::convert2Bangla(@$signatory->user_mobile) }}<br>
                ইমেইলঃ {{$signatory->user_email}}<br>
            </div>

        </div>
        <pagebreak></pagebreak>
        <div class="row">
            <div class="col-md-8 " style="text-align: right">
                <span  style="font-size: 16px;" class="input_ban">তারিখ: {{\App\Libraries\CommonFunction::convert2Bangla(date('d-m-Y', strtotime($appInfo->completed_date))) }}</span><br>
            </div>
        </div>
        <div class="text-center">
            <h4>উদ্যোক্তা/উদ্যোক্তাদের জন্য কতিপয় জ্ঞাতব্য বিষয়</h4>
        </div>
        <div style="font-size:14px">
            ১. উদ্যোক্তা/উদ্যোক্তাগণ তাঁদের প্রকল্প বাস্তবায়ন ও চালু করার জন্য অবকাঠামোগত ও অন্যান্য সুবিধাদি প্রয়োজনবোধে পরিবেশ দূষণ নিয়ন্ত্রন দপ্তরের ছাড়পত্র) স্বীয় উদ্যোগে সংগ্রহ করতে পারেন। বিকল্পে বিসিক সংশ্লিষ্ট এসব প্রয়োজনীয় সেবা/সুবিধাদি পাওয়ার জন্য উদ্যোক্তার অনুরোধে সহায়তা দান করবে। এ প্রসংগে উদ্যোক্তাগণকে বিসিকের জেলা/বিভাগ/প্রধান কার্যালয়ে যোগাযোগ করতে হবে।
            <br>
            ২. বেসরকারী খাতে বিনিয়োগ উৎসাহিত করার লক্ষ্যে বিসিকের পারমর্শদাতা কর্মকর্তাগণ বিনিয়োগ সংক্রান্ত সর্ব প্রকার পরামর্শ, তথ্য সরকারী নীতিমালা পদ্ধতি ও নিয়ন্ত্রন বিষয়ক ব্যাখ্যাদান ইত্যাদি সহ সর্বপ্রকার সেবা দানের জন্য সর্বদা প্রস্তুত।
            <br>
            ৩. শিল্প প্রকল্পের মালিকানা অথবা কারখানার অবস্থান সম্পর্কিত যে কোন পরিবর্তন অবশ্যই তাৎক্ষণিকভাবে বিসিককে অবহিত করতে হবে।

            <br>
            ৪. সরকার ঘোষিত শিল্প ও অর্থনৈতিক নীতিমালা ও জাতীয় রাজস্ব বোর্ড এবং অন্যান্য সংশ্লিষ্ঠ সরকারী সংস্থাসমূহ কর্তৃক সময়ে সময়ে জারীকৃত প্রজ্ঞাপন অনুযায়ী প্রকল্পটি বিভিন্ন প্রকার কর, আর্থিক সুবিধাদি এবং ইউটিলিটি চার্জ রেয়াতপ্রাপ্ত হবে। এসব সরকার ঘোষিত নীতি ও প্রজ্ঞাপন সমূহের অনুলিপি বিসিক এর জেলা/বিভাগ/প্রধান কার্যালয় হতে সংগ্রহ করা যাবে।
            <br>
            ৫. প্রস্তাবিত শিল্প প্রকল্পের বাস্তবায়ন অগ্রগতি যথাসময়ে বিসিককে অবহিত করতে হবে। যে সব প্রস্তাবিত প্রকল্পের বাস্তবায়ন নিবন্ধন পত্রে প্রদত্ত সময়সীমার মধ্যে সম্পূর্ণ হবে না, সে সব প্রকল্পের নিবন্ধন সময়সীমা অতিক্রমের ৩ মাসের মধ্যে পুনঃ নবায়ন করে নিতে হবে ; অন্যথায় প্রদত্ত নিবন্ধন বাতিল বলে গন্য করা হবে।
            <br>
            ৬. কতগুলি শিল্প পণ্য বাজারজাত করার পূর্বে বাংলাদেশ স্ট্যান্ডার্ডস এন্ড টেস্টিং ইনস্টিটিউশন (বি,এস,টি,আই) কর্তৃক নির্ধারিত মান সম্পন্ন হওয়া বাধ্যতামূলক। বি,এস,টি,আই এর সব সংশ্লিষ্ট প্রজ্ঞাপনের অনুলিপি বিসিক এর জেলা/বিভাগ/ প্রধান কার্যালয় হতে পাওয়া যাবে।
            <br>
            ৭. উৎপাদন প্রক্রিয়া ও কর্মকান্ডের প্রেক্ষিতে কোন কোন শিল্প প্রকল্প স্থাপনের পূর্বে সরকারের পরিবেশ অধিদপ্তরের ছাড়পত্রের প্রয়োজন হতে পারে।
            <br>
            ৮. ইহা ব্যতীত এই শিল্প প্রকল্প স্থাপন নিম্নলিখিত শর্তসমূহ অবশ্যই পালনীয়;
            <br>

            <div style="padding-left: 45px; ">

                ৮.১ কারখানার সম্মূখে কমপক্ষে ১.৪x০.৮ মিটার সাইজ বিসিক নিবন্ধনকৃত লিখে সাইন বোর্ড লাগাতে হবে।
                <br>
                ৮.২ এ নিবন্ধনপত্র হস্তান্তরযোগ্য নয়। ৫ (পাঁচ) বছর পর পর নবায়নযোগ্য;
                <br>
                ৮.৩ দাখিলকৃত কাগজপত্রের ভিত্তিতে এ নিবন্ধনপত্র ইস্যু করা হলো;
                <br>
                ৮.৪ কারখানায় কোন প্রকার অবৈধ নিষিদ্ধ পন্য উৎপাদন করা যাবেনা। করা হলে তার দায়-দায়িত্ব উদ্যোক্তাকেই বহন করতে হবে।
                <br>
                ৮.৫ এই রেজিস্টেশন উৎপাদিত পন্যের মান নিয়ন্ত্রণে নিশ্চয়তা বহন করে না;
                <br>
                ৮.৬ উৎপাদিত পণ্যের মান নিয়ন্ত্রনে সংশ্লিষ্ট কর্তৃপক্ষের অনুমোদন গ্রহণ করতে হবে;
                <br>
                ৮.৭ পন্য উৎপাদনে আইনি জটিলতায় বিসিক কর্তৃপক্ষ দায়ী হবে না;
                <br>
                ৮.৮ বিসিক কর্তৃপক্ষ নিবন্ধন বাতিলের ক্ষমতা সংরক্ষণ করেন;
                <br>
                ৮.৯ পরিবেশ ছাড়পত্র গ্রহণ সাপেক্ষে পরিবেশ দূষণমুক্ত রাখতে হবে;
                <br>
                ৮.১০ দূর্ঘটনা এড়ানোর লক্ষ্যে ফায়ার সার্ভিস বিভাগের অনুমতি নিতে হবে এবং পর্যাপ্ত সংখ্যক অগ্নি নির্বাপক যন্ত্র কারখানায় স্থাপন
                করতে হবে।


            </div>
            ৯. উপরে বর্ণিত কোন শর্ত ভঙ্গ হলে বিসিক এ বিনিয়োগ প্রকল্পের নিবন্ধন বাতিল করার ক্ষমতা সংরক্ষণ করে।
            <br>
            ১০. উদ্যোক্তা প্রদত্ত তথ্যের ভিত্তিতে বিসিক কর্তৃক অনলাইনে পুনঃনিবন্ধন প্রদান করা হয়েছে। তথ্যের অসঙ্গতি পরিলক্ষিত হতে পুনঃনিবন্ধন বাতিল হতে পারে।
        </div>
        <br>

        <div class="row">
            <div class="col-md-4" style="float: left;width: 55%;">
                <img src="data:image/png;base64,{{$qrCode}}" alt="Qrcode" />
            </div>
            <div class="col-md-8 " style="text-align: right; ">
                <img src="{{ (!empty($signatory->signature) ? url('uploads/users/signature/' . $signatory->signature) : url('assets/images/paper-business-contract-pen-signature-icon-vector-15749289.jpg')) }}"/>
                <br>
                {{@$signatory->user_first_name}}<br>
                {{@$signatory->designation}}<br>
                ফোনঃ {{@\App\Libraries\CommonFunction::convert2Bangla($signatory->user_mobile) }}<br>
                ইমেইলঃ {{$signatory->user_email}}<br>
            </div>

        </div>
    </div>

</div>

</body>
</html>
