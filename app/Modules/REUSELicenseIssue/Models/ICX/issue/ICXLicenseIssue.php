<?php

namespace App\Modules\REUSELicenseIssue\Models\ICX\issue;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ICXLicenseIssue extends Model implements FormInterface {

    protected $table = 'icx_license_issue';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = -1;
    private $submitted_status_id = 1;
    private $applicant_desk_id = 0;
    private $dg_desk_id = 3;

    use SPPaymentManager;
    use SPAfterPaymentManager;

    public function createForm( $currentInstance ): string {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $this->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $this->process_type_id )->value( 'name' );
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']  = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( 'REUSELicenseIssue::ICX.Issue.master', $data ) );

    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {

        $this->process_type_id = $currentInstance->process_type_id;
        if ( $request->get( 'app_id' ) ) {
            $appData     = ICXLicenseIssue::find( Encryption::decodeId($request->get( 'app_id' )) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new ICXLicenseIssue();
            $processData = new ProcessList();
        }


        $appData = $this->storeICXLicenseData( $appData, $request );

        if ( $appData->id ) {

            //shareholder data insert operation
            if ( $request->get( 'shareholderDataCount' ) > 0 ) {
                CommonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id );
            }
          // dd($request->get( 'contactPersonDataCount'));
            // contact person data insert operation

                CommonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id );



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

                    $resultData = $processData->id . '-' . $processData->tracking_no .
                        $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                        $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? "";
                    $processData->hash_value    = Encryption::encode( $resultData );

                } else {
                    $processData->status_id = 1;
                    $processData->submitted_at = Carbon::now();
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                }
            }

            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name'] = $request->company_org_name;
//                $jsonData['Office name'] = CommonFunction::getBSCICOfficeName($request->get('pref_reg_office'));
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );

            $processData->save();
            //process list data insert
        }

        //  Required Documents for attachment
        $doc_type_id = '';
        DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );


        //=================================================payment code==========================
        // Payment info will not be updated for resubmit
