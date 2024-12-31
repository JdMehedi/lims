<?php
/**
 * Author: Mehedi Hasan Ahad
 * Date: 12 March, 2023
 */

namespace App\Modules\REUSELicenseIssue\Models\TVAS\amendment;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
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
use App\Modules\REUSELicenseIssue\Models\TVAS\amendment\TVASLicenseMaster;

class TVASLicenseAmendment extends Model implements FormInterface {

    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'tvas_license_amendment';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    protected $chairman_desk_id    = 1;
    protected $applicant_desk_id   = 0;
    protected $shortfall_status_id = 5;
    protected $draft_status_id     = - 1;
    protected $submitted_status_id = 1;


    public function createForm($currentInstance): string
    {
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $currentInstance->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality']      = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                  ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        return strval( view( "REUSELicenseIssue::TVAS.Amendment.master", $data ) );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        if ( $request->get( 'app_id' ) ) {
            $appData     = TVASLicenseAmendment::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new TVASLicenseAmendment();
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
            $processData->company_id = CommonFunction::getUserCompanyWithZero() ?? 0;

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
                    $process_type_info = ProcessType::where( 'id', $this->process_type_id )
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
                    $processData->status_id = 1;
                    $processData->desk_id   = 3;
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->license_no      = $appData->license_no;
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
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('TVAS', $currentInstance->process_type_id, $processData->id, $this->table, 'AMM', $appData->id);
        }


        // Payment info will not be updated for resubmit
//        if ( $processData->status_id != 2 && ! empty( $appData->company_type ) ) {
//
//            $fixed_amount_array = [
//                1 => 0,
//                2 => 0,
//                3 => 0,
//                4 => 0,
//                5 => 0,
//                6 => 0
//            ];
//            $contact_info       = [
//                'contact_name'    => $request->applicant_name,
//                'contact_email'   => $request->applicant_email,
//                'contact_no'      => $request->applicant_mobile_no,
//                'contact_address' => $request->applicant_address,
//            ];
//            $check_payment_type = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
//            $payment_id         = ! $check_payment_type ? $currentInstance->storeSubmissionFeeData( $appData->id, 1, $contact_info, $fixed_amount_array, $request ) : '';
//
//        }
        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {

//            if ( empty( $processData->tracking_no ) ) {
//                $trackingPrefix = 'TVASA-' . date( "Ymd" ) . '-';
//                commonFunction::generateTrackingNumber( $trackingPrefix, $currentInstance->process_type_id, $processData->id, $appData->id, $this->table );
//            }

//            if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit') {
//                $this->storeSubmissionFeeDataV2( $appData->id, 1, $contact_info, $fixed_amount_array, $request );
//            }

            DB::commit();
//            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
//                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
//            }

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'TVAS License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'TVAS License Amendment',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);

        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'TVAS License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'TVAS License Amendment',
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

        return redirect( 'client/tvas-license-amendment/list/' . Encryption::encodeId( $currentInstance->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $decodedAppId = Encryption::decodeId( $applicationId );

        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                      ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
                                      ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
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
                                          'applicant_district.area_nm as applicant_dis_nm',
                                          'applicant_thana.area_nm as applicant_thana_nm',
                                          'reg_off_district.area_nm as reg_off_dis_nm',
                                          'reg_off_thana.area_nm as reg_off_thana_nm',
                                          'op_office_district.area_nm as op_office_district_nm',
                                          'op_office_thana.area_nm as op_office_thana_nm',
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

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();

        // for sub-view
        $shareholders_data = Shareholder::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => $process_type_id  ] )
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


        foreach ( $shareholders_data as $index => $value ) {
//            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
//                $value->image = CommonFunction::imagePathToBase64( public_path( $value->image ) );
//            }
            $nationality        = Countries::where( 'id', $value->nationality )->first( [ 'name' ] );
            $value->nationality = $nationality->name;
        }
//        $data['shareholders_data'] = $shareholders_data;
        $data['appShareholderInfo'] = $shareholders_data; // for sub-view

        $contact_person_data = ContactPerson::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => $process_type_id  ] )->get();

