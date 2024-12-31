<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;


use App\Models\User;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\ExistingCallCenterDetails;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\ProposalArea;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
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

class CallCenterRenew extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;
    protected $table = 'call_center_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id = 6;
    protected $chairman_desk_id = 3;
    protected $applicant_desk_id = 0;
    protected $shortfall_status_id = 5;
    protected $draft_status_id = - 1;
    protected $submitted_status_id = 1;
    private $re_submit_status_id = 2;


    public function createForm( $currentInstance ): string {
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $this->process_type_id;
        $data['acl_name']         = $this->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality']      = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                  ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( "REUSELicenseIssue::BPO.Renew.form", $data ) );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $issue_company_id      = CallCenterNew::where('license_no', $request->license_no)->value('company_id');
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        if ( empty( $data['license_no'] )) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $data['appInfo'] = ProcessList::leftJoin( 'call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->join( 'call_center_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->where( 'ms.license_no', $request->license_no )
                                      ->where( 'ms.status', 1 )
                                      ->first( [
                                          'ms.license_issue_date',
                                          'ms.expiry_date',
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
                                          'apps.company_name as org_nm',
                                          'apps.company_type as org_type',
                                          'apps.noc_district as op_office_district',
                                          'apps.noc_thana as op_office_thana',
                                          'apps.noc_address as op_office_address',
                                          'apps.noc_address2 as op_office_address2',
                                      ] );
        if ( empty( $data['appInfo'] ) ) {
            $companyId               = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['division']        = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['nationality']     = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                     ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
            $data['process_type_id'] = $this->process_type_id;


            $public_html = strval( view( 'REUSELicenseIssue::BPO.Renew.search-blank', $data ) );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $this->process_type_id;

        $shareholders_data = Shareholder::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => 5 // 5 = Call Center issue process type id
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


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => 5
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['image']         = ! empty( $value->shareholders_image ) ? CommonFunction::imagePathToBase64( public_path( $item->image ) ) : '';
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['proposal_area'] = ProposalArea::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();

        $data['companyUserType'] = CommonFunction::getCompanyUserType();


        $public_html = (string) view( 'REUSELicenseIssue::BPO.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {
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
        // dd($request->all());

        $appData = $this->storeLicenseData( $appData, $request );

        if ( $appData->id ) {
            //dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            // Contact Person data insert
            CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id, );
            // Proposal Area data insert
            $this->storeProposalAreaData( $appData->id, $request );
            // Share Holder Data insert
            CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );
            // store Existing Call Center Details
            $this->storeExistingCallCenterDetails( $appData->id, $request );

            $this->storeProcessListData( $request, $processData, $appData );
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('BPO', $this->process_type_id, $processData->id, $this->table, 'REN', $appData->id);
        }

        // Payment info
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id )) {
            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isptspli_type, 1 );
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );

            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }

        if ( $request->get( 'actionBtn' ) == 'submit'  && $processData->status_id != $this->re_submit_status_id ) {
//            if ( empty( $appData->tracking_no ) ) {
//                $trackingPrefix = 'BPOR-CC-' . date( "Ymd" ) . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
//                #CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
//                //CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }

            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();

                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'BPO/ Call Center Registration Renew',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);

            // for Pay Order
            if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit' ) {
                $unfixed_amount_array = [
                    1 => 0,
                    2 => $request->get( 'pay_amount' ),
                    3 => 0,
                    4 => 0,
                    5 => $request->get( 'vat_on_pay_amount' ),
                    6 => 0
                ];
                $contact_info         = [
                    'contact_name'    => $request->get( 'contact_name' ),
                    'contact_email'   => $request->get( 'contact_email' ),
                    'contact_no'      => $request->get( 'contact_no' ),
                    'contact_address' => $request->get( 'contact_address' ),
                ];
                $this->storeSubmissionFeeDataV2( $appData->id, 1, $contact_info, $unfixed_amount_array, $request );
            }

            // Send mail to desk officer for notifying that an application has been submitted

//            $getAllDeskUserForSendMail = User::where([
//                'user_type' => '4x404',
//                'desk_id' => 1,
//            ])->get()->toArray();


