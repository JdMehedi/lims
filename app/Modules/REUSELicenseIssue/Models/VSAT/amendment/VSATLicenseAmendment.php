<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\VSAT\amendment;

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
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATTechnicalSpecification;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VSATLicenseAmendment extends Model implements FormInterface
{
    protected $table = 'vsat_license_amendment';
    protected $guarded = ['id'];
    protected $process_type_id;
    use SPPaymentManager;
    use SPAfterPaymentManager;
    // expect column for data store from nix_license_issue table
//    public $except_column = [
//        'id',
//        'tracking_no',
//        'issue_tracking_no',
//        'expiry_date',
//        'license_issue_date',
//        'certificate_link',
//        'created_at',
//        'updated_at'
//    ];

    public function setvalue($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function createForm($currentInstance): string
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $companyId = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        $data['process_type_id'] = $this->process_type_id;
        $data['acl_name'] = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where('id', $this->process_type_id)->value('name');


        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();

        $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality')->pluck('nationality', 'id')->toArray();

        return strval(view("REUSELicenseIssue::VSAT.Amendment.master", $data));
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {

        $this->process_type_id = $currentInstance->process_type_id;
        if ( $request->get( 'app_id' ) ) {
            // $appData     = VSATLicenseIssue::find( Encryption::decodeId($request->get( 'app_id' )));
            $appData     = self::find( Encryption::decodeId( $request->get( 'app_id' ) ) );
            $processData = ProcessList::where( [
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new VSATLicenseAmendment();
            $processData = new ProcessList();
        }

        $appData->license_category = $request->get( 'license_category' );
        $appData->sattelite_type   = $request->get( 'origin_or_satelite' );


        $appData->license_issue_date   = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $appData->expiry_date  = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;


        $appData->company_id            = CommonFunction::getUserCompanyWithZero();
        $appData->license_no                = $request->get( 'license_no' );
        $appData->org_nm                 = $request->get( 'company_name' );
        $appData->org_type               = $request->get( 'company_type' );
        $appData->org_district           = $request->get( 'reg_office_district' );
        $appData->org_upazila            = $request->get( 'reg_office_thana' );
        $appData->org_address            = $request->get( 'reg_office_address' );
        $appData->org_permanent_district = $request->get( 'op_office_district' );
        $appData->org_permanent_upazila  = $request->get( 'op_office_thana' );
        $appData->org_permanent_address  = $request->get( 'op_office_address' );

        $appData->applicant_name      = $request->get( 'applicant_name' );
        $appData->applicant_telephone = $request->get( 'applicant_telephone' );
        $appData->applicant_district  = $request->get( 'applicant_district' );
        $appData->applicant_address   = $request->get( 'applicant_address' );
        $appData->applicant_mobile    = $request->get( 'applicant_mobile' );
        $appData->applicant_email     = $request->get( 'applicant_email' );
        $appData->applicant_upazila   = $request->get( 'applicant_thana' );
        $appData->applicant_website   = $request->get( 'applicant_website' );
        $appData->total_share_value   = $request->get( 'total_share_value' );
        $appData->total_no_of_share   = $request->get( 'total_no_of_share' );

        $appData->declaration_q1      = $request->get( 'declaration_q1' );
        $appData->declaration_q1_text = $request->get( 'declaration_q1_text' );
        $appData->declaration_q2      = $request->get( 'declaration_q2' );
        $appData->declaration_q2_text = $request->get( 'declaration_q2_text' );
        $appData->declaration_q3      = $request->get( 'declaration_q3' );
        $appData->status              = 1;
        $appData->updated_at          = Carbon::now();

        //images
        if ( $request->hasFile( 'declaration_q3_images' ) ) {
            $yearMonth = date( "Y" ) . "/" . date( "m" ) . "/";
            $path      = 'uploads/vsat-license-renew/' . $yearMonth;
            if ( ! file_exists( $path ) ) {
                mkdir( $path, 0777, true );
            }
            $_file_path = $request->file( 'declaration_q3_images' );
            $file_path  = trim( uniqid( 'BTRC_LIMS-' . '-', true ) . $_file_path->getClientOriginalName() );
            $_file_path->move( $path, $file_path );
            $appData->declaration_q3_doc = $path . $file_path;
        }
        $appData->save();

        if ( $appData->id ) {
            /** Store into vsat_license_issue_shareholders table */
//            $this->storeShareHolder( $appData, $request );
            CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );

            /** Store into vsat_license_contact_person table */
//            $this->storeContactPerson( $appData->id, $request );
            CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id );

            /** Store into vsat_service_provider_info table */
            $this->storeServiceProvider( $appData->id, $request );

            /** Store into vsat_hub_operator_info table */
            $this->storeVSATHubOperatorInfo( $appData->id, $request );

            /** Store into vsat_technical_spec table */
            $this->storeTechnicalSpecification( $appData->id, $request );

            /** Store into vsat_list_of_equipment table */
            $this->storeListOfEquipment( $appData->id, $request );

            //dynamic document start
            DocumentsController::storeAppDocuments( $currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request );

            //process list data insert
            $processData->company_id = CommonFunction::getUserCompanyWithZero();

            //Set category id for process differentiation
            $processData->cat_id = 1;
            if ( $request->get( 'actionBtn' ) == "draft" ) {
                $processData->status_id = - 1;
                $processData->desk_id   = 0;
            } else {
                if ( $processData->status_id == 5 ) { // For shortfall
                    // Get last desk and status
                    $submission_sql_param        = [
                        'app_id'          => $appData->id,
                        'process_type_id' => $currentInstance->process_type_id,
                    ];
                    $process_type_info           = ProcessType::where( 'id', $currentInstance->process_type_id )
                        ->orderBy( 'id', 'desc' )
                        ->first( [
                            'form_url',
                            'process_type.process_desk_status_json',
                            'process_type.name',
                        ] );
                        $license_json_data=[];
                    if(!empty($request->get( 'license_category' ))){
                        if($request->get( 'license_category' ) == 1){
                            $license_json_data['license_category']= 'VSAT HUB Operator';
                        }else if($request->get( 'license_category' ) == 2){
                            $license_json_data['license_category']= 'VSAT User';
                        }else{
                            $license_json_data['license_category']= 'VSAT RT User';
                        }

                    }
                    if(!empty($request->get( 'origin_or_satelite' ))){
                        if($request->get( 'origin_or_satelite' ) == 1){
                            $license_json_data['Origin']= 'National Satelite';
                        }else{
                            $license_json_data['Origin']= 'Foreign Satelite';
                        }

                    }
                    $processData['license_json']=json_encode($license_json_data);

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
                    // $processData->status_id = - 1;
                    // $processData->desk_id   = 0;
                    $processData->status_id = 1;
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
                    $processData->submitted_at = Carbon::now();
                    $license_json_data=[];
                    if(!empty($request->get( 'license_category' ))){
                        if($request->get( 'license_category' ) == 1){
                            $license_json_data['license_category']= 'VSAT HUB Operator';
                        }else if($request->get( 'license_category' ) == 2){
                            $license_json_data['license_category']= 'VSAT User';
                        }else{
                            $license_json_data['license_category']= 'VSAT RT User';
                        }

                    }
                    if(!empty($request->get( 'origin_or_satelite' ))){
                        if($request->get( 'origin_or_satelite' ) == 1){
                            $license_json_data['Origin']= 'National Satelite';
                        }else{
                            $license_json_data['Origin']= 'Foreign Satelite';
                        }

                    }
                    $processData['license_json']=json_encode($license_json_data);
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $currentInstance->process_type_id;
            $processData->office_id       = 0;
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            $jsonData['Company Name']     = $request->company_name;
            $jsonData['Email']            = Auth::user()->user_email;
            $jsonData['Phone']            = Auth::user()->user_mobile;
            $processData['json_object']   = json_encode( $jsonData );
            $processData->save();
        }

        //  Required Documents for attachment
        DocumentsController::storeAppDocuments( $currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request );
        if ( $request->get( 'actionBtn' ) == 'submit' ) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'VSATA-' . date( "Ymd" ) . '-';
                commonFunction::generateTrackingNumber( $trackingPrefix, $currentInstance->process_type_id, $processData->id, $appData->id, $this->table );
            }
            DB::commit();

            // if ( $request->get( 'payment_type' ) !== 'pay_order' ) {
            //     return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            // }
        }
        // Payment info will not be updated for resubmit
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != 2 ) ) {
            $unfixed_amount_array = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0
            ];
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];

            $check_payment_type = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
