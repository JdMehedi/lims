<?php


namespace App\Modules\REUSELicenseIssue\Models\ISP\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\Countries;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Modules\REUSELicenseIssue\Models\Shareholder;

class ISPLicenseRenew extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'isp_license_renew';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;


    public function createForm( $currentInstance ): string {
        $this->process_type_id   = $currentInstance->process_type_id;
        $data['acl_name']        = $currentInstance->acl_name;
        $data['process_type_id'] = $currentInstance->process_type_id;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']        = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']            = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['nationality']     = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                 ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( 'REUSELicenseIssue::ISP.Renew.form', $data ) );
    }

    public function storeForm( $request, $currentInstance ): RedirectResponse {

        $this->process_type_id = $currentInstance->process_type_id;
        $license_no            = $request->get( 'license_no' );
        if ( empty( $license_no ) ) {
            Session::flash( 'error', 'Invalid License No [ISPR-006]' );

            return redirect()->back()->withInput();
        }

        if ( $request->get( 'app_id' ) ) {
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new ISPLicenseRenew();
            $processData = new ProcessList();
        }


        /** Fetch ISP License Issue existing data and prepare them for renew application */
        $this->storeRenewData( $license_no, $appData, $request );

        /** Prepare new additional data for renew application */
        $this->prprRenewData( $request, $appData , $license_no);
        $appData->save();

        if ( $appData->id ) {
            /** Store data to Equipment List Data */
            $this->storeISPEquipment( $license_no, $appData->id, $request );

            /** Store data to Tariff Chart Data */
            $this->storeISPTariffChart( $license_no, $appData->id, $request );

            /** Store data to Share Holder Data */
            $this->storeShareHolderData( $license_no, $appData->id, $request );

            /** Store data to Contact Person Data */
            $this->storeContactPersonData( $license_no, $appData->id, $request );

            /** Store data to isp_license_bandwidth */
            $this->storeToIspLicenseBandwidth( $request, $appData->id );

            /** Store data to isp_license_connectivity */
            $this->storeToIspLicenseConnectivity( $request, $appData->id );

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
                    $processData->user_id        = $processData->shortfall_resubmit_to;
                    $processData->process_desc   = 'Re-submitted form applicant';
                    $processData->resubmitted_at = Carbon::now(); // application resubmission Date
                    $license_json_data=[];
                    $license_name = DB::table('license_type')
                        ->where('id', $request->get('type_of_isp_licensese'))
                        ->first();
                    $div_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_division'))
                        ->where('area_type', 1)
                        ->pluck('area_nm')
                        ->first();
                    $dist_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_district'))
                        ->where('area_type', 2)
                        ->pluck('area_nm')
                        ->first();
                    $upz_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_thana'))
                        ->where('area_type', 3)
                        ->pluck('area_nm')
                        ->first();
                    $license_json_data['License Type']= $license_name->name;
                    if(isset($div_name)){
                        $license_json_data['Division']= ($div_name)? $div_name: ''  ;
                    }
                    if(isset($dist_name)){
                        $license_json_data['District']= ($dist_name)? $dist_name: '';
                    }
                    if(isset($upz_name)){
                        $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
                    }
                    $processData->license_json =json_encode($license_json_data);

                    $resultData = $processData->id . '-' . $processData->tracking_no .
                                  $processData->desk_id . '-' . $processData->status_id . '-' . $processData->user_id . '-' .
                                  $processData->updated_by;

                    $processData->previous_hash = $processData->hash_value ?? '';
                    $processData->hash_value    = Encryption::encode( $resultData );
                } else {
                    $processData->status_id = -1;
                    $processData->desk_id   = 0;
                    $processData->submitted_at = Carbon::now();
                    $license_json_data=[];
                    $license_name = DB::table('license_type')
                        ->where('id', $request->get('type_of_isp_licensese'))
                        ->first();
                    $div_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_division'))
                        ->where('area_type', 1)
                        ->pluck('area_nm')
                        ->first();
                    $dist_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_district'))
                        ->where('area_type', 2)
                        ->pluck('area_nm')
                        ->first();
                    $upz_name = DB::table('area_info')
                        ->where('area_id', $request->get('isp_licensese_area_thana'))
                        ->where('area_type', 3)
                        ->pluck('area_nm')
                        ->first();

                    $license_json_data['License Type']= $license_name->name;
                    if(isset($div_name)){
                        $license_json_data['Division']= ($div_name)? $div_name: ''  ;
                    }
                    if(isset($dist_name)){
                        $license_json_data['District']= ($dist_name)? $dist_name: '';
                    }
                    if(isset($upz_name)){
                        $license_json_data['Upazilla']= ($upz_name)?$upz_name : '';
                    }

                    $processData->license_json =json_encode($license_json_data);
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
            CommonFunction::generateUniqueTrackingNumber('ISP', $this->process_type_id, $processData->id, $this->table, 'REN', $appData->id);
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
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id ==  -1 ) {

//            if ( empty( $appData->tracking_no ) ) {
//                $trackingPrefix = 'ISPR-' . date( 'Ymd' ) . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
                //CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
                //CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }

            //Preparing E-nothi Data Start
         /*   $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End
            */
            if (isset($payment_id) &&  $request->get('payment_type') !== 'pay_order' ) {
                DB::commit();

                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            /*
            //Preparing E-nothi Data Start
            $processListInfo = ProcessList::where([
                'id' => $processData->id,
                'ref_id' => $appData->id
            ])->latest()->first([
                'tracking_no'
            ])->toArray();
            commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);
            //Preparing E-nothi Data End
            */

            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            $trackingNumber = self::where('id','=', $processData->ref_id)->value('tracking_no');
            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'ISP License Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'ISP License Renew',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
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
           CommonFunction::DNothiRequest($processData->id, $request->get( 'actionBtn' ));

       }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Renew Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Renew Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Renew Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [ISPR-007]' );
        }

        return redirect( '/isp-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $process_type_id = $processTypeId;
        $decodedAppId = Encryption::decodeId( $applicationId );
        $data['process_type_id'] =  $this->process_type_id = $processTypeId;

//        $data['appInfo'] = ProcessList::leftJoin( 'isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
//                                      ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
//                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $processTypeId ) {
//                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
//                                          $join->on( 'sfp.process_type_id', '=', DB::raw( $processTypeId ) );
//                                      } )
//                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $processTypeId ) {
//                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
//                                          $join->on( 'ps.process_type_id', '=', DB::raw( $processTypeId ) );
//                                      } )
//                                      ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
//                                      ->leftJoin( 'area_info as area_info_district', 'area_info_district.area_id', '=', 'apps.org_district' )
//                                      ->leftJoin( 'area_info as area_info_thana', 'area_info_thana.area_id', '=', 'apps.org_upazila' )
//                                      ->leftJoin( 'area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division' )
//                                      ->leftJoin( 'area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district' )
//                                      ->leftJoin( 'area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila' )
//                                      ->where( 'process_list.ref_id', $decodedAppId )
//                                      ->where( 'process_list.process_type_id', $processTypeId )
//                                      ->first( [
//                                          'process_list.id as process_list_id',
//                                          'process_list.desk_id',
//                                          'process_list.process_type_id',
//                                          'process_list.status_id',
//                                          'process_list.ref_id',
//                                          'process_list.tracking_no',
//                                          'process_list.company_id',
//                                          'process_list.process_desc',
//                                          'process_list.submitted_at',
//                                          'ps.status_name',
//                                          'process_type.form_url',
//                                          'area_info_district.area_nm as dis_nm',
//                                          'area_info_thana.area_nm as thana_nm',
//                                          'apps.*',
//
//                                          'isp_license_division_info.area_nm as isp_license_division',
//                                          'isp_license_district_info.area_nm as isp_license_district',
//                                          'isp_license_upazila_info.area_nm as isp_license_upazila',
//
//                                          'sfp.contact_name as sfp_contact_name',
//                                          'sfp.contact_email as sfp_contact_email',
//                                          'sfp.contact_no as sfp_contact_phone',
//                                          'sfp.address as sfp_contact_address',
//                                          'sfp.pay_amount as sfp_pay_amount',
//                                          'sfp.vat_on_pay_amount as sfp_vat_tax',
//                                          'sfp.transaction_charge_amount as sfp_bank_charge',
//                                          'sfp.payment_status as sfp_payment_status',
//                                          'sfp.pay_mode as pay_mode',
//                                          'sfp.pay_mode_code as pay_mode_code',
//                                          'sfp.total_amount as sfp_total_amount',
//                                      ] );


        $data['appInfo'] = ProcessList::leftJoin('isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('sp_payment as sfp', function ($join) use ($processTypeId) {
                $join->on('sfp.app_id', '=', 'process_list.ref_id');
                $join->on('sfp.process_type_id', '=', DB::raw($processTypeId));
            })
            ->leftJoin('pay_order_payment as pop', function ($join) use ($processTypeId) {
                $join->on('pop.app_id', '=', 'process_list.ref_id');
                $join->on('pop.process_type_id', '=', DB::raw($processTypeId));
            })
            ->leftJoin('process_status as ps', function ($join) use ($processTypeId) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($processTypeId));
            })
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
            ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
            ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
            ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
            ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
            ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
            ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
            ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
            ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
            ->leftJoin('area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district')
            ->leftJoin('area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana')
            ->where('process_list.ref_id', $decodedAppId)
            ->where('process_list.process_type_id', $processTypeId)
            ->first([
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
                'apps.*',

                'isp_license_division_info.area_nm as isp_license_division',
                'isp_license_district_info.area_nm as isp_license_district',
                'isp_license_upazila_info.area_nm as isp_license_upazila',

                'location_of_ins_district.area_nm as location_of_ins_district_en',
                'location_of_ins_thana.area_nm as location_of_ins_thana_en',

                'sfp.contact_name as sfp_contact_name',
                'sfp.id as payment_id',
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
            ]);