//            if(!empty($getAllDeskUserForSendMail)){
//                foreach ($getAllDeskUserForSendMail as $user){
//                    $receiverInfo = [
//                        array(
//                            'user_mobile' => $user['user_mobile'],
//                            'user_email' => $user['user_email']
//                        )
//                    ];
//                    CommonFunction::sendEmailSMS( 'DESK_PROCESS_MAIL_TO_DESK_OFFICER', $appInfo, $receiverInfo );
//                }
//            }


        }

        // Send Email notification to user on application re-submit
        if ( $processData->status_id == $this->re_submit_status_id ) {
//            CommonFunction::sendEmailForReSubmission( $processData );

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'BPO/ Call Center Registration Renew',
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

        return redirect( 'client/bpo-or-call-center-new-app/list/' . Encryption::encodeId( $this->process_type_id ) );

    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin( 'call_center_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
                                      ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
                                      ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.noc_district' )
                                      ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.noc_thana' )
                                      ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                                      ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
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
                                          'reg_off_district.area_nm as reg_office_district_en',
                                          'reg_off_thana.area_nm as reg_office_thana_en',
                                          'noc_dis.area_nm as op_office_district_en',
                                          'noc_thana.area_nm as op_office_thana_en',
                                          'applicant_district.area_nm as applicant_district_en',
                                          'applicant_thana.area_nm as applicant_thana_en',
                                          'apps.*',
                                          'apps.company_name as org_nm',
                                          'apps.company_type as org_type',
                                          'apps.noc_address as op_office_address',
                                          'apps.noc_address2 as op_office_address2',

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


        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $process_type_id )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();


        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 1;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
        }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();


        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $process_type_id])->get();

        $contact_person_data = ContactPerson::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => 6  ] )->get(); // 5 = Call Center Issue Process type id

        foreach ( $contact_person_data as $index => $value ) {
            $value->contact_district_name = Area::where( 'area_id', $value->district )->value( 'area_nm' );
            $value->contact_upazila_name  = Area::where( 'area_id', $value->upazila )->value( 'area_nm' );
        }
        $data['contact_person'] = $contact_person_data;
        $proposal_area_data = RenewProposalArea::where( [ 'call_center_renew_id' => $data['appInfo']['id'] ] )->get();

        foreach ( $proposal_area_data as $index => $value ) {
            $disInfo                  = Area::where( 'area_id', $value->proposal_district )->first( [
                'area_id',
                'area_nm'
            ] );
            $value->proposal_district = $disInfo->area_nm;
            $thanaInfo                = Area::where( 'area_id', $value->proposal_thana )->first( [
                'area_id',
                'area_nm'
            ] );
            $value->proposal_thana    = $thanaInfo->area_nm;
        }
        $data['proposal_area'] = $proposal_area_data;

