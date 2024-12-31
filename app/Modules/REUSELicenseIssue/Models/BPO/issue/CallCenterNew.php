<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\issue;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;

//use App\Modules\BPOCallCenterNew\Models\ContactPerson;
//use App\Modules\BPOCallCenterNew\Models\ProposalArea;
use App\Models\User;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
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

class CallCenterNew extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'call_center_issue';
    protected $guarded = [ 'id' ];
    public $timestamps = true;
    protected $process_type_id = 5;
    protected $acl_name = 'call_center_license_issue';
    protected $chairman_desk_id = 3;
    protected $applicant_desk_id = 0;
    protected $shortfall_status_id = 5;
    protected $draft_status_id = - 1;
    protected $submitted_status_id = 1;


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

        return strval( view( "REUSELicenseIssue::BPO.Issue.master", $data ) );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {

        if ( $request->get( 'app_id' ) ) {
            $appData     = CallCenterNew::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new CallCenterNew();
            $processData = new ProcessList();
        }

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

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == "draft" ) {
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
                    $processData->desk_id   = $this->chairman_desk_id;
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


        //Generate new Tracking number
        $tracking_no = "";
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            $tracking_no = CommonFunction::generateUniqueTrackingNumber('BPO', $this->process_type_id, $processData->id, $this->table, 'ISS', $appData->id);
        }

        if ( $request->get( 'actionBtn' ) == 'submit' ) {
//            if ( empty( $appData->tracking_no ) ) {
//                $trackingPrefix = 'BPO-CC-' . date( "Ymd" ) . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
//                //CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
//                //CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }

//            CommonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $tracking_no, $processData->id);
            DB::commit();

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Issue', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'BPO/ Call Center Registration Issue',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);



            // Send mail to desk officer for notifying that an application has been submitted

//            $getAllDeskUserForSendMail = User::where([
//                'user_type' => '4x404',
//                'desk_id' => 1,
//            ])->get()->toArray();
//
//
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
        if ( $processData->status_id == 2 ) {
//            $appInfo      = [
//                'app_id'            => $processData->ref_id,
//                'status_id'         => $processData->status_id,
//                'process_type_id'   => $processData->process_type_id,
//                'tracking_no'       => $processData->tracking_no,
//                'process_type_name' => 'BPO/Call Center',
//                'remarks'           => '',
//            ];
//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
//            //send email for application re-submission...
//            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
            $tracking_no = $processData->tracking_no;
//            CommonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $tracking_no, $processData->id);

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'BPO/ Call Center Registration Issue', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'BPO/ Call Center Registration Issue',
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

        return redirect( 'client/bpo-or-call-center-new-app/list/' . Encryption::encodeId( 5 ) );

    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin( 'call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                          'process_list.bulk_status as pr_bulk_status',
                                          'process_list.bulk_object as pr_bulk_object',
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
                                                          ->orderBy('created_at', 'desc')
                                                         ->get();


        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 1;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
        }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $process_type_id])->get();


        $contact_person_data = ContactPerson::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => 5  ] )->get(); // 5 = Call Center Issue Process type id

        foreach ( $contact_person_data as $index => $value ) {
            $value->contact_district_name = Area::where( 'area_id', $value->district )->value( 'area_nm' );
            $value->contact_upazila_name  = Area::where( 'area_id', $value->upazila )->value( 'area_nm' );
        }

        $data['contact_person'] = $contact_person_data;

        $proposal_area_data = ProposalArea::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();
//        dd($proposal_area_data);
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


        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( "REUSELicenseIssue::BPO.Issue.masterView", $data );

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
        $data['thana']     = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        $data['appInfo']     = ProcessList::leftJoin( 'call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
            'app_id'          => $applicationId,
            'process_type_id' => $process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }

        $data['contact_person'] = $contact_data;
        $data['proposal_area'] = ProposalArea::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $public_html = (string) view( "REUSELicenseIssue::BPO.Issue.masterEdit", $data );

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

    private function unfixedAmountsForGovtApplicationFee( $isp_license_type, $payment_step_id ) {
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
        $LicenseIssueObj->company_type             = $request->get( 'company_type' );
        $LicenseIssueObj->reg_office_district      = $request->get( 'reg_office_district' );
        $LicenseIssueObj->reg_office_thana         = $request->get( 'reg_office_thana' );
        $LicenseIssueObj->reg_office_address       = $request->get( 'reg_office_address' );
        $LicenseIssueObj->reg_office_address2       = $request->get( 'reg_office_address2' );
        $LicenseIssueObj->noc_district             = $request->get( 'op_office_district' );
        $LicenseIssueObj->noc_thana                = $request->get( 'op_office_thana' );
        $LicenseIssueObj->noc_address              = $request->get( 'op_office_address' );
        $LicenseIssueObj->noc_address2              = $request->get( 'op_office_address2' );
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
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }

    private function storeProposalAreaData( $appDataId, $request ) {
        if ( isset( $request->proposal_district ) && count( $request->proposal_district ) > 0 ) {
            ProposalArea::where( 'ref_id', $appDataId )->delete();
            foreach ( $request->proposal_district as $index => $value ) {
                $dataObj                       = new ProposalArea();
                $dataObj->ref_id               = $appDataId;
                $dataObj->proposal_district    = isset( $request->proposal_district[ $index ] ) ? $request->proposal_district[ $index ] : null;
                $dataObj->proposal_thana       = ! empty( $request->proposal_thana[ $index ] ) ? $request->proposal_thana[ $index ] : '';
                $dataObj->proposal_address     = isset( $request->proposal_address[ $index ] ) ? $request->proposal_address[ $index ] : null;
                $dataObj->proposal_no_of_seats = isset( $request->proposal_no_of_seats[ $index ] ) ? $request->proposal_no_of_seats[ $index ] : null;
                $dataObj->proposal_employee    = isset( $request->proposal_employee[ $index ] ) ? $request->proposal_employee[ $index ] : null;
                $dataObj->local                = isset( $request->local[ $index ] ) ? $request->local[ $index ] : null;
                $dataObj->expatriate           = isset( $request->expatriate[ $index ] ) ? $request->expatriate[ $index ] : null;
                $dataObj->created_at           = date( 'Y-m-d H:i:s' );
                $dataObj->save();
            }
        }
    }
}
