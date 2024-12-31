<?php


namespace App\Modules\SonaliPayment\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\QRCodeDetails;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Mpdf\Mpdf;
use DB;
use Illuminate\Support\Facades\Log;

class PaymentInvoiceController extends Controller
{
    /**
     * Payment Voucher
     * @param null $paymentId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function paymentVoucher($paymentId = null)
    {
        if (empty($paymentId)) {
            Session::flash('error', 'Invalid payment id [PIC-113]');
            return redirect()->back();
        }
        $base_url = (substr(env('PROJECT_ROOT'), (strlen(env('PROJECT_ROOT')) - 1)) == '/') ? substr(env('PROJECT_ROOT'), 0, (strlen(env('PROJECT_ROOT')) - 1)) : env('PROJECT_ROOT');
        try {
            $decodedPaymentId = Encryption::decodeId($paymentId);
            $paymentInfo = SonaliPayment::query()
                ->leftJoin('sp_payment_configuration', 'sp_payment_configuration.id', 'sp_payment.payment_config_id')
                ->where('sp_payment.id', $decodedPaymentId)
                ->first([
                    'sp_payment.*',
                    'sp_payment_configuration.payment_name as purpose_payment',
                ]);
            if (empty($paymentInfo)) {
                Session::flash('error', 'Invalid payment id [PIC-113]');
                return redirect()->back();
            }
            $type_of_license = '';
            if(in_array($paymentInfo->process_type_id, [1, 2, 3, 4])) { // only for ISP
                $process_type_table = ProcessType::process_type_table_by_id($paymentInfo->process_type_id);
                $process_table = DB::table($process_type_table)
                    ->leftJoin('license_type', 'license_type.id', $process_type_table.'.isp_license_type')
                    ->where($process_type_table.'.id', $paymentInfo->app_id)->first([$process_type_table.'.isp_license_type','license_type.name as type_of_license']);
                $type_of_license = $process_table->type_of_license;
            }

            $company = CompanyInfo::query()
                ->leftJoin('process_list', 'process_list.company_id', '=', 'company_info.id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.process_type_id', $paymentInfo->process_type_id)
                ->where('process_list.ref_id', $paymentInfo->app_id)
                ->first(['company_info.org_nm', 'process_type.name']);
            $companyName = $company->org_nm;
            $process_type = $company->name;
            $voucher_title = '';
            $voucher_subtitle = '';
            $voucher_logo_path = app_path('/Modules/SonaliPayment/resources/images/btrc.png');

            $mobile_number = Configuration::where('caption', 'SP_PAYMENT_HELPLINE_VOUCHER')->first();
            //dd($base_url . '/show-voucher/' . Encryption::encodeId($decodedPaymentId));
            $dn1d = new DNS1D();
            $dn1dx = new DNS2D();
            $qrCode_url =  $base_url . '/show-voucher/' . Encryption::encodeId($decodedPaymentId);
            $qrCode = $dn1dx->getBarcodePNG($qrCode_url, 'QRCODE');
            $request_id = $paymentInfo->request_id; // tracking no push on barcode.
            $trackingNo = $paymentInfo->app_tracking_no; // tracking no push on barcode.
            // Saved URL by this function with proper information
            $this->saveQRCodeDetails($paymentInfo, $qrCode_url, $company->name, auth()->user()->id);
            if (!empty($request_id)) {
                $barcode = $dn1d->getBarcodePNG($request_id, 'C39');
                $barcode_url = 'data:image/png;base64,' . $barcode;
            } else {
                $barcode_url = '';
            }

            $contents = view("SonaliPayment::paymentVoucher-pdf-v2",
                compact('decodedPaymentId', 'paymentInfo', 'barcode_url', 'companyName',
                    'voucher_title', 'voucher_subtitle', 'voucher_logo_path', 'trackingNo', 'process_type', 'request_id', 'qrCode', 'type_of_license'))->render();

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
                    ],
                'default_font' => 'kalpurush',
                'setAutoTopMargin' => 'pad',
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 12,
            ]);
            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle(config('app.project_name'));
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
//            $mpdf->SetHTMLFooter('
//                    <table width="100%">
//                        <tr>
//                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
//                            <td width="50%" align="right"><i style="font-size: 10px;">Help line: ' . $mobile_number->value . '</i></td>
//                        </tr>
//                    </table>');
            $stylesheet = file_get_contents(app_path('/Modules/SonaliPayment/resources/css/pdf_style_v2.min.css'));
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';

            if ($paymentInfo->status_code == 200) {
                $mpdf->SetWatermarkImage(app_path('/Modules/SonaliPayment/resources/images/paid.png'), 1, [36, 35], [80, 79]);
                $mpdf->showWatermarkImage = true;
            }

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output($paymentInfo->app_tracking_no . '.pdf', 'I');

        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something went wrong! [PIC-117]');
            return Redirect::back()->withInput();
        }
    }

    /**
     * Counter payment voucher
     * @param null $paymentId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function counterPaymentVoucher($paymentId = null)
    {
        if (empty($paymentId)) {
            Session::flash('error', 'Invalid payment id [PIC-112]');
            return redirect()->back();
        }

        try {
            $decodedPaymentId = Encryption::decodeId($paymentId);
            $paymentInfo = SonaliPayment::where('id', $decodedPaymentId)->first();
            $companyName = CompanyInfo::leftJoin('process_list', 'process_list.company_id', '=', 'company_info.id')
                ->where('process_list.process_type_id', $paymentInfo->process_type_id)
                ->where('process_list.ref_id', $paymentInfo->app_id)
                ->value('org_nm');

            $mobile_number = Configuration::where('caption', 'SP_PAYMENT_HELPLINE_VOUCHER')->first();
            $voucher_title = config('app.project_name');
            $voucher_subtitle = 'ICT Division';
            $voucher_logo_path = app_path('/modules/SonaliPayment/resources/images/business_automation.png');
            $dn1d = new DNS1D();
            $trackingNo = $paymentInfo->app_tracking_no; // tracking no push on barcode.
            if (!empty($trackingNo)) {
                $barcode = $dn1d->getBarcodePNG($trackingNo, 'C39');
                $barcode_url = 'data:image/png;base64,' . $barcode;
            } else {
                $barcode_url = '';
            }

            $contents = view("SonaliPayment::counterVoucher-pdf",
                compact('decodedPaymentId', 'paymentInfo', 'barcode_url', 'companyName',
                    'voucher_title', 'voucher_subtitle', 'voucher_logo_path'))->render();

            $mpdf = new Mpdf([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default font family
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                10, // margin footer
                'P'
            ]);
            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle(config('app.project_name'));
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">Help line: ' . $mobile_number->value . '</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents(app_path('/modules/SonaliPayment/resources/css/pdf_style.min.css'));
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';

            if ($paymentInfo->status_code == 200) {
                $mpdf->SetWatermarkImage(app_path('/modules/SonaliPayment/resources/images/paid.png'), 1, [36, 35], [80, 180]);
                $mpdf->showWatermarkImage = true;
            }

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output($paymentInfo->app_tracking_no . '.pdf', 'I');

        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Sorry! Something went wrong! [PIC-114]');
            return Redirect::back()->withInput();
        }
    }

    public function saveQRCodeDetails($paymentInfo, $qrCodeUrl, $processType, $createdBy) {
        try {
            QRCodeDetails::firstOrCreate(
                [
                    'app_id' => $paymentInfo->app_id,
                ], // These are the attributes to search for
                [
                    'payment_config_id' => $paymentInfo->payment_config_id,
                    'process_type_id' => $paymentInfo->process_type_id,
                    'process_type_name' => $processType,
                    'app_tracking_no' => $paymentInfo->app_tracking_no,
                    'payment_step_id' => $paymentInfo->payment_step_id,
                    'pay_mode' => $paymentInfo->pay_mode,
                    'pay_mode_code' => $paymentInfo->pay_mode_code,
                    'transaction_id' => $paymentInfo->transaction_id,
                    'request_id' => $paymentInfo->request_id,
                    'payment_date' => $paymentInfo->payment_date,
                    'ref_tran_no' => $paymentInfo->ref_tran_no,
                    'qr_code_url' => $qrCodeUrl,
                    'created_at' => now(),
                    'created_by' => $createdBy,
                ]
            );

        } catch (Exception $e) {

            Log::error('Failed to save QR code details: ' . $e->getMessage(), [
                'paymentInfo' => $paymentInfo,
                'qrCodeUrl' => $qrCodeUrl,
                'processType' => $processType,
                'createdBy' => $createdBy,
            ]);
            Session::flash('error', 'Failed to save QR code details. Please try again later.');
        }
    }
    public function showVoucher($tracking_no) {
        try {
            $paymentID = SonaliPayment::where('app_tracking_no', '=', $tracking_no)->first();
            if(!empty($paymentID) && $paymentID['payment_type'] == 'online_payment'){
                $paymentDocodedId = Encryption::encodeId($paymentID['id']);
                $base_url = (substr(env('PROJECT_ROOT'), (strlen(env('PROJECT_ROOT')) - 1)) == '/') ? substr(env('PROJECT_ROOT'), 0, (strlen(env('PROJECT_ROOT')) - 1)) : env('PROJECT_ROOT');
                $voucherUrl =  $base_url . '/show-voucher/' .$paymentDocodedId;
                return redirect($voucherUrl);
            }else{
                $errorMessage = '<style>body { font-family: Arial, sans-serif; background-color: #f0f0f0; padding: 20px; }</style>';
                $errorMessage .= '<h2 style="color: red;">The tracking number you provided does not have an associated voucher.</h2>';
                $errorMessage .= '<p> Please check the tracking number and try again.</p>';
                return response($errorMessage, 404);
            }
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), $e->getFile());
        }
    }
}
