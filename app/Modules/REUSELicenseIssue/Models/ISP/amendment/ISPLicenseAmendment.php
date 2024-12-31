<?php


namespace App\Modules\REUSELicenseIssue\Models\ISP\amendment;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Models\User;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\REUSELicenseIssue\Models\BPO\Amendment\CallCenterMaster;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\ProposalArea as IssueProposalArea;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\RenewProposalArea;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewTariffChart;
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
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ISPLicenseAmendment extends Model implements FormInterface {
    use SPPaymentManager;
    use SPAfterPaymentManager;
    protected $table = 'isp_license_amendment';
    protected $guarded = [ 'id' ];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;


    public function createForm( $currentInstance ): string {
        $this->process_type_id   = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id'] = $this->process_type_id;
        $data['acl_name']        = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');

        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

       return strval(view("REUSELicenseIssue::ISP.Amendment.master", $data));

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
            $appData     = new ISPLicenseAmendment();
            $processData = new ProcessList();
        }


        $appData = $this->storeLicenseData($appData, $request);
        $appData->save();

        if ( $appData->id ) {
            /** Store data to Equipment List Data */
            $license_no = $request->get('license_no');
            $this->storeISPEquipment( $appData->id, $request);

            /** Store data to Tariff Chart Data */
            $this->storeISPTariffChart( $appData->id, $request);

            /** Store data to Share Holder Data */
//            $this->storeShareHolderData( $license_no, $appData->id, $request );
            commonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id, );

            /** Store data to Contact Person Data */
//            $this->storeContactPersonData( $license_no, $appData->id, $request );
            commonFunction::storeContactPerson($request, $this->process_type_id, $appData->id, );


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
                    $processData->status_id = 1;
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
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
            $processData->process_type_id = $this->process_type_id;
            $processData->license_no          = $appData->license_no;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            $jsonData['Company Name'] = $appData->org_nm;
            $jsonData['Email']          = Auth::user()->user_email;
            $jsonData['Phone']          = Auth::user()->user_mobile;
            $processData['json_object'] = json_encode( $jsonData );
            $processData->save();
        }

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('ISP', $this->process_type_id, $processData->id, $this->table, 'AMM', $appData->id);
        }

        // =================================================payment code==========================
        // Payment info will not be updated for resubmit
//        if ( $processData->status_id != 2 && ! empty( $appData->isp_license_type ) ) {
//            $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $appData->isp_license_type, 1 );
//            $contact_info         = [
//                'contact_name'    => $request->contact_name,
//                'contact_email'   => $request->contact_email,
//                'contact_no'      => $request->contact_no,
//                'contact_address' => $request->contact_address,
//            ];
//            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
//            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
//        }
        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {

//            if ( empty( $appData->tracking_no ) ) {
//                $trackingPrefix = 'ISPA-' . date( 'Ymd' ) . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
//                //CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
//                //CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
//            }
//            if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
//                DB::commit();
//                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
//            }

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



            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_SUBMIT', ['{$serviceName}' => 'ISP License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'ISP License Amendment',
                'remarks'           => ''
            ];
            CommonFunction::sendEmailSMS( 'APP_SUBMIT', $appInfo, $receiverInfo);


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

            $trackingNumber = self::where('id', '=', $appData->id)->value('tracking_no');

            // Send Email notification to user on application re-submit
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'ISP License Amendment', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'ISP License Amendment',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        // for Pay Order
        $check_payment_type = false;
        if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit' ) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => $request->get( 'pay_amount' ), // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => $request->get( 'vat_on_pay_amount' ), // Govt-Vat-Fee
                6 => 0 //govt-vendor-vat-fee
            ];
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $this->storeSubmissionFeeDataV2( $appData->id, 1, $contact_info, $unfixed_amount_array, $request );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id, $request->get( 'actionBtn' ));

        }

        if ( $processData->status_id == - 1 ) {
            Session::flash( 'success', 'Successfully updated the Amendment Application!' );
        } elseif ( $processData->status_id == 1 ) {
            Session::flash( 'success', 'Successfully Amendment Application Submitted !' );
        } elseif ( $processData->status_id == 2 ) {
            Session::flash( 'success', 'Successfully Amendment Application Re-Submitted !' );
        } else {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [ISPR-007]' );
        }

        return redirect( '/isp-license-ammendment/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {
        $decodedAppId = Encryption::decodeId( $applicationId );

        $this->process_type_id = $data['process_type_id'] = $process_type_id = $processTypeId;

        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;

        $data['appInfo'] = ProcessList::leftJoin( 'isp_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
            } )
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
            ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
            ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
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

                'isp_license_division_info.area_nm as isp_license_division_name',
                'isp_license_district_info.area_nm as isp_license_district_name',
                'isp_license_upazila_info.area_nm as isp_license_upazila_name',

                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
                'reg_off_district.area_nm as reg_office_district_en',
                'reg_off_thana.area_nm as reg_office_thana_en',
                'noc_dis.area_nm as op_office_district_en',
                'noc_thana.area_nm as op_office_thana_en',
                'apps.*',
                'company_info.incorporation_num',
                'company_info.incorporation_date',
            ] );

        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();

        // for sub-view
        $shareholders_data = ISPLicenseAmendment::Join( 'shareholders', 'shareholders.app_id', '=', 'isp_license_amendment.id' )
            ->where( [ 'isp_license_amendment.id' => $decodedAppId, 'shareholders.process_type_id' => 3 ] ) // 7 = Call Center Amendment Process type id
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
            $nationality        = Countries::where( 'id', $value->nationality )->first( [ 'name' ] );
            $value->nationality = $nationality->name;
        }
        $data['appShareholderInfo'] = $shareholders_data; // for sub-view

        $contact_person_data = ContactPerson::where( [ 'app_id' => $data['appInfo']['id'] ,'process_type_id' => 3  ] )->get(); // 5 = Call Center Issue Process type id



        $data['contact_person'] = $contact_person_data;

        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }

