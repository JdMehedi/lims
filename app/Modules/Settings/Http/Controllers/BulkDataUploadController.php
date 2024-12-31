<?php

namespace App\Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterMaster;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\surrender\CallCenterSurrender;
use App\Modules\REUSELicenseIssue\Models\BWA\BWALicenseMaster;
use App\Modules\REUSELicenseIssue\Models\BWA\issue\BWALicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\MNO\issue\MNOLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\MNO\issue\MNOLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\MNP\issue\MNPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\MNP\MNPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\NTTNLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SS\SSLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\TC\issue\TCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TC\TCLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseMaster;
use App\Modules\Signup\Http\Controllers\SignupController;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;


class BulkDataUploadController extends Controller
{
    private $process_type_id;
    private $sl_no;
    private $licenseType;
    private $division;
    private $district;
    private $upozila;
    private $org_name;
    private $reg_office_address;
    private $license_no;
    private $companyType;
    private $registration_type;
    private $issue_date;
    private $expire_date;
    private $contact_person_name;
    private $contact_person_designation;
    private $contact_person_email;
    private $contact_person_number;
    private static $companyInfo;
    private $divisionID;
    private $districtID;
    private $upazilaID;
    private $remarks;

    public function initVariables($column)
    {
        $this->sl_no = $column[0];
        $this->licenseType = $column[2];
        $this->division = $column[3];
        $this->district = $column[4];
        $this->upozila = $column[5];
        $this->org_name = $column[6];
        $this->reg_office_address = $column[7];
        $this->license_no = $column[8];
        $this->companyType = $column[16];
        $this->issue_date = $column[9];
        $this->expire_date = $column[10];
        $this->contact_person_name = $column[12];
        $this->contact_person_designation = $column[13];
        $this->contact_person_email = $column[14];
        $this->contact_person_number = $column[15];
        $this->remarks = $column[17];
    }

    public function licenseDataUploadForm(Request $request)
    {

        return view('Settings::license_data_upload.licenseDataUploadForm');
    }

    public function getBulkUploadData()
    {
        $BulkDataInfo = ProcessList::leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
            ->where('bulk_status', 1)
            ->get([
                'process_list.*',
                'company_info.org_nm as company_name'
            ]);
        foreach ($BulkDataInfo as $key => $bulk_data) {
            $bulk_data_array = json_decode($bulk_data->bulk_object, true);
            if (!empty($bulk_data_array)) {
                $bulk_data->reg_office_address = $bulk_data_array['reg_office_address'] ?? '';
                $bulk_data->license_no = $bulk_data_array['license_no'] ?? '';
                $bulk_data->license_issue_date = $bulk_data_array['license_issue_date'] ?? '';
                $bulk_data->expiry_date = $bulk_data_array['expiry_date'] ?? '';
                $bulk_data->designation = $bulk_data_array['name'] ?? '';
                $bulk_data->name = $bulk_data_array['designation'] ?? '';
                $bulk_data->email = $bulk_data_array['email'] ?? '';
                $bulk_data->mobile = $bulk_data_array['mobile'] ?? '';
            }
        }

        return Datatables::of($BulkDataInfo)
            ->make(true);

    }

    public function storeBulkData(Request $request, Excel $excel)
    {
        $validators = Validator::make($request->all(), [
            'license_name' => 'required',
            'bulk_file' => 'required',
        ]);
        if ($validators->fails()) {
            return redirect()->back()
                ->withErrors($validators)
                ->withInput();
        }
        $file = $request->file('bulk_file');
        $this->process_type_id = intval($request->get('license_name'));
        $file_name = $file->getClientOriginalName();
        //Move Uploaded File
        $destinationPath = 'uploads';
        $file->move($destinationPath, $file_name);
        $excelData = $excel->toCollection(new ISPLicenseMaster(), 'uploads/' . $file_name);
        try {
            foreach ($excelData as $index => $data) {
                foreach ($data as $key => $d) {
              $this->initVariables($d);
                    if ($key > 0 && !empty($this->sl_no)) {
                        $storeCompany = $this->storeCompanyInfo();
                        //if (!$storeCompany)
                            //return back();
//                        if(CommonFunction::checkExistModuleApplication($this->process_type_id, self::$companyInfo->id) && !env('CHECK_EXIST_APPLICATION')){
//                            continue;
//                        }
                        switch ($this->process_type_id) {
                            case 1:
                                $this->storeIspLicenseInfo($d);
                                break;
                            case 4:
                                $this->storeIspSurrenderLicenseInfo($d);
                                break;
                            case 5:
                                $this->storeBpoLicenseInfo($d);
                                break;
                            case 8:
                                $this->storeBpoSurrenderLicenseInfo($d);
                                break;
                            case 9:
                                $this->storeNixLicenseInfo($d);
                                break;
                            case 13:
                                $this->storeVSATLicenseInfo($d);
                                break;
                            case 17:
                                $this->storeIIGLicenseInfo($d);
                                break;
                            case 21:
                                $this->storeIPTSPLicenseInfo($d);
                                break;
                            case 29:
                                $this->storeVtsLicenseInfo($d);
                                break;
                            case 33:
                                $this->storeICXLicenseInfo($d);
                                break;
                            case 37:
                                $this->storeIGWLicenseInfo($d);
                                break;
                            case 25:
                                $this->storeTvasLicenseInfo($d);
                                break;
                            case 50:
                                $this->storeNttnLicenseInfo($d);
                                break;
                            case 54:
                                $this->storeITCLicenseInfo($d);
                                break;
                            case 58:
                                $this->storeMNOLicenseInfo($d);
                                break;
                            case 62:
                                $this->storeSCSLicenseInfo($d);
                                break;
                            case 66:
                                $this->storeTCLicenseInfo($d);
                                break;
                            case 70:
                                $this->storeMnpLicenseInfo($d);
                                break;
                            case 74:
                                $this->storeBwaLicenseInfo($d);
                                break;
                            case 78:
                                $this->storeSsLicenseInfo($d);
                                break;
                            default:
                                Session::flash('error', 'Please provide valid data.');
                        }
                        DB::commit();
                    }
                }
            }
            return back();

        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            DB::rollBack();
            dd($e->getLine(), $e->getMessage(), $e->getFile());
            Session::flash('error', 'Something went wrong.');
            return back();
        }
    }

    public function storeContactPerson($LicenseIssueDataId, $process_type_id)
    {
        $contactPerson = new ContactPerson();
        $contactPerson->name = $this->contact_person_name;
        $contactPerson->designation = $this->contact_person_designation;
        $contactPerson->app_id = $LicenseIssueDataId;
        $contactPerson->process_type_id = $process_type_id;
        $contactPerson->email = $this->contact_person_email;
        $contactPerson->mobile = $this->contact_person_number;

        $contactPerson->save();
        return $contactPerson;
    }

