<?php

namespace App\Modules\CertificateGeneration\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CertificateGeneration\Services\IndustryReRegistration;
use App\Modules\CompanyProfile\Models\CompanyInfo;

use App\Modules\IndustryNew\Models\AnnualProductionCapacity;
use App\Modules\IndustryNew\Models\InvestorList;
use App\Modules\IndustryNew\Models\LoanSourceCountry;
use App\Modules\IndustryNew\Models\MachineryImported;
use App\Modules\IndustryNew\Models\MachineryLocal;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Models\BPO\Amendment\Amendment;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;
use App\Modules\REUSELicenseIssue\Models\BPO\surrender\CallCenterSurrender;
use App\Modules\REUSELicenseIssue\Models\BWA\amendment\BWALicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\BWA\renew\BWALicenseRenew;
use App\Modules\REUSELicenseIssue\Models\BWA\surrender\BWALicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ICX\amendment\ICXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ICX\renew\ICXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ICX\surrender\ICXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IGW\amendment\IGWLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IGW\renew\IGWLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IGW\surrender\IGWLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IIG\amendment\IIGLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IIG\renew\IIGLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IIG\surrender\IIGLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IPTSP\surrender\IPTSPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ITC\amendment\ITCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ITC\renew\ITCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ITC\surrender\ITCLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNO\amendment\MNOLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\MNO\renew\MNOLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNO\surrender\MNOLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNP\amendment\MNPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\MNP\renew\MNPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNP\surrender\MNPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NIX\amendment\NIXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NIX\renew\NIXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NIX\surrender\NIXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NTTN\amendment\NTTNLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\renew\NTTNLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NTTN\surrender\NTTNLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SCS\amendment\SCSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SCS\renew\SCSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SCS\surrender\SCSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SS\amendment\SSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SS\renew\SSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SS\surrender\SSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TC\amendment\TCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TC\renew\TCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TC\surrender\TCLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TVAS\amendment\TVASLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TVAS\renew\TVASLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TVAS\surrender\TVASLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VTS\amendment\VTSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\renew\VTSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VTS\surrender\VTSLicenseSurrender;
use App\Modules\SCSLicenseIssue\Models\SCSLicenseIssue;
use App\Modules\Settings\Models\ApplicationGuideline;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Milon\Barcode\DNS1D;

use Milon\Barcode\DNS2D;
use App\Modules\CertificateGeneration\helper;
use Illuminate\Support\Facades\Log;

class CertificateGenerationController extends Controller
{
//    use IndustryReRegistration;
    private $offset = 0;//default offset
    private $limit = 5;//default limit
    private $base_url;
    private $base_folder = 'uploads';


    public function __construct() {
        $this->base_url = (substr(env('PROJECT_ROOT'), (strlen(env('PROJECT_ROOT')) - 1)) == '/') ? substr(env('PROJECT_ROOT'), 0, (strlen(env('PROJECT_ROOT')) - 1)) : env('PROJECT_ROOT');
    }