//        $data['appShareholderInfo'] = self::join( 'isp_license_master as master', 'master.issue_tracking_no', '=', 'isp_license_renew.issue_tracking_no' )
//                                                                                         ->join( 'shareholders as shareholder', 'shareholder.app_id', '=', 'isp_license_renew.id' )
//                                                                                        ->where('shareholder.process_type_id',$processTypeId)
//                                                                                         ->where( [ 'isp_license_renew.id' => $decodedAppId ] )->get( [ 'shareholder.*' ] );
//
        $data['appShareholderInfo'] = Shareholder::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id,
            'status' => 0
        ] )->get();

        $data['shareholderInfoForRenew'] = Shareholder::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id,
            'status' => 1
        ] )->get();

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
                                                         ->where( 'ref_id', $decodedAppId )
                                                         ->whereNotNull('uploaded_path')
                                                         ->get();

        /** Fetch data from isp_license_bandwidth */
//        $data['isp_license_bandwidth'] = ISPLicenseBandwidth::where( [ 'isp_license_renew_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from isp_license_connectivity */
//        $data['isp_license_connectivity'] = ISPLicenseConnectivity::where( [ 'isp_license_renew_id' => $data['appInfo']['id'] ] )->get();

        //       Fetch data from isp_license_equipment_list
        $data['isp_equipment_list'] = ISPLicenseRenewEquipmentList::where(['isp_license_id' => $data['appInfo']['id']])->get();

