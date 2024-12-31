<html>

<body>
<div class="row">
    <div style="text-align: center;">
        {{--header--}}
            <div class="header" style="margin:auto;width: 100%">
                <div style="text-align: center;">
                  <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                    <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                    <strong style="font-size: 30px;">REGULATORY COMMISSION</strong><br>
                    <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
                </div>
            </div>
        <br>
        <img src="data:image/png;base64,{{$qrCode}}" width="100px" height="100px" alt="barcode" />
        <br>
        <br>
        <strong style="font-size: 20px;">OPERATOR LICENSE</strong>
        <br>
        <strong style="font-size: 20px;">FOR</strong>
        <br>
        @if($appInfo->process_type_id==17||$appInfo->process_type_id==18)
        <strong style="font-size: 20px;">INTERNATIONAL INTERNET GATEWAY (IIG)</strong>
        @elseif($appInfo->process_type_id==37||$appInfo->process_type_id==38)
        <strong style="font-size: 18px;">INTERNATIONAL GATEWAY(IGW)</strong><br><br>
        @elseif($appInfo->process_type_id==62||$appInfo->process_type_id==63)
        <strong style="font-size: 18px;">SUBMARINE CABLE SERVICE</strong><br><br>
        @elseif($appInfo->process_type_id==66||$appInfo->process_type_id==67)
        <strong style="font-size: 18px;">TOWER SHARING</strong><br><br>
        @elseif($appInfo->process_type_id==74||$appInfo->process_type_id==75)
        <strong style="font-size: 18px;">PUBLIC SWITCHED TELEPHONE NETWORK (PSTN)</strong><br><br>

        @else
        <strong style="font-size: 20px;">{{$service_name}}</strong>
        @endif
        <br>
        <br>
        <strong style="font-size: 20px;">ISSUED</strong>
        <br>
        <br>
        <strong style="font-size: 20px;">TO</strong>
        <br>
        <br>
        <span style="font-size: 25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>{{$appInfo->org_nm ?? ''}}</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
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
        <span style="font-size: 20px; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('j',strtotime($appInfo->license_issue_date))}}<sup>{{date('S',strtotime($appInfo->license_issue_date))}}</sup>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><strong style="font-size: 20px; margin-top: 50px;">DAY OF</strong><span style="font-size: 20px; padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('F',strtotime($appInfo->license_issue_date)).', '.date('Y',strtotime($appInfo->license_issue_date))}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

        {{--        page 2--}}
{{--        header--}}
        <div class="header" style="margin:auto;width: 100%">
            <div style="text-align: center;">
                <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
                <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
            </div>
        </div>


        <strong style="font-size: 18px;">OPERATOR LICENSE</strong><br>
        <strong style="font-size: 18px;">FOR</strong><br>
        @if($appInfo->process_type_id==17||$appInfo->process_type_id==18)
        <strong style="font-size: 18px;">INTERNATIONAL INTERNET GATEWAY (IIG)</strong><br><br>
        @elseif($appInfo->process_type_id==37||$appInfo->process_type_id==38)
        <strong style="font-size: 18px;">INTERNATIONAL GATEWAY(IGW)</strong><br><br>
        @elseif($appInfo->process_type_id==62||$appInfo->process_type_id==63)
        <strong style="font-size: 18px;">SUBMARINE CABLE SERVICE</strong><br><br>
        @elseif($appInfo->process_type_id==66||$appInfo->process_type_id==67)
        <strong style="font-size: 18px;">TOWER SHARING</strong><br><br>
        @elseif($appInfo->process_type_id==74||$appInfo->process_type_id==75)
        <strong style="font-size: 18px;">PUBLIC SWITCHED TELEPHONE NETWORK (PSTN)</strong><br><br>
        @else
        <strong style="font-size: 18px;">{{$service_name ?? ''}}</strong><br><br>
        @endif


        <table class="table-responsive" width="100%" border="1">
            <tr>
                <td style="padding: 10px;"><strong style="font-size: 18px;">LICENSE NO:</strong></td>
                <td style="padding: 10px;"><strong style="font-size: 18px;">{{$appInfo->license_no}}</strong></td>
                <td style="padding: 10px;"><strong style="font-size: 18px;">DATE</strong></td>
{{--                <td style="padding: 10px;"><strong style="font-size: 18px;">{{date('d.M.Y',strtotime($appInfo->license_issue_date))}}</strong></td>--}}
                <td style="padding: 10px;"><strong style="font-size: 18px;">{{date('d F, Y', strtotime($appInfo->license_issue_date))}}</strong></td>
            </tr>
        </table>
        <br>


        <strong style="font-size: 18px;">In Exercise of the Powers</strong><br>
        <strong style="font-size: 18px;">under section 36 of the Bangladesh Telecommunication Regulation Act, 2001</strong><br>
        <strong style="font-size: 18px;">(Act No. XVIII of 2001)</strong><br>

        <strong style="font-size: 18px;">BANGLADESH TELECOMMUNICATION REGULATORY COMMISSION</strong><br>
        <strong style="font-size: 18px;">is pleased to grant the license in favour of</strong><br><br>
        <span style="font-size: 25px;padding-bottom: 5px;">
           <strong>{{!empty($companyInfo->org_nm) ? $companyInfo->org_nm : ''}} {{!empty($isp_license_type_area_info) ? ('(' . ($isp_license_type_area_info['name'].', ' ?? '')  . $isp_license_type_area_info['type'] .')' ) : ''  }}</strong></span><br>

        <strong style="font-size: 18px;">represented by its Proprietor/Partner/Managing Director/Chairman/CEO having registered office at</strong><br><br>
        <span style="font-size: 25px; padding-bottom: 5px; text-align: center"><strong>{{$companyInfo->office_location}}</strong></span><br>
        <strong>  <span>{{ $companyInfo->district_name . ', ' . $companyInfo->thana_name}}</span></strong>


      <div>
          <strong style="font-size: 18px;">as an Operator of<br>
              Internet Services<br>
              in Bangladesh<br>
              whereby it is authorized<br>
              to establish, maintain and operate the associated systems and<br>
              ON NON-EXCLUSIVE BASIS<br>
              under the terms and conditions given in the following pages<br>
              including the schedules annexed hereto.</strong>

      </div>
      <br>
        {{--        page 3--}}
        <div style="margin-top: 8px">
            <strong style="font-size: 18px;">Table</strong><br><br>
            <div style="width: 100%">
                <table style="width: 100%;font-size: 15px" class="table table-bordered table-condensed" >
                    <thead>
                    <tr>
                        <th style="text-align: center; font-weight: bold;width: 7%">SL</th>
                        <th style="text-align: center; font-weight: bold;width: 80%">Subject</th>
                        <th style="text-align: center; font-weight: bold;width: 13%">Page</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding-left: 7px">Preamble.............................................................................................................................................</td>
                            <td class="text-center">4</td>
                        </tr>
                        <tr>
                            <td class="text-center">1.</td>
                            <td style="padding-left: 7px">Scope of License .............................................................................................................................</td>
                            <td class="text-center">5</td>
                        </tr>
                        <tr>
                            <td class="text-center">2.</td>
                            <td style="padding-left: 7px">Duration of the License ......................................................................................................</td>
                            <td class="text-center">5</td>
                        </tr>
                        <tr>
                            <td class="text-center">3.</td>
                            <td style="padding-left: 7px">Renewal of the License ....................................................................................................................</td>
                            <td class="text-center">5</td>
                        </tr>
                        <tr>
                            <td class="text-center">4.</td>
                            <td style="padding-left: 7px">Fees and Charges ..........................................................................................................................</td>
                            <td class="text-center">5</td>
                        </tr>
                        <tr>
                            <td class="text-center">5.</td>
                            <td style="padding-left: 7px">Bank Guarantee ..............................................................................................................................</td>
                            <td class="text-center">6</td>
                        </tr>
                        <tr>
                            <td class="text-center">6.</td>
                            <td style="padding-left: 7px">Commencement of Operation .......................................................................................................</td>
                            <td class="text-center">6</td>
                        </tr>
                        <tr>
                            <td class="text-center">7.</td>
                            <td style="padding-left: 7px">Systems ..............................................................................................................................................</td>
                            <td class="text-center">6</td>
                        </tr>
                        <tr>
                            <td class="text-center">8.</td>
                            <td style="padding-left: 7px">Services .................................................................................................................................................</td>
                            <td class="text-center">6</td>
                        </tr>
                        <tr>
                            <td class="text-center">9.</td>
                            <td style="padding-left: 7px">Tariff ..............................................................................................................................................</td>
                            <td class="text-center">7</td>
                        </tr>
                        <tr>
                            <td class="text-center">10.</td>
                            <td style="padding-left: 7px">Network and Connectivity ................................................................................................................</td>
                            <td class="text-center">7</td>
                        </tr>
                        <tr>
                            <td class="text-center" >11.</td>
                            <td style="padding-left: 7px"> Rollout Obligations .............................................................................................................................</td>
                            <td class="text-center">7</td>
                        </tr>
                        <tr>
                            <td class="text-center">12.</td>
                            <td style="padding-left: 7px"> Sharing of Facilities ...........................................................................................................</td>
                            <td class="text-center">7</td>
                        </tr>
                        <tr>
                            <td class="text-center">13.</td>
                            <td style="padding-left: 7px"> Information, Inspection, Reporting and Monitoring ............................................................</td>
                            <td class="text-center">8</td>
                        </tr>
                        <tr>
                            <td class="text-center">14.</td>
                            <td style="padding-left: 7px"> Amendments .............................................................................................................................</td>
                            <td class="text-center">8</td>
                        </tr>
                        <tr>
                            <td class="text-center">15.</td>
                            <td style="padding-left: 7px"> Changes in Ownership ......................................................................................................................</td>
                            <td class="text-center">8</td>
                        </tr>
                        <tr>
                            <td class="text-center">16.</td>
                            <td style="padding-left: 7px"> Consumer Protection ...........................................................................................................</td>
                            <td class="text-center">8</td>
                        </tr>
                        <tr>
                            <td class="text-center">17.</td>
                            <td style="padding-left: 7px"> Transfer, Assignment and Pledge as Security .........................................................</td>
                            <td class="text-center">8</td>
                        </tr>
                        <tr>
                            <td class="text-center">18 </td>
                            <td style="padding-left: 7px">Lawful Interception .......................................................................................................................</td>
                            <td class="text-center">9</td>
                        </tr>
                        <tr>
                            <td class="text-center">19.</td>
                            <td style="padding-left: 7px"> Parental Control Guidance .............................................................................................................</td>
                            <td class="text-center">9</td>
                        </tr>
                        <tr>
                            <td class="text-center">20.</td>
                            <td style="padding-left: 7px"> Suspension, Cancellation and Fines ............................................................................................</td>
                            <td class="text-center">9</td>
                        </tr>
                        <tr>
                            <td class="text-center">21.</td>
                            <td style="padding-left: 7px"> Impact of Suspension and Cancellation of License ..................................................................</td>
                            <td class="text-center">9</td>
                        </tr>
                        <tr>
                            <td class="text-center">22.</td>
                            <td style="padding-left: 7px"> Miscellaneous ......................................................................................................................................</td>
                            <td class="text-center">9</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        {{--        page 4--}}
        <pagebreak>
{{--            header--}}
            <div class="header" style="margin:auto;width: 100%;">
                <div style="text-align: center;">
                    <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                    <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                    <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
                    <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
                </div>
            </div>
            <br>
        <div class="row" style="width: 100%" >
            <div style="width: 50%;float: left;">
                No {{$appInfo->license_no ? $appInfo->license_no : ''}}
{{--                No ....................................................................................................--}}
            </div>
            <div style="width: 50%;float: right;">
{{--                Date ...............................--}}
                Date {{$appInfo->license_issue_date ? date('d F, Y',strtotime($appInfo->license_issue_date)) : ''}}
            </div>
        </div>
            <br>
        <div>
            @if($appInfo->process_type_id==17||$appInfo->process_type_id==18)
            <strong style="font-size: 25px">INTERNATIONAL INTERNET GATEWAY (IIG) </strong><br>
            @elseif($appInfo->process_type_id==37||$appInfo->process_type_id==38)
            <strong style="font-size: 18px;">INTERNATIONAL GATEWAY(IGW)</strong><br><br>
            @elseif($appInfo->process_type_id==62||$appInfo->process_type_id==63)
            <strong style="font-size: 18px;">SUBMARINE CABLE SERVICE</strong><br><br>
            @elseif($appInfo->process_type_id==66||$appInfo->process_type_id==67)
            <strong style="font-size: 18px;">TOWER SHARING</strong><br><br>
            @elseif($appInfo->process_type_id==74||$appInfo->process_type_id==75)
            <strong style="font-size: 18px;">PUBLIC SWITCHED TELEPHONE NETWORK (PSTN)</strong><br><br>
            @else
            <strong style="font-size: 25px">{{$service_name}} OPERATOR LICENSE</strong><br>
            @endif
            <p style="font-size: 15px">(Issued under section 36 of Bangladesh Telecommunication Regulation Act, 2001)</p>
            <br>
            <br>
            <p style="text-align: justify;font-size: 15px">The Bangladesh Telecommunication Regulatory Commission (hereinafter referred to as the
                "Commission") has been empowered under section 36 of the Bangladesh Telecommunication
                Regulation Act, 2001 (hereinafter referred to as the "Act") to issue Licenses for the operation and
                provision of telecommunication services.</p>
            <br>
            <p style="font-size: 15px;text-align: justify;">Having given due consideration to the principles of transparency, fairness, non-discrimination
                and all other relevant principles, the Commission has decided to issue License on Internet Services.</p>
            <br>
            <div style="font-size: 15px;text-align: justify;">
                Therefore, in exercise of the powers conferred by Section 36 of the Bangladesh
                Telecommunication Regulation Act, 2001, the Commission upon consideration of their application
                dated {{date('d M, Y',strtotime($appInfo->submitted_at))}} and payment of license fee and other charges, is pleased to
                grant {{$appInfo->org_nm}} having its registered head office
                at <br><br>
                <div style="text-align: center">
                    <div>
                        <div><strong style="font-size: 20px;">{{ $appInfo->org_nm }} </strong><br>{{$appInfo->reg_office_district_name . ', ' . $appInfo->reg_office_thana_name}}</div>
                    </div>
                </div>
            </div>
            <br>
            <strong style="font-size: 20px;text-align: center">LICENSE</strong>
            <div style="font-size: 15px;text-align: justify;line-height: 1.4rem">
                    For a period of 05 (five) years with effect from the
                <span style="padding-bottom: 5px;">{{date('j',strtotime($appInfo->license_issue_date))}}<sup>{{date('S',strtotime($appInfo->license_issue_date))}}</sup></span>
                day of
                <span style="padding-bottom: 5px;">{{date('F',strtotime($appInfo->license_issue_date)).', '.date('Y',strtotime($appInfo->license_issue_date))}}</span>
                to
                <span style="padding-bottom: 5px;">{{date('j',strtotime($appInfo->expiry_date))}}<sup>{{date('S',strtotime($appInfo->expiry_date))}}</sup></span>
                day of
                <span style="padding-bottom: 5px;">{{date('F',strtotime($appInfo->expiry_date)).', '.date('Y',strtotime($appInfo->expiry_date))}}</span>
            </div>
            <br>
            <strong style="font-size: 20px;text-align: center">TO</strong>
            <div style="font-size: 15px;text-align: justify">
                <span style="text-align: left">Build, maintain and operate Internet Services, hereinafter refer to as the service for internet </span>
                <br>
                throughout <span style="padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;<strong>{{!empty($isp_license_type_area_info) ? ( ($isp_license_type_area_info['name']!=''? $isp_license_type_area_info['name'].', ':'')  . $isp_license_type_area_info['type'] ) : ''  }}</strong>&nbsp;&nbsp;&nbsp;</span> subject to
                the following terms and conditions:
            </div>
        </div>

        <pagebreak>

        <div style="text-align: justify">
            <ul>
                <ol style="font-weight: bold;font-size: 15px">1.&nbsp; SCOPE OF THE LICENSE</ol>
                <br>
                <ol style="font-size: 15px">1.1&nbsp; The Licensee shall provide all types of internet/data services and all types of IP based services
                    to the end users.</ol>
                <br>
                <ol style="font-size: 15px">1.2&nbsp; The Licensee shall take lease the transmission network from the NTTN operator(s). However,
                    in case of unavailability of NTTN services, the ISP operators, are entitled to provide its
                    service by following the provisions of infrastructure Sharing Guideline. The licensee can use
                    Wi-Fi service/technology to serve its customers following appropriate instruction and
                    permission from the Commission.</ol>
                <br>
                <ol style="font-size: 15px">1.3&nbsp; The Licensee shall provide the internet/data service to the users by using last mile connectivity
                    whose length shall be limited to approximately 03 (Three) km for metropolitan areas and 06
                    (Six) km for other locations except metropolitan areas. In case of last mile connectivity, the
                    licensee shall follow all instructions, orders, directives of the local authority.</ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">2.&nbsp; DURATION OF THE LICENSE</span>
                    <br><br>
                    <span style="margin-left: 35px">The duration of the Licenses, shall initially be for a term of 05 (five) years unless and until
                    cancelled by the Commission earlier.</span>
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">3.&nbsp; RENEWAL OF THE LICENSE</ol><br>
                <ol style="font-size: 15px">3.1&nbsp; The Licensee(s) shall apply before 180 (One hundred and eighty) days of the expiration of
                    duration of its License for renewal or else the License shall stand cancelled after the expiry date
                    of the License as per law, If the Licensee continues its business thereafter without valid License,
                    penal action shall be followed.</ol>
                <br>
                <ol style="font-size: 15px">3.2&nbsp; Upon expiry of the initial term mentioned in Clause No. 2, the ISP License may be renewed for
                    subsequent terms, each of 05 (five) years in duration subject to the approval from the
                    Government, payment of necessary fees and charges, and to such terms and conditions, as may
                    be specified herein and/or by the Government/Commission under the Act in the time of each
                    renewal. The application form with necessary information is given in APPENDIX-3 of the
                    Guideline.</ol><br>
                <ol style="font-weight: bold;font-size: 15px">4.&nbsp; FEES AND CHARGES</ol>
                <br>
                <ol style="font-size: 15px">4.1&nbsp;The Licensee shall be required to pay necessary fees and charges to the Commission. The Fees
                    and Charges are non-refundable. The details of fees and charges are summarized in the table
                    mentioned in the Clause No. 16 of the Guideline.</ol>
                <br>
                <ol style="font-size: 15px">4.2&nbsp; The fees and charges as described in the guidelines shall have to be paid in due time. The due
                    amount may be paid within 60 (sixty) days after the stipulated date by paying a late fee
                    (additional) at the rate of 15% (fifteen percent) per annum as fine to the Commission. If the
                    amount along with late fee is not paid within the 60 (sixty) days as stipulated, such failure may
                    result in stand cancellation of the License.</ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">5.&nbsp;BANK GUARANTEE</ol>
                <br>
                <ol style="font-size: 15px">5.1 &nbsp;The licensee shall submit a Bank Guarantee as mentioned in Clause No. 16 of the guidelines in
                    favour of Bangladesh Telecommunication Regulatory Commission (BTRC) within 30 (thirty)
                    days from the date of issuance of the license in a prescribed form (APPENDIX-5 of the
                    Guidelines) issued by a scheduled Bank [Schedule to the Bangladesh Bank Order, 1972 (P.O.
                    No. 127 of 1972)]. This Guarantee is irrevocable and shall remain in force for the total tenure
                    of the ISP License.</ol>
                <br><ol style="font-size: 15px">5.2 &nbsp;This Bank Guarantee shall be in force initially for 03 (three) years from the date thereof. On the
                    very next date of completion of the initial term, the Licensee shall submit Bank Guarantee for
                    the subsequent 03 (three) years and in this way shall continue submitting Bank Guarantees for
                    each of the remaining and subsequent term of the ISP License.</ol>
                <br><ol style="font-size: 15px">5.3 &nbsp;This Bank Guarantee will serve as security deposit for dues annually payable under the terms
                    and conditions of the License. In case of failure to make payment within the stipulated time, the
                    equivalent amount of annual fee will be encashed from the Guarantee for each year or fraction
                    thereof. The Commission may encash the Guarantee to any extent to realize the outstanding
                    dues/fines as well. When the full Guarantee will be encashed by the Commission for non-
                    payment of outstanding dues. The Commission will take necessary action to cancel the License.</ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">6.&nbsp; COMMENCEMENT OF OPERATION</span>
                    <br><br>
                    <span style="margin-left: 35px">The Licensee shall commence operation within 06 (six) months from the date of issuance of the
                        License. Time extension may be considered by the Commission upon receiving of application
                        regarding time extension from Licensee stating reasons thereof, otherwise License may be
                        cancelled.
                    </span>
                </ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">7.&nbsp; SYSTEMS</span>
                    <br><br>
                    <span style="margin-left: 35px">The Licensee shall be connected to the International Internet Gateway (IIG). The Licensee shall
                        take lease the transmission network from the NTTN operator(s). The Licensee can use Wi-Fi
                        service/ technology to serve its customers following appropriate instruction of the Commission
                        with prior approval from the Commission. The Licensee shall be connected to National Internet
                        Exchange (NIX) for domestic inter-operator data traffic.
                    </span>
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">8.&nbsp;SERVICES</ol><br>
                <ol>
                    8.1&nbsp; The Licensees are allowed to provide all types of internet/data and IP based services. These
                    include, but not limited to, the following services:
                    <br>
                    <br>
                    <ul>
                        <ol>i) Internet Connectivity,</ol>
                        <ol>ii) Electronic mail,</ol>
                        <ol> iii) News Group,</ol>
                        <ol>iv) Internet relay chat,</ol>
                        <ol>v) File Transfer Protocol (FTP) based services,</ol>
                        <ol>vi) Any innovative bundled service which are IP based,</ol>
                        <ol>vii) Instant Messaging</ol>
                    </ul>
                </ol>
                <br>
                <ol>
                    8.2&nbsp;The Licensee is allowed to provide the following services subject to the prior approval of the
                    Commission:
                    <br>
                    <br>
                    <ul>
                        <ol> i) Any other Over The Top (OTT) services</ol>
                        <ol>ii) Video Conference</ol>
                    </ul>
                </ol>
                <br>
                <ol>
                    8.3&nbsp; The Licensee shall have to obtain prior approval issued by Commission regarding Over the Top
                    (OTT) and Internet of Things (IoT) services from time to time until new Regulation/
                    Instructions/ Directives/ Orders regarding the said Services are issued by the Commission. The
                    ISP Licensee may provide new internet related services with prior permission from the
                    Commission time to time.
                </ol><br>
                <ol>
                    8.4&nbsp;The Licensee shall have to follow the Instructions/ Directives/ Orders issued by Commission
                    regarding Triple Play (Data, Voice & Video) service from time to time.
                </ol><br>
                <ol>
                    8.5&nbsp; The ISP licensee is allowed to provide IPTV services subject to the fulfilment of the conditions
                    of Ministry of Information.
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">9.&nbsp; TARIFF</ol>
                <br>
                <ol>
                    9.1&nbsp; The Commission shall have the right to determine the tariff, in the manner as and when
                    necessary.
                </ol>
                <br>
                <ol>9.2&nbsp;All other conditions for Tariff described in the Clause No. 21 of the Guideline shall also be
                    applicable for the licensee.</ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">10.&nbsp; NETWORK AND CONNECTIVITY</ol>
                <br>
                <ol>10.1&nbsp;The Nationwide, Divisional, District and Upazila/Thana ISP Licensee shall be connected to the
                    licensed International Internet Gateway (IIG) for taking lease of internet bandwidth.</ol><br>
                <ol>10.2&nbsp; All the conditions for Network and Connectivity described in the Clause No. 22 of the
                    Guideline shall also be applicable for the licensee.</ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">11.&nbsp; ROLLOUT OBLIGATION</ol>
                <br>
                <ol>11.1&nbsp; The licensee shall fulfil the rollout obligations as mentioned in the Clause No. 23 of the
                    Guideline.</ol> <br>
                <ol>11.2&nbsp; The Commission reserves the right to cancel ISP license if the licensee fails to fulfil the above-
                    mentioned rollout obligations.</ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">12.&nbsp; SHARING OF FACILITIES</ol>
                <br>
                <ol>12.1&nbsp; The modalities for sharing infrastructure shall be as per Infrastructure Sharing Guidelines as
                    approved by the Commission.</ol>
                <br>
                <ol>12.2&nbsp; The Licensee shall follow the conditions of the Act, any Regulations/Bye-laws/ Directives/
                    Instructions/Permit/Guidelines/Orders/Circulars/Decisions etc. in case of infrastructure and
                    facility sharing and such conditions as may be imposed by the Commission from time to time.</ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">13.&nbsp;  INFORMATION, INSPECTION, REPORTING AND MONITORING</ol>
                <br>
                <ol>13.1&nbsp;The Licensee shall furnish necessary information and other related matters as may be sought
                    by the Commission from time to time.</ol>
                <br>
                <ol>13.2&nbsp; The Commission or any person authorized by the Commission shall have unfettered right and
                    authority to obtain the copies of records, documents and other information relating to the
                    Licensees’ business, for the purpose of enabling the Commission to perform its functions
                    under the Act and provisions of the Guideline.</ol>
                <br>
                <ol>13.3&nbsp; The conditions for Information, Inspection, Reporting and Monitoring described in the Clause
                    No. 25 of the Guideline shall also be applicable for the ISP licensee.</ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">14.&nbsp; AMENDMENTS</span>
                    <br><br>
                    <span style="margin-left: 35px">The Commission has the right and authority to change, amend, vary or revoke any of the terms
                        and conditions of the License prepared based on the Guideline and also to incorporate new
                        terms and conditions necessary for the interest of national security, or public interest, or any
                        other reason, in consonance with the provisions of the Act and Regulations.
                    </span>
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">15.&nbsp; CHANGES IN OWNERSHIP</ol>
                <br>
                <ol>15.1&nbsp; The Licensee shall seek prior written approval from the Commission before making any
                    change in its ownership. Any change in the ownership shall not be valid or effective without
                    the prior written approval of the Commission. In this case the commission shall follow section
                    37(2)(i) of Bangladesh Telecommunication Regulation Act, 2001.</ol>
                <br>
                <ol>15.2&nbsp; The Licensee shall neither transfer any share nor issue new shares without prior written
                    permission from the Commission.</ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">16.&nbsp;CONSUMER PROTECTION</span>
                    <br><br>
                    <span style="margin-left: 35px">The conditions for consumer protection described in the Clause No. 29 of the Guideline shall
                            also be applicable for the ISP licensee.
                    </span>
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">17.&nbsp; TRANSFER, ASSIGNMENT AND PLEDGE AS SECURITY</ol>
                <br>
                <ol>17.1&nbsp;The Licensee shall notify the Commission to obtain any loan for deployment of its network.
                    The License and Radio Equipment shall not be assigned or pledged as security. There shall be
                    no liability of the Commission for obtaining any loan from Bank and other financial
                    institution.</ol>
                <br><ol>17.2&nbsp; The License and any right accrued there-under shall not be transferred, wholly or partly, without
                    prior permission of the Government, and such transfer, if any, shall be void.</ol>
                <br>
                <pagebreak>
                <ol style=""><span style="font-weight: bold;font-size: 15px">18.&nbsp; LAWFUL INTERCEPTION (LI)</span>
                    <br>
                    <span style="margin-left: 35px">The operational system of the Licensee shall be LI compatible and the licensee shall only be
                        connected with LI monitoring systems LEA premise. The Licensee shall ensure LI
                        Compliance through identification, verification, authorization and monitoring the internet
                        usage of its Wi-Fi subscriber. The Licensee shall comply with Rules /Regulations/ Bye-laws/
                        Directives/ Guidelines/ Instructions/ Orders/ Circulars/ Decisions etc. regarding Lawful
                        Interception (LI) issued from time to time by the Commission or the Government.
                    </span>
                </ol>
                <br>
                <ol style=""><span style="font-weight: bold;font-size: 15px">19.&nbsp; PARENTAL CONTROL GUIDANCE</span>
                    <br>
                    <span style="margin-left: 35px">
                        The conditions for parental control guidance described in the Clause No. 34 of the Guideline
                        shall also be applicable for the licensee.
                    </span>
                </ol>
                <br>

                <ol style="font-weight: bold;font-size: 15px">20.&nbsp; SUSPENSION, CANCELLATION AND FINES</ol>
                <br>
                <ol>20.1&nbsp; The Commission may, in any of the events specified in Section 46 of the Act, suspend or
                    cancel all or any part of the License issued under the Guidelines and/or impose fine as
                    mentioned in Section 46(3) of the Act with the prior permission of the Government.</ol><br>
                <ol>20.2&nbsp; The Commission may also impose fine under Section 63(3) and Section 64(3) of the Act for
                    any violation of any condition of this License.</ol><br>
                <ol>20.3&nbsp; All other conditions for suspension, cancellation and fines described in the Clause No. 37 of
                    the Guideline shall also be applicable for the licensee.</ol>

                <ol style=""><span style="font-weight: bold;font-size: 15px">21.&nbsp; IMPACT OF SUSPENSION AND CANCELLATION OF LICENSE</span>
                    <br>
                    <span style="margin-left: 35px">The conditions for impact of suspension and cancellation of License described in the Clause
                            No. 37 of the Guideline shall also be applicable for the ISP licensee.
                    </span>
                </ol>
                <br>
                <ol style="font-weight: bold;font-size: 15px">22.&nbsp; MISCELLANEOUS</ol>
                <br>
                <ol>22.1&nbsp; BWA Operators/Cellular Mobile Phone Operators shall follow their respective guidelines for
                    providing internet and internet related services.</ol> <br>
                <ol>22.2&nbsp; The Licensee shall comply with all terms and conditions of the license, applicable legislation
                    including the Bangladesh Telecommunication Regulation Act, 2001 and any applicable
                    subsidiary legislation and all directions issued by the Commission including any new
                    enactments as may be considered expedient and necessary from time to time.</ol> <br>
                <ol>22.3&nbsp; The Commission reserves exclusive right and authority to explain or interpret any provision
                    of the License, if any confusion arises regarding the actual sense or import of any provision
                    of the License. The explanation of the Commission shall be final and binding on the licensee.</ol><br>
                <ol>22.4&nbsp; The licensee shall have to pay Social Obligation Fee as per the regulation or Act imposed by
                    the Government/Commission from time to time.</ol><br>
                <ol>22.5&nbsp; The Commission will take initiative for annual technical, financial and compliance audit of
                    the Licensee at any time. The audit team authorized by the Commission shall have the right
                    for auditing technical, financial and compliance position of Licensee for any year. The
                    Licensee shall comply and shall furnish all relevant information and documents as sought by
                    the audit team. The Licensee shall preserve all the relevant data/information for technical and
                    financial audit as per the laws of the land. The directives/decisions/instructions of the
                    Commission regarding technical, financial and compliance audit shall be binding on the
                    Licensee. The audit team authorized by the Commission shall have the access to the
                    computerized accounting system of the Licensee as and when deemed necessary by the
                    Commission.</ol><br>
                <ol>22.6&nbsp; All correspondence shall be in writing and shall be sent to the licensees’ registered place of
                    business. However, in required cases, electronic means of correspondence (e-mail etc.) shall
                    also be used as per the direction from the Commission.</ol><br>

                <ol>22.7 &nbsp;The Licensee shall observe the requirements of any applicable international conventions on
                    telecommunications to the extent that such a convention imposes obligations on Bangladesh
                    unless expressly exempted by the Commission..</ol><br>
                <ol>22.8 &nbsp;The Commission and/or any other Government departments shall not be liable for any loss,
                    damage, claim, and charge, expense which may be incurred as a result of or in relation to the
                    activities of the licensees, its employees, agents or authorized representatives.</ol><br>
                <ol>22.9 &nbsp;The Commission reserves the right at its discretion to make the terms and conditions of the
                    license publicly available in any medium and format whether on the Commission’s or any
                    other official government website, in any manner they deem fit.</ol><br>
                <ol>22.10&nbsp; Depending on the output of traffic analysis, if licensee understands that International voice/
                    IP transit traffic either in normal or encrypted format is passing through its system or detect
                    any illegal use of connectivity, the licensee shall immediately report with related supporting
                    documents to the Commission.</ol><br>
                <ol>22.11&nbsp; If any condition or term herein is deemed to be invalid, unenforceable or illegal for some
                    reason, that condition or term shall be severable and the remainder of the License shall remain
                    in full force and effect.</ol><br>
                <ol>22.12&nbsp; The appendices annexed herewith shall be the integral part of the Guideline.</ol><br>
                <ol>22.13&nbsp; The Licensee shall seek written prior approval from the Commission before making any
                    amendment or change in its name.</ol><br>
                <ol>22.14&nbsp; If any contradiction arises between existing ISP license conditions and the conditions of this
                    license, then the provisions of the Guidelines shall prevail.</ol><br>

                <ol>
                    22.15&nbsp; Unless otherwise stated -
                    <br>
                    <br>
                    <ul>
                        <ol>(i) All headings are for convenience only and shall not affect the interpretation of the
                        provisions of these guidelines;
                        </ol>
                        <ol>(ii) The words importing the singular or plural shall be deemed to include the plural or
                            singular respectively;</ol>
                        <ol>(iii) Any expression in masculine gender shall denote both genders;</ol>

                        <ol>(iv) Any reference in these guidelines to a person shall be deemed to include natural and
                        legal persons;</ol>
                        <ol>(v) All references to legislation or guidelines or directions issued by the Commission shall
                            include all amendments made from time to time;</ol>
                        <ol>(vi) The term or shall include and but not vice versa;</ol>

                        <ol>(vii) Any reference in the License to "writing" or "written" includes a reference to official
                            facsimile transmission, official e-mail, or comparable means of communication;</ol>
                    </ul>
                </ol>
                <br>
                <ol>22.16&nbsp; None of the provisions of the License shall be deemed to have been waived by any act or
                    acquiescence on the part of the Commission, but only by an instrument in writing signed
                    /issued by the Commission. No waiver of any provision of the License shall be construed as
                    a waiver of any other provision or of the same provision on another occasion.</ol><br>
                <ol>22.17&nbsp; The Licensee shall pay any fees/charges imposed by the Government for local authorities. The
                    licensee is not bound to pay any other charges imposed by any other authorities which are not
                    approved by the Government.</ol><br>
                <ol>22.18&nbsp; Without prior written approval from the Commission, the licensee shall not be allowed to
                    build/operate PoP within 01 (one) kilometre area of its existing PoP.</ol><br>
                <ol>22.19&nbsp; All pornography related websites shall be blocked and stopped by ISP licensee with the help
                    of their respective bandwidth provider i.e International Internet Gateway (IIG)/ National
                    Internet exchange (NIX) Operator.</ol><br>
                <ol>22.20&nbsp; No person shall obstruct to or interfere in providing ISP services without any legitimate
                    ground. If any person breaches the mentioned provision then it shall be treated as an offence
                    and the person shall be liable to be imprisoned or to be fined or the both as per the laws of
                    Bangladesh Telecommunication Regulation Act, 2001.</ol><br>
                <ol>22.21&nbsp; Entities having any of the ITC/NTTN/IIG/ISP licensees shall not provide its services jointly
                    with other ITC/NTTN/IIG/ISP licensees by creating an anti-competitive environment. If such
                    activities are found among the above licensees, the Commission shall take legal actions
                    against the licensees as per law of the land.</ol><br>

                <ol>
                    <br>
                    This license shall be governed by and construed in accordance with the laws of Bangladesh.
                    This license is issued with the approval of the appropriate authority.
                    <br>
                    <br>
                    <div style="text-align: center;">

                        <span>Signed on this <span style="padding-bottom: 5px;">&nbsp;&nbsp;&nbsp;{{date('j',strtotime($appInfo->license_issue_date))}}<sup>{{date('S',strtotime($appInfo->license_issue_date))}}</sup>&nbsp;&nbsp;&nbsp;</span> day of
                            <span style="padding-bottom: 5px;">&nbsp;&nbsp;{{date('F',strtotime($appInfo->license_issue_date))}}&nbsp;&nbsp;</span>, <span style="padding-bottom: 5px;">&nbsp;{{date('Y',strtotime($appInfo->license_issue_date))}}&nbsp;</span>
                        </span>
                        <br>
                        <span>for and on behalf of the</span> <br>
                        <span style="">Bangladesh Telecommunication Regulatory Commission</span>
                    </div>
                </ol>
            </ul>
        </div>
            <br>
            <br>
            <br>
            <br>
        <div style="width: 100%;">
            <div style="width: 70%;float: left">&nbsp;</div>
            <div style="width: 28%;float: right;text-align: center">
                @if($signatory->signature_encode)
                <img src="{{'data:image/jpeg;base64,'.$signatory->signature_encode}}" width="100px" alt="img"><br>
                <span>{{$signatory->user_first_name.''.$signatory->user_middle_name. '' .$signatory->user_last_name}}</span> <br>
                <span>{{$signatory->designation ?? ''}}</span> <br>
                @endif
{{--                @if($signatory->user_mobile)<span>Mobile: {{$signatory->user_mobile ?? ''}}</span> <br>@endif--}}
{{--                @if($signatory->user_email)<span>Email: {{$signatory->user_email ?? ''}}</span> <br>@endif--}}
                <span>Legal and Licensing Division</span> <br>
                <span>BTRC</span>
            </div>

        </div>
    </div>
</div>

</body>
</html>
