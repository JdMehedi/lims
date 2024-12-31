<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\NIX\amendment;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\NIX\renew\NIXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentTHubOperatorInfo;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
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

class NIXLicenseAmendment extends Model implements FormInterface {
    protected $table = 'nix_license_amendment';
    protected $guarded = [ 'id' ];

    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;
    private $form_url = 'nix-license-amendment';
    private $applicant_desk_id = 0;
    private $chairman_desk_id = 1;

    use SPPaymentManager;
    use SPAfterPaymentManager;

    // expect column for data store from nix_license_issue table
    public $except_column = [
        'id',
        'tracking_no',
        'issue_tracking_no',
        'expiry_date',
        'license_issue_date',
        'certificate_link',
        'created_at',
        'updated_at'
    ];

    public function setvalue( $name, $value ) {
        $this->attributes[ $name ] = $value;
    }

    public function createForm($currentInstance): string
    {
        $this->process_type_id   = $currentInstance->process_type_id;
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $this->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( "REUSELicenseIssue::NIX.Amendment.master", $data ) );
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );

        if ( empty( $license_no ) ) {
            Session::flash( 'error', "Invalid License No [ISPR-006]" );

            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = NIXLicenseAmendment::find( Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new NIXLicenseAmendment();
            $processData = new ProcessList();
        }
        $appData->company_id   = CommonFunction::getUserCompanyWithZero();
        $appData->license_no =  $license_no ;
        $appData->company_name = $request->get( 'company_name' );
        $appData->company_type = $request->get( 'company_type' );

        $appData->reg_office_district = $request->get( 'reg_office_district' );
        $appData->reg_office_thana    = $request->get( 'reg_office_thana' );
        $appData->reg_office_address  = $request->get( 'reg_office_address' );

        $appData->op_office_district = $request->get( 'noc_district' );
        $appData->op_office_thana    = $request->get( 'noc_thana' );
        $appData->op_office_address  = $request->get( 'noc_address' );


        $appData->applicant_name      = $request->get( 'applicant_name' );
        $appData->applicant_district  = $request->get( 'applicant_district' );
        $appData->applicant_thana     = $request->get( 'applicant_thana' );
        $appData->applicant_address   = $request->get( 'applicant_address' );
        $appData->applicant_email     = $request->get( 'applicant_email' );
        $appData->applicant_website   = $request->get( 'applicant_website' );
        $appData->applicant_mobile    = $request->get( 'applicant_mobile' );
        $appData->applicant_telephone = $request->get( 'applicant_telephone' );

        $appData->declaration_q1      = $request->get( 'declaration_q1' );
        $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $appData->declaration_q2      = $request->get( 'declaration_q2' );
        $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $appData->declaration_q3      = $request->get( 'declaration_q3' );
        $appData->declaration_q3_text = $request->get( 'declaration_q3_text' );
        $appData->total_no_of_share   = $request->get('total_no_of_share');
        $appData->total_share_value   = $request->get('total_share_value');
        $appData->status              = 1;
        $appData->updated_at          = Carbon::now();

        $appData->save();

        if ( $appData->id ) {
            //shareholder data insert operation
            if ( $request->get( 'shareholderDataCount' ) > 0 ) {
                commonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id );
            }


            // contact person data insert operation
            if ( $request->get( 'contactPersonDataCount' ) > 0 ) {
                commonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id );
            }

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation   dd($request->all());
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == 'draft' ) {
                $processData->status_id = - 1;
                $processData->desk_id   = 0;
            } else {
                if ( $processData->status_id == 5 ) {
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

                    $resultData = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? '';
                    $processData->hash_value    = Encryption::encode( $resultData );

                } else {
                    $processData->status_id = 1;
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);;
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name']   = $request->company_name;
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );
            $processData->submitted_at  = Carbon::now();
            $processData->save();
            //process list data insert
        }


        //  Required Documents for attachment
        $doc_type_id = '';
        DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );


        if ( $request->get( 'actionBtn' ) == 'submit' &&  $processData->status_id != 2) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'NIX-A-' . date( 'Ymd' ) . '-';
                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
            }


            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'NIX License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'NIX License Amendment',
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
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'NIX License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'NIX License Amendment',
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

        return redirect( 'client/nix-license-ammendment/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {
        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;
        $data['appInfo']         = ProcessList::leftJoin( 'nix_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as per_off_district', 'per_off_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as per_off_thana', 'per_off_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
            ->where( 'process_list.ref_id', $decodedAppId )
            ->where( 'process_list.process_type_id', $process_type_id )
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

                'per_off_district.area_nm as op_office_district_en',
                'per_off_thana.area_nm as op_office_thana_en',

                'apps.*',
                'apps.company_name as org_nm',
                'apps.company_type as org_type',

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

        $data['appShareholderInfo'] = Shareholder::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id
        ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id
        ] )->get();


        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }
//                    if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
//                        $data['payment_step_id'] = 1;
//                        $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
//                    }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html = (string) view( 'REUSELicenseIssue::NIX.Amendment.masterView', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id   = $data['process_type_id'] = $process_type_id = $processTypeId;

        $applicationId = Encryption::decodeId( $applicationId );

        $data['appInfo'] = ProcessList::leftJoin('nix_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_status as ps',
                function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
            ->leftJoin('sp_payment as sfp',
                function ($join) use ($process_type_id) {
                    $join->on('sfp.app_id', '=', 'process_list.ref_id');
                    $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
                })
            ->where( 'process_list.ref_id', $applicationId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
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
            ]);
        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])
            ->get([
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
            ]);

        foreach ($shareholders_data as $index => $value) {
            if (public_path($value->shareholders_image) && !empty($value->shareholders_image)) {
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }
        $data['contact_person'] = $contact_data;

        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        $public_html = (string)view('REUSELicenseIssue::NIX.Amendment.masterEdit', $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function fetchData($request, $currentInstance): JsonResponse{
        $this->process_type_id = $currentInstance->process_type_id;
        $process_type_id = $currentInstance->process_type_id;
        $data['license_no'] = $request->license_no;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        if (empty($data['license_no'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }
        $issue_company_id      = NIXLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }
        $data['master_data'] = NIXLicenseMaster::where('license_no', $request->license_no)->first();

        if ($data['master_data']->renew_tracking_no) {
            $data['appInfo'] = ProcessList::leftJoin('nix_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('nix_license_master as ms',
                    function ($join) use ($process_type_id) {
                        $join->on('ms.renew_tracking_no', '=', 'apps.tracking_no');
                    })
                ->leftJoin('process_status as ps',
                    function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw(10));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw(11));
                    })
                ->where('ms.license_no', $request->license_no)
                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 10)
                ->first([
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
                    'apps.org_district as reg_office_district',
                    'apps.org_upazila as reg_office_thana',
                    'apps.org_address as reg_office_address',
                    'apps.org_permanent_district as op_office_district',
                    'apps.org_permanent_upazila as op_office_thana',
                    'apps.org_permanent_address as op_office_address',
                    'apps.applicant_upazila as applicant_thana',
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
                ]);
        } else {
            $data['license_no'] = $request->license_no;
            $data['appInfo'] = ProcessList::leftJoin('nix_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('nix_license_master as ms',
                    function ($join) use ($process_type_id) {
                        $join->on('ms.issue_tracking_no', '=', 'apps.tracking_no');
                    })
                ->leftJoin('process_status as ps',
                    function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw(9));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw(11));
                    })
                ->where('ms.license_no', $request->license_no)
                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 9)
                ->first([
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

                    'apps.per_office_district as op_office_district',
                    'apps.per_office_thana as op_office_thana',
                    'apps.per_office_address as op_office_address',
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
                ]);
        }



        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage'] = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');

        $data['divisions'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'asc')->pluck('area_nm', 'area_id')->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        if (empty($data['appInfo'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])
            ->get([
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
            ]);

        foreach ($shareholders_data as $index => $value) {
            if (public_path($value->shareholders_image) && !empty($value->shareholders_image)) {
                $value->shareholders_image = CommonFunction::imagePathToBase64(public_path($value->shareholders_image));
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }
        $data['contact_person'] = $contact_data;

        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();


        $public_html = (string)view('REUSELicenseIssue::NIX.Amendment.search', $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }
}