    public function generateCertificate($index = '-1')
    {
        /*  *** initial start must be -1
            *** second parameter will be set 0
            *** and others parameter will be set like 1,2,3....15
         # multi thread start */
        if ($index > 15) {
            exit;
        } elseif ($index == '-1') {
            $this->offset = 0;
        } else {
            $this->offset = ($index * $this->limit) + $this->limit;
        }
        /* # multi thread end */

        $pdfDataTake = PdfPrintRequestQueue::where("job_receiving_status", 0)
            // ->whereIn('process_type_id', ['33','34','35','36','37','38','39','40','74','75','70','71','72','73','1', '2', '3','4','5','6','7','8','9','10','11','12','13', '15','17','18','19','20','14','25', '21', '22', '26', '27', '50','51','52','53','54','55','56', '57','58','59','60','61','62','63','23','24','28','29','30', '83', '84', '78', '79', '80', '81'])
            ->where("no_of_try_job_sending", '<', 3)
            ->skip($this->offset)
            ->take($this->limit);
        $pdfData = $pdfDataTake->get();
//        $pdfDataTake->update(["job_receiving_status" => '-1', "no_of_try_job_sending" => DB::raw("no_of_try_job_sending+1")]);

        if ($pdfData->isEmpty()) {
            echo '<br/>No PDF in queue to send! ' . date("j F, Y, g:i a");
        }
        $dn1d = new DNS1D();
        $dn1dx = new DNS2D();
        foreach ($pdfData as $row) {
            try {
                if ($row->process_type_id == 1) {
                    $unique_id = generateUniqueId();
                    //get all data
                    $appInfo = $this->appData($row->process_type_id, $row->app_id, $row->signatory);

                    if (!empty($appInfo['appInfo']->isp_license_type)){
                        if($appInfo['appInfo']->isp_license_type == 1){
                            $appInfo['isp_license_type_area_info']['type'] = 'Nationwide';
                            $appInfo['isp_license_type_area_info']['name'] = '';
                        }if($appInfo['appInfo']->isp_license_type == 2 && !empty($appInfo['appInfo']->isp_license_division)){
                            $appInfo['isp_license_type_area_info']['type'] = 'Divisional';
                            $appInfo['isp_license_type_area_info']['name'] = DB::table('area_info')->where(['area_type'=>1,'area_id'=>$appInfo['appInfo']->isp_license_division])->first()->area_nm;
                        }if($appInfo['appInfo']->isp_license_type == 3 && !empty($appInfo['appInfo']->isp_license_district)){
                            $appInfo['isp_license_type_area_info']['type'] = 'District';
                            $appInfo['isp_license_type_area_info']['name'] = DB::table('area_info')->where(['area_type'=>2,'area_id'=>$appInfo['appInfo']->isp_license_district])->first()->area_nm;
                        }if($appInfo['appInfo']->isp_license_type == 4 && !empty($appInfo['appInfo']->isp_license_upazila)){
                            $appInfo['isp_license_type_area_info']['type'] = 'Thana/Upazila';
                            $appInfo['isp_license_type_area_info']['name'] = DB::table('area_info')->where(['area_type'=>3,'area_id'=>$appInfo['appInfo']->isp_license_upazila])->first()->area_nm;
                        }
                    }

//                    $mpdf = new \Mpdf\Mpdf();
//                    $mpdf->WriteHTML('<h1>Hello world!</h1>');
//                    $mpdf->Output();
                    $barcode = $dn1d->getBarcodePNG($appInfo['appInfo']->tracking_no, 'C39', 2, 60);
                    $appInfo['qrCode'] = $dn1dx->getBarcodePNG($this->base_url . '/docs/' . $unique_id, 'QRCODE');
                    $img = '<img src="data:image/png;base64,' . $barcode . '" height="30"  alt="barcode" />';

                    if($appInfo['appInfo']->status_id == 5){

//                        $content = view("CertificateGeneration::isp_license_issue", $appInfo)
                        $appInfo['ContactPersonData'] = ContactPerson::where('process_type_id','=', $appInfo['appInfo']->process_type_id)
                            ->where('app_id', '=', $appInfo['appInfo']->ref_id)
                            ->first();
                        $registerInfo = ISPLicenseIssue::select('reg_office_address', 'reg_office_thana', 'reg_office_district', 'shortfall_reason')->where('id', '=',$appInfo['appInfo']->ref_id )->first();
                        $appInfo['registerOfficeAddress'] = $registerInfo->reg_office_address;
                        $appInfo['registerOfficeThana'] = Area::where('area_id', '=',$registerInfo->reg_office_thana )->value('area_nm');
                        $appInfo['registerOfficeDistrict'] = Area::where('area_id', '=',$registerInfo->reg_office_district )->value('area_nm');
                        $appInfo['shortFallReason'] =  $registerInfo->shortfall_reason;
                        $appInfo['module_name'] = ProcessType::where([
                            'id' => $appInfo['appInfo']->process_type_id
                        ])->value('name');

                        $content = view("CertificateGeneration::isp_license_issue_pdf_shortfall", $appInfo)->render();

                    }elseif($appInfo['appInfo']->status_id == 15){
                        $modal = ISPLicenseIssue::class;
                        $content = $this->GeneratePDFForPaymentRequest($modal, $appInfo, 2, 1);
                    }elseif($appInfo['appInfo']->status_id == 60){
                        $modal = ISPLicenseIssue::class;
                        $content = $this->GeneratePDFForAnnualOrBGFee($modal, $appInfo, 3);
                    }else{
                        $content = view("CertificateGeneration::isp_license_issue", $appInfo)
                            ->render();
                    }


                    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
                    $fontDirs = $defaultConfig['fontDir'];

                    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
                    $fontData = $defaultFontConfig['fontdata'];

                    $mpdf = new \Mpdf\Mpdf([
                        'tempDir' => storage_path(),
                        'fontDir' => array_merge($fontDirs, [
                            public_path('assets/fonts'), // to find like /public/fonts/SolaimanLipi.ttf
                        ]),
                        'fontdata' => $fontData + [
                                'kalpurush' => [
                                    'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                                ],
//                                'nikosh' => [
//                                    'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
//                                'solaimanlipi' => [
//                                    'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
                                'sans-serif' => [
                                    'R' => 'sans-serif.ttf', 'I' => 'sans-serif.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                                ],
//                                'NikoshBAN' => [
//                                    'R' => 'NikoshBAN.ttf', 'I' => 'NikoshBAN.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
//                                'Nikosh' => [
//                                    'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
                            ],
                        'default_font' => 'kalpurush',
//                        'default_font' => 'solaimanlipi',
                        'setAutoTopMargin' => 'pad',
                        'mode' => 'utf-8',
                        'format' => 'A4',
                        'default_font_size' => 11,
                    ]);

//            $mpdf->useSubstitutions;
                    $mpdf->SetProtection(array('print'));
                    $mpdf->SetDefaultBodyCSS('color', '#000');
                    $mpdf->SetTitle("Bangladesh Telecommunication Regulatory Commission(BTRC)");
                    $mpdf->SetSubject("Subject");
                    $mpdf->SetAuthor("Business Automation Limited");
                    $mpdf->SetFont('sans-serif', '', 11);
                    //$mpdf->autoScriptToLang = true;
                    $mpdf->baseScript = 1;
                    $mpdf->autoVietnamese = true;
                    $mpdf->SetDisplayMode('fullwidth');
                    $mpdf->SetFooter('{PAGENO}', 'O', false);
                    $mpdf->SetWatermarkImage(
                        'assets/images/new_images/logo.png',
                        0.1,
                        '2000',
                        array(10,90),
                    );
                    $mpdf->showWatermarkImage = true;

                    if(!in_array($appInfo['appInfo']->status_id, [5,15, 60])){
                        $mpdf->WriteHTML('
                    <div style="border: 1px solid black; height: 100%">
                        <div style="height: 25%;text-align: center;color: #034EA2">
                            <h1 style="text-align: center; margin: 85px; 0px 85px 0px;font-weight: bolder;font-size: 30px;">INTERNET SERVICE PROVIDER LICENSE</h1>
                        </div>
                        <div style="height: 50%; width: 100%;">
                            <img src="assets/images/new_images/isp_certificate_header.png" alt="" style="height: 100%;" >
                        </div>
                        <div style="height:20%;width: 100%;margin: 10% 0% 10% 0%">
                            <div style="width: 25%;float: left">
                                <img src="assets/images/new_images/logo.png" alt="" >
                            </div>
                            <div style="width: 75%;float: right">
                                <strong style="font-size: 25px;">BANGLADESH TELECOMMUNICATION</strong><br>
                                <strong style="font-size: 25px;">REGULATORY COMMISSION</strong>
                            </div>
                        </div>
                     </div>
                    ', 2);
                    }

//html Header used in html file


//                    $mpdf->SetHTMLHeader('
//                                <div class="header" style="margin:auto;width: 100%">
//                                    <div style="text-align: center;">
//                                            <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
//                                            <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
//                                            <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
//                                            <h4>IEB Bhaban Ramna, Dhaka-1000</h4>
//                                    </div>
//                                </div>
//                                ');

//                    $mpdf->SetHTMLFooter('
//<div class="text-center">
//<h6 style="">'.'Page '.'{PAGENO}'.' of '.'12'.'</h6></div>');




                    $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

                    $mpdf->setAutoTopMargin = 'stretch';
                    $mpdf->setAutoBottomMargin = 'stretch';
                    $mpdf->showWatermarkImage = true;
//                    if(in_array(config('app.APP_ENV'), ['local','dev','uat','training'])){
//                        $mpdf->SetWatermarkImage('assets/images/pdf_watermark_test.png',
//                            0.6,'D','F'
//                        );
//                    }else{
//                        $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
//                            0.1,'F','F'
//                        );
//                    }

//                    $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
//                        0.1,'F','F'
//                    );
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->WriteHTML($content, 2);
//                    $mpdf->defaultfooterfontstyle = 'B';
                    $mpdf->defaultfooterline = 0;
                    $mpdf->SetCompression(true);
                    $baseURL = $this->base_folder."/certificate/";
                    $directoryName = $baseURL . date("Y/m");
                    $directoryNameYear = $baseURL . date("Y");

                    directoryFunction($directoryName, $directoryNameYear);
                    $certificateName = uniqid("certificate_", true);
                    $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
                    $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
//exit();

                    $fullPath = $this->base_url . '/' . $pdfFilePath;
                    saveCertificate($row, $fullPath, $unique_id, $appInfo['appInfo']);

                    //Send email to all the desk officers
                    if(!in_array($appInfo['appInfo']->status_id, [5,15, 60])) {
                        $modelName = ISPLicenseIssue::class;
                        $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                        $applicationInfo = [
                            'app_id'            => $appInfo['appInfo']->ref_id,
                            'status_id'         => $appInfo['appInfo']->status_id,
                            'process_type_id'   => $appInfo['appInfo']->process_type_id,
                            'tracking_no'       => $appInfo['appInfo']->issue_tracking_no,
                            'process_type_name' => 'ISP License Issue',
                            'remarks'           => '',
                            'org_nm'            => $appInfo['companyInfo']->org_nm
                        ];
                        CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'ISP License Issue', $modelName);
                        $this->SendEmailToAllDeskOfficers($appInfo);
                    }

                    if(in_array($appInfo['appInfo']->status_id, [5,15, 60])){
                        return true;
                    }
                    echo "certificate generate successfully " . PHP_EOL;

                }
                elseif (in_array($row->process_type_id, [2, 3,4])) { // ISP license renew & Amendment
                    $this->renewCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id,[5, 6, 7,8]) ){
                    $this->NewBPOIssueCertificateGenerate($row);
                }
                elseif ($row->process_type_id == 9 or $row->process_type_id == 10) { // bpo call center issue
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id,[13,14,15])) { // vsat module
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id,[17,18,19,20])) { // vsat module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [78,79, 80, 81])) { // ss module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [25,26, 27, 28])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [58,59,60,61])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id,[21,24,23,22])) { // IPTSP module
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id,[29,30,83,84])){
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [50,51,52,53])) { // NTTN module
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [54,55,56,57])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [70,71, 72, 73])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [74,75,76,77])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [9,10,11,12])) { // NIX module
                    $this->BPOIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [37,38,39,40])) { // IGW module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [58,59,60,61])) { // NIX module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [62,63,64,65])) { // tvas module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [33,34,35,36])) { // ICX module
                    $this->TVASIssueCertificateGenerate($row);
                }
                elseif (in_array($row->process_type_id, [66,67,68,69])) { // NIX module
                    $this->TVASIssueCertificateGenerate($row);
                }
                else {
                    if($row->process_type_id){
                        $this->renewCertificateGenerate($row);
                    }else{
                        dd("the process type was not found!");
                    }

                }

            } catch (\Exception $e) {
                Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
                $row->job_receiving_status = -1;
                $row->job_receiving_response = $e->getMessage() . ', line: ' . $e->getLine();
                $row->save();
            }

        }

    }


    public function appData($process_type_id, $decodedAppId, $signatory)
    {

        $process_status = ProcessList::where([
            'process_type_id' =>$process_type_id,
            'ref_id' => $decodedAppId
        ])->first()->status_id;

        $process_type= ProcessType::find($process_type_id);


        $data['appInfo'] = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id');


        if($process_type->is_special==1){

                $data['service_name'] =  $process_type->name;
                if($process_type->type==1){
                    $data['appInfo'] =
                    $data['appInfo']
                    ->leftJoin('special_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');



                }elseif($process_type->type==2){
                    $data['appInfo'] =
                    $data['appInfo']
                    ->leftJoin('special_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');


                }elseif($process_type->type==3){
                    $data['appInfo'] =
                    $data['appInfo']
                    ->leftJoin('special_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');


                }elseif($process_type->type==4){
                    $data['appInfo'] =
                    $data['appInfo']
                    ->leftJoin('special_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');


                }


        }

        if($process_type_id == 81){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('ss_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('ss_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                                                   ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                                                   ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'SATELITE SERVICE (SS)';
        }

        if($process_type_id == 1){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('isp_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');

            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }

            $data['service_name'] = 'INTERNET SERVICE PROVIDER (ISP)';
        }

        if($process_type_id == 2){
            if(!in_array($process_status, [5, 15])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('isp_license_master as apps', DB::raw('apps.renew_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'INTERNET SERVICE PROVIDER (ISP)';
        }

        if($process_type_id == 3){
            $data['appInfo'] =
                $data['appInfo']
                    ->leftJoin('isp_license_master as apps',DB::raw('apps.amendment_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
            ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'INTERNET SERVICE PROVIDER (ISP)';
        }

        if($process_type_id == 78){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('ss_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');

            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('ss_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }

            $data['service_name'] = 'SATELITE SERVICE (SS)';
        }
        if($process_type_id == 4){

            if(!in_array($process_status, [5, 15])) {
                $data['appInfo'] =
                    $data['appInfo']->leftJoin('isp_license_master as apps',DB::raw('apps.cancellation_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else{
                $data['appInfo'] = $data['appInfo']->leftJoin('isp_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id');
            }

            $data['service_name'] = 'INTERNET SERVICE PROVIDER (ISP)';
        }

        if($process_type_id == 5){

            $data['appInfo'] =
                $data['appInfo']
                    ->leftJoin('call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');

            $data['service_name'] = 'BPO/Call Center License';
        }

        if($process_type_id == 6){

            $data['appInfo'] =
                $data['appInfo']
                    ->leftJoin('call_center_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');

            $data['service_name'] = 'BPO/Call Center License Renew';
        }

        if($process_type_id == 7){

//            $data['appInfo'] =
//                $data['appInfo']
//                    ->leftJoin('call_center_amendment as apps', 'apps.id', '=', 'process_list.ref_id');
            $data['appInfo'] = $data['appInfo']->leftJoin('call_center_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'BPO/Call Center License Amendment';
        }
        if($process_type_id == 8){
            $data['appInfo'] = $data['appInfo']->leftJoin('call_center_surrender as apps','apps.id', '=', 'process_list.ref_id')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            $data['service_name'] = 'BPO/Call Center License Surrender';
        }


        if($process_type_id == 9){
//            $data['appInfo'] = $data['appInfo']->leftJoin('nix_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no');
            if($process_status != 5) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('nix_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            } else {
                $data['appInfo'] = $data['appInfo']->leftJoin('nix_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'National Internet Exchange License (NIX)';
        }

        if($process_type_id == 10){
            $data['appInfo'] = $data['appInfo']->leftJoin('nix_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'National Internet Exchange License (NIX)';
        }

        if($process_type_id == 11){
            $data['appInfo'] = $data['appInfo']->leftJoin('nix_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'National Internet Exchange License (NIX)';
        }

        if($process_type_id == 12){
            $data['appInfo'] = $data['appInfo']->leftJoin('nix_license_master as apps', 'apps.cancellation_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'National Internet Exchange License (NIX)';
        }

        if($process_type_id == 19){
            $data['appInfo'] = $data['appInfo']->leftJoin('iig_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'IIG Services';
        }
        if($process_type_id == 20){
            if(!in_array($process_status, [5, 15])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('iig_license_master as apps', DB::raw('apps.renew_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('iig_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'IIG Service';
        }
        if($process_type_id == 70){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('mnp_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('mnp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'MNP';
        }
        if($process_type_id == 71){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('mnp_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('mnp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'MNP';
        }

        if($process_type_id == 77){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('bwa_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('bwa_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'BWA';
        }

        if($process_type_id == 56){
            $data['appInfo'] = $data['appInfo']->leftJoin('itc_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'ITC Services';
        }
        if($process_type_id == 57){
            $data['appInfo'] = $data['appInfo']->leftJoin('itc_license_cancellation as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            $data['service_name'] = 'ITC Services';
        }

        if($process_type_id == 72){
            $data['appInfo'] = $data['appInfo']->leftJoin('mnp_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'MNP Services';
        }
        if($process_type_id == 68){
            $data['appInfo'] = $data['appInfo']->leftJoin('tc_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'TC Services';
        }
        if($process_type_id == 73){
            $data['appInfo'] = $data['appInfo']->leftJoin('mnp_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'MNP Services';
        }
        if($process_type_id == 50){
//            $data['appInfo'] = $data['appInfo']->leftJoin('nttn_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no');
            if($process_status != 5) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('nttn_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no');
            } else {
                $data['appInfo'] = $data['appInfo']->leftJoin('nttn_license_renew as apps', 'apps.id', '=', 'process_list.ref_id');
            }
            $data['service_name'] = 'Nationwide Telecommunication Transmission Network (NTTN)';
        }

        if($process_type_id == 51){
            $data['appInfo'] = $data['appInfo']->leftJoin('nttn_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'Nationwide Telecommunication Transmission Network (NTTN)';
        }

        if($process_type_id == 52){
            $data['appInfo'] = $data['appInfo']->leftJoin('nttn_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'Nationwide Telecommunication Transmission Network (NTTN)';
        }

        if($process_type_id == 53){
            $data['appInfo'] = $data['appInfo']->leftJoin('nttn_license_master as apps', 'apps.cancellation_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'Nationwide Telecommunication Transmission Network (NTTN)';
        }

        if($process_type_id == 13){
            $data['appInfo'] = $data['appInfo']->leftJoin('vsat_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'VSAT';
        }

        if($process_type_id == 15){
            $data['appInfo'] = $data['appInfo']->leftJoin('vsat_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');

            $data['service_name'] = 'VSAT';
        }

        if($process_type_id == 14){
            $data['appInfo'] = $data['appInfo']->leftJoin('vsat_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no');
            $data['service_name'] = 'VSAT';
        }
        if($process_type_id == 17){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('iig_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('iig_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'IIG Services';
        }
        if($process_type_id == 18){
            $data['appInfo'] = $data['appInfo']->leftJoin('iig_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'IIG Services';
        }
        if($process_type_id == 62){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('scs_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('scs_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'SCS';
        }
        if($process_type_id == 63){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('scs_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('scs_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'SCS';
        }
        if($process_type_id == 64){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('scs_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('scs_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'SCS';
        }
        if($process_type_id == 65){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('scs_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('scs_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'SCS';
        }
        if($process_type_id == 69){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('tc_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('tc_license_cancellation as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'TC';
        }
        if($process_type_id == 25){
            if($process_status != 5){
                $data['appInfo'] = $data['appInfo']->leftJoin('tvas_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            } else {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('tvas_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }

            $data['service_name'] = 'Telecommunication Value Added Services (TVAS)';
        }


        if($process_type_id == 24){
            if($process_status != 5){
                $data['appInfo'] = $data['appInfo']->leftJoin('iptsp_license_master as apps', 'apps.cancellation_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('iptsp_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'Internet Protocol Telephony Service Provider (IPTSP) ';
        }

        if($process_type_id == 28){
            $data['appInfo'] = $data['appInfo']->leftJoin('tvas_license_master as apps', 'apps.cancellation_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'Telecommunication Value Added Services (TVAS)';
        }

        if($process_type_id == 26){
            $data['appInfo'] = $data['appInfo']->join('tvas_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'Telecommunication Value Added Services (TVAS)';
        }

        if($process_type_id == 27){
            $data['appInfo'] = $data['appInfo']->leftJoin('tvas_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'Telecommunication Value Added Services (TVAS)';
        }
        if($process_type_id == 29){
            if($process_status != 5){
                $data['appInfo'] = $data['appInfo']->leftJoin('vts_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('vts_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'VTS';
        }
        if($process_type_id == 30){
            if($process_status != 5) {
                $data['appInfo'] = $data['appInfo']->join('vts_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else{
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('vts_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'VTS';
        }

        if($process_type_id == 83){
            $data['appInfo'] = $data['appInfo']->join('vts_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'VTS';
        }

        if($process_type_id == 84){
            $data['appInfo'] = $data['appInfo']->join('vts_license_master as apps', 'apps.cancellation_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'VTS';
        }

        if($process_type_id == 21){
            if($process_status != 5) {
                $data['appInfo'] = $data['appInfo']->leftJoin('iptsp_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                    $data['appInfo'] =
                        $data['appInfo']
                            ->leftJoin('iptsp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                            ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                            ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'Internet Protocol Telephony Service Provider (IPTSP) ';
        }
        if($process_type_id == 22){
            if($process_status != 5){
                $data['appInfo'] = $data['appInfo']->leftJoin('iptsp_license_master as apps', 'apps.renew_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] =
                    $data['appInfo']
                         ->leftJoin('iptsp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                         ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                         ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'Internet Protocol Telephony Service Provider (IPTSP) ';
        }

        if($process_type_id == 23){
            if($process_status != 5){
                $data['appInfo'] = $data['appInfo']->leftJoin('iptsp_license_master as apps', 'apps.amendment_tracking_no', '=', 'process_list.tracking_no')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
            $data['appInfo'] =
                $data['appInfo']
                    ->leftJoin('iptsp_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'Internet Protocol Telephony Service Provider (IPTSP) ';
        }

        if($process_type_id == 58){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('mno_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('mno_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'MNO';
        }
        if($process_type_id == 79){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('ss_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {

                $data['appInfo'] = $data['appInfo']
                    ->leftJoin('ss_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'SATELITE SERVICE (SS)';


        }

        if($process_type_id == 80){
            $data['appInfo'] = $data['appInfo']->leftJoin('ss_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'SS Services';
        }

        if($process_type_id == 59){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('mno_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('mno_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.op_office_thana');
            }
            $data['service_name'] = 'MNO';
        }
        if($process_type_id == 60){
            $data['appInfo'] = $data['appInfo']->leftJoin('mno_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'MNO Services';
        }
        if($process_type_id == 61){
            $data['appInfo'] = $data['appInfo']->leftJoin('mno_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'MNO Services';
        }
        if($process_type_id == 54){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('itc_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('itc_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'ITC';
        }


        if($process_type_id == 55){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('itc_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('itc_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'ITC';
        }

        if($process_type_id == 74){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('bwa_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('bwa_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'BWA';
        }
        if($process_type_id == 76){
            $data['appInfo'] = $data['appInfo']->leftJoin('bwa_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'PSTN Services';
        }
        if($process_type_id == 37){
                if(!in_array($process_status, [5,15,60])) {
                    $data['appInfo'] =
                        $data['appInfo']
                            ->leftJoin('igw_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                            ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                            ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
                }else {
                    $data['appInfo'] = $data['appInfo']->leftJoin('igw_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
                }
                $data['service_name'] = 'IGW';
        }

        if($process_type_id == 38){
            $data['appInfo'] = $data['appInfo']->leftJoin('igw_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'IGW Services';
        }
        if($process_type_id == 39){
            $data['appInfo'] = $data['appInfo']->leftJoin('igw_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'IGW Services';
        }
        if($process_type_id == 40){
            $data['appInfo'] = $data['appInfo']->leftJoin('igw_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'IGW Services';
        }

        if($process_type_id == 75){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('bwa_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('bwa_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'BWA';
        }
        if($process_type_id == 33){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('icx_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('icx_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.reg_office_thana');
            }
            $data['service_name'] = 'ICX';
        }
        if($process_type_id == 34){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('icx_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('icx_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                     ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.op_office_thana');
            }
            $data['service_name'] = 'ICX';
        }
        if($process_type_id == 35){
            $data['appInfo'] = $data['appInfo']->leftJoin('icx_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
            ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
            ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'ICX Services';
        }
        if($process_type_id == 36){
            $data['appInfo'] = $data['appInfo']->leftJoin('icx_license_master as apps', 'apps.issue_tracking_no', '=', 'process_list.tracking_no')
                                ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                                ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            $data['service_name'] = 'ICX Services';
        }
        if($process_type_id == 66){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('tc_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('tc_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.applicant_thana');
            }
            $data['service_name'] = 'TC';
        }
        if($process_type_id == 67){
            if(!in_array($process_status, [5,15,60])) {
                $data['appInfo'] =
                    $data['appInfo']
                        ->leftJoin('tc_license_master as apps', DB::raw('apps.issue_tracking_no collate utf8mb4_unicode_ci'), '=', 'process_list.tracking_no')
                        ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                        ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }else {
                $data['appInfo'] = $data['appInfo']->leftJoin('tc_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'apps.org_district')
                    ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'apps.org_upazila');
            }
            $data['service_name'] = 'TC';
        }

        $data['appInfo'] = $data['appInfo']->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })
            ->where('process_list.ref_id', $decodedAppId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'process_list.completed_date',
                'process_list.closed_by',
                'district_info.area_nm as reg_office_district_name',
                'thana_info.area_nm as reg_office_thana_name',
                'ps.status_name',
                'process_type.form_url',
                'apps.license_no as license_no',
                'apps.*',
            ]);


        $data['companyInfo'] = CompanyInfo::where('id',$data['appInfo']->company_id)
            ->leftJoin('area_info as district_info', 'district_info.area_id', '=', 'company_info.office_district')
            ->leftJoin('area_info as thana_info', 'thana_info.area_id', '=', 'company_info.office_thana')
            ->first(['org_nm','office_division','office_district','office_thana','office_location','district_info.area_nm as district_name','thana_info.area_nm as thana_name']);
       //  if(in_array($data['appInfo']->status_id, [5,15, 60])){
        // if ($data['appInfo']->closed_by != 0){
            if(in_array($data['appInfo']->status_id, [25,65])){
                $data['signatory'] = Users::where('id', $data['appInfo']->closed_by)->first([
                    'user_first_name',
                    'user_middle_name',
                    'user_last_name',
                    'designation',
                    'user_mobile',
                    'user_email',
                    'signature',
                    'signature_encode',
                ]);
            }else{
                $data['signatory'] = Users::where('id', $signatory)->first([
                    'user_first_name',
                    'user_middle_name',
                    'user_last_name',
                    'designation',
                    'user_mobile',
                    'user_email',
                    'signature',
                    'signature_encode',
                ]);
            }
//        }
        return $data;
    }

    public function renewCertificateGenerate($row)
    {

        $dn1d = new DNS1D();
        $dn1dx = new DNS2D();
        $unique_id = generateUniqueId();

        //get all data
        $appInfo = $this->appData($row->process_type_id, $row->app_id, $row->signatory);

        $barcode = $dn1d->getBarcodePNG($appInfo['appInfo']->tracking_no, 'C39', 2, 60);
        $appInfo['qrCode'] = $dn1dx->getBarcodePNG($this->base_url . '/docs/' . $unique_id, 'QRCODE');
        $img = '<img src="data:image/png;base64,' . $barcode . '" height="30"  alt="barcode" />';

        // process type wise table shortfall certificate generate
        if($appInfo['appInfo']->status_id == 5 ){
            if($appInfo['appInfo']->process_type_id === 2){
                $modal = ISPLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif($appInfo['appInfo']->process_type_id === 3){
                $modal = ISPLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }elseif($appInfo['appInfo']->process_type_id === 4){
                $modal = ISPLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
        }elseif($appInfo['appInfo']->status_id == 15 ){
            if($appInfo['appInfo']->process_type_id === 2){
                $modal = ISPLicenseRenew::class;
                $content = $this->GeneratePDFForPaymentRequest($modal, $appInfo, 2, 1);
            }
        }else {
            $content = view("CertificateGeneration::isp_license_issue", $appInfo)->render();
        }
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path(),
            'fontDir' => array_merge($fontDirs, [
                public_path('assets/fonts'), // to find like /public/fonts/SolaimanLipi.ttf
            ]),
            'fontdata' => $fontData + [
//                    'kalpurush' => [
//                        'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
//                    'nikosh' => [
//                        'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                    ],
                ],
            'default_font' => 'solaimanlipi',
            'setAutoTopMargin' => 'pad',
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
        ]);

        ## $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("Bangladesh Telecommunication Regulatory Commission(BTRC)");
        $mpdf->SetSubject("Subject");
        $mpdf->SetAuthor("Business Automation Limited");
        //$mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->SetWatermarkImage(
            'assets/images/new_images/logo.png',
            0.1,
            '2000',
            array(10,90),
        );
        $mpdf->showWatermarkImage = true;
//        $basePath = __DIR__; // The base path of your PHP script
//        $faviconPath = $basePath . '/public/assets/images/new_images/favicon.png';
//
//        $mpdf->SetHTMLHeader("<link href='$faviconPath' type='image/x-icon' rel='icon'>");

//        $mpdf->SetHTMLHeader('<div class="header" style="margin:auto;width: 100%">
//                                        <div style="text-align: center;">
//                                                <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
//                                                <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
//                                                <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
//                                                <h4>IEB Bhaban Ramna, Dhaka-1000</h4>
//                                        </div>
//                                    </div>');

        $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->showWatermarkImage = true;
        /*
        if(in_array(config('app.APP_ENV'), ['local','dev','uat','training'])){
            $mpdf->SetWatermarkImage('assets/images/pdf_watermark_test.png',
                0.6,'D','F'
            );
        }else{
            $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
                0.1,'F','F'
            );
        }

        $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
            0.1,'F','F'
        );
        */

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content, 2);
        // $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;
        $mpdf->SetCompression(true);
        $baseURL = $this->base_folder."/certificate/";
        $directoryName = $baseURL . date("Y/m");
        $directoryNameYear = $baseURL . date("Y");

        directoryFunction($directoryName, $directoryNameYear);
        $certificateName = uniqid("certificate_", true);
        $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
        $fullPath = $this->base_url . '/' . $pdfFilePath;
        saveCertificate($row, $fullPath, $unique_id);

        if($appInfo['appInfo']->process_type_id === 2){
            //Send email to all the desk officers
            if(!in_array($appInfo['appInfo']->status_id, [5, 15, 60])){
                $modelName = ISPLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => 'ISP License Renew',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'ISP License Renew', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }elseif($appInfo['appInfo']->process_type_id === 3){
            //Send email to all the desk officers
            if(!in_array($appInfo['appInfo']->status_id, [5, 15])) {
                $modelName = ISPLicenseAmendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->amendment_tracking_no,
                    'process_type_name' => 'ISP License Amendment',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'ISP License Amendment', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }elseif($appInfo['appInfo']->process_type_id === 4){
            //Send email to all the desk officers
            if(!in_array($appInfo['appInfo']->status_id, [5, 15])) {
                $modelName = ISPLicenseSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->cancellation_tracking_no,
                    'process_type_name' => 'ISP License Surrender',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'ISP License Surrender', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }

        if(in_array($appInfo['appInfo']->status_id, [5,15, 60])){
            return true;
        }

        echo "certificate generate successfully " . PHP_EOL;
    }
    public function BPOIssueCertificateGenerate($row)
    {
        $dn1d = new DNS1D();
        $dn1dx = new DNS2D();
        $unique_id = generateUniqueId();

        //get all data
        $appInfo = $this->appData($row->process_type_id, $row->app_id, $row->signatory);
        $barcode = $dn1d->getBarcodePNG($appInfo['appInfo']->tracking_no, 'C39', 2, 60);
        $appInfo['qrCode'] = $dn1dx->getBarcodePNG($this->base_url . '/docs/' . $unique_id, 'QRCODE');
        $img = '<img src="data:image/png;base64,' . $barcode . '" height="30"  alt="barcode" />';

        // process type wise table shortfall certificate generate
        if($appInfo['appInfo']->status_id == 5 ){
            if($appInfo['appInfo']->process_type_id === 29){
                $modal = VTSLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 30){
                $modal = VTSLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 50){
                $modal = NTTNLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 51){
                $modal = NTTNLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 52){
                $modal = NTTNLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 53){
                $modal = NTTNLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 83){
                $modal = VTSLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 84){
                $modal = VTSLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 9){
                $modal = NIXLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 10){
                $modal = NIXLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 11){
                $modal = NIXLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 12){
                $modal = NIXLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 13){
                $modal = VSATLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif($appInfo['appInfo']->process_type_id === 14){
                $modal = VSATLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 21){
                $modal = IPTSPLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 22){
                $modal = IPTSPLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 23){
                $modal = IPTSPLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
            elseif ($appInfo['appInfo']->process_type_id === 24){
                $modal = IPTSPLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
        }else {
            $content = view("CertificateGeneration::crt_common", $appInfo)->render();
        }
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path(),
            'fontDir' => array_merge($fontDirs, [
                public_path('assets/fonts'), // to find like /public/fonts/SolaimanLipi.ttf
            ]),
            'fontdata' => $fontData + [
//                    'kalpurush' => [
//                        'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
//                    'nikosh' => [
//                        'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                    ],
                ],
            //'default_font' => 'kalpurush',
            'default_font' => 'solaimanlipi',
            'setAutoTopMargin' => 'pad',
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
        ]);

        ## $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("Bangladesh Telecommunication Regulatory Commission(BTRC)");
        $mpdf->SetSubject("Subject");
        $mpdf->SetAuthor("Business Automation Limited");
        //$mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->SetWatermarkImage(
            'assets/images/new_images/logo.png',
            0.1,
            '2000',
            array(10,90),
        );
        $mpdf->showWatermarkImage = true;

        if($appInfo['appInfo']->status_id != 5) {
            $mpdf->SetHTMLHeader('<div class="header" style="margin:auto;width: 100%">
                                        <div style="text-align: center;">
                                                <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                                                <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                                                <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
                                                <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
                                        </div>
                                    </div>');

        }

        $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->showWatermarkImage = true;
        /*
        if(in_array(config('app.APP_ENV'), ['local','dev','uat','training'])){
            $mpdf->SetWatermarkImage('assets/images/pdf_watermark_test.png',
                0.6,'D','F'
            );
        }else{
            $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
                0.1,'F','F'
            );
        }

        $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
            0.1,'F','F'
        );
        */

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content, 2);
        // $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;
        $mpdf->SetCompression(true);
        $baseURL = $this->base_folder."/certificate/";
        $directoryName = $baseURL . date("Y/m");
        $directoryNameYear = $baseURL . date("Y");

        directoryFunction($directoryName, $directoryNameYear);
        $certificateName = uniqid("certificate_", true);
        $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
        $fullPath = $this->base_url . '/' . $pdfFilePath;
        saveCertificate($row, $fullPath, $unique_id);

        if($appInfo['appInfo']->process_type_id === 29){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = VTSLicenseIssue::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->issue_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 30){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = VTSLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 50){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NTTNLicenseIssue::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 51){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NTTNLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 52){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NTTNLicenseAmendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 53){
            //Send email to all desk officers
            if($appInfo['appInfo']->status_id != 5){
                $modelName = NTTNLicenseSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->tracking_no,
                    'process_type_name' => 'NTTN License Surrender',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'NTTN License Surrender', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 83){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = VTSLicenseAmendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->amendment_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 84){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = VTSLicenseSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->cancellation_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 21){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = IPTSPLicenseIssue::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->issue_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 22){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = IPTSPLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 23){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = IPTSPLicenseAmendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->amendment_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 24){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = IPTSPLicenseSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->cancellation_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 9){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NIXLicenseIssue::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 10){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NIXLicenseRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 11){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NIXLicenseAmendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->renew_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }
        elseif($appInfo['appInfo']->process_type_id === 12){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = NIXLicenseSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $service_name = ProcessType::where('id', $appInfo['appInfo']->process_type_id)->value('name');
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->cancellation_tracking_no,
                    'process_type_name' => $service_name,
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], $service_name, $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }

        if($appInfo['appInfo']->status_id != 5){
            echo "certificate generate successfully " . PHP_EOL;
        }
    }

    public function NewBPOIssueCertificateGenerate($row)
    {
        $dn1d = new DNS1D();
        $dn1dx = new DNS2D();
        $unique_id = generateUniqueId();

        //get all data
        $appInfo = $this->appData($row->process_type_id, $row->app_id, $row->signatory);

        //Send email to all desk officers
//        if($appInfo['appInfo']->status_id != 5){
//            $this->SendEmailToAllDeskOfficers($appInfo);
//        }

        $barcode = $dn1d->getBarcodePNG($appInfo['appInfo']->tracking_no, 'C39', 2, 60);
        $appInfo['qrCode'] = $dn1dx->getBarcodePNG($this->base_url . '/docs/' . $unique_id, 'QRCODE');
        $img = '<img src="data:image/png;base64,' . $barcode . '" height="30"  alt="barcode" />';

        if($appInfo['appInfo']->status_id == 5 ){
            if($appInfo['appInfo']->process_type_id === 5){
                $modal = CallCenterNew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif($appInfo['appInfo']->process_type_id === 6){
                $modal = CallCenterRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }elseif($appInfo['appInfo']->process_type_id === 7){
                $modal = Amendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }elseif($appInfo['appInfo']->process_type_id === 8){
                $modal = CallCenterSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
        }
        elseif ($appInfo['appInfo']->status_id == 15){
            if($appInfo['appInfo']->process_type_id === 5){
                $modal = CallCenterNew::class;
                $content = $this->GeneratePDFForPaymentRequest($modal, $appInfo, 1, 1);
            } elseif($appInfo['appInfo']->process_type_id === 6){
                $modal = CallCenterRenew::class;
                $content = $this->GeneratePDFForPaymentRequest($modal, $appInfo, 1, 1);
            }
        }
        elseif ($appInfo['appInfo']->status_id == 25 && $row->pdf_diff == 3){
            if($appInfo['appInfo']->process_type_id === 5){
                $modal = CallCenterNew::class;
                $content = $this->GeneratePDFSecCertificate($modal, $appInfo, 1, 1);
            } elseif($appInfo['appInfo']->process_type_id === 6){
                $modal = CallCenterRenew::class;
                $content = $this->GeneratePDFSecCertificate($modal, $appInfo, 1, 1);
            }
        }
        else {
            $content = view("CertificateGeneration::bpo_call_center_certificate", $appInfo)->render();
        }
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => storage_path(),
            'fontDir' => array_merge($fontDirs, [
                public_path('assets/fonts'), // to find like /public/fonts/SolaimanLipi.ttf
            ]),
            'fontdata' => $fontData + [
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                    ],
//                    'nikosh' => [
//                        'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
//                    'kalpurush' => [
//                        'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                    ],
                ],
            'default_font' => 'solaimanlipi',
            //'default_font' => 'solaimanlipi',
            //'default_font' => 'kalpurush',
            'setAutoTopMargin' => 'pad',
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
        ]);

        ## $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("Bangladesh Telecommunication Regulatory Commission(BTRC)");
        $mpdf->SetSubject("Subject");
        $mpdf->SetAuthor("Business Automation Limited");
        //$mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->SetWatermarkImage(
            'assets/images/new_images/logo.png',
            0.1,
            '2000',
            array(10,90),
        );
        $mpdf->showWatermarkImage = true;
        if(!in_array($appInfo['appInfo']->status_id, [5,15]) && ($appInfo['appInfo']->status_id == 25 && $row->pdf_diff != 3)){
            $mpdf->SetHTMLHeader('<div class="header" style="margin:auto;width: 100%">
                                        <div style="text-align: center;">
                                                <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                                                <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                                                <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
                                                <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
                                                <img src="data:image/png;base64,'.$appInfo['qrCode']. '" width="100px" height="100px" alt="barcode" />
                                        </div>
                                    </div>');
        }


        $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->showWatermarkImage = true;
        /*
        if(in_array(config('app.APP_ENV'), ['local','dev','uat','training'])){
            $mpdf->SetWatermarkImage('assets/images/pdf_watermark_test.png',
                0.6,'D','F'
            );
        }else{
            $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
                0.1,'F','F'
            );
        }

        $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
            0.1,'F','F'
        );
        */

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($content, 2);
        // $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;
        $mpdf->SetCompression(true);
        $baseURL = $this->base_folder."/certificate/";
        $directoryName = $baseURL . date("Y/m");
        $directoryNameYear = $baseURL . date("Y");

        directoryFunction($directoryName, $directoryNameYear);
        $certificateName = uniqid("certificate_", true);
        $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
//        exit();
        $fullPath = $this->base_url . '/' . $pdfFilePath;
        saveCertificate($row, $fullPath, $unique_id);

        if($appInfo['appInfo']->process_type_id === 5){
            //Send email to all the desk officers
            if(!in_array($appInfo['appInfo']->status_id, [5,15]) && ($appInfo['appInfo']->status_id == 25 && $row->pdf_diff != 3)){
                $modelName = CallCenterNew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);

                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->tracking_no,
                    'process_type_name' => 'BPO/ Call Center Registration Issue',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                $appInfo['appInfo']['org_nm'] = $appInfo['companyInfo']->org_nm;
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'BPO/ Call Center Registration Issue', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }elseif($appInfo['appInfo']->process_type_id === 6){
            //Send email to all the desk officers
            if(!in_array($appInfo['appInfo']->status_id, [5,15]) && ($appInfo['appInfo']->status_id == 25 && $row->pdf_diff != 3)){
                $modelName = CallCenterRenew::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->tracking_no,
                    'process_type_name' => 'BPO/ Call Center Registration Renew',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                $appInfo['appInfo']['org_nm'] = $appInfo['companyInfo']->org_nm;
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'BPO/ Call Center Registration Renew', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }elseif($appInfo['appInfo']->process_type_id === 7){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = Amendment::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->amendment_tracking_no,
                    'process_type_name' => 'BPO/ Call Center Registration Amendment',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                $appInfo['appInfo']['org_nm'] = $appInfo['companyInfo']->org_nm;
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'BPO/ Call Center Registration Amendment', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }elseif($appInfo['appInfo']->process_type_id === 8){
            //Send email to all the desk officers
            if($appInfo['appInfo']->status_id != 5) {
                $modelName = CallCenterSurrender::class;
                $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                $applicationInfo = [
                    'app_id'            => $appInfo['appInfo']->ref_id,
                    'status_id'         => $appInfo['appInfo']->status_id,
                    'process_type_id'   => $appInfo['appInfo']->process_type_id,
                    'tracking_no'       => $appInfo['appInfo']->tracking_no,
                    'process_type_name' => 'BPO/ Call Center Registration Surrender',
                    'remarks'           => '',
                    'org_nm'            => $appInfo['companyInfo']->org_nm
                ];
                $appInfo['appInfo']['org_nm'] = $appInfo['companyInfo']->org_nm;
                CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'BPO/ Call Center Registration Surrender', $modelName);
                $this->SendEmailToAllDeskOfficers($appInfo);
            }
        }

        if(in_array($appInfo['appInfo']->status_id, [5,15]) || ($appInfo['appInfo']->status_id == 25 && $row->pdf_diff == 3)){
            return true;
        }else{
            echo "certificate generate successfully " . PHP_EOL;
        }
    }


    public function TVASIssueCertificateGenerate($row)
    {

        $dn1d = new DNS1D();
        $dn1dx = new DNS2D();
        $unique_id = generateUniqueId();

        //get all data
        $appInfo = $this->appData($row->process_type_id, $row->app_id, $row->signatory);

        $barcode = $dn1d->getBarcodePNG($appInfo['appInfo']->tracking_no, 'C39', 2, 60);
        $appInfo['qrCode'] = $dn1dx->getBarcodePNG($this->base_url . '/docs/' . $unique_id, 'QRCODE');
        $img = '<img src="data:image/png;base64,' . $barcode . '" height="30"  alt="barcode" />';


        // process type wise table shortfall certificate generate
        if ($appInfo['appInfo']->status_id == 5) {
            if ($appInfo['appInfo']->process_type_id === 25) {
                $modal = TVASLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 26) {
                $modal = TVASLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 27) {
                $modal = TVASLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 28) {
                $modal = TVASLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 17) {
                $modal = IIGLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 18) {
                $modal = IIGLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 19) {
                $modal = IIGLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 20) {
                $modal = IIGLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 33) {
                $modal = ICXLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 34) {
                $modal = ICXLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 35) {
                $modal = ICXLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 36) {
                $modal = ICXLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 37) {
                $modal = IGWLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 38) {
                $modal = IGWLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 39) {

                $modal = IGWLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 40) {
                $modal = IGWLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 54) {
                $modal = ITCLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 55) {
                $modal = ITCLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 56) {
                $modal = ITCLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 57) {
                $modal = ITCLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 58) {
                $modal = MNOLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 59) {
                $modal = MNOLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 60) {
                $modal = MNOLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 61) {
                $modal = MNOLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 62) {
                $modal = SCSLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 63) {
                $modal = SCSLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 64) {
                $modal = SCSLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 65) {
                $modal = SCSLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 66) {
                $modal = TCLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 67) {
                $modal = TCLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 68) {
                $modal = TCLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 69) {
                $modal = TCLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 70) {
                $modal = MNPLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 71) {
                $modal = MNPLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 72) {
                $modal = MNPLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 73) {
                $modal = MNPLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 74) {
                $modal = BWALicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 75) {
                $modal = BWALicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 76) {
                $modal = BWALicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 77) {
                $modal = BWALicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 78) {
                $modal = SSLicenseIssue::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 79) {
                $modal = SSLicenseRenew::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 80) {
                $modal = SSLicenseAmendment::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            } elseif ($appInfo['appInfo']->process_type_id === 81) {
                $modal = SSLicenseSurrender::class;
                $content = $this->GeneratePDFForShortfall($modal, $appInfo);
            }
        } else {
            $allowedProcessTypeIds = [17, 18, 37, 38, 62, 63, 66, 67, 74, 75];
            if(in_array($appInfo['appInfo']->process_type_id, $allowedProcessTypeIds)){
                $content = view("CertificateGeneration::bidding_license_issue", $appInfo)->render();
            }
            else
               $content = view("CertificateGeneration::tvas_call_center_certificate", $appInfo)->render();
        }
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
            $fontData = $defaultFontConfig['fontdata'];

            $mpdf = new \Mpdf\Mpdf([
                'tempDir' => storage_path(),
                'fontDir' => array_merge($fontDirs, [
                    public_path('assets/fonts'), // to find like /public/fonts/SolaimanLipi.ttf
                ]),
                'fontdata' => $fontData + [
                    'kalpurush' => [
                        'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                    ],
//                                'nikosh' => [
//                                    'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
//                                'solaimanlipi' => [
//                                    'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
                    'sans-serif' => [
                        'R' => 'sans-serif.ttf', 'I' => 'sans-serif.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
                    ],
//                                'NikoshBAN' => [
//                                    'R' => 'NikoshBAN.ttf', 'I' => 'NikoshBAN.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
//                                'Nikosh' => [
//                                    'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                                ],
                ],
            'default_font' => 'kalpurush',
//                        'default_font' => 'solaimanlipi',
            'setAutoTopMargin' => 'pad',
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
        ]);
//                 'fontdata' => $fontData + [
// //                    'kalpurush' => [
// //                        'R' => 'kalpurush-kalpurush.ttf', 'I' => 'kalpurush-kalpurush.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
// //                    ],
// //                    'nikosh' => [
// //                        'R' => 'Nikosh.ttf', 'I' => 'Nikosh.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
// //                    ],
//                         'solaimanlipi' => [
//                             'R' => 'SolaimanLipi.ttf', 'I' => 'SolaimanLipi.ttf', 'useOTL' => 0xFF, 'useKashida' => 75,
//                         ],
//                     ],
//                 'default_font' => 'solaimanlipi',
// //            'default_font' => 'kalpurush',
//                 'setAutoTopMargin' => 'pad',
//                 'mode' => 'utf-8',
//                 'format' => 'A4',
//                 'default_font_size' => 11,
//             ]);

            ## $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("Bangladesh Telecommunication Regulatory Commission(BTRC)");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            //$mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetWatermarkImage(
                'assets/images/new_images/logo.png',
                0.1,
                '2000',
                array(10, 90),
            );
            $mpdf->showWatermarkImage = true;
            $allowedProcessTypeIds = [17, 18, 37, 38, 62, 63, 66, 67, 74, 75];
            if ($appInfo['appInfo']->status_id != 5 && !in_array($appInfo['appInfo']->process_type_id, $allowedProcessTypeIds)) {
                $mpdf->SetHTMLHeader('<div class="header" style="margin:auto;width: 100%">
                                        <div style="text-align: center;">
                                                <img src="assets/images/new_images/logo.png" alt="" height="18%"><br><br>
                                                <strong style="font-size: 30px;">BANGLADESH TELECOMMUNICATION</strong><br>
                                                <strong style="font-size: 30px;">REGULATORY COMMISSION</strong>
                                                <h4>Plot: E-5/A, Agargaon Administrative Area, Dhaka-1207.</h4>
                                            <img src="data:image/png;base64,' . $appInfo['qrCode'] . '" width="100px" height="100px" alt="barcode" />
                                        </div>
                                    </div>');
            }

            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');

            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->showWatermarkImage = true;
            /*
            if(in_array(config('app.APP_ENV'), ['local','dev','uat','training'])){
                $mpdf->SetWatermarkImage('assets/images/pdf_watermark_test.png',
                    0.6,'D','F'
                );
            }else{
                $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
                    0.1,'F','F'
                );
            }

            $mpdf->SetWatermarkImage('assets/images/BSCIC_logo-01.png',
                0.1,'F','F'
            );
            */

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($content, 2);
            // $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;
            $mpdf->SetCompression(true);
            $baseURL = $this->base_folder . "/certificate/";
            $directoryName = $baseURL . date("Y/m");
            $directoryNameYear = $baseURL . date("Y");

            directoryFunction($directoryName, $directoryNameYear);
            $certificateName = uniqid("certificate_", true);
            $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
            $fullPath = $this->base_url . '/' . $pdfFilePath;
            saveCertificate($row, $fullPath, $unique_id);

            if ($appInfo['appInfo']->process_type_id === 25) {
                //Send email to all desk officers
                if ($appInfo['appInfo']->status_id != 5) {
                    $modelName = TVASLicenseIssue::class;
                    $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                    $applicationInfo = [
                        'app_id' => $appInfo['appInfo']->ref_id,
                        'status_id' => $appInfo['appInfo']->status_id,
                        'process_type_id' => $appInfo['appInfo']->process_type_id,
                        'tracking_no' => $appInfo['appInfo']->tracking_no,
                        'process_type_name' => 'TVAS License Issue',
                        'remarks' => '',
                        'org_nm' => $appInfo['companyInfo']->org_nm
                    ];
                    CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'TVAS License Issue', $modelName);
                    $this->SendEmailToAllDeskOfficers($appInfo);
                }
            } elseif ($appInfo['appInfo']->process_type_id === 26) {
                //Send email to all desk officers
                if ($appInfo['appInfo']->status_id != 5) {
                    $modelName = TVASLicenseRenew::class;
                    $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                    $applicationInfo = [
                        'app_id' => $appInfo['appInfo']->ref_id,
                        'status_id' => $appInfo['appInfo']->status_id,
                        'process_type_id' => $appInfo['appInfo']->process_type_id,
                        'tracking_no' => $appInfo['appInfo']->tracking_no,
                        'process_type_name' => 'TVAS License Renew',
                        'remarks' => '',
                        'org_nm' => $appInfo['companyInfo']->org_nm
                    ];
                    CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'TVAS License Renew', $modelName);
                    $this->SendEmailToAllDeskOfficers($appInfo);
                }
            } elseif ($appInfo['appInfo']->process_type_id === 27) {
                //Send email to all desk officers
                if ($appInfo['appInfo']->status_id != 5) {
                    $modelName = TVASLicenseAmendment::class;
                    $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                    $applicationInfo = [
                        'app_id' => $appInfo['appInfo']->ref_id,
                        'status_id' => $appInfo['appInfo']->status_id,
                        'process_type_id' => $appInfo['appInfo']->process_type_id,
                        'tracking_no' => $appInfo['appInfo']->tracking_no,
                        'process_type_name' => 'TVAS License Amendment',
                        'remarks' => '',
                        'org_nm' => $appInfo['companyInfo']->org_nm
                    ];
                    CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'TVAS License Amendment', $modelName);
                    $this->SendEmailToAllDeskOfficers($appInfo);
                }
            } elseif ($appInfo['appInfo']->process_type_id === 28) {
                //Send email to all desk officers
                if ($appInfo['appInfo']->status_id != 5) {
                    $modelName = TVASLicenseSurrender::class;
                    $receiverInformation = CommonFunction::getReceiverInfo($modelName, $appInfo['appInfo']);
                    $applicationInfo = [
                        'app_id' => $appInfo['appInfo']->ref_id,
                        'status_id' => $appInfo['appInfo']->status_id,
                        'process_type_id' => $appInfo['appInfo']->process_type_id,
                        'tracking_no' => $appInfo['appInfo']->tracking_no,
                        'process_type_name' => 'TVAS License Surrender',
                        'remarks' => '',
                        'org_nm' => $appInfo['companyInfo']->org_nm
                    ];
                    CommonFunction::sendSMSEmailForApprove($receiverInformation[0]['user_mobile'], $receiverInformation, $applicationInfo, $appInfo['appInfo'], 'TVAS License Surrender', $modelName);
                    $this->SendEmailToAllDeskOfficers($appInfo);
                }
            }

            if ($appInfo['appInfo']->status_id != 5) {
                echo "certificate generate successfully " . PHP_EOL;
            }
    }

    public function SendEmailToAllDeskOfficers($appInfo){
//        $deskOfficersInfo = Users::where( 'user_type','=','4x404' )
//            ->where( 'user_status', 'active' )
//            ->whereNotNull("user_email")
//            ->get([
//                'user_email',
//                DB::raw("IF(ISNULL(user_mobile), '111', user_mobile) AS user_mobile")
//            ]);
//        dd($appInfo['appInfo']['ref_id'], $appInfo['appInfo']['process_type_id']);
        $deskOfficersInfo = ProcessHistory::distinct('process_list_hist.updated_by')
            ->select('process_list_hist.updated_by', 'u.user_type', 'u.user_email')
            ->leftJoin('users as u', 'process_list_hist.updated_by', '=', 'u.id')
            ->whereIn('process_list_hist.ref_id', [$appInfo['appInfo']['ref_id']])
            ->whereIn('process_list_hist.process_type_id', [$appInfo['appInfo']['process_type_id']])
            ->where('u.user_type', '4x404')
            ->get();

        $serviceName = ProcessType::where([
            'id' => $appInfo['appInfo']['process_type_id']
        ])->value('name');

        $appInfo['appInfo']['process_type_name'] = $serviceName;
        CommonFunction::sendEmailSMS( 'APPROVED_MAIL_FOR_DESK_OFFICER',$appInfo['appInfo'], $deskOfficersInfo, '' );
    }

    public function GeneratePDFForShortfall($modal, $appInfo){
        $appInfo['ContactPersonData'] = ContactPerson::where('process_type_id','=', $appInfo['appInfo']->process_type_id)
            ->where('app_id', '=', $appInfo['appInfo']->ref_id)
            ->first();
        $registerInfo = $modal::select('reg_office_address', 'reg_office_thana', 'reg_office_district', 'shortfall_reason')->where('id', '=',$appInfo['appInfo']->ref_id )->first();
        $appInfo['registerOfficeAddress'] = $registerInfo->reg_office_address;
        $appInfo['registerOfficeThana'] = Area::where('area_id', '=',$registerInfo->reg_office_thana )->value('area_nm');
        $appInfo['registerOfficeDistrict'] = Area::where('area_id', '=',$registerInfo->reg_office_district )->value('area_nm');
        $appInfo['shortFallReason'] =  $registerInfo->shortfall_reason;
        $appInfo['module_name'] = ProcessType::where([
            'id' => $appInfo['appInfo']->process_type_id
        ])->value('name');
        if (in_array($appInfo['appInfo']->process_type_id, [5, 6, 7, 8])) {
            return view("CertificateGeneration::bpo_call_center_pdf_shortfall", $appInfo)->render();
        } else {
            return view("CertificateGeneration::isp_license_issue_pdf_shortfall", $appInfo)->render();
        }

    }

    public function GeneratePDFForPaymentRequest($modal, $appInfo, $payment_step_id=0, $with_1st_annual_fee=0){
        $appInfo['ContactPersonData'] = ContactPerson::where('process_type_id','=', $appInfo['appInfo']->process_type_id)
            ->where('app_id', '=', $appInfo['appInfo']->ref_id)
            ->first();
        $registerInfo = $modal::select('reg_office_address', 'reg_office_thana', 'reg_office_district')->where('id', '=',$appInfo['appInfo']->ref_id )->first();
        $appInfo['registerOfficeAddress'] = $registerInfo->reg_office_address;
        $appInfo['registerOfficeThana'] = Area::where('area_id', '=',$registerInfo->reg_office_thana )->value('area_nm');
        $appInfo['registerOfficeDistrict'] = Area::where('area_id', '=',$registerInfo->reg_office_district )->value('area_nm');
        $process_type_id = $appInfo['appInfo']->process_type_id;
        $appInfo['module_name'] = ProcessType::where([
            'id' => $process_type_id
        ])->value('name');
        //pdf amount calculation
        $processInfo = $appInfo['appInfo'];
        $amountArray = getPaymentDetails($processInfo, $payment_step_id, $with_1st_annual_fee);
        $pdfAmountArray = [
            "mainAmount" => $amountArray['main_amount'],
            "vatAmount" => $amountArray['vat_amount'],
            "totalAmount" => $amountArray['total_amount'],
        ];
        $appInfo['pdfAmountArray'] = $pdfAmountArray;
        $content = "";
        if($process_type_id == 1 || $process_type_id==2){
            $content = view("CertificateGeneration::request_for_payment_pdf", $appInfo)->render();
        }elseif ($process_type_id == 5 || $process_type_id==6){
            $appInfo['service_name'] = "বিপিও/কলসেন্টার";
            $appInfo['license_duration'] = 5;
            $content = view("CertificateGeneration::request_payment_pdf_bpo_call", $appInfo)->render();
        }
        return $content;
    }

    public function GeneratePDFSecCertificate($modal, $appInfo, $payment_step_id=0, $with_1st_annual_fee=0){
        $appInfo['ContactPersonData'] = ContactPerson::where('process_type_id','=', $appInfo['appInfo']->process_type_id)
            ->where('app_id', '=', $appInfo['appInfo']->ref_id)
            ->first();
        $registerInfo = $modal::select('reg_office_address', 'reg_office_thana', 'reg_office_district')->where('id', '=',$appInfo['appInfo']->ref_id )->first();
        $appInfo['registerOfficeAddress'] = $registerInfo->reg_office_address;
        $appInfo['registerOfficeThana'] = Area::where('area_id', '=',$registerInfo->reg_office_thana )->value('area_nm');
        $appInfo['registerOfficeDistrict'] = Area::where('area_id', '=',$registerInfo->reg_office_district )->value('area_nm');
        $process_type_id = $appInfo['appInfo']->process_type_id;
        $appInfo['process_info'] = ProcessType::where([
            'id' => $process_type_id
        ])->first(["name","group_name"]);
        $content = "";
        if ($process_type_id == 5){
            $appInfo['service_name'] = "বিপিও/কলসেন্টার";
            $pdf_file = ApplicationGuideline::where([["group_nm", $appInfo['process_info']->group_name], ["status", 1]])->value("pdf_file");
            $appInfo['call_center_guideline_hyperlink'] = $pdf_file;
            $content = view("CertificateGeneration::registration_cert_pdf_bpo_call", $appInfo)->render();
        }
        if ( $process_type_id==6){
            $appInfo['service_name'] = "বিপিও/কলসেন্টার";
            $pdf_file = ApplicationGuideline::where([["group_nm", $appInfo['process_info']->group_name], ["status", 1]])->value("pdf_file");
            $appInfo['call_center_guideline_hyperlink'] = $pdf_file;
            $content = view("CertificateGeneration::registration_cert_pdf_bpo_call_renew", $appInfo)->render();
        }
        return $content;
    }

    public function GeneratePDFForAnnualOrBGFee($modal, $appInfo, $payment_step_id){
        $appInfo['ContactPersonData'] = ContactPerson::where('process_type_id','=', $appInfo['appInfo']->process_type_id)
            ->where('app_id', '=', $appInfo['appInfo']->ref_id)
            ->first();
        $registerInfo = $modal::select('reg_office_address', 'reg_office_thana', 'reg_office_district')->where('id', '=',$appInfo['appInfo']->ref_id )->first();
        $appInfo['registerOfficeAddress'] = $registerInfo->reg_office_address;
        $appInfo['registerOfficeThana'] = Area::where('area_id', '=',$registerInfo->reg_office_thana )->value('area_nm');
        $appInfo['registerOfficeDistrict'] = Area::where('area_id', '=',$registerInfo->reg_office_district )->value('area_nm');
        $process_type_id = $appInfo['appInfo']->process_type_id;
        $appInfo['module_name'] = ProcessType::where([
            'id' => $process_type_id
        ])->value('name');
        $amountArray = getPaymentDetails($appInfo["appInfo"], $payment_step_id, 0, 1);
        $pdfAmountArray = [
            "mainAmount" => $amountArray['main_amount'],
            "vatAmount" => $amountArray['vat_amount'],
            "totalAmount" => $amountArray['total_amount'] * 4,
        ];
        $paymentJson = ProcessType::where('id', $process_type_id)->get('process_desk_status_json');
        $isp_license_type = ISPLicenseIssue::where('id', $appInfo['appInfo']->ref_id)->value('isp_license_type');
        $getPaymentJson = json_decode($paymentJson, true);
        $feesJson = json_decode($getPaymentJson[0]['process_desk_status_json'], true);
        $bg_object = $feesJson['bg_object'];
        if($isp_license_type == '1'){
            $pdfAmountArray['bank_guarantee_amount'] = $bg_object['nationwide'];
        } elseif($isp_license_type == '2'){
            $pdfAmountArray['bank_guarantee_amount'] = $bg_object['divisional'];
        } elseif($isp_license_type == '3'){
            $pdfAmountArray['bank_guarantee_amount'] = $bg_object['district'];
        } elseif($isp_license_type == '4'){
            $pdfAmountArray['bank_guarantee_amount'] = $bg_object['thana'];
        }
        $appInfo['pdfAmountArray'] = $pdfAmountArray;
        $content = view("CertificateGeneration::request_for_annual_or_bg_fee", $appInfo)->render();
        return $content;
    }
}
