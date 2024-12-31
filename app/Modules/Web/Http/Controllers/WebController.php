<?php namespace App\Modules\Web\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\ActRules;
use App\Modules\Settings\Models\ApplicationGuideline;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\HomeContent;
use App\Modules\Settings\Models\HomePageSlider;
use App\Modules\Settings\Models\IframeList;
use App\Modules\Settings\Models\IndustrialCityList;
use App\Modules\Settings\Models\NeedHelp;
use App\Modules\Settings\Models\Notice;
use App\Modules\Settings\Models\TermsCondition;
use App\Modules\Settings\Models\UserManual;
use App\Modules\Settings\Models\WhatsNew;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use yajra\Datatables\Datatables;
use Config;

class WebController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect("dashboard");
        }

        if(env('IS_MOBILE')){
            return view('public_home.login-with-mobile');
        }else{
            $notice = Notice::where('status', 'public')
                ->where('is_active', 1)
                ->orderBy('notice.updated_at', 'desc')
                ->limit(3)
                ->get();
            $latestnotice = $notice->take(2);
            if ($notice->count() <= 2){
                $totalNotice = 0;
            }else{
                $totalNotice = 1;
            }

            $topNotice =  Notice::where('status', 'public')
                ->where('is_active', 1)
                ->where( 'updated_at', '>', Carbon::now()->subDays(3))
                ->orderBy('notice.updated_at', 'desc')
                ->get();

            $latestNotice = Notice::where('status', 'public')
                ->where('is_active', 1)
                ->where('is_archive', 0)
                ->orderBy('notice.updated_at', 'desc')
                ->first();

            $home_slider_image = HomePageSlider::where('status', 1)
                ->orderby('id', 'DESC')
                ->take(5)
                ->get([
                    'slider_image',
                    'slider_title',
                    'slider_url',
                ]);

            $home_content = HomeContent::whereIn('type', ['chairman', 'necessary info'])
                ->where('status', 1)
                ->get();
            $chairmanData = $home_content->where('type', 'chairman')->first();
            $necessaryInfo = $home_content->where('type', 'necessary info')
                ->sortBy('order', SORT_NUMERIC)
                ->all();


            $CityData = IndustrialCityList::where('status', 1)
                ->where('type', 0)
                ->where('is_archive', 0)
                ->count();

            // Cache for 1 day
            $total_stakeholder = Cache::remember('total_stakeholder', 60 * 60 * 24, function () {
                return Configuration::where('caption', 'STAKEHOLDER_SERVCE')->value('value');
            });

            // Cache for 1 day
            $processType = Cache::remember('total_process_type', 60 * 60 * 24, function () {
                return ProcessType::where('status', 1)->count();
            });
            $serviceList = ProcessList::where('status_id', 25)->count();



            $data['total_application']= ProcessList::count();
            $data['total_draft'] = ProcessList::where('status_id','=','-1')->count();
            $data['total_approve_application'] = ProcessList::where('status_id','=','25')->count();
            $data['total_reject_application'] =ProcessList::where('status_id','=','6')->count();


            return view('home', compact('notice', 'home_slider_image', 'topNotice','latestNotice',
                'chairmanData', 'necessaryInfo',
                'latestnotice', 'totalNotice', 'CityData', 'total_stakeholder', 'processType', 'serviceList','data'));
        }
    }

    public function loadCityOffice(Request $request){

        $area_id = Encryption::decodeId($request->area_id);

        $office = IndustrialCityList::leftJoin('area_info as area', 'area.area_id', '=', 'industrial_city_list.district')
            ->leftJoin('area_info as district', 'district.area_id', '=', 'industrial_city_list.district_en')
            ->leftJoin('area_info as upazila', 'upazila.area_id', '=', 'industrial_city_list.upazila')
            ->where('status', 1)
            ->where('type', 0)
            ->where('is_archive', 0)
            ->where('area.pare_id', $area_id)
            ->orderby('order', 'ASC')
            ->get([
                'industrial_city_list.*',
                'district.area_nm_ban as district_name',
                'upazila.area_nm_ban as upazila_name',
            ])->toArray();

        foreach ($office as &$data){
          $data['id'] = Encryption::encodeId($data['id']);
        }

        return response()->json($office);
    }

    public function notice(Request $request)
    {
        $this->checkAjaxRequest($request);

        $notice_tab = Notice::where('status', 'public')
            ->where('is_active', 1)
            ->where('is_archive', 0)
            ->orderBy('notice.updated_at', 'desc')
            ->limit(10)
            ->get(['id', 'heading', 'details', 'importance', 'status', 'updated_at as update_date']);

        $content = strval(view('Web::notice', compact('notice_tab')));
        return response()->json(['response' => $content]);
    }

    public function userManual(Request $request)
    {
        $this->checkAjaxRequest($request);

        $content = strval(view('Web::user_manual'));
        return response()->json(['response' => $content]);
    }

    public function getUserManual(Request $request)
    {
        $this->checkAjaxRequest($request);

        $data = UserManual::where('status', 1)->orderBy('id', 'desc')->limit(9)->get(['typeName',
            'pdfFile',]);
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if (file_exists($data->pdfFile)) {
                    return '<a href="' . '/' . $data->pdfFile . '" class="btn btn-xs btn-success" aria-hidden="true" target="_blank" download><i class="fa fa-download"></i> Download</a>';
                } else {
                    return '';
                }

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function actandRules(Request $request)
    {
        $this->checkAjaxRequest($request);

        $content = strval(view('Web::act&rules'));
        return response()->json(['response' => $content]);
    }

    public function getActandRules(Request $request)
    {
        $this->checkAjaxRequest($request);

        DB::statement(DB::raw('set @rownum=0'));
        $data = ActRules::where('status', 1)->get([
            DB::raw('@rownum := @rownum+1 AS sl'),
            'subject',
            'pdf_link'
        ]);
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if (file_exists($data->pdf_link)) {
                    return '<a href="' . '/' . $data->pdf_link . '" class="btn btn-xs btn-success" aria-hidden="true" target="_blank" download><i class="fa fa-download"></i> Download</a>';
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    public function termsConditions(Request $request)
    {
        $this->checkAjaxRequest($request);

        $content = strval(view('Web::terms&conditions'));
        return response()->json(['response' => $content]);
    }

    public function getTermsConditions(Request $request)
    {
        $this->checkAjaxRequest($request);

        DB::statement(DB::raw('set @rownum=0'));
        $data = TermsCondition::where('status', 1)->get([
            DB::raw('@rownum := @rownum+1 AS sl'),
            'subject',
            'pdf_link',
        ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if (file_exists($data->pdf_link)) {
                    return '<a href="' . '/' . $data->pdf_link . '" class="btn btn-xs btn-success" aria-hidden="true" target="_blank" download><i class="fa fa-download"></i> Download</a>';
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    public function serviceList(Request $request)
    {
        $this->checkAjaxRequest($request);

        $content = strval(view('Web::service-list'));
        return response()->json(['response' => $content]);
    }

    public function getServiceList(Request $request)
    {
        $this->checkAjaxRequest($request);

        DB::statement(DB::raw('set @rownum=0'));
        $data = ProcessType::leftJoin('service_details as sd', 'sd.process_type_id', '=', 'process_type.id')
            ->where('sd.status', 1)
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'),
                'process_type.name',
                'process_type.name_bn',
                'sd.attachment'
            ]);

        return Datatables::of($data)
        // ->editColumn('sl', function () {
        //     return '';
        // })
            ->addColumn('action', function ($data) {
                if (file_exists($data->attachment)) {
                    $btn = '<a href="' . '/' . $data->attachment . '" class="btn btn-xs btn-info" aria-hidden="true" target="_blank" type="button"><i class="fa fa-folder-open-o"></i> ' . __('messages.available_sevices.view') . '</a>';
                    $btn .= ' <a href="' . '/' . $data->attachment . '" class="btn btn-xs btn-success" aria-hidden="true" target="_blank" download type="button"><i class="fa fa-download"></i> ' . __('messages.available_sevices.download') . '</a>';
                    return $btn;
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    public function applicationChart(Request $request)
    {
        $this->checkAjaxRequest($request);

        $dashboardObjectChart = \Illuminate\Support\Facades\DB::table('dashboard_object')
            ->where('db_obj_caption', 'dashboard_graph')
            ->where('db_obj_status', 1)
            ->orderBy('db_obj_sort')
            ->get();
        $content = strval(view('Web::chart', compact('dashboardObjectChart')));
        return response()->json(['response' => $content]);
    }

    public function viewNotice($id, $slug ='')
    {
//        $noticeId = Encryption::decodeId($id);
        $noticeData = Notice::find(CommonFunction::vulnerabilityCheck($id,'integer'));
        if(empty($noticeData)){
            abort(404);
        }
        $notice_doc = $noticeData->notice_document;
        return view('Web::view-notice', compact('noticeData', 'notice_doc'));
    }

    public function industrialCityDetails($id)
    {
        $industrialId = Encryption::decodeId($id);
        $industrialData = IndustrialCityList::find($industrialId);
        return view('Web::industrial-city-details', compact('industrialData'));
    }

    public function support()
    {
        $needHelp = NeedHelp::where('status', 1)->first();
        return view('Web::need-help', compact('needHelp'));
    }

    public function loadMoreNotice(Request $request)
    {
        $this->checkAjaxRequest($request);

        $notice = Notice::where('status', 'public')
            ->where('is_active', 1)
            ->orderBy('notice.updated_at', 'desc')
            ->paginate(2);

        if ($notice->count()) {
            $view = view('Web::notice-load-more', compact('notice'))->render();

            if ($notice->count() < 2){
                return response()->json([
                    'status' => true,
                    'data' => $view,
                    'hide' => 'hide',
                ]);
            }else{
                return response()->json([
                    'status' => true,
                    'data' => $view,
                ]);
            }

        }else{
            return response()->json([
                'status' => true,
                'hide' => 'hide',
            ]);
        }

    }

    public function checkAjaxRequest($request)
    {
        if (!$request->ajax()) {
            dd('Sorry! this is a request without proper way.');
        }
    }

    public function switchLang($lang){
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);
        }
        return redirect()->back();
    }

    public function guidelines($group_nm, $process_type){
        $group_nm = Encryption::decode($group_nm);
        //$process_type = Encryption::decode($process_type);

        $guidUrl = ApplicationGuideline::where(['group_nm_bn'=>$group_nm,'status'=>1])->first();

        if($process_type == 'getpdfpagenumber') {
            return $guidUrl->page_count;
        }

        if(empty($guidUrl)){
           return redirect()->back();
        }
        return redirect()->away(asset($guidUrl->pdf_file));
    }

    public function userMamual() {
        $user_manuals = UserManual::orderByRaw("COALESCE(user_manual.order, 9999999) ASC")
            ->orderBy('user_manual.id', 'asc')
            ->where('user_manual.status', 1)
            ->whereNotNull('user_manual.typeName')
            ->select('user_manual.typeName', 'user_manual.pdfFile')
            ->paginate(10);

        return view('public_home.user_manual', compact('user_manuals'));

    }

    public function guidelinesData() {
        $guidelines = ApplicationGuideline::where(['status'=>1])->orderBy('ordering', 'ASC')->get();
        return view('public_home.guidelines', compact('guidelines'));
    }

    public function noticeData() {
        $notices = DB::table('notice')
                    ->orderBy('ordering_prefix','DESC')
                    ->where('status','public')
                    ->where('is_active', 1)
                     ->get();
        return view('public_home.notice', compact('notices'));
    }

    public function licenseData() {
        $licenseData = DB::select(DB::raw("SELECT * FROM (SELECT id, NAME, name_bn, panel, form_url, icon, SUM(total_app) totalApplication, group_name FROM (SELECT
            pt.*,
            COUNT(pl.id) total_app
          FROM
            `process_type` pt
            LEFT JOIN `process_list` pl
              ON pt.`id` = pl.`process_type_id`
          GROUP BY pt.id ORDER BY id ASC) AS process_data
          GROUP BY group_name) pa
          WHERE id IN(1, 5, 9, 13, 21, 25, 50, 29)"));

        return view('public_home.license', compact('licenseData'));
    }

    public function qrVoucherPdf($paymentId) {
        if (empty($paymentId)) {
            Session::flash('error', 'Invalid payment id [PIC-113]');
            return redirect()->back();
        }
        $base_url = (substr(env('PROJECT_ROOT'), (strlen(env('PROJECT_ROOT')) - 1)) == '/') ? substr(env('PROJECT_ROOT'), 0, (strlen(env('PROJECT_ROOT')) - 1)) : env('PROJECT_ROOT');

        try {
            $decodedPaymentId = explode('_', Encryption::decode($paymentId));
            $decodedPaymentId = $decodedPaymentId[1];
            $paymentInfo = SonaliPayment::query()
                ->leftJoin('sp_payment_configuration', 'sp_payment_configuration.id', 'sp_payment.payment_config_id')
                ->where('sp_payment.id', $decodedPaymentId)
                ->first([
                    'sp_payment.*',
                    'sp_payment_configuration.payment_name as purpose_payment',
                ]);
            $type_of_license = '';
            if(!empty($paymentInfo->process_type_id)) {
                if(in_array($paymentInfo->process_type_id, [1, 2, 3 ,4])) { // only for ISP
                    $process_type_table = ProcessType::process_type_table_by_id($paymentInfo->process_type_id);
                    $process_table = DB::table($process_type_table)
                        ->leftJoin('license_type', 'license_type.id', $process_type_table.'.isp_license_type')
                        ->where($process_type_table.'.id', $paymentInfo->app_id)->first([$process_type_table.'.isp_license_type','license_type.name as type_of_license']);
                    $type_of_license = $process_table->type_of_license;
                }
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

            $dn1d = new DNS1D();
            $dn1dx = new DNS2D();
            $qrCode = $dn1dx->getBarcodePNG($base_url . '/show-voucher/' . Encryption::encodeId($decodedPaymentId), 'QRCODE');
            $request_id = $paymentInfo->request_id; // tracking no push on barcode.
            $trackingNo = $paymentInfo->app_tracking_no; // tracking no push on barcode.
            if (!empty($request_id)) {
                $barcode = $dn1d->getBarcodePNG($request_id, 'C39');
                $barcode_url = 'data:image/png;base64,' . $barcode;
            } else {
                $barcode_url = '';
            }

            return  view("SonaliPayment::paymentVoucher-pdf-v2",
                compact('decodedPaymentId', 'paymentInfo', 'barcode_url', 'companyName',
                    'voucher_title', 'voucher_subtitle', 'voucher_logo_path', 'trackingNo', 'process_type', 'request_id', 'qrCode', 'type_of_license'));

        } catch (\Exception $e) {
                dd($e->getMessage());
                Session::flash('error', 'Sorry! Something went wrong! [PIC-117]');
                return Redirect::back()->withInput();
            }
        }
}