//        foreach ( $contact_person_data as $index => $value ) {
//            $value->district = Area::where( 'area_id', $value->district )->value( 'area_nm' );
//            $value->upazila = Area::where( 'area_id', $value->upazila )->value( 'area_nm' );
//        }

        $data['contact_person'] = $contact_person_data;

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



        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Amendment.masterView', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $this->process_type_id   = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId = Encryption::decodeId( $applicationId );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'tvas_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
                                        ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                            $join->on( 'ps.id', '=', 'process_list.status_id' );
                                            $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ));
                                        })
                                        ->where( 'process_list.ref_id', $applicationId)
                                        ->where( 'process_list.process_type_id', $process_type_id)
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
                                            'apps.*'
                                        ]);

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where( [
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
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }
        $data['contact_person'] = $contact_data;
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Amendment.masterEdit', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $process_type_id = $data['process_type_id'] = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        $data['master_data'] = TVASLicenseMaster::where('license_no', $request->license_no)->first();
        if ($data['master_data']->renew_tracking_no) {
            $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                                          ->leftJoin('tvas_license_master as ms',
                                              function ( $join ) use ( $process_type_id) {
                                                  $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                                              })
                                          ->leftJoin('process_status as ps',
                                              function ( $join ) use ( $process_type_id) {
                                                  $join->on( 'ps.id', '=', 'process_list.status_id' );
                                                  $join->on( 'ps.process_type_id', '=', DB::raw( 26));
                                              })
                                          ->where( 'ms.license_no', $request->license_no)
                                          ->where( 'ms.status', 1 )
                                          ->where('process_list.process_type_id', 26)
                                          ->orderByDesc('id')
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
                                              'apps.per_office_district as op_office_district',
                                              'apps.per_office_thana as op_office_thana',
                                              'apps.per_office_address as op_office_address',

                                              'ms.issue_tracking_no',
                                              'apps.declaration_q1 as declaration_q1',
                                              'apps.declaration_q1_text as declaration_q1_text',
                                              'apps.declaration_q2 as declaration_q2',
                                              'apps.declaration_q2_text as declaration_q2_text',
                                              'apps.declaration_q3 as declaration_q3',
                                              'apps.dd_file_1 as dd_file_1'
                                          ] );

        } else {
            $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                          ->leftJoin( 'tvas_license_master as ms',
                                              function ( $join ) use ( $process_type_id ) {
                                                  $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                              } )
                                          ->leftJoin('process_status as ps',
                                              function ( $join ) use ( $process_type_id) {
                                                  $join->on( 'ps.id', '=', 'process_list.status_id' );
                                                  $join->on( 'ps.process_type_id', '=', DB::raw(25));
                                              })
                                          ->where( 'ms.license_no', $request->license_no )
                                          ->where( 'ms.status', 1 )
                                          ->where('process_list.process_type_id', 25)
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

                                              'apps.per_office_district as op_office_district',
                                              'apps.per_office_thana as op_office_thana',
                                              'apps.per_office_address as op_office_address',

                                              'ms.issue_tracking_no',
                                              'apps.declaration_q1 as declaration_q1',
                                              'apps.declaration_q1_text as declaration_q1_text',
                                              'apps.declaration_q2 as declaration_q2',
                                              'apps.declaration_q2_text as declaration_q2_text',
                                              'apps.declaration_q3 as declaration_q3',
                                              'apps.dd_file_1 as dd_file_1'
                                          ] );
        }

        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value('value');
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

        $public_html = (string) view( 'REUSELicenseIssue::TVAS.Amendment.search', $data );
        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
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

    private function storeLicenseData( $LicenseIssueObj, $request ) {
        $LicenseIssueObj->company_id               = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->company_name             = $request->get( 'company_name' );
        $LicenseIssueObj->company_type             = $request->get( 'company_type' );
        $LicenseIssueObj->license_no               = $request->get( 'license_no' );
        $LicenseIssueObj->reg_office_district      = $request->get( 'reg_office_district' );
        $LicenseIssueObj->reg_office_thana         = $request->get( 'reg_office_thana' );
        $LicenseIssueObj->reg_office_address       = $request->get( 'reg_office_address' );
        if( $request->has('permanent_office_district') && !empty($request->get('permanent_office_district'))){
            $LicenseIssueObj->op_office_district       = $request->get('permanent_office_district');
        }else{
            $LicenseIssueObj->op_office_district       = $request->get( 'noc_district' );
        }

        if( $request->has('permanent_office_thana') && !empty($request->get('permanent_office_thana'))){
            $LicenseIssueObj->op_office_thana      = $request->get('permanent_office_thana');
        }else{
            $LicenseIssueObj->op_office_thana      = $request->get( 'noc_thana' );
        }

        if( $request->has('permanent_office_address') && !empty($request->get('permanent_office_address'))){
            $LicenseIssueObj->op_office_address       = $request->get('permanent_office_address');
        }else{
            $LicenseIssueObj->op_office_address        = $request->get( 'noc_address' );
        }

        if( $request->has('applicant_telephone_no') && !empty($request->get('applicant_telephone_no'))){
            $LicenseIssueObj->applicant_telephone       = $request->get('applicant_telephone_no');
        }else{
            $LicenseIssueObj->applicant_telephone      = $request->get( 'applicant_telephone' );
        }

        if( $request->has('applicant_mobile_no') && !empty($request->get('applicant_mobile_no'))){
            $LicenseIssueObj->applicant_mobile       = $request->get('applicant_mobile_no');
        }else{
            $LicenseIssueObj->applicant_mobile         = $request->get( 'applicant_mobile' );
        }


        $LicenseIssueObj->applicant_name           = $request->get( 'applicant_name' );
        $LicenseIssueObj->applicant_district       = $request->get( 'applicant_district' );
        $LicenseIssueObj->applicant_thana          = $request->get( 'applicant_thana' );
        $LicenseIssueObj->applicant_address        = $request->get( 'applicant_address' );
        $LicenseIssueObj->applicant_email          = $request->get( 'applicant_email' );
        $LicenseIssueObj->declaration_q1           = $request->get( 'declaration_q1' );
        $LicenseIssueObj->declaration_q1_text      = $request->get( 'declaration_q1_text' );
        $LicenseIssueObj->q1_application_date      = date( 'Y-m-d', strtotime( $request->get( 'declaration_q1_application_date' ) ) );
        $LicenseIssueObj->declaration_q2           = $request->get( 'declaration_q2' );
        $LicenseIssueObj->declaration_q2_text      = $request->get( 'declaration_q2_text' );
        $LicenseIssueObj->declaration_q3           = $request->get( 'declaration_q3' );
        $LicenseIssueObj->declaration_q3_text      = $request->get( 'declaration_q3_text' );
        $LicenseIssueObj->q3_application_date      = date( 'Y-m-d', strtotime($request->get('declaration_q3_application_date')));
        $LicenseIssueObj->declaration_q4           = $request->get( 'declaration_q4' );
        $LicenseIssueObj->q4_license_number        = $request->get( 'q4_license_number' );
        $LicenseIssueObj->q4_case_no               = $request->get( 'q4_case_no' );
        $LicenseIssueObj->q4_amount                = $request->get( 'q4_amount' );
        $LicenseIssueObj->q4_bank_draft_no         = $request->get( 'q4_bank_draft_no' );
        $LicenseIssueObj->q4_given_comission       = $request->get( 'q4_given_comission' );
        $LicenseIssueObj->total_no_of_share        = $request->get( 'total_no_of_share' );
        $LicenseIssueObj->total_share_value        = $request->get( 'total_share_value' );
        $LicenseIssueObj->status                   = 1;
        $LicenseIssueObj->updated_at               = Carbon::now();
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }
}