//         Fetch data from isp_license_tariff_chart
        $data['isp_tariff_chart_list'] = ISPLicenseRenewTariffChart::where(['isp_license_id' => $data['appInfo']['id']])->get();

        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => 2 // 2 = isp renew process type id
        ] )->get();
        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
        elseif ($data['appInfo']->status_id == 25) { // 25 = generate license then eligible for second year annual fee
            $data['payment_step_id'] = 3;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
        elseif ($data['appInfo']->status_id == 54) { // 54 = success second annual payment
            $data['payment_step_id'] = 4;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
        elseif ($data['appInfo']->status_id == 55) { // 55 = success fourth year annual payment
            $data['payment_step_id'] = 5;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
        elseif ($data['appInfo']->status_id == 56) { // 56 = success fifth year annual payment
            $data['payment_step_id'] = 6;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }
        elseif ($data['appInfo']->status_id == 46) {
            $data['payment_step_id'] = 2;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
            $data['pay_order_info']  = DB::table('pay_order_info')
                                       ->where('pay_order_info_id', $data['appInfo']->payment_id)
                                       ->get();

            $data['payment_info'] = SonaliPayment::where([
                'app_id' => $data['appInfo']->id,
                'process_type_id' => $processTypeId,
                'payment_step_id' => 2
            ])->first();
            if(!empty($data['payment_info']->bg_expire_date)) {
                $data['payment_info']->bg_expire_date = date_format(date_create($data['payment_info']->bg_expire_date), 'Y-m-d');
            }
        }
        elseif ($data['appInfo']->status_id == 64) {
            $data['payment_step_id']  = 7;
            $data['unfixed_amounts']  = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);

            $data['payment_info'] = SonaliPayment::where([
                'app_id' => $data['appInfo']->id,
                'process_type_id' => $processTypeId,
                'payment_step_id' => 7,
                'is_bg' => 1
            ])->first();
            if(!empty($data['payment_info']->bg_expire_date)) {
                $data['payment_info']->bg_expire_date = date_format(date_create($data['payment_info']->bg_expire_date), 'Y-m-d');
            }
        } elseif ($data['appInfo']->status_id == 60) {
            $data['payment_step_id'] = 7;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        } elseif ($data['appInfo']->status_id == 65) { // 25 = generate license then eligible for second year annual fee
            $data['payment_step_id'] = 3;
            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
        }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();
        $public_html = (string) view( 'REUSELicenseIssue::ISP.Renew.view', $data );

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
        $data['thana']   = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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

        if ( $data['appInfo']->issue_tracking_no ) {
            $shareholders_data = Shareholder::where( [
                'app_id'          => $data['appInfo']['id'],
                'process_type_id' => $this->process_type_id // 1 = isp issue process type id
            ] )->get( [
                'shareholders.id as shareholders_id',
                'shareholders.app_id as shareholders_isp_issue_id',
                'shareholders.name as shareholders_name',
                'shareholders.nid as shareholders_nid',
                'shareholders.dob as shareholders_dob',
                'shareholders.passport as shareholders_passport',
                'shareholders.designation as shareholders_designation',
                'shareholders.mobile as shareholders_mobile',
                'shareholders.email as shareholders_email',
                'shareholders.image as shareholders_image',
                'shareholders.share_percent as shareholders_share_percent',
                'nationality as shareholders_nationality',
                'shareholders.no_of_share as no_of_share',
                'shareholders.share_value as share_value'
            ] );
        } else {
            $shareholders_data = Shareholder::where( [
                'app_id'          => $data['appInfo']['id'],
                'process_type_id' => 2 // 1 = isp issue process type id
            ] )->get( [
                'id as shareholders_id',
                'app_id as shareholders_isp_issue_id',
                'name as shareholders_name',
                'nid as shareholders_nid',
                'dob as shareholders_dob',
                'passport as shareholders_passport',
                'designation as shareholders_designation',
                'mobile as shareholders_mobile',
                'email as shareholders_email',
                'image as shareholders_image',
                'share_percent as shareholders_share_percent',
                'nationality as shareholders_nationality',
                'shareholders.no_of_share as no_of_share',
                'shareholders.share_value as share_value'
            ] );
        }

        foreach ( $shareholders_data as $index => $value ) {
            if ( ! empty( $value->shareholders_image ) && public_path( $value->shareholders_image ) ) {
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        /** Fetch data from isp_license_bandwidth */
        $data['isp_license_bandwidth'] = ISPLicenseBandwidth::where( [ 'isp_license_renew_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from isp_license_connectivity */
        $data['isp_license_connectivity'] = ISPLicenseConnectivity::where( [ 'isp_license_renew_id' => $data['appInfo']['id'] ] )->get();


        //       Fetch data from isp_license_equipment_list
        $data['isp_equipment_list'] = ISPLicenseRenewEquipmentList::where(['isp_license_id' => $data['appInfo']['id']])->get();

//         Fetch data from isp_license_tariff_chart
        $data['isp_tariff_chart_list'] = ISPLicenseRenewTariffChart::where(['isp_license_id' => $data['appInfo']['id']])->get();

        $contact_data = ContactPerson::where([
            'app_id' => $applicationId,
            'process_type_id' => $process_type_id
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;


        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                             ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
        if ( $data['appInfo']->issue_tracking_no ) {
            $public_html = (string) view( 'REUSELicenseIssue::ISP.Renew.form-edit', $data );
        } else {
            $public_html = (string) view( 'REUSELicenseIssue::ISP.Renew.form-edit-v2', $data );
        }
        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $issue_company_id      = ISPLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        if ( $companyId != $issue_company_id ) {
            return response()->json(['responseCode' => -1, 'msg' => 'Try with valid Owner']);
        }

            $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $process_type_id   = $this->process_type_id;

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']   = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo'] = ProcessList::leftJoin( 'isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'isp_license_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( 1 ) );       // comes for renew, so have to have payment for issue -> 1
                                      } )
                                      ->where( 'ms.license_no', $request->license_no )
                                      ->where( 'ms.status', 1 )
                                      ->where( 'process_list.process_type_id', 1 )                       // approved status can be renew
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
                'ms.company_id as master_company_id',
                'ms.issue_tracking_no',
                'ms.cancellation_tracking_no',
                'apps.declaration_q1 as declaration_q1',
                'apps.declaration_q1_text as declaration_q1_text',
                'apps.declaration_q2 as declaration_q2',
                'apps.declaration_q2_text as declaration_q2_text',
                'apps.declaration_q3 as declaration_q3',
                'apps.declaration_q3_doc as declaration_q3_doc',
                'apps.dd_file_1 as dd_file_1',
                'apps.dd_file_2 as dd_file_2',
                'apps.dd_file_3 as dd_file_3',

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
            if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
                return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

            }
        if ( empty( $data['appInfo'] ) ) {
//special code for cancel

$data['appInfotest'] = ProcessList::leftJoin( 'isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                                      ->leftJoin( 'isp_license_master as ms', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                                      } )
                                      ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'ps.id', '=', 'process_list.status_id' );
                                          $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                                      } )
                                      ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                                          $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                                          $join->on( 'sfp.process_type_id', '=', DB::raw( 1 ) );       // comes for renew, so have to have payment for issue -> 1
                                      } )
                                      ->where( 'ms.license_no', $request->license_no )
                                    //   ->where( 'ms.status', 1 )
                                      ->where( 'process_list.process_type_id', 1 )                       // approved status can be renew
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
                'ms.company_id as master_company_id',
                'ms.issue_tracking_no',
                'ms.cancellation_tracking_no',
                'apps.declaration_q1 as declaration_q1',
                'apps.declaration_q1_text as declaration_q1_text',
                'apps.declaration_q2 as declaration_q2',
                'apps.declaration_q2_text as declaration_q2_text',
                'apps.declaration_q3 as declaration_q3',
                'apps.declaration_q3_doc as declaration_q3_doc',
                'apps.dd_file_1 as dd_file_1',
                'apps.dd_file_2 as dd_file_2',
                'apps.dd_file_3 as dd_file_3',
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
            if ( $data['appInfotest']!= null && $data['appInfotest']->cancellation_tracking_no != null  ) {
                return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

            }

//special code for cancel end


            $companyId               = CommonFunction::getUserCompanyWithZero();
             $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
//            $data['companyInfo'] = null;
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['division']        = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['nationality']     = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                                                                     ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
            $data['process_type_id'] = $process_type_id = $this->process_type_id;
            $public_html             = strval( view( 'REUSELicenseIssue::ISP.Renew.search-blank', $data ) );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $data['process_type_id'] = $this->process_type_id;
        $shareholders_data       = Shareholder::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => 1 // 1 = isp issue process type id
        ] )->get( [
            'shareholders.id as shareholders_id',
            'shareholders.app_id as shareholders_isp_issue_id',
            'shareholders.name as shareholders_name',
            'shareholders.nid as shareholders_nid',
            'shareholders.dob as shareholders_dob',
            'shareholders.nationality as shareholders_nationality',
            'shareholders.passport as shareholders_passport',
            'shareholders.designation as shareholders_designation',
            'shareholders.mobile as shareholders_mobile',
            'shareholders.email as shareholders_email',
            'shareholders.image as shareholders_image',
            'shareholders.share_percent as shareholders_share_percent',
            'shareholders.no_of_share as no_of_share',
            'shareholders.share_value as share_value',
            'nationality as shareholders_nationality'
        ] );
        foreach ( $shareholders_data as $index => $value ) {
            if ( public_path( $value->shareholders_image ) && ! empty( $value->shareholders_image ) ) {
                $value->shareholders_image = CommonFunction::imagePathToBase64( public_path( $value->shareholders_image ) );
            }
        }
        $data['shareholders_data'] = $shareholders_data;

        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseEquipmentList::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->get();

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => 1 // for isp license issue
        ])->get();

        foreach ($contact_data as $index => $value) {
            if (public_path($value->image) && !empty($value->image)) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64(public_path($value->image));
            }
        }

        $data['contact_person'] = $contact_data;

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

        $public_html = (string) view( 'REUSELicenseIssue::ISP.Renew.search', $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    private function storeRenewData( $license_no, $appData, $request ) {
        $existedData = ISPLicenseMaster::leftJoin( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
            ->where( [ 'isp_license_master.license_no' => $license_no ] )->first();
        // if ( $request->get( 'form_version' ) == 'v1' && $existedData) {
        //     $appData->org_nm       = $existedData['org_nm'];
        //     $appData->org_type     = $existedData['org_type'];
        //     $appData->reg_office_district = $existedData['reg_office_district'];
        //     $appData->reg_office_thana = $existedData['reg_office_thana'];
        //     $appData->reg_office_address = $existedData['reg_office_address'];
        //     $appData->op_office_district = $existedData['op_office_district'];
        //     $appData->op_office_thana = $existedData['op_office_thana'];
        //     $appData->op_office_address = $existedData['op_office_address'];
        //     $appData->applicant_name = $existedData['applicant_name'];
        //     $appData->applicant_mobile   = $existedData['applicant_mobile'];
        //     $appData->applicant_telephone    = $existedData['applicant_telephone'];
        //     $appData->applicant_email    = $existedData['applicant_email'];
        //     $appData->applicant_district = $existedData['applicant_district'];
        //     $appData->applicant_thana  = $existedData['applicant_thana'];
        //     $appData->applicant_address  = $existedData['applicant_address'];

        //     $appData->isp_license_type     = $existedData['isp_license_type'];
        //     $appData->isp_license_division = $existedData['isp_license_division'];
        //     $appData->isp_license_district = $existedData['isp_license_district'];
        //     $appData->isp_license_upazila  = $existedData['isp_license_upazila'];

        //     $appData->declaration_q1      = $request->get( 'declaration_q1' );
        //     $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
        //     $appData->declaration_q2      = $request->get( 'declaration_q2' );
        //     $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
        //     $appData->declaration_q3      = $request->get( 'declaration_q3' );

        //     if ( $request->hasFile( 'declaration_q3_images' ) ) {
        //         $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
        //         $path      = 'uploads/isp-license-renew/' . $yearMonth;
        //         if ( ! file_exists( $path ) ) {
        //             mkdir( $path, 0777, true );
        //         }
        //         $_file_path = $request->file( 'declaration_q3_images' );
        //         $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
        //         $_file_path->move( $path, $file_path );
        //         $appData->declaration_q3_doc = $path . $file_path;
        //     } else {
        //         $appData->declaration_q3_doc = $existedData['declaration_q3_doc'];
        //     }


        //     $appData->location_of_ins_district = $request->get('location_of_ins_district');
        //     $appData->location_of_ins_thana    = $request->get('location_of_ins_thana');
        //     $appData->location_of_ins_address  = $request->get('location_of_ins_address');
        //     $appData->location_of_ins_address2 = $request->get('location_of_ins_address2');

        //     $appData->home       = $request->get('home');
        //     $appData->cyber_cafe = $request->get('cyber_cafe');
        //     $appData->office     = $request->get('office');
        //     $appData->others     = $request->get('others');

        //     $appData->corporate_user = $request->get('corporate_user');
        //     $appData->personal_user  = $request->get('personal_user');
        //     $appData->branch_user    = $request->get('branch_user');
        //     // list_of_clients
        //     if ($request->hasFile('list_of_clients')) {
        //         $yearMonth = date('Y') . '/' . date('m') . '/';
        //         $path      = 'uploads/isp-license-renew/' . $yearMonth;
        //         if (!file_exists($path)) {
        //             mkdir($path, 0777, true);
        //         }
        //         $_file_path = $request->file('list_of_clients');
        //         $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
        //         $_file_path->move($path, $file_path);
        //         $appData->list_of_clients = $path . $file_path;
        //     }


        //     $appData->status     = 1;
        //     $appData->created_at = Carbon::now();

        //     $appData->company_id        = ! empty( $existedData['company_id'] ) ? $existedData['company_id'] : 0;
        //     $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
        //     $appData->license_no        = $license_no;
        //     $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
        //     $appData->issue_date       = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
        //     $appData->total_no_of_share = $existedData['total_no_of_share'];
        //     $appData->total_share_value = $existedData['total_share_value'];
        //     return $appData;
        // // } else {
            $appData->org_nm       = $request->company_name;
            $appData->org_type     = $request->company_type;
            $appData->incorporation_num   = $request->get('incorporation_num');
            $appData->incorporation_date = $request->get('incorporation_date');
            $appData->reg_office_district = $request->reg_office_district;
            $appData->reg_office_thana = $request->reg_office_thana;
            $appData->reg_office_address = $request->reg_office_address;
            $appData->reg_office_address2 = $request->reg_office_address2;
            $appData->op_office_district = $request->op_office_district;
            $appData->op_office_thana = $request->op_office_thana;
            $appData->op_office_address = $request->op_office_address;
            $appData->op_office_address2 = $request->op_office_address2;
            $appData->applicant_name = $request->applicant_name;
            $appData->applicant_mobile   = $request->applicant_mobile;
            $appData->applicant_telephone    = $request->applicant_telephone;
            $appData->applicant_email    = $request->applicant_email;
            $appData->applicant_district = $request->applicant_district;
            $appData->applicant_thana  = $request->applicant_thana;
            $appData->applicant_address  = $request->applicant_address;
            $appData->applicant_address2  = $request->applicant_address2;


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

            $appData->location_of_ins_district = $request->get('location_of_ins_district');
            $appData->location_of_ins_thana    = $request->get('location_of_ins_thana');
            $appData->location_of_ins_address  = $request->get('location_of_ins_address');
            $appData->location_of_ins_address2 = $request->get('location_of_ins_address2');

            $appData->home       = $request->get('home');
            $appData->cyber_cafe = $request->get('cyber_cafe');
            $appData->office     = $request->get('office');
            $appData->others     = $request->get('others');

            $appData->corporate_user = $request->get('corporate_user');
            $appData->personal_user  = $request->get('personal_user');
            $appData->branch_user    = $request->get('branch_user');
            // list_of_clients
            if ($request->hasFile('list_of_clients')) {
                $yearMonth = date('Y') . '/' . date('m') . '/';
                $path      = 'uploads/isp-license-renew/' . $yearMonth;
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

        //Updated Information for Resubmit application
        //trade file
        $appData->trade_license_number          = $request->get('trade_license_number');
        $appData->current_trade_license_number  = $request->get('current_trade_license_number');
        $appData->trade_validity                = $request->get('trade_validity');
        $appData->trade_address                 = $request->get('trade_address');
        $appData->tax_clearance                 = $request->get('tax_clearance');
        $appData->current_tax_clearance         = $request->get('current_tax_clearance');
        $appData->current_trade_validity        = $request->get('current_trade_validity');
        $appData->current_trade_address         = $request->get('current_trade_address');

        if ( $request->hasFile( 'trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->tax_clearance_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_trade_license_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_trade_license_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TRADE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->current_trade_license_attachment = $path . $simple_file_name;
        }

        if ( $request->hasFile( 'current_tax_clearance_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_tax_clearance_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-TAX' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->current_tax_clearance_attachment = $path . $simple_file_name;
        }


        //House rental
        $appData->house_rental_address              = $request->get('house_rental_address');
        $appData->house_rental_validity             = $request->get('house_rental_validity');
        $appData->current_house_rental_address      = $request->get('current_house_rental_address');
        $appData->current_house_rental_validity     = $request->get('current_house_rental_validity');


        if ( $request->hasFile( 'house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->house_rental_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_house_rental_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_house_rental_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-HOUSE' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->current_house_rental_attachment = $path . $simple_file_name;
        }

        //ISPAB
        $appData->ispab_validity                = $request->get('ispab_validity');
        $appData->current_ispab_validity        = $request->get('current_ispab_validity');

        if ( $request->hasFile( 'ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'ispab_attachment' );
            $simple_file_name = trim( uniqid( 'ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->ispab_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_ispab_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_ispab_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-ISPAB' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->current_ispab_attachment = $path . $simple_file_name;
        }

//        //Shareholder
//        $appData->number_of_share                   = $request->get('number_of_share');
//        $appData->shareholders_name                 = $request->get('shareholders_name');
//        $appData->shareholders_nid_passport         = $request->get('shareholders_nid_passport');
//        $appData->current_number_of_share           = $request->get('current_number_of_share');
//        $appData->current_shareholders_name         = $request->get('current_shareholders_name');
//        $appData->current_shareholders_nid_passport = $request->get('current_shareholders_nid_passport');
//        if ( $request->hasFile( 'shareholders_attachment' ) ) {
//            $path             = CommonFunction::makeDir( 'resubmitApplication' );
//            $simpleFile       = $request->file( 'shareholders_attachment' );
//            $simple_file_name = trim( uniqid( 'SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
//            $simpleFile->move( $path, $simple_file_name );
//            $appData->shareholders_attachment = $path . $simple_file_name;
//        }
//        if ( $request->hasFile( 'current_shareholders_attachment' ) ) {
//            $path             = CommonFunction::makeDir( 'resubmitApplication' );
//            $simpleFile       = $request->file( 'current_shareholders_attachment' );
//            $simple_file_name = trim( uniqid( 'CURRENT-SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
//            $simpleFile->move( $path, $simple_file_name );
//            $appData->current_shareholders_attachment = $path . $simple_file_name;
//        }
            return $appData;
        // }

    }

    private function prprRenewData( $request, $appData, $license_no ) {
        $existedData = ISPLicenseMaster::leftJoin( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
            ->where( [ 'isp_license_master.license_no' => $license_no ] )->first();

        $appData->issue_date            = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;
        $appData->expiry_date           = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
//        $appData->installation_location = ! empty( $request->installation_location ) ? $request->installation_location : '';
//        $appData->no_of_individual      = ! empty( $request->no_of_individual ) ? $request->no_of_individual : '';
//        $appData->no_of_corporate       = ! empty( $request->no_of_corporate ) ? $request->no_of_corporate : '';
//        $appData->corporate_user        = ! empty( $request->corporate_user ) ? $request->corporate_user : '';
//        $appData->branch_user           = ! empty( $request->branch_user ) ? $request->branch_user : '';
//        $appData->personal_user         = ! empty( $request->personal_user ) ? $request->personal_user : '';
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

            // if ( count( $ExistedData ) == 0 ) {
            //     return false;
            // }

            ISPLicenseRenewEquipmentList::where( 'isp_license_id', $appDataId )->delete();

            // foreach ( $ExistedData as $index => $item ) {
            //     $equipObj                 = new ISPLicenseRenewEquipmentList();
            //     $equipObj->isp_license_id = $appDataId;
            //     $equipObj->name           = $item->name;
            //     $equipObj->brand_model    = $item->brand_model;
            //     $equipObj->quantity       = $item->quantity;
            //     $equipObj->remarks        = $item->remarks;
            //     $equipObj->created_at     = date( 'Y-m-d H:i:s' );
            //     $equipObj->save();
            // }
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

            // if ( count( $ExistedData ) == 0 ) {
            //     return false;
            // }

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
            // foreach ( $ExistedData as $index => $item ) {
            //     $tariffObj                    = new ISPLicenseRenewTariffChart();
            //     $tariffObj->isp_license_id    = $appDataId;
            //     $tariffObj->package_name_plan = $item->package_name_plan;
            //     $tariffObj->bandwidth_package = $item->bandwidth_package;
            //     $tariffObj->price             = $item->price;
            //     $tariffObj->duration          = $item->duration;
            //     $tariffObj->remarks           = $item->remarks;
            //     $tariffObj->created_at        = date( 'Y-m-d H:i:s' );
            //     $tariffObj->save();
            // }

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

        // Store Contact Person
        CommonFunction::storeContactPerson($request, $this->process_type_id, $appDataId);

//        if ( $request->get( 'form_version' ) == 'v1' ) {
//            $contactPersonExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
//                                                        ->join( 'contact_person as cp', 'apps.id', '=', 'cp.app_id' )
//                                                        ->select( DB::raw( 'cp.*' ) )
//                                                        ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();
//
//            if ( count( $contactPersonExistedData ) == 0 ) {
//                return false;
//            }
//
//            ContactPerson::where( [ 'app_id' => $appDataId, 'process_type_id' => $this->process_type_id ] )->delete();
//
//            foreach ( $contactPersonExistedData as $item ) {
//                $contactPersonObj                  = new ContactPerson();
//                $contactPersonObj->app_id          = $appDataId;
//                $contactPersonObj->process_type_id = $this->process_type_id;
//                $contactPersonObj->name            = $item->name;
//                $contactPersonObj->designation     = $item->designation;
//                $contactPersonObj->mobile          = $item->mobile;
//                $contactPersonObj->email           = $item->email;
//                $contactPersonObj->website         = $item->website;
//                $contactPersonObj->district        = $item->district;
//                $contactPersonObj->upazila         = $item->upazila;
//                $contactPersonObj->address         = $item->address;
//                $contactPersonObj->created_at      = date( 'Y-m-d H:i:s' );
//                $contactPersonObj->save();
//            }
//        } else {
//
//            ContactPerson::where( [ 'app_id' => $appDataId, 'process_type_id' => $this->process_type_id ] )->delete();
//
//            foreach ( $request->contact_person_name as $index => $item ) {
//                $contactPersonObj                  = new ContactPerson();
//                $contactPersonObj->app_id          = $appDataId;
//                $contactPersonObj->process_type_id = $this->process_type_id;
//                $contactPersonObj->name            = $item;
//                $contactPersonObj->designation     = isset( $request->contact_designation[ $index ] ) ? $request->contact_designation[ $index ] : '';
//                $contactPersonObj->mobile          = isset( $request->contact_mobile[ $index ] ) ? $request->contact_mobile[ $index ] : '';
//                $contactPersonObj->email           = isset( $request->contact_person_email[ $index ] ) ? $request->contact_person_email[ $index ] : '';
//                $contactPersonObj->website         = isset( $request->contact_website[ $index ] ) ? $request->contact_website[ $index ] : '';
//                $contactPersonObj->district        = isset( $request->contact_district[ $index ] ) ? $request->contact_district[ $index ] : '';
//                $contactPersonObj->upazila         = isset( $request->contact_thana[ $index ] ) ? $request->contact_thana[ $index ] : '';
//                $contactPersonObj->address         = $request->contact_person_address[ $index ];
//                $contactPersonObj->image = isset( $request->correspondent_contact_photo_base64[ $index] ) ? $request->correspondent_contact_photo_base64[ $index] : '';
//                $contactPersonObj->created_at      = date( 'Y-m-d H:i:s' );
//                $contactPersonObj->save();
//            }
//        }

    }

    private function storeShareHolderData( $license_no, $appDataId, $request ) {
        CommonFunction::storeShareHolderPerson($request, $this->process_type_id,$appDataId);

//        if ( $request->get( 'form_version' ) == 'v1' ) {
//            $ExistedData = ISPLicenseMaster::join( 'isp_license_issue as apps', 'apps.tracking_no', '=', 'isp_license_master.issue_tracking_no' )
//                                           ->join( 'shareholders as cp', 'apps.id', '=', 'cp.app_id' )
//                                           ->select( DB::raw( 'cp.*' ) )
//                                           ->where( [ 'isp_license_master.license_no' => $license_no ] )->get();
//
//            if ( count( $ExistedData ) == 0 ) {
//                return false;
//            }
//
//            Shareholder::where( ['app_id' => $appDataId, 'process_type_id' => $this->process_type_id] )->delete();
//
//            foreach ( $ExistedData as $item ) {
//                $shareHolderData                  = new Shareholder();
//                $shareHolderData->app_id          = $appDataId;
//                $shareHolderData->process_type_id = $this->process_type_id;
//                $shareHolderData->name            = $item->name;
//                $shareHolderData->nid             = $item->nid;
//                $shareHolderData->passport        = $item->passport;
//                $shareHolderData->designation     = $item->designation;
//                $shareHolderData->nationality     = $item->nationality;
//                $shareHolderData->mobile          = $item->mobile;
//                $shareHolderData->email           = $item->email;
//                $shareHolderData->dob             = $item->dob;
//                $shareHolderData->share_percent   = $item->share_percent;
//                $shareHolderData->image           = ! empty( $item->image ) ? $item->image : null;
//
//                //images
//
//                $shareHolderData->created_at = Carbon::now();
//                $shareHolderData->save();
//            }
//        } else {
//            Shareholder::where( ['app_id' => $appDataId, 'process_type_id' => $this->process_type_id] )->delete();
//            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
//            $path      = 'uploads/shareholder/' . $yearMonth;
//            if ( ! file_exists( $path ) ) {
//                mkdir( $path, 0777, true );
//            }
//            foreach ( $request->shareholder_name as $index => $item ) {
//                $shareHolderData                = new Shareholder();
//                $shareHolderData->app_id        = $appDataId;
//                $shareHolderData->process_type_id = $this->process_type_id;
//                $shareHolderData->name          = $item;
//                $shareHolderData->nid           = $request->shareholder_nid[ $index ];
//                $shareHolderData->passport      = $request->shareholder_passport[ $index ];
//                $shareHolderData->designation   = $request->shareholder_designation[ $index ];
//                $shareHolderData->nationality   = $request->shareholder_nationality[ $index ];
//                $shareHolderData->mobile        = $request->shareholder_mobile[ $index ];
//                $shareHolderData->email         = $request->shareholder_email[ $index ];
//                $shareHolderData->dob           = ! empty( $request->shareholder_dob[ $index ] ) ? date( 'Y-m-d', strtotime( $request->shareholder_dob[ $index ] ) ) : null;
//                $shareHolderData->share_percent = $request->shareholder_share_of[ $index ];
////                $shareHolderData->image          = ! empty( $item->image ) ? $item->image : null;
//                if ( ! empty( $request->correspondent_photo_base64[ $index ] ) ) {
//                    $splited                  = explode( ',', substr( $request->correspondent_photo_base64[ $index ], 5 ), 2 );
//                    $imageData                = $splited[1];
//                    $base64ResizeImage        = base64_encode( ImageProcessing::resizeBase64Image( $imageData, 300, 300 ) );
//                    $base64ResizeImage        = base64_decode( $base64ResizeImage );
//                    $correspondent_photo_name = trim( uniqid( 'BSCIC_IR-' . '-', true ) . '.' . 'jpeg' );
//                    file_put_contents( $path . $correspondent_photo_name, $base64ResizeImage );
//                    $shareHolderData->image = $path . $correspondent_photo_name;
//                } else {
//                    if ( empty( $appData->auth_person_pic ) ) {
//                        $shareHolderData->image = Auth::user()->user_pic;
//                    }
//                }
//
//                //images
//
//                $shareHolderData->created_at = Carbon::now();
//                $shareHolderData->save();
//            }
//        }

    }

//    public function unfixedAmountsForGovtServiceFee($isp_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0) {
//        date_default_timezone_set("Asia/Dhaka");
//        if (empty($this->process_type_id)) $this->process_type_id = $process_type_id;
//
//        $vat_percentage = Configuration::where('caption', 'GOVT_VENDOR_VAT_FEE')->value('value');
//        if (empty($vat_percentage)) {
//            DB::rollback();
//            Session::flash('error', 'Please, configure the value for VAT.[INR-1026]');
//            return redirect()->back()->withInput();
//        }
//
//        $SpPaymentAmountConfData = SpPaymentAmountConf::where([
//            'process_type_id' => $this->process_type_id,
//            'payment_step_id' => $payment_step_id,
//            'license_type_id' => $isp_license_type,
//            'status' => 1,
//        ])->first();
//
//        $unfixed_amount_array = [];
//
//        if ($payment_step_id == 1) {
//            $unfixed_amount_array = [
//                1 => 0, // Vendor-Service-Fee
//                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
//                3 => 0, // Govt. Application Fee
//                4 => 0, // Vendor-Vat-Fee
//                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
//                6 => 0, //govt-vendor-vat-fee
//                7 => 0, //govt-annual-fee
//                8 => 0, //govt-delay-fee
//                9 => 0, //govt-annual-vat-feef
//                10 => 0 //govt-delay-vat-fee
//            ];
//        } elseif ($payment_step_id == 2) {
//
//            $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where([
//                'process_type_id' => $this->process_type_id,
//                'payment_step_id' => 3,
//                'license_type_id' => $isp_license_type,
//                'status' => 1,
//            ])->first();
//
//            //TODO:: delay fee calculation
//            $submissionPaymentData = SonaliPayment::where([
//                'app_id' => $app_id,
//                'process_type_id' => $this->process_type_id,
//                'payment_step_id' => 1,
//                'payment_status' => 1
//            ])->first(['updated_at']); // Submission payment date
//
//            $delay_fee                 = 0;
//            $delay_vat_fee             = 0;
//            $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
//            $currentDateTime           = date('Y-m-d', strtotime('-1 year'));
//
//            if ($currentDateTime > $submissionPaymentDateTime) {
//                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
//                $daily_delay_fee = intval($yarly_delay_fee) / 365;
//                $date_diff       = date_diff(date_create($currentDateTime), date_create($submissionPaymentDateTime));
//                $delay_day_count = abs(intval($date_diff->format('%r%a')));
//                $delay_fee       = $delay_day_count * $daily_delay_fee;
//                $delay_vat_fee   = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
//            }
//
//            $unfixed_amount_array = [
//                1 => 0, // Vendor-Service-Fee
//                2 => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
//                3 => 0, // Govt. Application Fee
//                4 => 0, // Vendor-Vat-Fee
//                5 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, // Govt-Vat-Fee
//                6 => 0, //govt-vendor-vat-fee
//                7 => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
//                8 => $delay_fee, //govt-delay-fee
//                9 => ($spPaymentAmountforAnnualFee->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
//                10 => $delay_vat_fee //govt-delay-vat-fee
//            ];
//
//        } elseif (in_array($payment_step_id, [3, 4, 5, 6])) {
//            //TODO::Delay fee calculation
//            $annualFeeData = AnnualFeeInfo::where([
//                'process_type_id' => $this->process_type_id,
//                'app_id' => $app_id,
//                'status' => 0
//            ])->first();
//
//            $delay_fee       = 0;
//            $delay_vat_fee   = 0;
//            $paymentLastDate = strval($annualFeeData->payment_due_date);
//            $currentDateTime = date('Y-m-d');
//            if ($currentDateTime > $paymentLastDate) {
//                $yarly_delay_fee = ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100; // 15% delay fee after all
//                $daily_delay_fee = intval($yarly_delay_fee) / 365;
//                $date_diff       = date_diff(date_create($currentDateTime), date_create($paymentLastDate));
//                $delay_day_count = abs(intval($date_diff->format('%r%a')));
//                $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
//                $delay_vat_fee   = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
//            }
//
//            $unfixed_amount_array = [
//                1 => 0, // Vendor-Service-Fee
//                2 => 0, // Govt-Service-Fee
//                3 => 0, // Govt. Application Fee
//                4 => 0, // Vendor-Vat-Fee
//                5 => 0, // Govt-Vat-Fee
//                6 => 0, //govt-vendor-vat-fee
//                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
//                8 => $delay_fee, //govt-delay-fee
//                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-fee
//                10 => $delay_vat_fee //govt-delay-vat-fee
//            ];
//        } elseif ($payment_step_id == 7) {
//            $unfixed_amount_array = [
//                1 => 0, // Vendor-Service-Fee
//                2 => 0, // Govt-Service-Fee
//                3 => 0, // Govt. Application Fee
//                4 => 0, // Vendor-Vat-Fee
//                5 => 0, // Govt-Vat-Fee
//                6 => 0, //govt-vendor-vat-fee
//                7 => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
//                8 => 0, //govt-delay-fee
//                9 => ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100, //govt-annual-vat-feef
//                10 => 0 //govt-delay-vat-fee
//            ];
//        }
//
//
//        return $unfixed_amount_array;
//    }

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
                2 => round($SpPaymentAmountConfData->pay_amount,2), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), // Govt-Vat-Fee
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
            $renewApplicationSubmissionDate = !empty($submissionPaymentData->updated_at) ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
            $issueLicenseExpiryDate = ISPLicenseRenew::leftJoin('isp_license_master','isp_license_renew.license_no', '=', 'isp_license_master.license_no')->where([
                'isp_license_renew.id' => $app_id
            ])->first(['isp_license_master.expiry_date as expiry_date']);

            if ($renewApplicationSubmissionDate > $issueLicenseExpiryDate->expiry_date) {
                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = $yarly_delay_fee / 365;
                $date_diff       = date_diff(date_create($renewApplicationSubmissionDate), date_create($issueLicenseExpiryDate->expiry_date));
                $delay_day_count = abs($date_diff->format('%r%a'));
                $delay_fee       = $delay_day_count * $daily_delay_fee;
                $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
            }
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => round($SpPaymentAmountConfData->pay_amount, 2), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => round($spPaymentAmountforAnnualFee->pay_amount,2), //1st year govt-annual-fee
                8 => round($delay_fee,2), //govt-delay-fee
                9 => round(($spPaymentAmountforAnnualFee->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-fee
                10 => round($delay_vat_fee, 2) //govt-delay-vat-fee
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
                7 => round($SpPaymentAmountConfData->pay_amount,2), //govt-annual-fee
                8 => round($delay_fee,2), //govt-delay-fee
                9 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-fee
                10 => round($delay_vat_fee,2) //govt-delay-vat-fee
            ];
        } elseif ($payment_step_id == 7) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => round($SpPaymentAmountConfData->pay_amount,2), //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => round(($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100,2), //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
        }


        return $unfixed_amount_array;
    }

}
