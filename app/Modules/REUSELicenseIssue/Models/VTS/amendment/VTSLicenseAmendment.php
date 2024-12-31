<?php

namespace App\Modules\REUSELicenseIssue\Models\VTS\amendment;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseMaster;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class VTSLicenseAmendment extends Model implements FormInterface {

    protected $table = 'vts_license_amendment';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $form_url = 'vts-license-amendment';
    private $applicant_desk_id = 0;
    private $chairman_desk_id = 1;

    use SPPaymentManager;
    use SPAfterPaymentManager;


    public function createForm($currentInstance): string
    {
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']  = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( 'REUSELicenseIssue::VTS.Amendment.master', $data ) );
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $app_id                = $request->get('app_id');

        if ($request->get('app_id')) {
            $appData     = VTSLicenseAmendment::find(Encryption::decodeId($app_id));
            $processData = ProcessList::where([
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id' => $appData->id
            ])->first();
        } else {
            $appData     = new VTSLicenseAmendment();
            $processData = new ProcessList();
        }


        $appData = $this->storeLicenseData( $appData, $request );

        if ( $appData->id ) {
            //dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            // Contact Person data insert
            CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id, );
            // Share Holder Data insert
            CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) === "draft" ) {
                $processData->status_id = $this->draft_status_id;
                $processData->desk_id   = $this->applicant_desk_id;
            } else {
                if ( $processData->status_id == $this->shortfall_status_id ) {
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id'          => $appData->id,
                        'process_type_id' => $this->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where( 'id', $this->process_type_id )
                        ->orderBy( 'id', 'desc' )
                        ->first( [
                            'form_url',
                            'process_type.process_desk_status_json',
                            'process_type.name',
                        ] );
                    $resubmission_data           = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
                    $processData->status_id      = $resubmission_data['process_starting_status'];
                    $processData->desk_id        = $resubmission_data['process_starting_desk'];
                    $processData->process_desc   = 'Re-submitted form applicant';
                    $processData->resubmitted_at = Carbon::now(); // application resubmission Date
                    $resultData                  = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;
                    $processData->previous_hash  = $processData->hash_value ?? "";
                    $processData->hash_value     = Encryption::encode( $resultData );
                } else {
                    $processData->status_id = $this->submitted_status_id;
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                    $processData->submitted_at = Carbon::now();
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            $jsonData['Company Name']     = $request->company_name;
            $jsonData['Email']            = Auth::user()->user_email;
            $jsonData['Phone']            = Auth::user()->user_mobile;
            $processData['json_object']   = json_encode( $jsonData );
            $processData->submitted_at    = Carbon::now();
            $processData->save();
        }



        if ( $request->get( 'actionBtn' ) == 'submit' &&  $processData->status_id != 2) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'VTS-A-' . date( 'Ymd' ) . '-';
                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
            }


            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'VTS License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

            //TODO:: send email
            $receiverInfo = [
                array(
                    'user_mobile' => Auth::user()->user_mobile,
                    'user_email' => Auth::user()->user_email
                )
            ];

            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $trackingNumber,
                'process_type_name' => 'VTS License Amendment',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);


        }

        // Send Email notification to user on application re-submit
        if ( $processData->status_id == $this->re_submit_status_id ) {
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            $trackingNumber = self::where('id','=', $processData->ref_id)->value('tracking_no');
            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'VTS License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

            //TODO:: send email
            $receiverInfo = [
                array(
                    'user_mobile' => Auth::user()->user_mobile,
                    'user_email' => Auth::user()->user_email
                )
            ];

            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $trackingNumber,
                'process_type_name' => 'VTS License Amendment',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id,$request->get( 'actionBtn' ));

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]' );
        }

        return redirect( "client/$this->form_url/list/" . Encryption::encodeId( $this->process_type_id ) );

    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {
        $decodedAppId = Encryption::decodeId( $applicationId );
        $data['process_type_id'] = $processTypeId;
        $data['form_url'] = $this->form_url;
        $process_type_id = $processTypeId;
        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;
        $data['appInfo'] = ProcessList::leftJoin( "$this->table as apps", 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $processTypeId ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $processTypeId ) );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $processTypeId ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $processTypeId ) );
            } )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_upazila', 'applicant_upazila.area_id', '=', 'apps.applicant_thana' )
            ->where( 'process_list.ref_id', $decodedAppId )
            ->where( 'process_list.process_type_id', $processTypeId )
            ->first( [
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'ps.status_name',
                'process_type.form_url',

                'reg_office_district.area_nm as reg_office_district_en',
                'reg_office_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'apps.op_office_address',

                'applicant_district.area_nm as applicant_district_en',
                'applicant_upazila.area_nm as applicant_thana_en',

                'apps.*',
                'sfp.contact_name as sfp_contact_name',
                'sfp.contact_email as sfp_contact_email',
                'sfp.contact_no as sfp_contact_phone',
                'sfp.address as sfp_contact_address',
                'sfp.pay_amount as sfp_pay_amount',
                'sfp.vat_on_pay_amount as sfp_vat_tax',
                'sfp.transaction_charge_amount as sfp_bank_charge',
                'sfp.payment_status as sfp_payment_status',
                'sfp.pay_mode as pay_mode',
                'sfp.pay_mode_code as pay_mode_code',
                'sfp.total_amount as sfp_total_amount',
                'company_info.incorporation_num',
                'company_info.incorporation_date',
            ] );

        $data['appShareholderInfo'] = self::Join( 'shareholders', 'shareholders.app_id', '=', "$this->table.id" )
            ->where( [
                "$this->table.id"              => $decodedAppId,
                'shareholders.process_type_id' => $processTypeId
            ] )
            ->get( [
                'shareholders.id as shareholders_id',
                'shareholders.app_id as shareholders_ref_id',
                'shareholders.name as name',
                'shareholders.nationality as nationality',
                'shareholders.passport as passport',
                'shareholders.nid as nid',
                'shareholders.dob as dob',
                'shareholders.designation as designation',
                'shareholders.mobile as mobile',
                'shareholders.email as email',
                'shareholders.image as image',
                'shareholders.share_percent as share_percent',
                'shareholders.no_of_share as no_of_share',
                'shareholders.share_value as share_value'
            ] );

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $processTypeId
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['contact_upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
        }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html       = (string) view( "REUSELicenseIssue::VTS.Amendment.masterView", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id   = $processTypeId;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId     = Encryption::decodeId( $applicationId );
        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( "$this->table as apps", 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->where( 'process_list.ref_id', $applicationId )
            ->where( 'process_list.process_type_id', $process_type_id )
            ->first( [
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.submitted_at',
                'ps.status_name',
                'ps.color',
                'apps.*',
                'apps.org_nm as company_name',
                'sfp.contact_name as sfp_contact_name',
                'sfp.contact_email as sfp_contact_email',
                'sfp.contact_no as sfp_contact_phone',
                'sfp.address as sfp_contact_address',
                'sfp.pay_amount as sfp_pay_amount',
                'sfp.vat_on_pay_amount as sfp_vat_tax',
                'sfp.transaction_charge_amount as sfp_bank_charge',
                'sfp.payment_status as sfp_payment_status',
                'sfp.pay_mode as pay_mode',
                'sfp.pay_mode_code as pay_mode_code',
                'sfp.total_amount as sfp_total_amount',
            ] );


        // Share Holder Person
        $shareholders_data = Shareholder::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $this->process_type_id
        ] )->get( [
            'shareholders.id as shareholders_id',
            'shareholders.app_id as app_id',
            'shareholders.name as shareholders_name',
            'shareholders.nationality as shareholders_nationality',
            'shareholders.passport as shareholders_passport',
            'shareholders.nid as shareholders_nid',
            'shareholders.dob as shareholders_dob',
            'shareholders.designation as shareholders_designation',
            'shareholders.mobile as shareholders_mobile',
            'shareholders.email as shareholders_email',
            'shareholders.image as shareholders_image',
            'shareholders.share_percent as shareholders_share_percent',
            'shareholders.no_of_share as no_of_share',
            'shareholders.share_value as share_value',
        ] );

        foreach ( $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;


        // Contact Person
        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $this->process_type_id
        ] )->get();
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['image']         = ! empty( $item->image ) ? CommonFunction::imagePathToBase64( public_path( $item->image ) ) : '';
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $public_html = (string) view( "REUSELicenseIssue::VTS.Amendment.masterEdit", $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        $issue_company_id      = VTSLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['master_data'] = VTSLicenseMaster::where('license_no', $request->license_no)->first();

        if (!empty($data['master_data']->renew_tracking_no)) {
            $data['appInfo'] = ProcessList::leftJoin( 'vts_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin('vts_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                    })
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( 30));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(83));
                    })
                ->where( 'ms.license_no', $request->license_no)
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 30)
                ->first( [
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'ps.status_name',
                    'ps.color',
                    'apps.org_nm as company_name',
                    'apps.org_type as company_type',
                    'apps.*',
                    'ms.issue_tracking_no',


                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_tax',
                    'sfp.transaction_charge_amount as sfp_bank_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.is_pay_order_verified',
                    'sfp.payment_type'
                ] );
        } else {
            $data['license_no']    = $request->license_no;
            $data['appInfo'] = ProcessList::leftJoin( 'vts_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( 'vts_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                    } )
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw(29));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(83));
                    })
                ->where( 'ms.license_no', $request->license_no )
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 29)
                ->first( [
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'ps.status_name',
                    'ps.color',
                    'apps.*',
                    'apps.org_nm as company_name',
                    'apps.org_type as company_type',
                    'ms.issue_tracking_no',
                    'apps.declaration_q1 as declaration_q1',
                    'apps.declaration_q1_text as declaration_q1_text',
                    'apps.declaration_q2 as declaration_q2',
                    'apps.declaration_q2_text as declaration_q2_text',
                    'apps.declaration_q3 as declaration_q3',
                    'apps.dd_file_1 as dd_file_1',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_tax',
                    'sfp.transaction_charge_amount as sfp_bank_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.is_pay_order_verified',
                    'sfp.payment_type'
                ] );
        }


        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )
            ->get( [
                'shareholders.id as shareholders_id',
                'shareholders.app_id as app_id',
                'shareholders.name as shareholders_name',
                'shareholders.nationality as shareholders_nationality',
                'shareholders.passport as shareholders_passport',
                'shareholders.nid as shareholders_nid',
                'shareholders.dob as shareholders_dob',
                'shareholders.designation as shareholders_designation',
                'shareholders.mobile as shareholders_mobile',
                'shareholders.email as shareholders_email',
                'shareholders.image as shareholders_image',
                'shareholders.share_percent as shareholders_share_percent',
                'shareholders.no_of_share as no_of_share',
                'shareholders.share_value as share_value',
            ] );

        foreach ( $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }
        $data['contact_person'] = $contact_data;

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $data['nationality']      = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::VTS.Amendment.search', $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    private function storeLicenseData( $licenseObj, $request ) {
        $licenseObj->company_id = CommonFunction::getUserCompanyWithZero();
        $licenseObj->org_nm              = $request->get( 'company_name' );
        $licenseObj->org_type            = $request->get( 'company_type' );
        $licenseObj->license_no          = $request->get( 'license_no', null );