//        if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
//            $data['payment_step_id'] = 2;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee($data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//        }elseif ( $data['appInfo']->status_id == 25 ) { // 25 = generate license then eligible for second year annual fee
//            $data['payment_step_id'] = 3;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//        } elseif ( $data['appInfo']->status_id == 54 ) { // 54 = success second annual payment
//            $data['payment_step_id'] = 4;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//        } elseif ( $data['appInfo']->status_id == 55 ) { // 55 = success fourth year annual payment
//            $data['payment_step_id'] = 5;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//        } elseif ( $data['appInfo']->status_id == 56 ) { // 56 = success fifth year annual payment
//            $data['payment_step_id'] = 6;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//        } elseif ( $data['appInfo']->status_id == 46 ) {
//            $data['payment_step_id'] = 2;
//            $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['appInfo']->isp_license_type, $data['payment_step_id'], $decodedAppId);
//            $data['pay_order_info']  = DB::table( 'pay_order_payment' )->where( [
//                'app_id'          => $data['appInfo']['id'],
//                'payment_step_id' => 2
//            ] )->first();
//            $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
//            $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
//        }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseAmendmentEquipmentList::where( [ 'isp_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseAmendmentTariffChart::where( [ 'isp_license_issue_id' => $data['appInfo']['id'] ] )->get();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::ISP.Amendment.masterView', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm( $processTypeId, $applicationId ): JsonResponse {
        $this->process_type_id   = $data['process_type_id'] = $process_type_id = $processTypeId;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $applicationId = Encryption::decodeId( $applicationId );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo']   = ProcessList::leftJoin( 'isp_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ));
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ));
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
                'apps.org_nm as company_name',
                'apps.org_type as company_type',
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
            ]);

        $data['companyUserType'] = CommonFunction::getCompanyUserType();
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
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

         $contact_data= ContactPerson::where( [
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
            $data['contact_person'][ $key ]['upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        /** Fetch data from isp_license_equipment_list */
        $data['isp_equipment_list'] = ISPLicenseAmendmentEquipmentList::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->get();

        /** Fetch data from isp_license_tariff_chart */
        $data['isp_tariff_chart_list'] = ISPLicenseAmendmentTariffChart::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->get();

        $public_html = (string) view( 'REUSELicenseIssue::ISP.Amendment.masterEdit', $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $this->process_type_id = $currentInstance->process_type_id;
        $process_type_id = $currentInstance->process_type_id;
        $data['license_no']    = $request->license_no;
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $issue_company_id      = ISPLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }

        if ( $companyId != $issue_company_id ) {
            return response()->json(['responseCode' => -1, 'msg' => 'Try with valid Owner']);
        }


        $data['master_data'] = ISPLicenseMaster::where('license_no', $request->license_no)->first();

        if (!empty($data['master_data']->renew_tracking_no)) {
            $data['appInfo'] = ProcessList::leftJoin( 'isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin('isp_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                    })
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( 2));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(3));
                    })
                ->where( 'ms.license_no', $request->license_no)
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 2)
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
                    'apps.issue_date as license_issue_date',
                    'apps.*',
                    'ms.issue_tracking_no',
                    'ms.cancellation_tracking_no',
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

                if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
                    return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                }