//            dd($appData->id);
            $payment_id         = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request ) : '';
        }
        /*
         * if application submitted and status is equal to draft then
         * generate tracking number and payment initiate
         */

        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'Industry New',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }
        if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit') { //  && (($request->actionBtn == 'submit') || ($request->actionBtn === 'Re-submit'))
            $unfixed_amount_array = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
                10 => 0
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

        return redirect( 'client/vsat-license-ammendment/list/' . Encryption::encodeId( $currentInstance->process_type_id ) );

    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {

        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;
        $processList = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            ->first(['company_id']);
        $compId = $processList->company_id;
        $data['process_type_id'] = $process_type_id;

        $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'company_info', 'company_info.id', '=', DB::raw($compId) )
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
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.org_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.org_upazila' )
            ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.org_permanent_district' )
            ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.org_permanent_upazila' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_upazila' )
            ->leftJoin( 'area_info as area_info_district', 'area_info_district.area_id', '=', 'apps.org_district' )
            ->leftJoin( 'area_info as area_info_thana', 'area_info_thana.area_id', '=', 'apps.org_upazila' )
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
                // 'op_office_district.area_nm as op_office_district_en',
                // 'op_office_thana.area_nm as op_office_thana_en',
                'noc_dis.area_nm as op_office_district_en',
                'noc_thana.area_nm as op_office_thana_en',
                'apps.org_address as reg_office_address',
                'apps.org_address as op_office_address',
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

        /** Fetch data from vsat_contact_person */
