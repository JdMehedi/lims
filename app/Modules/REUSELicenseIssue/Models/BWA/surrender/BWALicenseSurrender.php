<?php


namespace App\Modules\REUSELicenseIssue\Models\BWA\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\BWA\amendment\BWALicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\BWA\BWALicenseMaster;
use App\Modules\REUSELicenseIssue\Models\BWA\issue\BWALicenseIssue;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class BWALicenseSurrender extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'bwa_license_surrender';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private const FORM_PATH = 'REUSELicenseIssue::BWA.Surrender';

    private const BWA_ISSUE_LICENSE_PROCESS_TYPE_ID = 74;
    private const BWA_RENEW_LICENSE_PROCESS_TYPE_ID = 75;


    public function createForm( $currentInstance ): string {
        $this->process_type_id   = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();
        $data['process_type_id'] = $this->process_type_id;
        $data['acl_name']        = $currentInstance->acl_name;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view("REUSELicenseIssue::BWA.Surrender.form", $data));

    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {
        $this->process_type_id = $currentInstance->process_type_id;

        if ( $request->get( 'app_id' ) ) {
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new self();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData( $appData, $request );

        if ($appData->id) {

            # Store ShareHolder Person
            CommonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id );

            # Store Contact Person
            CommonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id );

            # dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            # dynamic document end

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == 'draft' ) {
                $processData->status_id = - 1;
                $processData->desk_id   = 0;
            } else {
                if ( $processData->status_id == 5 ) { // For shortfall
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id'          => $appData->id,
                        'process_type_id' => $this->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where( 'id', $this->process_type_id )
                        ->orderBy( 'id', 'desc' )
                        ->first([
                            'form_url',
                            'process_type.process_desk_status_json',
                            'process_type.name',
                        ]);
                    $resubmission_data           = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
                    $processData->status_id      = $resubmission_data['process_starting_status'];
                    $processData->desk_id        = $resubmission_data['process_starting_desk'];
                    $processData->process_desc   = 'Re-submitted form applicant';
                    $processData->resubmitted_at = Carbon::now(); // application resubmission Date

                    $resultData = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? '';
                    $processData->hash_value    = Encryption::encode( $resultData );
                } else {
                    $processData->status_id = 1;
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                    $processData->license_no   = $request->get('license_no');
                    $processData->submitted_at = Carbon::now();
                }
            }

            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            //TODO:: Need to change
            $jsonData['Company Name'] = $appData->org_nm;
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );
            $processData->save();
        }
        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('PSTN', $this->process_type_id, $processData->id, $this->table, 'SUR', $appData->id);
        }
        //=================================================payment code==========================
        if ( $request->get( 'actionBtn' ) == 'submit' ) {
            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();
                // TODO:: redirect to Sonali Payment Portal
            }
        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'BWA License Surrender',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id);

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the BWA Surrender Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully BWA Surrender Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully BWA Surrender Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [ISPR-007]' );
        }

        return redirect( '/bwa-license-cancellation/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId = Encryption::decodeId( $applicationId );
        $this->process_type_id = $processTypeId;
        $data['process_type_id'] = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( 'bwa_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $processTypeId ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $processTypeId ) );
            } )
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )

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

                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                'reg_off_district.area_nm as reg_office_district_en',
                'reg_off_thana.area_nm as reg_office_thana_en',
                'noc_dis.area_nm as op_office_district_en',
                'noc_thana.area_nm as op_office_thana_en',
                'apps.*',
            ] );

        $data['appShareholderInfo'] = Shareholder::where( [ 'app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $processTypeId )->where( 'ref_id', $decodedAppId )->whereNotNull('uploaded_path')->get();

        $data['contact_person']        = ContactPerson::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ] )->get();


        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 1;
            $data['unfixed_amounts'] =  [
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


        $public_html = (string) view( self::FORM_PATH.'.view', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $this->process_type_id   = $processTypeId;
        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId = Encryption::decodeId( $applicationId );

        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'bwa_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
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

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $this->process_type_id;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $shareholders_data       = Shareholder::where( [
            'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
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
                $value->image = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }

        $data['contact_person'] = $contact_data;

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $public_html = (string) view( self::FORM_PATH.".edit", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $licenseNo             = $request->get('license_no');
        $data['license_no']    = $licenseNo;
        $issue_company_id      = BWALicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['master_data'] = BWALicenseMaster::where('license_no', $licenseNo)->first();
        $issueLicenseProcessTypeId = 74;
        $selectedColumns  = [
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
        ];
        if (!empty($data['master_data']->renew_tracking_no)) {
            $data['appInfo'] = ProcessList::leftJoin( 'bwa_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin('bwa_license_master as ms', function ( $join ) {
                        $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                    })
                ->leftJoin('process_status as ps',
                    function ( $join ) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( self::BWA_RENEW_LICENSE_PROCESS_TYPE_ID));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(self::BWA_RENEW_LICENSE_PROCESS_TYPE_ID));
                    })
                ->where( 'ms.license_no', $licenseNo)
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', self::BWA_RENEW_LICENSE_PROCESS_TYPE_ID)
                ->first($selectedColumns);
        } else {

            $data['license_no']    = $request->license_no;
            $data['appInfo'] = ProcessList::leftJoin( 'bwa_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( 'bwa_license_master as ms',
                    function ( $join ) {
                        $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                    } )
                ->leftJoin('process_status as ps',
                    function ( $join ) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw(self::BWA_ISSUE_LICENSE_PROCESS_TYPE_ID));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(self::BWA_ISSUE_LICENSE_PROCESS_TYPE_ID));
                    })
                ->where( 'ms.license_no', $licenseNo )
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', $issueLicenseProcessTypeId)
                ->first($selectedColumns);
        }

        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
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
        $public_html = (string) view( self::FORM_PATH.".search", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function fetchAndPrprIssueData( $license_no, $appData, $request ) {
        if ( $request->get( 'form_version' ) == 'v1' ) {
            $existedData = ISPLicenseMaster::leftJoin( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
                                           ->where( [ 'isp_license_master.license_no' => $license_no ] )->first();

            $appData->org_nm       = $existedData['org_nm'];
            $appData->org_type     = $existedData['org_type'];
            $appData->org_mobile   = $existedData['org_mobile'];
            $appData->org_phone    = $existedData['org_phone'];
            $appData->org_email    = $existedData['org_email'];
            $appData->org_district = $existedData['org_district'];
            $appData->org_upazila  = $existedData['org_upazila'];
            $appData->org_address  = $existedData['org_address'];
            $appData->org_website  = $existedData['org_website'];


            $appData->isp_license_type     = $existedData['isp_license_type'];
            $appData->isp_license_division = $existedData['isp_license_division'];
            $appData->isp_license_district = $existedData['isp_license_district'];
            $appData->isp_license_upazila  = $existedData['isp_license_upazila'];

            $appData->declaration_q1      = $request->get( 'declaration_q1' );
            $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
            $appData->declaration_q2      = $request->get( 'declaration_q2' );
            $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
            $appData->declaration_q3      = $request->get( 'declaration_q3' );

            if ( $request->hasFile( 'declaration_q3_images' ) ) {
                $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
                $path      = 'uploads/isp-license-renew/' . $yearMonth;
                if ( ! file_exists( $path ) ) {
                    mkdir( $path, 0777, true );
                }
                $_file_path = $request->file( 'declaration_q3_images' );
                $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
                $_file_path->move( $path, $file_path );
                $appData->declaration_q3_doc = $path . $file_path;
            } else {
                $appData->declaration_q3_doc = $existedData['declaration_q3_doc'];
            }

            $appData->status     = 1;
            $appData->created_at = Carbon::now();

            $appData->company_id        = ! empty( $existedData['company_id'] ) ? $existedData['company_id'] : 0;
            $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
            $appData->license_no        = $license_no;
            $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;

            return $appData;
        } else {
            $appData->org_nm       = $request->company_org_name;
            $appData->org_type     = $request->company_type;
            $appData->org_mobile   = $request->applicant_mobile;
            $appData->org_phone    = $request->applicant_telephone;
            $appData->org_email    = $request->applicant_email;
            $appData->org_district = $request->applicant_district;
            $appData->org_upazila  = $request->applicant_thana;
            $appData->org_address  = $request->applicant_address;
            $appData->org_website  = $request->applicant_website;


            $appData->isp_license_type     = $request->type_of_isp_licensese;
            $appData->isp_license_division = $request->isp_licensese_area_division;
            $appData->isp_license_district = $request->isp_licensese_area_district;
            $appData->isp_license_upazila  = isset( $request->isp_licensese_area_thana ) ? $request->isp_licensese_area_thana : '';

            $appData->declaration_q1      = $request->get( 'declaration_q1' );
            $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
            $appData->declaration_q2      = $request->get( 'declaration_q2' );
            $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
            $appData->declaration_q3      = $request->get( 'declaration_q3' );

            if ( $request->hasFile( 'declaration_q3_images' ) ) {
                $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
                $path      = 'uploads/isp-license-renew/' . $yearMonth;
                if ( ! file_exists( $path ) ) {
                    mkdir( $path, 0777, true );
                }
                $_file_path = $request->file( 'declaration_q3_images' );
                $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
                $_file_path->move( $path, $file_path );
                $appData->declaration_q3_doc = $path . $file_path;
            }

            $appData->status     = 1;
            $appData->created_at = Carbon::now();

            $appData->company_id        = ! empty( $existedData['company_id'] ) ? $existedData['company_id'] : 0;
            $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
            $appData->license_no        = $license_no;
            $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;

            return $appData;
        }

    }

    private function prprRenewData( $request, $appData ) {
        $appData->issue_date            = date( 'Y-m-d', strtotime( $request->issue_date ) );
        $appData->expiry_date           = date( 'Y-m-d', strtotime( $request->expiry_date ) );
        $appData->installation_location = ! empty( $request->installation_location ) ? $request->installation_location : '';
        $appData->no_of_individual      = ! empty( $request->no_of_individual ) ? $request->no_of_individual : '';
        $appData->no_of_corporate       = ! empty( $request->no_of_corporate ) ? $request->no_of_corporate : '';
        $appData->corporate_user        = ! empty( $request->corporate_user ) ? $request->corporate_user : '';
        $appData->branch_user           = ! empty( $request->branch_user ) ? $request->branch_user : '';
        $appData->personal_user         = ! empty( $request->personal_user ) ? $request->personal_user : '';
        $appData->type_of_services      = ! empty( $request->service_type ) ? json_encode( $request->service_type ) : '';
        $appData->cable_length          = ! empty( $request->cable_length ) ? $request->cable_length : '';
        $appData->cable_type            = ! empty( $request->cable_type ) ? json_encode( $request->cable_type ) : '';
        $appData->installation_address  = ! empty( $request->installation_address ) ? $request->installation_address : '';

        return $appData;
    }

    private function storeISPEquipment( $license_no, $appDataId, $request ) {
        if ( $request->get( 'form_version' ) == 'v1' ) {
            $ExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
                                           ->join( 'isp_license_equipment_list as cp', 'apps.id', '=', 'cp.isp_license_issue_id' )
                                           ->select( DB::raw( 'cp.*' ) )
                                           ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();

            if ( count( $ExistedData ) == 0 ) {
                return false;
            }

            ISPLicenseRenewEquipmentList::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $ExistedData as $index => $item ) {
                $equipObj                 = new ISPLicenseRenewEquipmentList();
                $equipObj->isp_license_id = $appDataId;
                $equipObj->name           = $item->name;
                $equipObj->brand_model    = $item->brand_model;
                $equipObj->quantity       = $item->quantity;
                $equipObj->remarks        = $item->remarks;
                $equipObj->created_at     = date( 'Y-m-d H:i:s' );
                $equipObj->save();
            }

            return true;
        } else {
            ISPLicenseRenewEquipmentList::where( 'isp_license_id', $appDataId )->delete();
            foreach ( $request->equipment_name as $index => $item ) {
                $equipObj                 = new ISPLicenseRenewEquipmentList();
                $equipObj->isp_license_id = $appDataId;
                $equipObj->name           = $item;
                $equipObj->brand_model    = $request->equipment_brand_model[ $index ];
                $equipObj->quantity       = $request->equipment_quantity[ $index ];
                $equipObj->remarks        = $request->equipment_remarks[ $index ];
                $equipObj->created_at     = date( 'Y-m-d H:i:s' );
                $equipObj->save();
            }

            return true;
        }
    }

    private function storeISPTariffChart( $license_no, $appDataId, $request ) {
        if ( $request->get( 'form_version' ) == 'v1' ) {
            $ExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
                                           ->join( 'isp_license_tariff_chart as cp', 'apps.id', '=', 'cp.isp_license_issue_id' )
                                           ->select( DB::raw( 'cp.*' ) )
                                           ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();

            if ( count( $ExistedData ) == 0 ) {
                return false;
            }

            ISPLicenseRenewTariffChart::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $ExistedData as $index => $item ) {
                $tariffObj                    = new ISPLicenseRenewTariffChart();
                $tariffObj->isp_license_id    = $appDataId;
                $tariffObj->package_name_plan = $item->package_name_plan;
                $tariffObj->bandwidth_package = $item->bandwidth_package;
                $tariffObj->price             = $item->price;
                $tariffObj->duration          = $item->duration;
                $tariffObj->remarks           = $item->remarks;
                $tariffObj->created_at        = date( 'Y-m-d H:i:s' );
                $tariffObj->save();
            }

            return true;
        } else {
            ISPLicenseRenewTariffChart::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $request->tariffChart_package_name_plan as $index => $item ) {
                $tariffObj                    = new ISPLicenseRenewTariffChart();
                $tariffObj->isp_license_id    = $appDataId;
                $tariffObj->package_name_plan = $item;
                $tariffObj->bandwidth_package = $request->tariffChart_bandwidth_package[ $index ];
                $tariffObj->price             = $request->tariffChart_price[ $index ];
                $tariffObj->duration          = $request->tariffChart_duration[ $index ];
                $tariffObj->remarks           = $request->tariffChart_remarks[ $index ];
                $tariffObj->created_at        = date( 'Y-m-d H:i:s' );
                $tariffObj->save();
            }

            return true;
        }
    }

    private function storeToIspLicenseBandwidth( $request, $appDataId ) {
        ISPLicenseBandwidth::where( 'isp_license_renew_id', $appDataId )->delete();

        if ( count( $request->bandwidth_primary_iig ) > 0 ) {
            foreach ( $request->bandwidth_primary_iig as $index => $value ) {
                $bandwidthObj                       = new ISPLicenseBandwidth();
                $bandwidthObj->isp_license_renew_id = $appDataId;
                $bandwidthObj->name_of_primary_iig  = $value;
                $bandwidthObj->allocation           = $request->bandwidth_allocation[ $index ];
                $bandwidthObj->upstream             = $request->bandwidth_up_stream[ $index ];
                $bandwidthObj->downstream           = $request->bandwidth_down_stream[ $index ];
                $bandwidthObj->created_at           = date( 'Y-m-d H:i:s' );
                $bandwidthObj->save();
            }
        }
    }

    private function storeToIspLicenseConnectivity( $request, $appDataId ) {
        ISPLicenseConnectivity::where( 'isp_license_renew_id', $appDataId )->delete();

        if ( count( $request->connectivity_provider ) > 0 ) {
            foreach ( $request->connectivity_provider as $index => $value ) {
                $connectivityObj                        = new ISPLicenseConnectivity();
                $connectivityObj->isp_license_renew_id  = $appDataId;
                $connectivityObj->con_provider          = $value;
                $connectivityObj->allocation_upstream   = $request->connectivity_up_stream[ $index ];
                $connectivityObj->allocation_downstream = $request->connectivity_down_stream[ $index ];
                $connectivityObj->frequency_up          = $request->connectivity_up_frequency[ $index ];
                $connectivityObj->frequency_down        = $request->connectivity_down_frequency[ $index ];
                $connectivityObj->created_at            = date( 'Y-m-d H:i:s' );
                $connectivityObj->save();
            }
        }
    }

    private function storeContactPersonData( $license_no, $appDataId, $request ) {
        if ( $request->get( 'form_version' ) == 'v1' ) {
            $contactPersonExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
                                                        ->join( 'isp_license_contact_person as cp', 'apps.id', '=', 'cp.isp_license_issue_id' )
                                                        ->select( DB::raw( 'cp.*' ) )
                                                        ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();

            if ( count( $contactPersonExistedData ) == 0 ) {
                return false;
            }

            ISPLicenseRenewContactPerson::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $contactPersonExistedData as $item ) {
                $contactPersonObj                 = new ISPLicenseRenewContactPerson();
                $contactPersonObj->isp_license_id = $appDataId;
                $contactPersonObj->name           = $item->name;
                $contactPersonObj->designation    = $item->designation;
                $contactPersonObj->mobile         = $item->mobile;
                $contactPersonObj->email          = $item->email;
                $contactPersonObj->website        = $item->website;
                $contactPersonObj->district       = $item->district;
                $contactPersonObj->upazila        = $item->upazila;
                $contactPersonObj->address        = $item->address;
                $contactPersonObj->created_at     = date( 'Y-m-d H:i:s' );
                $contactPersonObj->save();
            }
        } else {

            ISPLicenseRenewContactPerson::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $request->contact_person_name as $index => $item ) {
                $contactPersonObj                 = new ISPLicenseRenewContactPerson();
                $contactPersonObj->isp_license_id = $appDataId;
                $contactPersonObj->name           = $item;
                $contactPersonObj->designation    = isset( $request->contact_designation[ $index ] ) ? $request->contact_designation[ $index ] : '';
                $contactPersonObj->mobile         = isset( $request->contact_mobile[ $index ] ) ? $request->contact_mobile[ $index ] : '';
                $contactPersonObj->email          = isset( $request->contact_person_email[ $index ] ) ? $request->contact_person_email[ $index ] : '';
                $contactPersonObj->website        = isset( $request->contact_website[ $index ] ) ? $request->contact_website[ $index ] : '';
                $contactPersonObj->district       = isset( $request->contact_district[ $index ] ) ? $request->contact_district[ $index ] : '';
                $contactPersonObj->upazila        = isset( $request->contact_thana[ $index ] ) ? $request->contact_thana[ $index ] : '';
                $contactPersonObj->address        = $request->contact_person_address[ $index ];
                $contactPersonObj->created_at     = date( 'Y-m-d H:i:s' );
                $contactPersonObj->save();
            }
        }

    }

    private function storeShareHolderData( $license_no, $appDataId, $request ) {
        if ( $request->get( 'form_version' ) == 'v1' ) {
            $ExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
                                           ->join( 'isp_license_issue_shareholders as cp', 'apps.id', '=', 'cp.isp_issue_id' )
                                           ->select( DB::raw( 'cp.*' ) )
                                           ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();

            if ( count( $ExistedData ) == 0 ) {
                return false;
            }

            ISPLicenseRenewShareHolder::where( 'isp_license_id', $appDataId )->delete();

            foreach ( $ExistedData as $item ) {
                $shareHolderData                 = new ISPLicenseRenewShareHolder();
                $shareHolderData->isp_license_id = $appDataId;
                $shareHolderData->name           = $item->name;
                $shareHolderData->nid            = $item->nid;
                $shareHolderData->passport       = $item->passport;
                $shareHolderData->designation    = $item->designation;
                $shareHolderData->nationality    = $item->nationality;
                $shareHolderData->mobile         = $item->mobile;
                $shareHolderData->email          = $item->email;
                $shareHolderData->dob            = $item->dob;
                $shareHolderData->share_percent  = $item->share_percent;
                $shareHolderData->image          = ! empty( $item->image ) ? $item->image : null;

                //images

                $shareHolderData->created_at = Carbon::now();
                $shareHolderData->save();
            }
        } else {
            ISPLicenseRenewShareHolder::where( 'isp_license_id', $appDataId )->delete();
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/shareholder/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            foreach ( $request->shareholder_name as $index => $item ) {
                $shareHolderData                 = new ISPLicenseRenewShareHolder();
                $shareHolderData->isp_license_id = $appDataId;
                $shareHolderData->name           = $item;
                $shareHolderData->nid            = $request->shareholder_nid[ $index ];
                $shareHolderData->passport       = $request->shareholder_passport[ $index ];
                $shareHolderData->designation    = $request->shareholder_designation[ $index ];
                $shareHolderData->nationality    = $request->shareholder_nationality[ $index ];
                $shareHolderData->mobile         = $request->shareholder_mobile[ $index ];
                $shareHolderData->email          = $request->shareholder_email[ $index ];
                $shareHolderData->dob            = ! empty( $request->shareholder_dob[ $index ] ) ? date( 'Y-m-d', strtotime( $request->shareholder_dob[ $index ] ) ) : null;
                $shareHolderData->share_percent  = $request->shareholder_share_of[ $index ];
//                $shareHolderData->image          = ! empty( $item->image ) ? $item->image : null;
                if ( ! empty( $request->correspondent_photo_base64[ $index ] ) ) {
                    $splited                  = explode( ',', substr( $request->correspondent_photo_base64[ $index ], 5 ), 2 );
                    $imageData                = $splited[1];
                    $base64ResizeImage        = base64_encode( ImageProcessing::resizeBase64Image( $imageData, 300, 300 ) );
                    $base64ResizeImage        = base64_decode( $base64ResizeImage );
                    $correspondent_photo_name = trim( uniqid( 'BSCIC_IR-' . '-', true ) . '.' . 'jpeg' );
                    file_put_contents( $path . $correspondent_photo_name, $base64ResizeImage );
                    $shareHolderData->image = $path . $correspondent_photo_name;
                } else {
                    if ( empty( $appData->auth_person_pic ) ) {
                        $shareHolderData->image = Auth::user()->user_pic;
                    }
                }

                //images

                $shareHolderData->created_at = Carbon::now();
                $shareHolderData->save();
            }
        }

    }

    private function unfixedAmountsForGovtServiceFee( $isp_license_type, $payment_step_id ) {
        $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        if ( empty( $vat_percentage ) ) {
            DB::rollback();
            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );

            return redirect()->back()->withInput();
        }

        $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
            'process_type_id' => $this->process_type_id,
            'payment_step_id' => $payment_step_id,
            'license_type_id' => $isp_license_type,
            'status'          => 1,
        ] )->first();