//        $licenseObj->issue_tracking_no   = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;

//        if(!empty($request->get('issue_date')) && !empty($request->get('expiry_date'))){
//            $issue_date = Carbon::createFromFormat('d-M-Y', $request->get('issue_date'));
//            $formattedIssueDate = $issue_date->format('Y-m-d');
//            $expire_date = Carbon::createFromFormat('d-M-Y', $request->get('expiry_date'));
//            $formattedExpireDate = $expire_date->format('Y-m-d');
//            $licenseObj->license_issue_date = $formattedIssueDate ?? null;
//            $licenseObj->expiry_date = $formattedExpireDate ?? null;
//        }

        $licenseObj->reg_office_district = $request->get( 'reg_office_district' );
        $licenseObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $licenseObj->reg_office_address  = $request->get( 'reg_office_address' );
        $licenseObj->op_office_district  = $request->get( 'noc_district' );
        $licenseObj->op_office_thana     = $request->get( 'noc_thana' );
        $licenseObj->op_office_address   = $request->get( 'noc_address' );

        $licenseObj->applicant_name      = $request->get( 'applicant_name' );
        $licenseObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $licenseObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $licenseObj->applicant_email     = $request->get( 'applicant_email' );
        $licenseObj->applicant_district  = $request->get( 'applicant_district' );
        $licenseObj->applicant_thana   = $request->get( 'applicant_thana' );
        $licenseObj->applicant_address   = $request->get( 'applicant_address' );
        $licenseObj->total_share_value   = $request->get( 'total_share_value' );
        $licenseObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $licenseObj->status              = 1;
        $licenseObj->updated_at          = Carbon::now();
        $licenseObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $licenseObj->save();
        return $licenseObj;
    }

}