//        $data['vsat_contact_person_list'] = VSATLicenseContactPerson::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();
        $data['contact_person'] = ContactPerson::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $process_type_id
        ])->get();

        foreach ($data['contact_person'] as $key => $item) {

            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');

            $data['contact_person'][$key]['contact_upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }

        /** Fetch data from vsat_operator_info */
        $data['vsat_hub_info'] = VSATAmendmentTHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_license_list_equipment_info */
        $data['vsat_equipment_list'] = VSATAmendmentLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATServiceProviderInfo */
        $data['vsat_service_provider'] = VSATAmendmentServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_hub_info */
        $data['vsat_technical_specification'] = VSATAmendmentTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATLicenseIssue */
        $data['vsat_license_issue'] =VSATLicenseIssue::where( ['id' => $data['appInfo']['id'] ] )->first();

        /** Fetch data from VSATLicenseIssue */
//        $data['vsat_license_issue_shareholder'] = VSATLicenseIssueShareholder::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();
        $data['appShareholderInfo'] = Shareholder::where([
            'app_id' => $decodedAppId,
            'process_type_id' => $process_type_id
        ])->get();
        $data['org_permanent_district'] = DB::table( 'area_info' )->where( 'area_id', $data['appInfo']->org_permanent_district )->first( [
            'area_nm'
        ] );

        $data['org_permanent_upazila'] = DB::table( 'area_info' )->where( 'area_id', $data['appInfo']->org_permanent_upazila )->first( [
            'area_nm'
        ] );

        $data['applicant_district'] = DB::table( 'area_info' )->where( 'area_id', $data['appInfo']->applicant_district )->first( [
            'area_nm'
        ] );

        $data['applicant_upazila'] = DB::table( 'area_info' )->where( 'area_id', $data['appInfo']->applicant_upazila )->first( [
            'area_nm'
        ] );

        foreach ( $data['appShareholderInfo'] as $key => $item ) {
            $data['vsat_license_issue_shareholder'][ $key ]['shareholder_nationality'] = DB::table( 'country_info' )->where( 'id', $item->nationality )->first( [
                'nationality'
            ] )->nationality;

        }

        foreach ( $data['appShareholderInfo'] as $key => $item ) {

            $data['appShareholderInfo'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm');

            $data['appShareholderInfo'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm');
        }

//        $data['appShareholderInfo'] = VSATLicenseIssueShareholder::where( 'vsat_license_issue_id', $decodedAppId )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana'] = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( "REUSELicenseIssue::VSAT.Amendment.masterView", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function editForm($processTypeId, $applicationId): JsonResponse
    {
        $this->process_type_id   = $data['process_type_id'] = $process_type_id = $processTypeId;

        $applicationId = Encryption::decodeId( $applicationId );

        $data['appInfo'] = ProcessList::leftJoin('vsat_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
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
                'apps.org_nm as company_name',
                'apps.org_type as company_type',
                'apps.org_district as reg_office_district',
                'apps.org_upazila as reg_office_thana',
                'apps.org_address as reg_office_address',
                'apps.org_permanent_district as op_office_district',
                'apps.org_permanent_upazila as op_office_thana',
                'apps.org_permanent_address as op_office_address',
                'apps.applicant_upazila as applicant_thana',
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



        /** Fetch data from vsat_operator_info */
        $data['vsat_hub_info'] = VSATAmendmentTHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_license_list_equipment_info */
        $data['vsat_equipment_list'] = VSATAmendmentLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATServiceProviderInfo */
        $data['vsat_service_provider'] = VSATAmendmentServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_hub_info */
        $data['vsat_technical_specification'] = VSATAmendmentTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();


        $data['pay_order_info'] = DB::table( 'pay_order_payment' )
            ->where( [
                'app_id'          => $data['appInfo']['id'],
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ] )->first();

        if ( ! empty( $data['pay_order_info']->pay_order_date ) ) {
            $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        }

        $public_html = (string)view('REUSELicenseIssue::VSAT.Amendment.masterEdit', $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function fetchData($request, $currentInstance): JsonResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $process_type_id = $currentInstance->process_type_id;

        $data['license_no'] = $request->license_no;
        $companyId = CommonFunction::getUserCompanyWithZero();

        $data['companyInfo'] = CompanyInfo::where('is_approved', 1)->where('id', $companyId)->first();

        if (empty($data['license_no'])) {
            return response()->json(['responseCode' => -1, 'msg' => 'Please provide valid license no']);
        }
        $issue_company_id      = VSATLicenseIssue::where('license_no', $request->license_no)->value('company_id');

        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['master_data'] = VSATLicenseAmendmentMaster::where('license_no', $request->license_no)->first();

        if ($data['master_data']->renew_tracking_no) {
            $data['appInfo'] = ProcessList::leftJoin('vsat_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('vsat_license_master as ms',
                    function ($join) use ($process_type_id) {
                        $join->on('ms.renew_tracking_no', '=', 'apps.tracking_no');
                    })
                ->leftJoin('process_status as ps',
                    function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw(14));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw(15));
                    })
                ->where('ms.license_no', $request->license_no)
                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 14)
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
            $data['appInfo'] = ProcessList::leftJoin('vsat_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('vsat_license_master as ms',
                    function ($join) use ($process_type_id) {
                        $join->on('ms.issue_tracking_no', '=', 'apps.tracking_no');
                    })
                ->leftJoin('process_status as ps',
                    function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw(13));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw(15));
                    })
                ->where('ms.license_no', $request->license_no)
                ->where('ms.status', 1)
                ->where('process_list.process_type_id', 13)
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
                    'apps.org_nm as company_name',
                    'apps.org_type as company_type',
                    // 'apps.org_district as reg_office_district',
                    // 'apps.org_upazila as reg_office_thana',
                    // 'apps.org_address as reg_office_address',
                    // 'apps.org_permanent_district as op_office_district',
                    // 'apps.org_permanent_upazila as op_office_thana',
                    // 'apps.org_permanent_address as op_office_address',
                    // 'apps.applicant_upazila as applicant_thana',
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
        $data['thana'] = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
// dd($data['appInfo']);
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



        /** Fetch data from vsat_operator_info */
        $data['vsat_hub_info'] = VSATHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_license_list_equipment_info */
        $data['vsat_equipment_list'] = VSATLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATServiceProviderInfo */
        $data['vsat_service_provider'] = VSATServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_hub_info */
        $data['vsat_technical_specification'] = VSATTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();


        $data['pay_order_info'] = DB::table( 'pay_order_payment' )
            ->where( [
                'app_id'          => $data['appInfo']['id'],
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ] )->first();
        if ( ! empty( $data['pay_order_info']->pay_order_date ) ) {
            $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        }
        if ( ! empty( $data['pay_order_info']->bg_expire_date ) ) {
            $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
        }

        $public_html = (string)view('REUSELicenseIssue::VSAT.Amendment.search', $data);


        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    private function storeServiceProvider( $appDataId, $request ) {
        if ( isset( $request->service_provider ) && count( $request->service_provider ) > 0 ) {
            VSATAmendmentServiceProviderInfo::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->service_provider as $index => $value ) {
                $serviceProviderObj                        = new VSATAmendmentServiceProviderInfo();
                $serviceProviderObj->vsat_license_issue_id = $appDataId;
                $serviceProviderObj->service_provider_ame  = $value;
                $serviceProviderObj->service_detials       = $request->service_details[ $index ];
                $serviceProviderObj->location              = $request->service_location[ $index ];
                $serviceProviderObj->created_at            = date( 'Y-m-d H:i:s' );
                $serviceProviderObj->save();
            }
        }
    }

    private function storeVSATHubOperatorInfo( $appDataId, $request ) {
        if ( isset( $request->vsat_place_name ) && count( $request->vsat_place_name ) > 0 ) {
            VSATAmendmentTHubOperatorInfo::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->vsat_place_name as $index => $value ) {
                $hubOperatorObj                        = new VSATAmendmentTHubOperatorInfo();
                $hubOperatorObj->vsat_license_issue_id = $appDataId;
                $hubOperatorObj->place_name            = $value;
                $hubOperatorObj->geographical_location = $request->vsat_location[ $index ];
                $hubOperatorObj->created_at            = date( 'Y-m-d H:i:s' );
                $hubOperatorObj->save();
            }
        }
    }

    private function storeTechnicalSpecification( $appDataId, $request ) {
        if ( isset( $request->technical_name ) && count( $request->technical_name ) > 0 ) {
            VSATAmendmentTechnicalSpecification::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->technical_name as $index => $value ) {
                $technicalSpecObj                        = new VSATAmendmentTechnicalSpecification();
                $technicalSpecObj->vsat_license_issue_id = $appDataId;
                $technicalSpecObj->name                  = $value;
                $technicalSpecObj->type                  = $request->technical_type[ $index ];
                $technicalSpecObj->manufacturer          = $request->technical_manufacturer[ $index ];
                $technicalSpecObj->country_of_origin     = $request->technical_country_of_Origin[ $index ];
                $technicalSpecObj->power_output          = $request->technical_power_output[ $index ];
                $technicalSpecObj->created_at            = date( 'Y-m-d H:i:s' );
                $technicalSpecObj->save();
            }
        }
    }

    private function storeListOfEquipment( $appDataId, $request ) {
        if ( isset( $request->list_equipment ) && count( $request->list_equipment ) > 0 ) {
            VSATAmendmentLicenseListOfEquipment::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->list_equipment as $index => $value ) {
                $listOfEquipmentObj                        = new VSATAmendmentLicenseListOfEquipment();
                $listOfEquipmentObj->vsat_license_issue_id = $appDataId;
                $listOfEquipmentObj->equipment_name        = $value;
                $listOfEquipmentObj->storage_capacity      = $request->list_storage[ $index ];
                $listOfEquipmentObj->data                  = $request->list_data[ $index ];
                $listOfEquipmentObj->created_at            = date( 'Y-m-d H:i:s' );
                $listOfEquipmentObj->save();
            }
        }
    }


    public function unfixedAmountsForGovtServiceFee( $isp_license_type, $payment_step_id ) {
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

    public function getPaymentDataByLicense( Request $request ) {
        $payment_type    = $request->payment_type;
        $license_type    = $request->license_type;
        $process_type_id = $request->process_type_id;
        if ( ! $payment_type || ! $license_type || ! $process_type_id ) {
            return response()->json( [
                'responseCode' => - 1,
                'msg'          => 'Process type, Payment type and license type need to be provided.',
                'data'         => []
            ] );
        }

        $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $license_type, $payment_type );

        if ( ! $unfixed_amount_array ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Payment data not found.', 'data' => [] ] );
        }


        $data = [
            'oss_fee' => $unfixed_amount_array[1] + $unfixed_amount_array[2] + $unfixed_amount_array[3],
            'vat'     => $unfixed_amount_array[4] + $unfixed_amount_array[5] + $unfixed_amount_array[6],
        ];

        return response()->json( [
            'responseCode' => 1,
            'msg'          => 'Payment info found successfully.',
            'data'         => $data
        ] );
    }


}