    public function storeProcessListData($application, $process_type_id, $contact_data, array $additional_data = [])
    {
        $processList = new ProcessList();
        $processList->ref_id = $application->id;
        $processList->company_id = self::$companyInfo->id;
        $processList->office_id = 0;
        $processList->cat_id = 1;
        $processList->tracking_no = '';
        $processList->license_no = $this->license_no;
        $processList->json_object = '';
        $processList->desk_id = 0;
        $processList->user_id = 0;
        $processList->process_type_id = $process_type_id;
        $processList->status_id = 25;
        $processList->read_status = 1;
        $processList->priority = 1;
        $processList->on_behalf_of_user = 0;
        $processList->process_desc = '';
        $processList->closed_by = 0;
        $processList->locked_at = empty($locked_at) ? null : $locked_at;
        $processList->locked_by = 0;
        $processList->submitted_at = Carbon::now();
        $processList->resubmitted_at = empty($resubmitted_at) ? null : $resubmitted_at;
        $processList->completed_date = empty($completed_date) ? null : $completed_date;
        $processList->created_at = Carbon::now();
        $processList->created_by = Auth::user()->id;
        $processList->updated_at = '';
        $processList->updated_by = Auth::user()->id;
        $processList->bulk_status = 1;
        $bulkData['reg_office_address'] = $application->reg_office_address;
        $bulkData['license_no'] = $application->license_no;
        $bulkData['license_issue_date'] = $application->license_issue_date;
        $bulkData['expiry_date'] = $application->expiry_date;
        $bulkData['designation'] = $contact_data->designation;
        $bulkData['name'] = $contact_data->name;
        $bulkData['email'] = $contact_data->email;
        $bulkData['mobile'] = $contact_data->mobile;
        $bulkData['remarks'] = $this->remarks;
        $processList->bulk_object = json_encode($bulkData + $additional_data);
        $jsonData['Applicant Name'] = $contact_data->name;
        $jsonData['Company Name'] = self::$companyInfo->org_nm;
        $jsonData['Email'] = $this->exploadDataValue($contact_data->email);
        $jsonData['Phone'] = $this->exploadDataValue($contact_data->mobile);
        $processList['json_object'] = json_encode($jsonData);
        $processList->save();
        return $processList;
    }

    public function exploadDataValue($data)
    {
        if ((strpos($data, ',') || strpos($data, '&')) === true) {
            if (strpos($data, ',') !== false) {
                $array = explode(',', $data);
                return trim(reset($array));
            }

            if (strpos($data, '&') !== false) {
                $array = explode('&', $data);
                return trim(reset($array));
            }
        } else {
            return $data;
        }
    }


    public function storeCompanyInfo()
    {
        $companyName = (new SignupController())->formatCompanyName($this->org_name);
        $company = CompanyInfo::query()
            ->where('org_nm', 'LIKE', "%$companyName%")
            ->first();
        //if ($company) {
            //Session::flash('error', "The Organization ($this->org_name) already exists.");
            //self::$companyInfo = $company;
            //return false;
        //}
        // First, define a lookup array for company types
        $companyTypes = [
            'Proprietorship' => 1,
            'Partnership' => 2,
            'Private Limited' => 3,
            'Public Limited' => 4,
            'Government institutions' => 5,
            'Autonomous organization' => 6,
            'Educational Institutions' => 7,
        ];

        $this->companyType = trim($this->companyType);
        // Then, use a single query to retrieve all area ids at once
        $areas = DB::table('area_info')
            ->whereIn('area_nm', [$this->division, $this->district, $this->upozila])
            ->whereIn('area_type', [1, 2, 3])
            ->pluck('area_id', 'area_type');

        $companyInfo = CompanyInfo::firstOrNew(
            ['org_nm' => $this->org_name],
//            [
//                'factory_division' => $areas[1] ?? null,
//                'factory_district' => $areas[2] ?? null,
//                'factory_thana' => $areas[3] ?? null,
//                'org_type' => $companyTypes[$this->companyType] ?? null,
//                'company_status' => 1,
//            ]
        );

        $companyInfo->org_nm = $this->org_name;
        $companyInfo->factory_division = $areas[1] ?? null;
        $companyInfo->factory_district = $areas[2] ?? null;
        $companyInfo->factory_thana = $areas[3] ?? null;
        $companyInfo->org_type = $companyTypes[$this->companyType] ?? null;
        $companyInfo->company_status = 1;
        $companyInfo->save();
        self::$companyInfo = $companyInfo;
        return true;
    }