//        dd($SpPaymentAmountConfData);

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
            6 => 0 //govt-vendor-vat-fee
        ];

        return $unfixed_amount_array;
    }

    private function storeLicenseData( $licenseObj, $request ) {

        $licenseObj->reason_of_surrender = $request->get( 'reason_of_surrender' );
        $licenseObj->surrender_date      = date('Y-m-d',strtotime($request->get( 'surrender_date' )));
        $licenseObj->org_nm              = $request->get( 'company_name' );
        $licenseObj->org_type            = $request->get( 'company_type' );
        $licenseObj->license_no          = $request->get( 'license_no' );
        $licenseObj->reg_office_district = $request->get( 'reg_office_district' );
        $licenseObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $licenseObj->reg_office_address  = $request->get( 'reg_office_address' );
        $licenseObj->op_office_district  = $request->get( 'op_office_district' );
        $licenseObj->op_office_thana     = $request->get( 'op_office_thana' );
        $licenseObj->op_office_address   = $request->get( 'op_office_address' );

        $licenseObj->applicant_name      = $request->get( 'applicant_name' );
        $licenseObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $licenseObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $licenseObj->applicant_email     = $request->get( 'applicant_email' );
        $licenseObj->applicant_district  = $request->get( 'applicant_district' );
        $licenseObj->applicant_thana     = $request->get( 'applicant_thana' );
        $licenseObj->applicant_address   = $request->get( 'applicant_address' );

        $licenseObj->license_issue_date            = date( 'Y-m-d', strtotime( $request->issue_date ) );
        $licenseObj->expiry_date           = date( 'Y-m-d', strtotime( $request->expiry_date ) );

        // list_of_clients
        if ( $request->hasFile( 'list_of_clients' ) ) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/bwa-license-ammendment/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'list_of_clients' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $licenseObj->list_of_clients = $path . $file_path;
        }

        $licenseObj->declaration_q1      = $request->get( 'declaration_q1' );
        $licenseObj->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $licenseObj->declaration_q2      = $request->get( 'declaration_q2' );
        $licenseObj->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $licenseObj->declaration_q3      = $request->get( 'declaration_q3' );
        $licenseObj->declaration_q3_doc  = $request->get( 'declaration_q3_doc');

        $licenseObj->status              = 1;
        $licenseObj->updated_at          = Carbon::now();
        $licenseObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $licenseObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $licenseObj->total_share_value   = $request->get( 'total_share_value' );

        //images
        $licenseObj->save();

        return $licenseObj;
    }
}