//            $proposal_area_data = RenewProposalArea::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();
//
//            foreach ( $proposal_area_data as $index => $value ) {
//                $value->proposal_district = Area::where( 'area_id', $value->proposal_district )->value('area_nm');
//                $value->proposal_thana    = Area::where( 'area_id', $value->proposal_thana )->value('area_nm');
//            }
//            $data['proposal_area'] = $proposal_area_data;
        } else {
            $data['license_no']    = $request->license_no;
            $data['appInfo'] = ProcessList::leftJoin( 'isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( 'isp_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                    } )
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw(1));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(3));
                    })
                ->where( 'ms.license_no', $request->license_no )
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 1)
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
                    'ms.cancellation_tracking_no',
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

                if ( $data['appInfo']!= null && $data['appInfo']->cancellation_tracking_no != null  ) {
                    return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                }
//            $proposal_area_data = IssueProposalArea::where( [ 'ref_id' => $data['appInfo']['id'] ] )->get();
//
//            foreach ( $proposal_area_data as $index => $value ) {
//                $value->proposal_district = Area::where( 'area_id', $value->proposal_district )->value('area_nm');
//                $value->proposal_thana    = Area::where( 'area_id', $value->proposal_thana )->value('area_nm');
//            }
//            $data['proposal_area'] = $proposal_area_data;
        }



        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {


$data['appInfotest'] = ProcessList::leftJoin( 'isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                    ->leftJoin( 'isp_license_master as ms',
                        function ( $join ) use ( $process_type_id ) {
                            $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                        } )
                    ->leftJoin('process_status as ps',
                        function ( $join ) use ( $process_type_id) {
                            $join->on( 'ps.id', '=', 'process_list.status_id' );
                            $join->on( 'ps.process_type_id', '=', DB::raw(1));
                        })
                    ->leftJoin('sp_payment as sfp',
                        function ( $join ) use ( $process_type_id ) {
                            $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                            $join->on( 'sfp.process_type_id', '=', DB::raw(3));
                        })
                    ->where( 'ms.license_no', $request->license_no )
                    ->where('process_list.process_type_id', 1)
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
                        'ms.cancellation_tracking_no',
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
                    if ( $data['appInfotest']!= null && $data['appInfotest']->cancellation_tracking_no != null  ) {
                    return response()->json( [ 'responseCode' => - 1, 'msg' => 'Application cancelled on provided license number' ] );

                    }

//special code for cancel end

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

        if(!empty($data['master_data']->renew_tracking_no)){
            /** Fetch data from isp_license_equipment_list */
            $data['isp_equipment_list'] = ISPLicenseRenewEquipmentList::where( [ 'isp_license_id' => $data['appInfo']['ref_id'] ] )->latest()->get();

            /** Fetch data from isp_license_tariff_chart */
            $data['isp_tariff_chart_list'] = ISPLicenseRenewTariffChart::where( [ 'isp_license_id' => $data['appInfo']['ref_id'] ] )->latest()->get();

        }else{
            /** Fetch data from isp_license_equipment_list */
            $data['isp_equipment_list'] = ISPLicenseEquipmentList::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->latest()->get();

            /** Fetch data from isp_license_tariff_chart */
            $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where( [ 'isp_license_issue_id' => $data['appInfo']['ref_id'] ] )->latest()->get();
        }

        $public_html = (string) view( 'REUSELicenseIssue::ISP.Amendment.search', $data );


        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    private function storeLicenseData($appData, $request){
        $LicenseAmendmentObj = $appData;
        $LicenseAmendmentObj->org_nm   = $request->get( 'company_name' );
        $LicenseAmendmentObj->org_type = $request->get( 'company_type' );
        $LicenseAmendmentObj->incorporation_num   = $request->get( 'incorporation_num' );
        $LicenseAmendmentObj->incorporation_date = $request->get( 'incorporation_date' );
        $LicenseAmendmentObj->license_no = $request->get( 'license_no' );
        $LicenseAmendmentObj->reg_office_district = $request->get( 'reg_office_district' );
        $LicenseAmendmentObj->reg_office_thana    = $request->get( 'reg_office_thana' );
        $LicenseAmendmentObj->reg_office_address  = $request->get( 'reg_office_address' );
        $LicenseAmendmentObj->op_office_district  = $request->get( 'noc_district' );
        $LicenseAmendmentObj->op_office_thana     = $request->get( 'noc_thana' );
        $LicenseAmendmentObj->op_office_address   = $request->get( 'noc_address' );

        $LicenseAmendmentObj->applicant_name      = $request->get( 'applicant_name' );
        $LicenseAmendmentObj->applicant_mobile    = $request->get( 'applicant_mobile' );
        $LicenseAmendmentObj->applicant_telephone = $request->get( 'applicant_telephone' );
        $LicenseAmendmentObj->applicant_email     = $request->get( 'applicant_email' );
        $LicenseAmendmentObj->applicant_district  = $request->get( 'applicant_district' );
        $LicenseAmendmentObj->applicant_thana     = $request->get( 'applicant_thana' );
        $LicenseAmendmentObj->applicant_address   = $request->get( 'applicant_address' );

        $LicenseAmendmentObj->license_issue_date       = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $LicenseAmendmentObj->expiry_date              = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;

        $typeOfIspLicense                          = $request->get( 'type_of_isp_licensese' );
        $LicenseAmendmentObj->isp_license_type     = $typeOfIspLicense;
        $LicenseAmendmentObj->isp_license_division = $request->get( 'isp_licensese_area_division' );
        $LicenseAmendmentObj->isp_license_district = $request->get( 'isp_licensese_area_district' );
        $LicenseAmendmentObj->isp_license_upazila  = $request->get( 'isp_licensese_area_thana' );

        $LicenseAmendmentObj->location_of_ins_district = $request->get( 'location_of_ins_district' );
        $LicenseAmendmentObj->location_of_ins_thana    = $request->get( 'location_of_ins_thana' );
        $LicenseAmendmentObj->location_of_ins_address  = $request->get( 'location_of_ins_address' );

        $LicenseAmendmentObj->home       = $request->get( 'home' );
        $LicenseAmendmentObj->cyber_cafe = $request->get( 'cyber_cafe' );
        $LicenseAmendmentObj->office     = $request->get( 'office' );
        $LicenseAmendmentObj->others     = $request->get( 'others' );

        $LicenseAmendmentObj->corporate_user = $request->get( 'corporate_user' );
        $LicenseAmendmentObj->personal_user  = $request->get( 'personal_user' );
        $LicenseAmendmentObj->branch_user    = $request->get( 'branch_user' );
        // list_of_clients
        if ( $request->hasFile( 'list_of_clients' ) ) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/isp-license-amendment/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'list_of_clients' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $LicenseAmendmentObj->list_of_clients = $path . $file_path;
        }


        if ( $typeOfIspLicense == 1 ) {
            $LicenseAmendmentObj->isp_license_division = null;
            $LicenseAmendmentObj->isp_license_district = null;
            $LicenseAmendmentObj->isp_license_upazila  = null;
        } elseif ( $typeOfIspLicense == 2 ) {
            $LicenseAmendmentObj->isp_license_district = null;
            $LicenseAmendmentObj->isp_license_upazila  = null;
        } elseif ( $typeOfIspLicense == 3 ) {
            $LicenseAmendmentObj->isp_license_upazila = null;
        }

        $LicenseAmendmentObj->business_plan       = $request->get( 'business_plan' );
        $LicenseAmendmentObj->declaration_q1      = $request->get( 'declaration_q1' );
        $LicenseAmendmentObj->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $LicenseAmendmentObj->declaration_q2      = $request->get( 'declaration_q2' );
        $LicenseAmendmentObj->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $LicenseAmendmentObj->declaration_q3      = $request->get( 'declaration_q3' );
        $LicenseAmendmentObj->status              = 1;
        $LicenseAmendmentObj->updated_at = Carbon::now();
        $LicenseAmendmentObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $LicenseAmendmentObj->total_no_of_share   = $request->get( 'total_no_of_share' );
        $LicenseAmendmentObj->total_share_value   = $request->get( 'total_share_value' );
        //images
        if ( $request->hasFile( 'declaration_q3_images' ) ) {
            $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
            $path      = 'uploads/isp-license-amendment/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'declaration_q3_images' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $LicenseAmendmentObj->declaration_q3_doc = $path . $file_path;
        }
        //images
        $LicenseAmendmentObj->save();
        return $LicenseAmendmentObj;
    }

    private function storeISPEquipment( $appDataId, $request) {
        if ( isset( $request->equipment_name ) && count( $request->equipment_name ) > 0 ) {
            ISPLicenseAmendmentEquipmentList::where( 'isp_license_issue_id', $appDataId )->delete();

            foreach ( $request->equipment_name as $index => $value ) {
                $equipObj                       = new ISPLicenseAmendmentEquipmentList();
                $equipObj->isp_license_issue_id = $appDataId;
                $equipObj->name                 = $value;
                $equipObj->brand_model          = $request->equipment_brand_model[ $index ];
                $equipObj->quantity             = $request->equipment_quantity[ $index ];
                $equipObj->remarks              = $request->equipment_remarks[ $index ];
                $equipObj->created_at           = date( 'Y-m-d H:i:s' );
                $equipObj->save();
            }
        }
    }

    private function storeISPTariffChart( $appDataId, $request) {
        if ( isset( $request->tariffChart_package_name_plan ) && count( $request->tariffChart_package_name_plan ) > 0 ) {
            ISPLicenseAmendmentTariffChart::where( 'isp_license_issue_id', $appDataId )->delete();

            foreach ( $request->tariffChart_package_name_plan as $index => $value ) {
                $tariffObj                       = new ISPLicenseAmendmentTariffChart();
                $tariffObj->isp_license_issue_id = $appDataId;
                $tariffObj->package_name_plan    = $value;
                $tariffObj->bandwidth_package    = $request->tariffChart_bandwidth_package[ $index ];
                $tariffObj->price                = $request->tariffChart_price[ $index ];
                $tariffObj->duration             = $request->tariffChart_duration[ $index ];
                $tariffObj->remarks              = $request->tariffChart_remarks[ $index ];
                $tariffObj->created_at           = date( 'Y-m-d H:i:s' );
                $tariffObj->save();
            }
        }

    }

    public function unfixedAmountsForGovtServiceFee($isp_license_type, $payment_step_id, $app_id = 0) {
        date_default_timezone_set("Asia/Dhaka");

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

            $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 3,
                'license_type_id' => $isp_license_type,
                'status'          => 1,
            ] )->first();

            //TODO:: delay fee calculation
            $submissionPaymentData = SonaliPayment::where([
                'app_id' => $app_id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1,
                'payment_status' => 1
            ])->first(['updated_at']); // Submission payment date

            $delay_fee = 0;
            $delay_vat_fee = 0;
            $submissionPaymentDateTime = $submissionPaymentData ? date('Y-m-d', strtotime($submissionPaymentData->updated_at)) : date('Y-m-d');
            $currentDateTime = date('Y-m-d', strtotime('-1 year'));

            if ($currentDateTime > $submissionPaymentDateTime) {
                $yarly_delay_fee = (($SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount) * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = intval($yarly_delay_fee) / 365;
                $date_diff = date_diff(date_create($currentDateTime),date_create($submissionPaymentDateTime));
                $delay_day_count = abs(intval($date_diff->format('%r%a')));
                $delay_fee = $delay_day_count * $daily_delay_fee;
                $delay_vat_fee = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
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

        } elseif(in_array($payment_step_id, [3, 4, 5, 6])) {
            //TODO::Delay fee calculation
            $annualFeeData = AnnualFeeInfo::where([
                'process_type_id' => $this->process_type_id,
                'app_id' => $app_id,
                'status' => 0
            ])->first();

            $delay_fee = 0;
            $delay_vat_fee = 0;
            $paymentLastDate = strval($annualFeeData->payment_due_date);
            $currentDateTime = date('Y-m-d');
            if ($currentDateTime > $paymentLastDate) {
                $yarly_delay_fee = ($SpPaymentAmountConfData->pay_amount * $vat_percentage) / 100; // 15% delay fee after all
                $daily_delay_fee = intval($yarly_delay_fee) / 365;
                $date_diff = date_diff(date_create($currentDateTime),date_create($paymentLastDate));
                $delay_day_count = abs(intval($date_diff->format('%r%a')));
                $delay_fee = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                $delay_vat_fee = ($delay_fee * intval($vat_percentage)) / 100; // 15% vat over delay fee
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
        }


        return $unfixed_amount_array;
    }
}
