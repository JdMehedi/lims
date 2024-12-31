<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\SCS\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\SonaliPayment;
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

class SCSLicenseRenew extends Model implements FormInterface {
    protected $table = 'scs_license_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    // expect column for data store from nix_license_issue table
    use SPPaymentManager;
    use SPAfterPaymentManager;

    public $except_column = [
        'id',
        'tracking_no',
        'issue_tracking_no',
        'expiry_date',
        'issue_date',
        'certificate_link',
        'created_at',
        'updated_at'
    ];

    public function setvalue( $name, $value ) {
        $this->attributes[ $name ] = $value;
    }

    public function createForm($currentInstance): string
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['process_type_id'] = $process_type_id = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['acl_name'] = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval( view( "REUSELicenseIssue::SCS.Renew.form", $data ) );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {

        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );
        if ( empty( $license_no ) ) {
            Session::flash( 'error', 'Invalid License No [SCSR-006]' );

            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new SCSLicenseRenew();
            $processData = new ProcessList();
        }


        /** Fetch ISP License Issue existing data and prepare them for renew application */
        $this->fetchAndPrprIssueData( $license_no, $appData, $request );

        /** Prepare new additional data for renew application */
        $this->prprRenewData( $request, $appData , $license_no);
        $appData->save();

        if ( $appData->id ) {

            /** Store data to Share Holder Data */
            $this->storeShareHolderData( $license_no, $appData->id, $request );

            /** Store data to Contact Person Data */
            $this->storeContactPersonData( $license_no, $appData->id, $request );

            ## dynamic document start
            DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );
            ## dynamic document end

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
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                    $processData->submitted_at = Carbon::now();
                }
            }

            $processData->ref_id          = $appData->id;
            $processData->license_no          = $appData->license_no;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name'] = $appData->org_nm;
            #$jsonData['Office name'] = CommonFunction::getBSCICOfficeName($request->get('pref_reg_office'));
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );

            $processData->save();
            //process list data insert
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('SCS', $this->process_type_id, $processData->id, $this->table, 'REN', $appData->id);
        }

        // =================================================payment code==========================
        // Payment info will not be updated for resubmit
        $check_payment_type = false;
        if ((isset($request->payment_type) || $processData->status_id != 2) && !empty($appData->isp_license_type)) {
            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee($appData->isp_license_type, 1);
            $contact_info         = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];
            $check_payment_type   = (!empty($request->get('payment_type')) && $request->get('payment_type') === 'pay_order');
            $payment_id           = !$check_payment_type ? $this->storeSubmissionFeeData($appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }
        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == - 1 ) {


            if (isset($payment_id) &&  $request->get('payment_type') !== 'pay_order' ) {
                DB::commit();

                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {

            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            $trackingNumber = self::where('id','=', $processData->ref_id)->value('tracking_no');
            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'SCS License Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'SCS License Renew',
                'remarks'           => '',
            ];

            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        // for Pay Order
        if ($check_payment_type && $request->get('actionBtn') == 'submit') {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $request->get('pay_amount'), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => $request->get('vat_on_pay_amount'), // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
            $contact_info         = [
                'contact_name' => $request->get('contact_name'),
                'contact_email' => $request->get('contact_email'),
                'contact_no' => $request->get('contact_no'),
                'contact_address' => $request->get('contact_address'),
            ];
            $this->storeSubmissionFeeDataV2($appData->id, 1, $contact_info, $unfixed_amount_array, $request);
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id);

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Renew Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Renew Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Renew Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [SCSR-007]' );
        }

        return redirect( '/scs-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }


    private function fetchAndPrprIssueData( $license_no, $appData, $request ) {
     //   dd(1);
        $existedData = SCSLicenseMaster::leftJoin( 'scs_license_issue as apps', 'apps.tracking_no', '=', 'scs_license_master.issue_tracking_no' )
            ->where( [ 'scs_license_master.license_no' => $license_no ] )->first();

            $appData->org_nm       = $request->company_name;
            $appData->org_type     = $request->company_type;
            $appData->reg_office_district = $request->reg_office_district;
            $appData->reg_office_thana = $request->reg_office_thana;
            $appData->reg_office_address = $request->reg_office_address;
            $appData->op_office_district = $request->op_office_district;
            $appData->op_office_thana = $request->op_office_thana;
            $appData->op_office_address = $request->op_office_address;
            $appData->applicant_name = $request->applicant_name;
            $appData->applicant_mobile   = $request->applicant_mobile;
            $appData->applicant_telephone    = $request->applicant_telephone;
            $appData->applicant_email    = $request->applicant_email;
            $appData->applicant_district = $request->applicant_district;
            $appData->applicant_thana  = $request->applicant_thana;
            $appData->applicant_address  = $request->applicant_address;

            $appData->declaration_q1      = $request->get( 'declaration_q1' );
            $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
            $appData->declaration_q2      = $request->get( 'declaration_q2' );
            $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
            $appData->declaration_q3      = $request->get( 'declaration_q3' );

            if ( $request->hasFile( 'declaration_q3_images' ) ) {
                $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
                $path      = 'uploads/scs-license-renew/' . $yearMonth;
                if ( ! file_exists( $path ) ) {
                    mkdir( $path, 0777, true );
                }
                $_file_path = $request->file( 'declaration_q3_images' );
                $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
                $_file_path->move( $path, $file_path );
                $appData->declaration_q3_doc = $path . $file_path;
            }

            // list_of_clients
            if ($request->hasFile('list_of_clients')) {
                $yearMonth = date('Y') . '/' . date('m') . '/';
                $path      = 'uploads/scs-license-renew/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('list_of_clients');
                $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $appData->list_of_clients = $path . $file_path;
            }



            $appData->status     = 1;
            $appData->updated_at = Carbon::now();
            $company_id = CommonFunction::getUserCompanyWithZero();
            $appData->company_id        = ! empty( $company_id ) ? $company_id : 0;
            $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
            $appData->license_no        = $license_no;
            $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
            $appData->issue_date       = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
            $appData->total_no_of_share = $request->get('total_no_of_share');
            $appData->total_share_value = $request->get('total_share_value');
            return $appData;
        // }

    }

    private function prprRenewData( $request, $appData, $license_no ) {
        $existedData = SCSLicenseMaster::leftJoin( 'scs_license_issue as apps', 'apps.tracking_no', '=', 'scs_license_master.issue_tracking_no' )
            ->where( [ 'scs_license_master.license_no' => $license_no ] )->first();

        $appData->issue_date            = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
        $appData->expiry_date           = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;

        return $appData;
    }

    private function storeContactPersonData( $license_no, $appDataId, $request ) {

        // Store Contact Person
        CommonFunction::storeContactPerson($request, $this->process_type_id, $appDataId);
    }

    private function storeShareHolderData( $license_no, $appDataId, $request ) {
        CommonFunction::storeShareHolderPerson($request, $this->process_type_id,$appDataId);


    }

    public function viewForm( $processTypeId, $appId ): JsonResponse {
        $this->process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId( $appId );
        $process_type_id       = $this->process_type_id;

        $data['process_type_id'] = $process_type_id;


        $data['appInfo'] = ProcessList::leftJoin( 'scs_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                      ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
                                      ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
                                      ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                                      ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                                    //   ->leftJoin( 'area_info as contact_district', 'contact_district.area_id', '=', 'apps.cntct_prsn_district' )
                                    //   ->leftJoin( 'area_info as contact_thana', 'contact_thana.area_id', '=', 'apps.cntct_prsn_upazila' )
                                    //   ->leftJoin( 'area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division' )
                                    //   ->leftJoin( 'area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district' )
                                    //   ->leftJoin( 'area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila' )
                                   //   ->leftJoin( 'area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district' )
                                    //  ->leftJoin( 'area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana' )
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
                                        //   'contact_district.area_nm as contact_dis_nm',
                                        //   'contact_thana.area_nm as contact_thana_nm',
                                          'apps.*',
                                          'apps.issue_date as license_issue_date',

                                        //   'isp_license_division_info.area_nm as isp_license_division',
                                        //   'isp_license_district_info.area_nm as isp_license_district',
                                        //   'isp_license_upazila_info.area_nm as isp_license_upazila',

                                       //   'location_of_ins_district.area_nm as location_of_ins_district_en',
                                        //  'location_of_ins_thana.area_nm as location_of_ins_thana_en',

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
                                      ] );


        $data['appShareholderInfo'] = Shareholder::where( [ 'app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )->where( 'ref_id', $decodedAppId )->whereNotNull('uploaded_path')->get();
        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $processTypeId
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        // if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
        //     $data['payment_step_id'] = 2;
        //     $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        // } elseif ( $data['appInfo']->status_id == 16 ) { // 16 = Approved for annual payment
        //     $data['payment_step_id'] = 3;
        //     $data['unfixed_amounts'] = $this->unfixedAmountsForGovtApplicationFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        // } elseif ( $data['appInfo']->status_id == 46 ) {
        //     $data['payment_step_id']                         = 2;
        //     $data['unfixed_amounts']                         = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'] );
        //     $data['pay_order_info']                          = DB::table( 'pay_order_payment' )
        //                                                          ->where( [
        //                                                              'app_id'          => $data['appInfo']['id'],
        //                                                              'payment_step_id' => 2
        //                                                          ] )->first();
        //     $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        //     $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
        // }
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



        $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.view', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function unfixedAmountsForGovtServiceFee($isp_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0) {
        date_default_timezone_set("Asia/Dhaka");
        if (empty($this->process_type_id)) $this->process_type_id = $process_type_id;

        $vat_percentage = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
        if (empty($vat_percentage)) {
            DB::rollback();
            Session::flash('error', 'Please, configure the value for VAT.[INR-1026]');
            return redirect()->back()->withInput();
        }

        $SpPaymentAmountConfData = SpPaymentAmountConf::where([
            'process_type_id' => $this->process_type_id,
            'payment_step_id' => $payment_step_id,
            'license_type_id' => $isp_license_type,
            'status' => 1,
        ])->first();

        $unfixed_amount_array = [];

        if ($payment_step_id == 1) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 2) {

            $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where([
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 3,
                'license_type_id' => $isp_license_type,
                'status' => 1,
            ])->first();

            //TODO:: delay fee calculation
            $submissionPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1,
                'payment_status' => 1
            ])->first(['updated_at']); // Submission payment date

            $delay_fee                 = 0;
            $delay_vat_fee             = 0;
            $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
            $currentDateTime           = date('Y-m-d', strtotime('-1 year'));

            if ($currentDateTime > $submissionPaymentDateTime) {
                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = $yarly_delay_fee / 365;
                $date_diff       = date_diff(date_create($currentDateTime), date_create($submissionPaymentDateTime));
                $delay_day_count = abs($date_diff->format('%r%a'));
                $delay_fee       = $delay_day_count * $daily_delay_fee;
                $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
            }

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                8 => $delay_fee, //govt-delay-fee
                9 => ($spPaymentAmountforAnnualFee->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
                10 => $delay_vat_fee //govt-delay-vat-fee
            ];

        } elseif (in_array($payment_step_id, [3, 4, 5, 6])) {
            //TODO::Delay fee calculation
            $annualFeeData = AnnualFeeInfo::where([
                'process_type_id' => $this->process_type_id,
                'app_id' => $app_id,
                'status' => 0
            ])->first();

            $delay_fee       = 0;
            $delay_vat_fee   = 0;
            $paymentLastDate = strval($annualFeeData->payment_due_date);
            $currentDateTime = date('Y-m-d');
            if ($currentDateTime > $paymentLastDate) {
                $yarly_delay_fee = ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = $yarly_delay_fee / 365;
                $date_diff       = date_diff(date_create($currentDateTime), date_create($paymentLastDate));
                $delay_day_count = abs($date_diff->format('%r%a'));
                $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
            }

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                8 => $delay_fee, //govt-delay-fee
                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
                10 => $delay_vat_fee //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 7) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        }


        return $unfixed_amount_array;
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
            $this->process_type_id = $processTypeId;
            $data['process_type_id'] = $processTypeId;
            $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
            $companyId               = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
            $applicationId           = Encryption::decodeId( $applicationId );
            $process_type_id         = $processTypeId;
            $NixRenewData            = SCSLicenseRenew::find( $applicationId );
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

            $data['appInfo'] = ProcessList::leftJoin( 'scs_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                   'apps.issue_date as license_issue_date',

                    // 'sfp.contact_name as sfp_contact_name',
                    // 'sfp.contact_email as sfp_contact_email',
                    // 'sfp.contact_no as sfp_contact_phone',
                    // 'sfp.address as sfp_contact_address',
                    // 'sfp.pay_amount as sfp_pay_amount',
                    // 'sfp.vat_on_pay_amount as sfp_vat_tax',
                    // 'sfp.transaction_charge_amount as sfp_bank_charge',
                    // 'sfp.payment_status as sfp_payment_status',
                    // 'sfp.pay_mode as pay_mode',
                    // 'sfp.pay_mode_code as pay_mode_code',
                    // 'sfp.total_amount as sfp_total_amount',
                ] );
//dd($data['appInfo']);
            $data['companyUserType'] = CommonFunction::getCompanyUserType();
            $data['process_type_id'] = $process_type_id;

            $shareholders_data = Shareholder::where( [ 'app_id'          => $applicationId,
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

            $contact_data = ContactPerson::where( [ 'app_id'=> $applicationId,
                'process_type_id' => $processTypeId
            ] )->get();

            foreach ( $contact_data as $index => $value ) {
                if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                    $value->image_real_path = $value->image;
                    $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
                }
            }

            $data['contact_person'] = $contact_data;

            $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                    ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

            if ( empty( $NixRenewData->issue_tracking_no ) ) {
                $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.from-edit', $data );
            } else {
                $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.form-edit-v2', $data );
            }
       //     dd($data);

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData($request, $currentInstance): JsonResponse{
        $data['license_no']      = $request->get( 'license_no' );
        $issue_company_id      = SCSLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $issue_process_type_id = 62;
        $data['divisions']     = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts']     = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
        ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        // if license no empty then redirect to application black form for store renew data
        if ( empty( $data['license_no'] ) ) {
            $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.form', $data );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['appInfo'] = ProcessList::leftJoin( 'scs_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'scs_license_master as ns', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ns.issue_tracking_no', '=', 'apps.tracking_no' );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $issue_process_type_id ) );
            } )
            // ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $issue_process_type_id ) {
            //     $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
            //     $join->on( 'sfp.process_type_id', '=', DB::raw( 62 ) );       // comes for renew, so have to have payment for issue -> 1
            // } )
            ->where( 'ns.license_no', $request->license_no )
            ->where( 'process_list.process_type_id', 62 )
            ->where( 'ns.status', 1 )                         // approved status can be renew
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
                'ns.*',
                'apps.*',
                // 'sfp.contact_name as sfp_contact_name',
                // 'sfp.contact_email as sfp_contact_email',
                // 'sfp.contact_no as sfp_contact_phone',
                // 'sfp.address as sfp_contact_address',
                // 'sfp.pay_amount as sfp_pay_amount',
                // 'sfp.vat_on_pay_amount as sfp_vat_tax',
                // 'sfp.transaction_charge_amount as sfp_bank_charge',
                // 'sfp.payment_status as sfp_payment_status',
                // 'sfp.pay_mode as pay_mode',
                // 'sfp.pay_mode_code as pay_mode_code',
                // 'sfp.total_amount as sfp_total_amount',
            ] );

     //  dd($data['appInfo']);

        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
            // $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.search', $data );

            // return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

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
                $value->image_real_path    = $value->shareholders_image;
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        $contact_data = ContactPerson::where( [ 'app_id'=> $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }
        $data['contact_person'] = $contact_data;

        $public_html = (string) view( 'REUSELicenseIssue::SCS.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

    }
}