    private function storeIspLicenseInfo($data)
    {
        $isExistLicenseNo = ISPLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        // Define lookup arrays for license types
        $licenseTypes = [
            'Nationwide' => 1,
            'Divisional' => 2,
            'District' => 3,
            'Upazila/Thana' => 4,
        ];

        // Define a lookup array for company types
        $companyTypes = [
            'Proprietorship' => 1,
            'Partnership' => 2,
            'Private Limited' => 3,
            'Public Limited' => 4,
            'Government institutions' => 5,
            'Autonomous organization' => 6,
            'Educational Institutions' => 7,
        ];

//        dd($licenseTypes[$this->licenseType]);
        //check application limit
        if ($licenseTypes[$this->licenseType] === 2) {

            $areaId = DB::table('area_info')
                ->where('area_nm', $this->division)
                ->where('area_type', '=', 1)
                ->value('area_id');

            $totalAppByLicenseType = DB::table('isp_license_issue as apps')
                ->when($licenseTypes[$this->licenseType] == 2, function ($query) use ($areaId) {
                    $query->where('apps.isp_license_division', $areaId);
                    $query->where('apps.isp_license_type', 2);
                })
                ->count();

            $appLimit = DB::table('area_info')
                ->where('area_nm', '=', $this->division)
                ->where('area_type', '=', 1)
                ->value('app_limit');
            if ($totalAppByLicenseType > $appLimit) {
                DB::rollBack();
                Session::flash('error', "According to the BTRC guideline you are not allowed to apply for this category license in this designated area.");
                return back();
            }

        } elseif ($licenseTypes[$this->licenseType] === 3) {
            $areaId = DB::table('area_info')
                ->where('area_nm', $this->district)
                ->where('area_type', '=', 2)
                ->value('area_id');

            $totalAppByLicenseType = DB::table('isp_license_issue as apps')
                ->when($licenseTypes[$this->licenseType] == 3, function ($query) use ($areaId) {
                    $query->where('apps.isp_license_district', $areaId);
                    $query->where('apps.isp_license_type', 3);
                })
                ->count();

            $appLimit = DB::table('area_info')
                ->where('area_nm', '=', $this->district)
                ->where('area_type', '=', 2)
                ->value('app_limit');

            if ($totalAppByLicenseType > $appLimit) {
                DB::rollBack();
                Session::flash('error', "According to the BTRC guideline you are not allowed to apply for this category license in this designated area.");
                return back();
            }
        } elseif ($licenseTypes[$this->licenseType] === 4) {
            $areaId = DB::table('area_info')
                ->where('area_nm', $this->upozila)
                ->where('area_type', '=', 3)
                ->value('area_id');

            $totalAppByLicenseType = DB::table('isp_license_issue as apps')
                ->when($licenseTypes[$this->licenseType] == 4, function ($query) use ($areaId) {
                    $query->where('apps.isp_license_upazila', $areaId);
                    $query->where('apps.isp_license_type', 4);
                })
                ->count();

            $appLimit = DB::table('area_info')
                ->where('area_nm', '=', $this->upozila)
                ->where('area_type', '=', 3)
                ->value('app_limit');
//            dd($licenseTypes[$this->licenseType], $areaId,$totalAppByLicenseType, $appLimit);
            if ($totalAppByLicenseType > $appLimit) {
                DB::rollBack();
                Session::flash('error', "According to the BTRC guideline you are not allowed to apply for this category license in this designated area.");
                return back();
            }
        }

        // Get the license type ID
        $licenseType = $licenseTypes[$this->licenseType] ?? null;
        $this->calculateIssueAndExpireDate();
        $this->getDivisionDistrictUpazilaID();
        $ispLicenseIssue = new ISPLicenseIssue();
        $ispLicenseIssue->org_nm = $this->org_name;
        $ispLicenseIssue->org_type = $companyTypes[$this->companyType] ?? null;

        $ispLicenseIssue->isp_license_type = $licenseType;
        $ispLicenseIssue->reg_office_address = $this->reg_office_address;
        $ispLicenseIssue->license_no = $this->license_no;
        $ispLicenseIssue->license_issue_date = $this->issue_date;
        $ispLicenseIssue->expiry_date = $this->expire_date;
        $ispLicenseIssue->company_id = self::$companyInfo->id;
        $ispLicenseIssue->isp_license_division = $this->divisionID;
        $ispLicenseIssue->isp_license_district = $this->districtID;
        $ispLicenseIssue->isp_license_upazila = $this->upazilaID;
        $ispLicenseIssue->status = 1;
        $ispLicenseIssue->bulk_status = 1;
        $ispLicenseIssue->save();
        // store contact person data
        $contactPersonData = $this->storeContactPerson($ispLicenseIssue->id, $this->process_type_id);
        //store process list data
        $processList = $this->storeProcessListData($ispLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'ISP-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $ispLicenseIssue->id, 'isp_license_issue');
        CommonFunction::generateUniqueTrackingNumber('ISP',$this->process_type_id, $processList->id,'isp_license_issue','ISS', $ispLicenseIssue->id);
        // Get the license type ID
//        $licenseType = $licenseTypes[$this->licenseType] ?? null;
        $ispLicenseMaster = new ISPLicenseMaster();
        $ispLicenseMaster->license_no = $this->license_no;
        $ispLicenseMaster->license_issue_date = $this->issue_date;
        $ispLicenseMaster->isp_license_type = $licenseType;
        $ispLicenseMaster->isp_license_division = $this->divisionID;
        $ispLicenseMaster->isp_license_district = $this->districtID;
        $ispLicenseMaster->isp_license_upazila = $this->upazilaID;
        $ispLicenseMaster->expiry_date = $this->expire_date;
        $ispLicenseMaster->company_id = self::$companyInfo->id;
        $ispLicenseMaster->issue_tracking_no = !empty(ISPLicenseIssue::find($ispLicenseIssue->id)) ? ISPLicenseIssue::find($ispLicenseIssue->id)->tracking_no : null;
        $ispLicenseMaster->status = 1;
        $ispLicenseMaster->save();
        Session::flash('success', 'Bulk data migrated successfully.');

    }

    private function storeIspSurrenderLicenseInfo($data)
    {

        $isExistLicenseNo = ISPLicenseIssue::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo == 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no is not exists in ISP issue service so can't be uploaded.");
            return back();
        }
            // Define lookup arrays for license types
            $licenseTypes = [
                'Nationwide' => 1,
                'Divisional' => 2,
                'District' => 3,
                'Upazila/Thana' => 4,
            ];
            // Define a lookup array for company types
            $companyTypes = [
                'Proprietorship' => 1,
                'Partnership' => 2,
                'Private Limited' => 3,
                'Public Limited' => 4,
                'Government institutions' => 5,
                'Autonomous organization' => 6,
                'Educational Institutions' => 7,
            ];

            // Get the license type ID
            $licenseType = $licenseTypes[$this->licenseType] ?? null;
            $this->calculateIssueAndExpireDate();
            $this->getDivisionDistrictUpazilaID();
        $isExist = ISPLicenseSurrender::where('license_no', $this->license_no)->count();
        if ($isExist == 0) {
            $ispLicenseSurrender = new ISPLicenseSurrender();
            $ispLicenseSurrender->org_nm = $this->org_name;
            $ispLicenseSurrender->org_type = $companyTypes[$this->companyType] ?? null;

            $ispLicenseSurrender->isp_license_type = $licenseType;
            $ispLicenseSurrender->reg_office_address = $this->reg_office_address;
            $ispLicenseSurrender->license_no = $this->license_no;
            $ispLicenseSurrender->license_issue_date = $this->issue_date;
            $ispLicenseSurrender->expiry_date = $this->expire_date;
            $ispLicenseSurrender->company_id = self::$companyInfo->id;
            $ispLicenseSurrender->isp_license_division = $this->divisionID;
            $ispLicenseSurrender->isp_license_district = $this->districtID;
            $ispLicenseSurrender->isp_license_upazila = $this->upazilaID;
            $ispLicenseSurrender->status = 1;
            $ispLicenseSurrender->bulk_status = 1;
            $ispLicenseSurrender->save();

            // store contact person data
            $contactPersonData = $this->storeContactPerson($ispLicenseSurrender->id, $this->process_type_id);
            //store process list data
            $processList = $this->storeProcessListData($ispLicenseSurrender, $this->process_type_id, $contactPersonData);
            //generate tracking no
//        $trackingPrefix = 'ISP-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $ispLicenseIssue->id, 'isp_license_issue');
            CommonFunction::generateUniqueTrackingNumber('ISP',$this->process_type_id, $processList->id,'isp_license_surrender','SUR', $ispLicenseSurrender->id);
            // Reload the model to get the updated tracking_no
            $ispLicenseSurrender->refresh();
            $masterData = ISPLicenseMaster::where('license_no', $ispLicenseSurrender->license_no)->count();
            if($masterData == 0){
            $ispLicenseMaster = new ISPLicenseMaster();
            $ispLicenseMaster->license_no = $this->license_no;
            $ispLicenseMaster->license_issue_date = $this->issue_date;
            $ispLicenseMaster->expiry_date = $this->expire_date;
            $ispLicenseMaster->company_id = self::$companyInfo->id;
            $ispLicenseMaster->cancellation_tracking_no = !empty(ISPLicenseSurrender::find($ispLicenseSurrender->id)) ? ISPLicenseSurrender::find($ispLicenseSurrender->id)->tracking_no : null;
            $ispLicenseMaster->status = 1;
            $ispLicenseMaster->save();
            }

            ProcessList::where('license_no', $ispLicenseSurrender->license_no)->where('process_type_id',1)->update([
                'status_id' => -2,
            ]);

            ISPLicenseMaster::where('license_no', $ispLicenseSurrender->license_no)->update([
                'cancellation_tracking_no' => $ispLicenseSurrender->tracking_no,
                'isp_license_type' => null,
                'isp_license_division' => null,
                'isp_license_district' => null,
                'isp_license_upazila' => null,
                'status' => 0,
            ]);
            Session::flash('success', 'Bulk data migrated successfully.');
        }else{
            Session::flash('error', "License number $this->license_no is exists in ISP surrender service so can't be uploaded.");
            return back();
        }


    }