//            if ( $processData->status_id != 2 && ! empty( $appData->company_type ) ) {
//
//                $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isp_license_type, 1 );
//                $contact_info         = [
//                    'contact_name'    => $request->contact_name,
//                    'contact_email'   => $request->contact_email,
//                    'contact_no'      => $request->contact_no,
//                    'contact_address' => $request->contact_address,
//                ];
//                $payment_id           = $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array );
//            }
//        $check_payment_type = false;
        if ((isset($request->payment_type) || $processData->status_id == 2)) {
            $unfixed_amount_array = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0
            ];
            $contact_info = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];

            $check_payment_type = (!empty($request->get('payment_type')) && $request->get('payment_type') === 'pay_order');

            $payment_id = !$check_payment_type ? $this->storeSubmissionFeeData($appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }

        if ($check_payment_type) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $request->get('pay_amount'), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => $request->get('vat_on_pay_amount'), // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
            $contact_info = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];
            $this->storeSubmissionFeeDataV2($appData->id, 1, $contact_info, $unfixed_amount_array, $request);
        }

        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('ICX', $this->process_type_id, $processData->id, $this->table, 'ISS', $appData->id);
        }
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {

            DB::commit();

            //return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'Icx License Issue',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }
        DB::commit();
        if (in_array($processData->status_id, [1,2])){
           CommonFunction::DNothiRequest($processData->id, $request->get( 'actionBtn' ));
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

        return redirect( 'client/icx-license-issue/list/' . Encryption::encodeId( $this->process_type_id ) );

    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin( 'icx_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'pay_order_payment as pop', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'pop.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'pop.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                                      ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
                                      ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
                                      ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.permanent_office_district' )
                                      ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.permanent_office_thana' )
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
                                          'reg_office_district.area_nm as reg_office_district_en',
                                          'reg_office_thana.area_nm as reg_office_thana_en',
                                          'op_office_district.area_nm as op_office_district_en',
                                          'op_office_thana.area_nm as op_office_thana_en',
                                          'applicant_district.area_nm as applicant_district_en',
                                          'applicant_thana.area_nm as applicant_thana_en',
                                          'apps.company_org_name as org_nm',
                                          'apps.company_type as org_type',
                                          'apps.permanent_office_address as op_office_address',
                                          'apps.applicant_mobile_no as applicant_mobile',
                                          'apps.applicant_telephone_no as applicant_telephone',
                                          'apps.declaration_q1_textarea as declaration_q1_text',
                                          'apps.declaration_q2_textarea as declaration_q2_text',
                                          'apps.declaration_q3_textarea as declaration_q3_text',
                                          'apps.applicant_telephone_no as applicant_telephone',
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



        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $process_type_id )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


//        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
//            $data['payment_step_id'] = 1;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
//        }
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $decodedAppId, 'process_type_id' => $process_type_id])->get();


        $contact_person_data = ContactPerson::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => 33  ] )->get(); // 5 = Call Center Issue Process type id

        foreach ( $contact_person_data as $index => $value ) {
            $value->contact_district_name = Area::where( 'area_id', $value->district )->value( 'area_nm' );
            $value->contact_upazila_name  = Area::where( 'area_id', $value->upazila )->value( 'area_nm' );
        }

        $data['contact_person'] = $contact_person_data;

        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment

            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];;
        }



        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( "REUSELicenseIssue::ICX.Issue.masterView", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $decoded_process_type_id, $applicationId ): JsonResponse {
        $data['process_type_id'] = $decoded_process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        $applicationId           = Encryption::decodeId( $applicationId );
      //  dd($applicationId);
        $process_type_id         = $decoded_process_type_id;


        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']     = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo'] = ProcessList::leftJoin( 'icx_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'pay_order_payment as pop', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'pop.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'pop.process_type_id', '=', DB::raw( $process_type_id ) );
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
                                          'apps.company_org_name as org_nm',
                                          'apps.company_type as org_type',
                                          'apps.reg_office_address as op_office_address',
                                          'apps.permanent_office_district as op_office_district',
                                          'apps.permanent_office_thana as op_office_thana',
                                          'apps.applicant_mobile_no as applicant_mobile',
                                          'apps.applicant_telephone_no as applicant_telephone',
                                          'apps.declaration_q1_textarea as declaration_q1_text',
                                          'apps.declaration_q2_textarea as declaration_q2_text',
                                          'apps.declaration_q3_textarea as declaration_q3_text',

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
                                          'pop.contact_name as pop_name',
                                          'pop.contact_email as pop_email',
                                          'pop.contact_no as pop_mobile',
                                          'pop.address as pop_address',
                                          'pop.is_pay_order_verified'
                                      ] );

        $data['companyUserType'] = CommonFunction::getCompanyUserType();

        $shareholders_data = Shareholder::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $process_type_id
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

        /** Fetch data from isp_license_contact_person */
        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $applicationId,
            'process_type_id' => $process_type_id
        ] )->get();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        $data['pay_order_info'] = DB::table( 'pay_order_payment' )
                                    ->where( [
                                        'app_id'          => $data['appInfo']['id'],
                                        'process_type_id' => $process_type_id,
                                        'payment_step_id' => 1
                                    ] )->first();
        if ( ! empty( $data['pay_order_info']->pay_order_date ) ) {
            $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        }
        if ( ! empty( $data['pay_order_info']->bg_expire_date ) ) {
            $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
        }

        $public_html = (string) view( 'REUSELicenseIssue::ICX.Issue.masterEdit', $data );

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

    private function storeICXLicenseData( $icxLicenseIssueObj, $request ) {
        $icxLicenseIssueObj->company_id   = CommonFunction::getUserCompanyWithZero();
        $icxLicenseIssueObj->company_org_name = $request->get('company_name');
        $icxLicenseIssueObj->company_type = $request->get('company_type');

        $icxLicenseIssueObj->reg_office_district = $request->get( 'reg_office_district' );
        $icxLicenseIssueObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $icxLicenseIssueObj->reg_office_address  = $request->get( 'reg_office_address' );

        $icxLicenseIssueObj->permanent_office_district = $request->get( 'op_office_district' );
        $icxLicenseIssueObj->permanent_office_thana    = $request->get( 'op_office_thana' );
        $icxLicenseIssueObj->permanent_office_address  = $request->get( 'op_office_address');


        $icxLicenseIssueObj->applicant_name      = $request->get( 'applicant_name' );
        $icxLicenseIssueObj->applicant_mobile_no    = $request->get( 'applicant_mobile' );
        $icxLicenseIssueObj->applicant_telephone_no = $request->get('applicant_telephone');
        $icxLicenseIssueObj->applicant_email     = $request->get( 'applicant_email' );
        $icxLicenseIssueObj->applicant_district  = $request->get( 'applicant_district' );
        $icxLicenseIssueObj->applicant_thana     = $request->get( 'applicant_thana' );
        $icxLicenseIssueObj->applicant_address   = $request->get( 'applicant_address' );
        $icxLicenseIssueObj->applicant_website   = $request->get( 'applicant_website' );

        $icxLicenseIssueObj->total_share_value   = $request->get( 'total_share_value');
        $icxLicenseIssueObj->total_no_of_share   = $request->get( 'total_no_of_share');

        $icxLicenseIssueObj->declaration_q1      = $request->get( 'declaration_q1' );
        $icxLicenseIssueObj->declaration_date_of_application = $request->get( 'declaration_date_of_application' );
        $icxLicenseIssueObj->declaration_q1_textarea      = $request->get('declaration_q1_text');
        $icxLicenseIssueObj->declaration_q2      = $request->get( 'declaration_q2' );
        $icxLicenseIssueObj->declaration_q2_textarea = $request->get( 'declaration_q2_text' );
        $icxLicenseIssueObj->declaration_q3      = $request->get( 'declaration_q3' );
        $icxLicenseIssueObj->declaration_q3_textarea = $request->get( 'declaration_q3_text');
        $icxLicenseIssueObj->declaration_q3__date_of_application = $request->get( 'declaration_date_of_application' );
        $icxLicenseIssueObj->declaration_q4      = $request->get( 'declaration_q4');
        $icxLicenseIssueObj->declaration_q4_period_of_involvement = $request->get('declaration_q4_period_of_involvement');
        $icxLicenseIssueObj->declaration_q4_case_no    = $request->get( 'declaration_q4_case_no');
        $icxLicenseIssueObj->declaration_q4_amount     = $request->get('declaration_q4_amount');
        $icxLicenseIssueObj->declaration_q4_cheque_or_bank_draft     = $request->get( 'declaration_q4_cheque_or_bank_draft');
        $icxLicenseIssueObj->declaration_q4_2      = $request->get( 'declaration_q4_2');
        //images
//        if ( $request->hasFile( 'declaration_q3_images' ) ) {
//            $yearMonth = date( "Y" ) . "/" . date( "m" ) . "/";
//            $path      = 'uploads/icx-license-issue/' . $yearMonth;
//            if ( ! file_exists( $path ) ) {
//                mkdir( $path, 0777, true );
//            }
//            $_file_path = $request->file( 'declaration_q3_images' );
//            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
//            $_file_path->move( $path, $file_path );
//            $icxLicenseIssueObj->declaration_q3_doc = $path . $file_path;
//        }

        $icxLicenseIssueObj->status     = 1;
        $icxLicenseIssueObj->updated_at = Carbon::now();

        $icxLicenseIssueObj->save();

        return $icxLicenseIssueObj;
    }

    public function unfixedAmountsForGovtApplicationFee( $isp_license_type, $payment_step_id ) {
        $vat_percentage = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        if ( empty( $vat_percentage ) ) {
            DB::rollback();
            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );

            return redirect()->back()->withInput();
        }

//        $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
//            'process_type_id' => $this->process_type_id,
//            'payment_step_id' => $payment_step_id,
//            'license_type_id' => $isp_license_type,
//            'status'          => 1,
//        ] )->first();

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
}