//        $proposal_area_data = RenewProposalArea::where( [ 'call_center_renew_id' => $data['appInfo']['id'] ] )->get();
//
//        foreach ( $proposal_area_data as $index => $value ) {
//            $value->proposal_district = Area::where( 'area_id', $value->proposal_district )->value( 'area_nm' );
//            $value->proposal_thana    = Area::where( 'area_id', $value->proposal_thana )->value( 'area_nm' );
//        }
//        $data['proposal_area'] = $proposal_area_data;


        $existingCallCenterData = ExistingCallCenterDetails::where( 'call_center_renew_id', $data['appInfo']['id'] )->get();
        foreach ( $existingCallCenterData as $index => $value ) {
            $value->district = Area::where( 'area_id', $value->district )->value( 'area_nm' );
            $value->thana    = Area::where( 'area_id', $value->thana )->value( 'area_nm' );
        }
        $data['existingCallCenterData'] = $existingCallCenterData;
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();


        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $public_html = (string) view( "REUSELicenseIssue::BPO.Renew.view", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $this->process_type_id;

        $applicationId     = Encryption::decodeId( $applicationId );
        $process_type_id   = $this->process_type_id;
        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        $data['appInfo']     = ProcessList::leftJoin( 'call_center_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                                          ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                              $join->on( 'ps.id', '=', 'process_list.status_id' );
                                              $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
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
                                              'apps.company_name as org_nm',
                                              'apps.company_type as org_type',
                                              'apps.noc_district as op_office_district',
                                              'apps.noc_thana as op_office_thana',
                                              'apps.noc_address as op_office_address',
                                              'apps.noc_address2 as op_office_address2',
                                          ] );


        $shareholders_data = Shareholder::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $processTypeId
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
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }

        $data['contact_person'] = $contact_data;

        $data['proposal_area'] = RenewProposalArea::where( [ 'call_center_renew_id' => $data['appInfo']['id'] ] )->get();
        $data['existingCallCenterDetails'] = ExistingCallCenterDetails::where( [ 'call_center_renew_id' => $data['appInfo']['id'] ] )->get();

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        if ( $data['appInfo']->issue_tracking_no ) {
            $public_html = (string) view( 'REUSELicenseIssue::BPO.Renew.form-edit', $data );
        } else {
            $public_html = (string) view( 'REUSELicenseIssue::BPO.Renew.form-edit-v2', $data );
        }

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    private function unfixedAmountsForGovtServiceFee( $payment_step_id ) {
        $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        if ( empty( $vat_percentage ) ) {
            DB::rollback();
            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );

            return redirect()->back()->withInput();
        }
        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => 0, // Govt-Vat-Fee
            6 => 0 //govt-vendor-vat-fee
        ];

        return $unfixed_amount_array;
    }

    private function storeLicenseData( $LicenseIssueObj, $request ) {

        $LicenseIssueObj->company_id               = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->company_name             = $request->get( 'company_name' );
        $LicenseIssueObj->issue_tracking_no        = ! empty( $request->get( 'issue_tracking_no' ) ) ? Encryption::decodeId( $request->get( 'issue_tracking_no' ) ) : null;
        $LicenseIssueObj->license_no               = $request->get( 'license_no' );
        $LicenseIssueObj->license_issue_date       = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $LicenseIssueObj->expiry_date              = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;
        $LicenseIssueObj->company_type             = $request->get( 'company_type' );
        $LicenseIssueObj->reg_office_district      = $request->get( 'reg_office_district' );
        $LicenseIssueObj->reg_office_thana         = $request->get( 'reg_office_thana' );
        $LicenseIssueObj->reg_office_address       = $request->get( 'reg_office_address' );
        $LicenseIssueObj->reg_office_address2       = $request->get( 'reg_office_address2' );
        $LicenseIssueObj->noc_district             = $request->get( 'op_office_district' );
        $LicenseIssueObj->noc_thana                = $request->get( 'op_office_thana' );
        $LicenseIssueObj->noc_address              = $request->get( 'op_office_address' );
        $LicenseIssueObj->noc_address2             = $request->get( 'op_office_address2' );
        $LicenseIssueObj->applicant_name           = $request->get( 'applicant_name' );
        $LicenseIssueObj->applicant_district       = $request->get( 'applicant_district' );
        $LicenseIssueObj->applicant_thana          = $request->get( 'applicant_thana' );
        $LicenseIssueObj->applicant_address        = $request->get( 'applicant_address' );
        $LicenseIssueObj->applicant_address2        = $request->get( 'applicant_address2' );
        $LicenseIssueObj->applicant_email          = $request->get( 'applicant_email' );
        $LicenseIssueObj->applicant_mobile         = $request->get( 'applicant_mobile' );
        $LicenseIssueObj->applicant_telephone      = $request->get( 'applicant_telephone' );
        $LicenseIssueObj->present_business_actives = $request->get( 'present_business_actives' );
//        $LicenseIssueObj->proposal_service_type    = json_encode( $request->get( 'proposal_service_type' ) );
//        $LicenseIssueObj->proposal_service         = json_encode( $request->get( 'proposal_service' ) );
        $LicenseIssueObj->declaration_q1           = $request->get( 'declaration_q1' );
        $LicenseIssueObj->declaration_q1_text      = $request->get( 'declaration_q1_text' );
        $LicenseIssueObj->q1_application_date      = date( 'Y-m-d', strtotime( $request->get( 'declaration_q1_application_date' ) ) );
        $LicenseIssueObj->declaration_q2           = $request->get( 'declaration_q2' );
        $LicenseIssueObj->declaration_q2_text      = $request->get( 'declaration_q2_text' );
        $LicenseIssueObj->declaration_q3           = $request->get( 'declaration_q3' );
        $LicenseIssueObj->declaration_q3_text      = $request->get( 'declaration_q3_text' );
        $LicenseIssueObj->total_no_of_share        = $request->get( 'total_no_of_share' );
        $LicenseIssueObj->total_share_value        = $request->get( 'total_share_value' );
        $LicenseIssueObj->total_share_value        = $request->get( 'total_share_value' );

        $LicenseIssueObj->bandwidth_call_center     = $request->get( 'bandwidth_call_center' );
        $LicenseIssueObj->address_of_foreign        = $request->get( 'address_of_foreign' );
        $LicenseIssueObj->existing_bandwidth_iplc   = $request->get( 'existing_bandwidth_iplc' );
        $LicenseIssueObj->existing_bandwidth_backup = $request->get( 'existing_bandwidth_backup' );
        $LicenseIssueObj->bandwidth_provider_iplc   = $request->get( 'bandwidth_provider_iplc' );
        $LicenseIssueObj->bandwidth_provider_backup = $request->get( 'bandwidth_provider_backup' );
        $LicenseIssueObj->local_employee            = $request->get( 'local_employee' );
        $LicenseIssueObj->foreign_employee          = $request->get( 'foreign_employee' );
        $LicenseIssueObj->fast_financial_years      = $request->input( 'financial_years' )[1] ?? null;
        $LicenseIssueObj->fast_financial_amount     = $request->input( 'financial_amount' )[1] ?? null;
        $LicenseIssueObj->second_financial_years    = $request->input( 'financial_years' )[2] ?? null;
        $LicenseIssueObj->second_financial_amount   = $request->input( 'financial_amount' )[2] ?? null;
        //images
        if ( $request->hasFile( 'declaration_q3_images' ) ) {
            $yearMonth = date( "Y" ) . "/" . date( "m" ) . "/";
            $path      = 'uploads/isp-license-issue/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'declaration_q3_images' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $LicenseIssueObj->declaration_q3_images = $path . $file_path;
        }
        //images
        $LicenseIssueObj->status     = 1;
        $LicenseIssueObj->updated_at = Carbon::now();

        //Updated Information for Resubmit application
        //trade file
        $LicenseIssueObj->trade_license_number          = $request->get('trade_license_number');
        $LicenseIssueObj->trade_validity                = $request->get('trade_validity');
        $LicenseIssueObj->trade_address                 = $request->get('trade_address');
        $LicenseIssueObj->tax_clearance                 = $request->get('tax_clearance');
        $LicenseIssueObj->current_trade_license_number  = $request->get('current_trade_license_number');
        $LicenseIssueObj->current_tax_clearance         = $request->get('current_tax_clearance');
        $LicenseIssueObj->current_trade_validity        = $request->get('current_trade_validity');
        $LicenseIssueObj->current_trade_address         = $request->get('current_trade_address');

        if ( $request->hasFile( 'trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->tax_clearance_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_tax_clearance_attachment = $path . $simple_file_name;
        }


        //House rental
        $LicenseIssueObj->house_rental_address              = $request->get('house_rental_address');
        $LicenseIssueObj->house_rental_validity             = $request->get('house_rental_validity');
        $LicenseIssueObj->current_house_rental_address      = $request->get('current_house_rental_address');
        $LicenseIssueObj->current_house_rental_validity     = $request->get('current_house_rental_validity');


        if ( $request->hasFile( 'house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->house_rental_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_house_rental_attachment = $path . $simple_file_name;
        }

        //ISPAB
        $LicenseIssueObj->ispab_validity                = $request->get('ispab_validity');
        $LicenseIssueObj->current_ispab_validity        = $request->get('current_ispab_validity');

        if ( $request->hasFile( 'ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'ispab_attachment' );
            $simple_file_name = trim( uniqid( 'ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->ispab_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_ispab_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_ispab_attachment = $path . $simple_file_name;
        }

        //Shareholder
        $LicenseIssueObj->number_of_share                   = $request->get('number_of_share');
        $LicenseIssueObj->shareholders_name                 = $request->get('shareholders_name');
        $LicenseIssueObj->shareholders_nid_passport         = $request->get('shareholders_nid_passport');
        $LicenseIssueObj->current_number_of_share           = $request->get('current_number_of_share');
        $LicenseIssueObj->current_shareholders_name         = $request->get('current_shareholders_name');
        $LicenseIssueObj->current_shareholders_nid_passport = $request->get('current_shareholders_nid_passport');
        if ( $request->hasFile( 'shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->shareholders_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $LicenseIssueObj->current_shareholders_attachment = $path . $simple_file_name;
        }
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }

    private function storeProcessListData( $request, $processListObj, $appData ) {

        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();
        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ( $request->get( 'actionBtn' ) === 'draft' ) {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id   = 0;
        } elseif ( $processListObj->status_id === $this->shortfall_status_id ) {
            // For shortfall
            $submission_sql_param = [
                'app_id'          => $appData->id,
                'process_type_id' => $this->process_type_id,
            ];

            $process_type_info = ProcessType::where( 'id', $this->process_type_id )
                                            ->orderBy( 'id', 'desc' )
                                            ->first( [
                                                'form_url',
                                                'process_type.process_desk_status_json',
                                                'process_type.name'
                                            ] );

            $resubmission_data              = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
            $processListObj->status_id      = $resubmission_data['process_starting_status'];
            $processListObj->desk_id        = $resubmission_data['process_starting_desk'];
            $processListObj->process_desc   = 'Re-submitted form applicant';
            $processListObj->resubmitted_at = Carbon::now(); // application resubmission Date

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value    = Encryption::encode( $resultData );

        } else {// submit
            if($request->payment_type =='online_payment'){
                $processListObj->status_id = -1; // online payment will be draft until confirmed. Confirmation done at
                // callbackMultiple function of app\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController.php
                $processListObj->desk_id   = 0; // desk will be of applicant himself.
            }else{
                $processListObj->status_id = $this->submitted_status_id;
                $processListObj->desk_id   = $this->chairman_desk_id;
            }

        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->license_no = $appData->license_no;
        $processListObj->office_id       = 0;
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode( $jsonData );
        $processListObj->submitted_at    = Carbon::now();
        $processListObj->save();

        return $processListObj;

    }

    private function storeProposalAreaData( $appDataId, $request ) {
        if ( isset( $request->proposal_district ) && count( $request->proposal_district ) > 0 ) {
            RenewProposalArea::where( 'call_center_renew_id', $appDataId )->delete();
            $proposalAreaData = [];
            foreach ( $request->proposal_district as $index => $value ) {
                $proposalAreaData[] = [
                    'call_center_renew_id' => $appDataId,
                    'proposal_district'    => $request->proposal_district[ $index ] ?? 0,
                    'proposal_thana'       => $request->proposal_thana[ $index ] ?? 0,
                    'proposal_address'     => $request->proposal_address[ $index ] ?? null,
                    'proposal_no_of_seats' => $request->proposal_no_of_seats[ $index ] ?? null,
                    'proposal_employee'    => $request->proposal_employee[ $index ] ?? null,
                    'local'                => $request->local[ $index ] ?? null,
                    'expatriate'           => $request->expatriate[ $index ] ?? null,
                    'created_at'           => now()
                ];
            }
            RenewProposalArea::insert( $proposalAreaData );
        }
    }

    private function storeExistingCallCenterDetails( $appDataId, $request ) {
        $existingCallCenterCount = $request->input( 'existing_call_center_count' );

        if ( ! empty( $existingCallCenterCount ) ) {
            $existingCallCenterDetails = [];

            foreach ( $request->existing_district as $index => $value ) {
                $existingCallCenterDetails[] = [
                    'call_center_renew_id'      => $appDataId,
                    'district'                  => $value,
                    'thana'                     => $request->input( 'existing_thana' )[ $index ] ?? 0,
                    'address'                   => $request->input( 'existing_address' )[ $index ] ?? null,
                    'nature_of_center'          => $request->input( 'nature_of_center' )[ $index ] ?? null,
//                    'type_of_center'            => $request->input( 'type_of_center' )[ $index ] ?? null,
                  //  'name_call_center_provider' => $request->input( 'name_call_center_provider' )[ $index ] ?? null,
                    'existing_license_no'       => $request->input( 'existing_license_no' )[ $index ] ?? null,
                    'date_of_license'           => ! empty( $request->input( 'date_of_license' )[ $index ] ) ? date( 'Y-m-d', strtotime( $request->input( 'date_of_license' )[ $index ] ) ) : null,
                    'starting_date_of_service'  => ! empty( $request->input( 'starting_date_of_service' )[ $index ] ) ? date( 'Y-m-d', strtotime( $request->input( 'starting_date_of_service' )[ $index ] ) ) : null,
                    'no_of_agents'              => $request->input( 'no_of_agents' )[ $index ] ?? null,
                    'bandwidth'                 => $request->input( 'bandwidth' )[ $index ] ?? null,
                    'name_of_clients'           => $request->input( 'name_of_clients' )[ $index ] ?? null,
                    'type_of_activity'          => $request->input( 'type_of_activity' )[ $index ] ?? null,
                    'status'                    => 1,
                    'created_at'                => now(),
                ];
            }
            ExistingCallCenterDetails::where( 'call_center_renew_id', $appDataId )->delete();
            ExistingCallCenterDetails::insert( $existingCallCenterDetails );
        }

    }
}