    private function storeTvasLicenseInfo($data)
    {
        $isExistLicenseNo = TVASLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $tvasLicenseIssue = new TVASLicenseIssue();
        $tvasLicenseIssue->reg_office_address = $this->reg_office_address;
        $tvasLicenseIssue->license_no = $this->license_no;
        $tvasLicenseIssue->license_issue_date = $this->issue_date;
        $tvasLicenseIssue->expiry_date = $this->expire_date;
        $tvasLicenseIssue->company_id = self::$companyInfo->id;
        $tvasLicenseIssue->company_name = self::$companyInfo->org_nm ?? null;
        $tvasLicenseIssue->company_type = self::$companyInfo->org_type ?? null;
        $tvasLicenseIssue->status = 1;
        $tvasLicenseIssue->bulk_status = 1;
        $tvasLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($tvasLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($tvasLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'TVAS-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $tvasLicenseIssue->id, 'tvas_license_issue');
        CommonFunction::generateUniqueTrackingNumber('TVAS',$this->process_type_id, $processList->id,'tvas_license_issue','ISS', $tvasLicenseIssue->id);
        // store master data
        $tvasLicenseMaster = new TVASLicenseMaster();
        $tvasLicenseMaster->license_no = $this->license_no;
        $tvasLicenseMaster->license_issue_date = $this->issue_date;
        $tvasLicenseMaster->expiry_date = $this->expire_date;
        $tvasLicenseMaster->company_id = self::$companyInfo->id;
        $tvasLicenseMaster->issue_tracking_no = !empty(TVASLicenseIssue::find($tvasLicenseIssue->id)) ? TVASLicenseIssue::find($tvasLicenseIssue->id)->tracking_no : null;
        $tvasLicenseMaster->status = 1;
        $tvasLicenseMaster->save();
    }

    private function storeBpoLicenseInfo($data)
    {

        $isExistLicenseNo = CallCenterMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $bpoLicenseIssue = new CallCenterNew();
        $bpoLicenseIssue->reg_office_address = $this->reg_office_address;
        $bpoLicenseIssue->license_no = $this->license_no;
        $bpoLicenseIssue->license_issue_date = $this->issue_date;
        $bpoLicenseIssue->expiry_date = $this->expire_date;
        $bpoLicenseIssue->company_id = self::$companyInfo->id;
        $bpoLicenseIssue->company_name = self::$companyInfo->org_nm ?? null;
        $bpoLicenseIssue->company_type = self::$companyInfo->org_type ?? null;

        $bpoLicenseIssue->status = 1;
        $bpoLicenseIssue->bulk_status = 1;
        $bpoLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($bpoLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($bpoLicenseIssue, $this->process_type_id, $contactPersonData, [
            'registration_type' => $data[18]
        ]);
        //generate tracking no
//        $trackingPrefix = 'BPO-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $bpoLicenseIssue->id, 'call_center_issue');
        CommonFunction::generateUniqueTrackingNumber('BPO',$this->process_type_id, $processList->id,'call_center_issue','ISS', $bpoLicenseIssue->id);
        // store master data
        $bpoLicenseMaster = new CallCenterMaster();
        $bpoLicenseMaster->license_no = $this->license_no;
        $bpoLicenseMaster->license_issue_date = $this->issue_date;
        $bpoLicenseMaster->expiry_date = $this->expire_date;
        $bpoLicenseMaster->company_id = self::$companyInfo->id;
        $bpoLicenseMaster->issue_tracking_no = !empty(CallCenterNew::find($bpoLicenseIssue->id)) ? CallCenterNew::find($bpoLicenseIssue->id)->tracking_no : null;
        $bpoLicenseMaster->status = 1;
        $bpoLicenseMaster->save();
    }
    private function storeBpoSurrenderLicenseInfo($data)
    {
        $isExistLicenseNo = CallCenterNew::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo == 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no is not exists in Call Center Issue service so can't be uploaded.");
            return back();
        }

        $this->calculateIssueAndExpireDate();

        $isExist = CallCenterSurrender::where('license_no', $this->license_no)->count();
        if ($isExist == 0) {
            $bpoLicenseSurrender = new CallCenterSurrender();
            $bpoLicenseSurrender->reg_office_address = $this->reg_office_address;
            $bpoLicenseSurrender->license_no = $this->license_no;
            $bpoLicenseSurrender->license_issue_date = $this->issue_date;
            $bpoLicenseSurrender->expiry_date = $this->expire_date;
            $bpoLicenseSurrender->company_id = self::$companyInfo->id;
            $bpoLicenseSurrender->company_name = self::$companyInfo->org_nm ?? null;
            $bpoLicenseSurrender->company_type = self::$companyInfo->org_type ?? null;

            $bpoLicenseSurrender->status = 1;
            $bpoLicenseSurrender->bulk_status = 1;
            $bpoLicenseSurrender->save();
            // store contact information
            $contactPersonData = $this->storeContactPerson($bpoLicenseSurrender->id, $this->process_type_id);
            // store process list information
            $processList = $this->storeProcessListData($bpoLicenseSurrender, $this->process_type_id, $contactPersonData, [
                'registration_type' => $data[18]
            ]);
            //generate tracking no
//        $trackingPrefix = 'BPO-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $bpoLicenseSurrender->id, 'call_center_issue');
            CommonFunction::generateUniqueTrackingNumber('BPO', $this->process_type_id, $processList->id, 'call_center_surrender', 'SUR', $bpoLicenseSurrender->id);
            // store master data
            $bpoLicenseMaster = new CallCenterMaster();
            $bpoLicenseMaster->license_no = $this->license_no;
            $bpoLicenseMaster->license_issue_date = $this->issue_date;
            $bpoLicenseMaster->expiry_date = $this->expire_date;
            $bpoLicenseMaster->company_id = self::$companyInfo->id;
            $bpoLicenseMaster->issue_tracking_no = !empty(CallCenterSurrender::find($bpoLicenseSurrender->id)) ? CallCenterSurrender::find($bpoLicenseSurrender->id)->tracking_no : null;
            $bpoLicenseMaster->status = 1;
            $bpoLicenseMaster->save();
            Session::flash('success', 'Bulk data migrated successfully.');
        }else{
            Session::flash('error', "License number $this->license_no is exists in Call center surrender service so can't be uploaded.");
            return back();
        }
    }

    private function storeNttnLicenseInfo($data)
    {
        $isExistLicenseNo = NTTNLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $nttnLicenseIssue = new NTTNLicenseIssue();
        $nttnLicenseIssue->reg_office_address = $this->reg_office_address;
        $nttnLicenseIssue->license_no = $this->license_no;
        $nttnLicenseIssue->license_issue_date = $this->issue_date;
        $nttnLicenseIssue->expiry_date = $this->expire_date;
        $nttnLicenseIssue->company_id = self::$companyInfo->id;
        $nttnLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $nttnLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $nttnLicenseIssue->status = 1;
        $nttnLicenseIssue->bulk_status = 1;
        $nttnLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($nttnLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($nttnLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'NTTN-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $nttnLicenseIssue->id, 'call_center_issue');
        CommonFunction::generateUniqueTrackingNumber('NTTN',$this->process_type_id, $processList->id,'nttn_license_issue','ISS', $nttnLicenseIssue->id);
        // store master data
        $nttnLicenseMaster = new NTTNLicenseMaster();
        $nttnLicenseMaster->license_no = $this->license_no;
        $nttnLicenseMaster->license_issue_date = $this->issue_date;
        $nttnLicenseMaster->expiry_date = $this->expire_date;
        $nttnLicenseMaster->company_id = self::$companyInfo->id;
        $nttnLicenseMaster->issue_tracking_no = !empty(NTTNLicenseIssue::find($nttnLicenseIssue->id)) ? NTTNLicenseIssue::find($nttnLicenseIssue->id)->tracking_no : null;
        $nttnLicenseMaster->status = 1;
        $nttnLicenseMaster->save();
    }

    private function storeVtsLicenseInfo($data)
    {
        $isExistLicenseNo = VTSLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $vtsLicenseIssue = new VTSLicenseIssue();
        $vtsLicenseIssue->reg_office_address = $this->reg_office_address;
        $vtsLicenseIssue->license_no = $this->license_no;
        $vtsLicenseIssue->license_issue_date = $this->issue_date;
        $vtsLicenseIssue->expiry_date = $this->expire_date;
        $vtsLicenseIssue->company_id = self::$companyInfo->id;
        $vtsLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $vtsLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $vtsLicenseIssue->status = 1;
        $vtsLicenseIssue->bulk_status = 1;
        $vtsLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($vtsLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($vtsLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'VTS-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $vtsLicenseIssue->id, 'call_center_issue');
        CommonFunction::generateUniqueTrackingNumber('VTS',$this->process_type_id, $processList->id,'vts_license_issue','ISS', $vtsLicenseIssue->id);
        // store master data
        $vtsLicenseMaster = new VTSLicenseMaster();
        $vtsLicenseMaster->license_no = $this->license_no;
        $vtsLicenseMaster->license_issue_date = $this->issue_date;
        $vtsLicenseMaster->expiry_date = $this->expire_date;
        $vtsLicenseMaster->company_id = self::$companyInfo->id;
        $vtsLicenseMaster->issue_tracking_no = !empty(VTSLicenseIssue::find($vtsLicenseIssue->id)) ? VTSLicenseIssue::find($vtsLicenseIssue->id)->tracking_no : null;
        $vtsLicenseMaster->status = 1;
        $vtsLicenseMaster->save();
    }

    private function storeIPTSPLicenseInfo($data)
    {
        $isExistLicenseNo = IPTSPLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $iptspLicenseIssue = new IPTSPLicenseIssue();
        $iptspLicenseIssue->reg_office_address = $this->reg_office_address;
        $iptspLicenseIssue->license_no = $this->license_no;
        $iptspLicenseIssue->license_issue_date = $this->issue_date;
        $iptspLicenseIssue->expiry_date = $this->expire_date;
        $iptspLicenseIssue->company_id = self::$companyInfo->id;
        $iptspLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $iptspLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $iptspLicenseIssue->status = 1;
        $iptspLicenseIssue->bulk_status = 1;
        $iptspLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($iptspLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($iptspLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);

        //generate tracking no
//        $trackingPrefix = 'IPTSP-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $iptspLicenseIssue->id, 'iptsp_license_issue');
        CommonFunction::generateUniqueTrackingNumber('IPTSP',$this->process_type_id, $processList->id,'iptsp_license_issue','ISS', $iptspLicenseIssue->id);
        // store master data
        $iptstLicenseMaster = new IPTSPLicenseMaster();
        $iptstLicenseMaster->license_no = $this->license_no;
        $iptstLicenseMaster->license_issue_date = $this->issue_date;
        $iptstLicenseMaster->expiry_date = $this->expire_date;
        $iptstLicenseMaster->company_id = self::$companyInfo->id;
        $iptstLicenseMaster->issue_tracking_no = !empty(IPTSPLicenseIssue::find($iptspLicenseIssue->id)) ? IPTSPLicenseIssue::find($iptspLicenseIssue->id)->tracking_no : null;
        $iptstLicenseMaster->status = 1;
        $iptstLicenseMaster->save();
    }

    private function storeIIGLicenseInfo($data)
    {
        $isExistLicenseNo = IIGLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $IIGLicenseIssue = new IIGLicenseIssue();
        $IIGLicenseIssue->reg_office_address = $this->reg_office_address;
        $IIGLicenseIssue->license_no = $this->license_no;
        $IIGLicenseIssue->license_issue_date = $this->issue_date;
        $IIGLicenseIssue->expiry_date = $this->expire_date;
        $IIGLicenseIssue->company_id = self::$companyInfo->id;
        $IIGLicenseIssue->company_name = self::$companyInfo->org_nm ?? null;
        $IIGLicenseIssue->company_type = self::$companyInfo->org_type ?? null;
        $IIGLicenseIssue->status = 1;
        $IIGLicenseIssue->bulk_status = 1;
        $IIGLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($IIGLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($IIGLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
//        $trackingPrefix = 'IIG-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $IIGLicenseIssue->id, 'iig_license_issue');
        CommonFunction::generateUniqueTrackingNumber('IIG',$this->process_type_id, $processList->id,'iig_license_issue','ISS', $IIGLicenseIssue->id);
        // store master data
        $iigLicenseMaster = new IIGLicenseMaster();
        $iigLicenseMaster->license_no = $this->license_no;
        $iigLicenseMaster->license_issue_date = $this->issue_date;
        $iigLicenseMaster->expiry_date = $this->expire_date;
        $iigLicenseMaster->company_id = self::$companyInfo->id;
        $iigLicenseMaster->issue_tracking_no = !empty(IIGLicenseIssue::find($IIGLicenseIssue->id)) ? IIGLicenseIssue::find($IIGLicenseIssue->id)->tracking_no : null;
        $iigLicenseMaster->status = 1;
        $iigLicenseMaster->save();
    }

    private function storeICXLicenseInfo($data)
    {
        $isExistLicenseNo = ICXLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $ICXLicenseIssue = new ICXLicenseIssue();
        $ICXLicenseIssue->reg_office_address = $this->reg_office_address;
        $ICXLicenseIssue->license_no = $this->license_no;
        $ICXLicenseIssue->license_issue_date = $this->issue_date;
        $ICXLicenseIssue->expiry_date = $this->expire_date;
        $ICXLicenseIssue->company_id = self::$companyInfo->id;
        $ICXLicenseIssue->company_org_name = self::$companyInfo->org_nm ?? null;
        $ICXLicenseIssue->company_type = self::$companyInfo->org_type ?? null;
        $ICXLicenseIssue->status = 1;
        $ICXLicenseIssue->bulk_status = 1;
        $ICXLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($ICXLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($ICXLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
        //$trackingPrefix = 'ICX-' . date('Ymd') . '-';
        //CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $ICXLicenseIssue->id, 'icx_license_issue');
        CommonFunction::generateUniqueTrackingNumber('ICX',$this->process_type_id, $processList->id,'icx_license_issue','ISS', $ICXLicenseIssue->id);
        // store master data
        $ICXLicenseMaster = new ICXLicenseMaster();
        $ICXLicenseMaster->license_no = $this->license_no;
        $ICXLicenseMaster->license_issue_date = $this->issue_date;
        $ICXLicenseMaster->expiry_date = $this->expire_date;
        $ICXLicenseMaster->company_id = self::$companyInfo->id;
        $ICXLicenseMaster->issue_tracking_no = !empty(ICXLicenseIssue::find($ICXLicenseIssue->id)) ? ICXLicenseIssue::find($ICXLicenseIssue->id)->tracking_no : null;
        $ICXLicenseMaster->status = 1;
        $ICXLicenseMaster->save();
    }

    private function storeVSATLicenseInfo($data)
    {
        $isExistLicenseNo = VSATLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $VSATLicenseIssue = new VSATLicenseIssue();
        $VSATLicenseIssue->org_address = $this->reg_office_address;
        $VSATLicenseIssue->license_no = $this->license_no;
        $VSATLicenseIssue->license_issue_date = $this->issue_date;
        $VSATLicenseIssue->expiry_date = $this->expire_date;
        $VSATLicenseIssue->company_id = self::$companyInfo->id;
        $VSATLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $VSATLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $VSATLicenseIssue->status = 1;
        $VSATLicenseIssue->bulk_status = 1;
        $VSATLicenseIssue->save();

        $VSATLicenseIssue->reg_office_address = $VSATLicenseIssue->org_address;

        // store contact information
        $contactPersonData = $this->storeContactPerson($VSATLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($VSATLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
//        $trackingPrefix = 'VSAT-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $VSATLicenseIssue->id, 'vsat_license_issue');
        CommonFunction::generateUniqueTrackingNumber('VSAT',$this->process_type_id, $processList->id,'vsat_license_issue','ISS', $VSATLicenseIssue->id);
        // store master data
        $VSATLicenseMaster = new VSATLicenseMaster();
        $VSATLicenseMaster->license_no = $this->license_no;
        $VSATLicenseMaster->license_issue_date = $this->issue_date;
        $VSATLicenseMaster->expiry_date = $this->expire_date;
        $VSATLicenseMaster->company_id = self::$companyInfo->id;
        $VSATLicenseMaster->issue_tracking_no = !empty(VSATLicenseIssue::find($VSATLicenseIssue->id)) ? VSATLicenseIssue::find($VSATLicenseIssue->id)->tracking_no : null;
        $VSATLicenseMaster->status = 1;
        $VSATLicenseMaster->save();
    }

    private function storeIGWLicenseInfo($data)
    {
        $isExistLicenseNo = IGWLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $IGWLicenseIssue = new IGWLicenseIssue();
        $IGWLicenseIssue->reg_office_address = $this->reg_office_address;
        $IGWLicenseIssue->license_no = $this->license_no;
        $IGWLicenseIssue->license_issue_date = $this->issue_date;
        $IGWLicenseIssue->expiry_date = $this->expire_date;
        $IGWLicenseIssue->company_id = self::$companyInfo->id;
        $IGWLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $IGWLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $IGWLicenseIssue->status = 1;
        $IGWLicenseIssue->bulk_status = 1;
        $IGWLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($IGWLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($IGWLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
//        $trackingPrefix = 'IGW-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $IGWLicenseIssue->id, 'igw_license_issue');
        CommonFunction::generateUniqueTrackingNumber('IGW',$this->process_type_id, $processList->id,'igw_license_issue','ISS', $IGWLicenseIssue->id);
        // store master data
        $IGWLicenseMaster = new IGWLicenseMaster();
        $IGWLicenseMaster->license_no = $this->license_no;
        $IGWLicenseMaster->license_issue_date = $this->issue_date;
        $IGWLicenseMaster->expiry_date = $this->expire_date;
        $IGWLicenseMaster->company_id = self::$companyInfo->id;
        $IGWLicenseMaster->issue_tracking_no = !empty(IGWLicenseIssue::find($IGWLicenseIssue->id)) ? IGWLicenseIssue::find($IGWLicenseIssue->id)->tracking_no : null;
        $IGWLicenseMaster->status = 1;
        $IGWLicenseMaster->save();
    }

    private function storeSCSLicenseInfo($data)
    {
        $isExistLicenseNo = SCSLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $SCSLicenseIssue = new SCSLicenseIssue();
        $SCSLicenseIssue->reg_office_address = $this->reg_office_address;
        $SCSLicenseIssue->license_no = $this->license_no;
        $SCSLicenseIssue->license_issue_date = $this->issue_date;
        $SCSLicenseIssue->expiry_date = $this->expire_date;
        $SCSLicenseIssue->company_id = self::$companyInfo->id;
        $SCSLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $SCSLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $SCSLicenseIssue->status = 1;
        $SCSLicenseIssue->bulk_status = 1;
        $SCSLicenseIssue->save();

        // store contact information
        $contactPersonData = $this->storeContactPerson($SCSLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($SCSLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
        $trackingPrefix = 'SCS-' . date('Ymd') . '-';
        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $SCSLicenseIssue->id, 'scs_license_issue');
        // store master data
        $IGWLicenseMaster = new SCSLicenseMaster();
        $IGWLicenseMaster->license_no = $this->license_no;
        $IGWLicenseMaster->license_issue_date = $this->issue_date;
        $IGWLicenseMaster->expiry_date = $this->expire_date;
        $IGWLicenseMaster->company_id = self::$companyInfo->id;
        $IGWLicenseMaster->issue_tracking_no = !empty(SCSLicenseIssue::find($SCSLicenseIssue->id)) ? SCSLicenseIssue::find($SCSLicenseIssue->id)->tracking_no : null;
        $IGWLicenseMaster->status = 1;
        $IGWLicenseMaster->save();
    }

    private function storeMNOLicenseInfo($data)
    {
        $isExistLicenseNo = MNOLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $MNOLicenseIssue = new MNOLicenseIssue();
        $MNOLicenseIssue->reg_office_address = $this->reg_office_address;
        $MNOLicenseIssue->license_no = $this->license_no;
        $MNOLicenseIssue->license_issue_date = $this->issue_date;
        $MNOLicenseIssue->expiry_date = $this->expire_date;
        $MNOLicenseIssue->company_id = self::$companyInfo->id;
        $MNOLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $MNOLicenseIssue->org_type = self::$companyInfo->org_type ?? null;
        $MNOLicenseIssue->status = 1;
        $MNOLicenseIssue->bulk_status = 1;
        $MNOLicenseIssue->save();

        // store contact information
        $contactPersonData = $this->storeContactPerson($MNOLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($MNOLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
//        $trackingPrefix = 'MNO-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $MNOLicenseIssue->id, 'mno_license_issue');
        CommonFunction::generateUniqueTrackingNumber('MNO',$this->process_type_id, $processList->id,'mno_license_issue','ISS', $MNOLicenseIssue->id);
        // store master data
        $MONLicenseMaster = new MNOLicenseMaster();
        $MONLicenseMaster->license_no = $this->license_no;
        $MONLicenseMaster->license_issue_date = $this->issue_date;
        $MONLicenseMaster->expiry_date = $this->expire_date;
        $MONLicenseMaster->company_id = self::$companyInfo->id;
        $MONLicenseMaster->issue_tracking_no = !empty(MNOLicenseIssue::find($MNOLicenseIssue->id)) ? MNOLicenseIssue::find($MNOLicenseIssue->id)->tracking_no : null;
        $MONLicenseMaster->status = 1;
        $MONLicenseMaster->save();
    }

    private function storeITCLicenseInfo($data)
    {
        $isExistLicenseNo = ITCLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $ITCLicenseIssue = new ITCLicenseIssue();
        $ITCLicenseIssue->reg_office_address = $this->reg_office_address;
        $ITCLicenseIssue->license_no = $this->license_no;
        $ITCLicenseIssue->license_issue_date = $this->issue_date;
        $ITCLicenseIssue->expiry_date = $this->expire_date;
        $ITCLicenseIssue->company_id = self::$companyInfo->id;
        $ITCLicenseIssue->company_name = self::$companyInfo->org_nm ?? null;
        $ITCLicenseIssue->company_type = self::$companyInfo->org_type ?? null;
        $ITCLicenseIssue->status = 1;
        $ITCLicenseIssue->bulk_status = 1;
        $ITCLicenseIssue->save();

        // store contact information
        $contactPersonData = $this->storeContactPerson($ITCLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($ITCLicenseIssue, $this->process_type_id, $contactPersonData, [
            'remarks' => $data[17]
        ]);
        //generate tracking no
//        $trackingPrefix = 'ITC-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $ITCLicenseIssue->id, 'itc_license_issue');
        CommonFunction::generateUniqueTrackingNumber('ITC',$this->process_type_id, $processList->id,'itc_license_issue','ISS', $ITCLicenseIssue->id);
        // store master data
        $ITCLicenseMaster = new ITCLicenseMaster();
        $ITCLicenseMaster->license_no = $this->license_no;
        $ITCLicenseMaster->license_issue_date = $this->issue_date;
        $ITCLicenseMaster->expiry_date = $this->expire_date;
        $ITCLicenseMaster->company_id = self::$companyInfo->id;
        $ITCLicenseMaster->issue_tracking_no = !empty(ITCLicenseIssue::find($ITCLicenseIssue->id)) ? ITCLicenseIssue::find($ITCLicenseIssue->id)->tracking_no : null;
        $ITCLicenseMaster->status = 1;
        $ITCLicenseMaster->save();
    }

    private function calculateIssueAndExpireDate()
    {
        // Calculate issue and expiration dates
        $baseDate = strtotime('January 1, 1900');
        $this->issue_date = date('Y-m-d', $baseDate + ($this->issue_date - 2) * 86400);
        $this->expire_date = date('Y-m-d', $baseDate + ($this->expire_date - 2) * 86400);
    }

    private function getDivisionDistrictUpazilaID()
    {
        $this->divisionID = DB::table('area_info')
            ->where('area_nm', $this->division)
            ->where('area_type', '=', 1)
            ->value('area_id');
        $this->districtID = DB::table('area_info')
            ->where('area_nm', $this->district)
            ->where('area_type', '=', 2)
            ->value('area_id');
        $this->upazilaID = DB::table('area_info')
            ->where([['area_nm', $this->upozila], ['pare_id', $this->districtID]])
            ->where('area_type', '=', 3)
            ->value('area_id');
    }

    private function storeTCLicenseInfo($data)
    {
        $isExistLicenseNo = TCLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $tcLicenseIssue = new TCLicenseIssue();
        $tcLicenseIssue->reg_office_address = $this->reg_office_address;
        $tcLicenseIssue->license_no = $this->license_no;
        $tcLicenseIssue->license_issue_date = $this->issue_date;
        $tcLicenseIssue->expiry_date = $this->expire_date;
        $tcLicenseIssue->company_id = self::$companyInfo->id;
        $tcLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $tcLicenseIssue->org_type = self::$companyInfo->org_type ?? null;


        $tcLicenseIssue->status = 1;
        $tcLicenseIssue->bulk_status = 1;
        $tcLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($tcLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($tcLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'TC-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $tcLicenseIssue->id,'tc_license_issue');
        CommonFunction::generateUniqueTrackingNumber('TC',$this->process_type_id, $processList->id,'tc_license_issue','ISS', $tcLicenseIssue->id);
        // store master data
        $tcLicenseMaster = new TCLicenseMaster();
        $tcLicenseMaster->license_no = $this->license_no;
        $tcLicenseMaster->license_issue_date = $this->issue_date;
        $tcLicenseMaster->expiry_date = $this->expire_date;
        $tcLicenseMaster->company_id = self::$companyInfo->id;
        $tcLicenseMaster->issue_tracking_no = !empty(TCLicenseIssue::find($tcLicenseIssue->id)) ? TCLicenseIssue::find($tcLicenseIssue->id)->tracking_no : null;
        $tcLicenseMaster->status = 1;
        $tcLicenseMaster->save();
    }

    private function storeNixLicenseInfo($data)
    {
        $isExistLicenseNo = NIXLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $nixLicenseIssue = new NIXLicenseIssue();
        $nixLicenseIssue->reg_office_address = $this->reg_office_address;
        $nixLicenseIssue->license_no = $this->license_no;
        $nixLicenseIssue->license_issue_date = $this->issue_date;
        $nixLicenseIssue->expiry_date = $this->expire_date;
        $nixLicenseIssue->company_id = self::$companyInfo->id;
        $nixLicenseIssue->company_name = self::$companyInfo->org_nm ?? null;
        $nixLicenseIssue->company_type = self::$companyInfo->org_type ?? null;


        $nixLicenseIssue->status = 1;
        $nixLicenseIssue->bulk_status = 1;
        $nixLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($nixLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($nixLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'NIX-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $nixLicenseIssue->id,'nix_license_issue');
        CommonFunction::generateUniqueTrackingNumber('NIX',$this->process_type_id, $processList->id,'nix_license_issue','ISS', $nixLicenseIssue->id);
        // store master data
        $nixLicenseMaster = new NIXLicenseMaster();
        $nixLicenseMaster->license_no = $this->license_no;
        $nixLicenseMaster->license_issue_date = $this->issue_date;
        $nixLicenseMaster->expiry_date = $this->expire_date;
        $nixLicenseMaster->company_id = self::$companyInfo->id;
        $nixLicenseMaster->issue_tracking_no = !empty(NIXLicenseIssue::find($nixLicenseIssue->id)) ? NIXLicenseIssue::find($nixLicenseIssue->id)->tracking_no : null;
        $nixLicenseMaster->status = 1;
        $nixLicenseMaster->save();
    }

    private function storeMnpLicenseInfo($data)
    {

        $isExistLicenseNo = MNPLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $mnpLicenseIssue = new MNPLicenseIssue();
        $mnpLicenseIssue->reg_office_address = $this->reg_office_address;
        $mnpLicenseIssue->license_no = $this->license_no;
        $mnpLicenseIssue->license_issue_date = $this->issue_date;
        $mnpLicenseIssue->expiry_date = $this->expire_date;
        $mnpLicenseIssue->company_id = self::$companyInfo->id;
        $mnpLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $mnpLicenseIssue->org_type = self::$companyInfo->org_type ?? null;


        $mnpLicenseIssue->status = 1;
        $mnpLicenseIssue->bulk_status = 1;
        $mnpLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($mnpLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($mnpLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'MNP-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $mnpLicenseIssue->id,'mnp_license_issue');
        CommonFunction::generateUniqueTrackingNumber('MNP',$this->process_type_id, $processList->id,'mnp_license_issue','ISS', $mnpLicenseIssue->id);
        // store master data
        $mnpLicenseMaster = new MNPLicenseMaster();
        $mnpLicenseMaster->license_no = $this->license_no;
        $mnpLicenseMaster->license_issue_date = $this->issue_date;
        $mnpLicenseMaster->expiry_date = $this->expire_date;
        $mnpLicenseMaster->company_id = self::$companyInfo->id;
        $mnpLicenseMaster->issue_tracking_no = !empty(MNPLicenseIssue::find($mnpLicenseIssue->id)) ? MNPLicenseIssue::find($mnpLicenseIssue->id)->tracking_no : null;
        $mnpLicenseMaster->status = 1;
        $mnpLicenseMaster->save();
    }

    private function storeBwaLicenseInfo($data)
    {

        $isExistLicenseNo = BWALicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $bwaLicenseIssue = new BWALicenseIssue();
        $bwaLicenseIssue->reg_office_address = $this->reg_office_address;
        $bwaLicenseIssue->license_no = $this->license_no;
        $bwaLicenseIssue->license_issue_date = $this->issue_date;
        $bwaLicenseIssue->expiry_date = $this->expire_date;
        $bwaLicenseIssue->company_id = self::$companyInfo->id;
        $bwaLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $bwaLicenseIssue->org_type = self::$companyInfo->org_type ?? null;


        $bwaLicenseIssue->status = 1;
        $bwaLicenseIssue->bulk_status = 1;
        $bwaLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($bwaLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($bwaLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'BWA-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $bwaLicenseIssue->id,'bwa_license_issue');
        CommonFunction::generateUniqueTrackingNumber('PSTN',$this->process_type_id, $processList->id,'bwa_license_issue','ISS', $bwaLicenseIssue->id);
        // store master data
        $bwaLicenseMaster = new BWALicenseMaster();
        $bwaLicenseMaster->license_no = $this->license_no;
        $bwaLicenseMaster->license_issue_date = $this->issue_date;
        $bwaLicenseMaster->expiry_date = $this->expire_date;
        $bwaLicenseMaster->company_id = self::$companyInfo->id;
        $bwaLicenseMaster->issue_tracking_no = !empty(BWALicenseIssue::find($bwaLicenseIssue->id)) ? BWALicenseIssue::find($bwaLicenseIssue->id)->tracking_no : null;
        $bwaLicenseMaster->status = 1;
        $bwaLicenseMaster->save();
    }

    private function storeSsLicenseInfo($data)
    {

        $isExistLicenseNo = SSLicenseMaster::where('license_no', $this->license_no)->count();
        if ($isExistLicenseNo > 0) {
            DB::rollBack();
            Session::flash('error', "License number $this->license_no already exists.");
            return back();
        }

        $this->calculateIssueAndExpireDate();
        $ssLicenseIssue = new SSLicenseIssue();
        $ssLicenseIssue->reg_office_address = $this->reg_office_address;
        $ssLicenseIssue->license_no = $this->license_no;
        $ssLicenseIssue->license_issue_date = $this->issue_date;
        $ssLicenseIssue->expiry_date = $this->expire_date;
        $ssLicenseIssue->company_id = self::$companyInfo->id;
        $ssLicenseIssue->org_nm = self::$companyInfo->org_nm ?? null;
        $ssLicenseIssue->org_type = self::$companyInfo->org_type ?? null;


        $ssLicenseIssue->status = 1;
        $ssLicenseIssue->bulk_status = 1;
        $ssLicenseIssue->save();
        // store contact information
        $contactPersonData = $this->storeContactPerson($ssLicenseIssue->id, $this->process_type_id);
        // store process list information
        $processList = $this->storeProcessListData($ssLicenseIssue, $this->process_type_id, $contactPersonData);
        //generate tracking no
//        $trackingPrefix = 'SS-' . date('Ymd') . '-';
//        CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processList->id, $ssLicenseIssue->id,'ss_license_issue');
        CommonFunction::generateUniqueTrackingNumber('SS',$this->process_type_id, $processList->id,'ss_license_issue','ISS', $ssLicenseIssue->id);
        // store master data
        $ssLicenseMaster = new SSLicenseMaster();
        $ssLicenseMaster->license_no = $this->license_no;
        $ssLicenseMaster->license_issue_date = $this->issue_date;
        $ssLicenseMaster->expiry_date = $this->expire_date;
        $ssLicenseMaster->company_id = self::$companyInfo->id;
        $ssLicenseMaster->issue_tracking_no = !empty(SSLicenseIssue::find($ssLicenseIssue->id)) ? SSLicenseIssue::find($ssLicenseIssue->id)->tracking_no : null;
        $ssLicenseMaster->status = 1;
        $ssLicenseMaster->save();
    }

}
