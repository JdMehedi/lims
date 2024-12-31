<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\VSAT\surrender;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewTariffChart;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATAmendmentTHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseContactPerson;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseRenewContactPerson;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseRenewListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATRenewHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATRenewServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATRenewTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseMaster;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Users\Models\Countries;
use App\Modules\REUSELicenseIssue\Models\VSAT\surrender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VSATLicenseSurrender extends Model implements FormInterface {
    protected $table = 'vsat_license_cancellation';
    protected $guarded = [ 'id' ];
    Protected $process_type_id;

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

        return strval( view( "REUSELicenseIssue::VSAT.Surrender.search-blank", $data ));
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
            $appData     = self::find( Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new self();
            $processData = new ProcessList();
        }
        $appData->company_id   = CommonFunction::getUserCompanyWithZero();
        $appData->org_nm = $request->get( 'company_name' );
        $appData->org_type = $request->get( 'company_type' );
        $appData->reason_of_surrender = $request->get( 'reason_of_surrender' );
        $appData->surrender_date = date( 'Y-m-d', strtotime( $request->get( 'surrender_date' ) ) );
        $appData->license_category = $request->get( 'license_category' );
        $appData->sattelite_type   = $request->get( 'origin_or_satelite' );
        $appData->license_no       = $request->get('license_no');
        $appData->license_issue_date   = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $appData->expiry_date  = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;

        $appData->reg_office_district = $request->get( 'reg_office_district' );
        $appData->reg_office_thana    = $request->get( 'reg_office_thana' );
        $appData->reg_office_address  = $request->get( 'reg_office_address' );
        $appData->reg_office_address2  = $request->get( 'reg_office_address2' );
        $appData->op_office_district = $request->get( 'op_office_district' );
        $appData->op_office_thana    = $request->get( 'op_office_thana' );
        $appData->op_office_address  = $request->get( 'op_office_address' );
        $appData->op_office_address2  = $request->get( 'op_office_address2' );

        $appData->per_office_district = $request->get( 'permanent_office_district' );
        $appData->per_office_thana    = $request->get( 'permanent_office_thana' );
        $appData->per_office_address  = $request->get( 'permanent_office_address' );


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
//                            $this->storeNixLicenseContactData( $request, $appData->id );
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

                    $processData->previous_hash = $processData->hash_value ?? '';
                    $processData->hash_value    = Encryption::encode( $resultData );

                } else {
                    $processData->status_id = 1;
                    $processData->desk_id   = 3;

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
            $processData->license_no      = $appData->license_no;
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


        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'VSATS-' . date( 'Ymd' ) . '-';
                commonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, 'vsat_license_cancellation' );
            }

        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'VSAT License Surrender',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
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

        return redirect( 'client/vsat-license-cancellation/list/' . Encryption::encodeId( $this->process_type_id ) );
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
        $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_cancellation as apps', 'apps.id', '=', 'process_list.ref_id' )
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
            ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as per_off_district', 'per_off_district.area_id', '=', 'apps.per_office_district' )
            ->leftJoin( 'area_info as per_off_thana', 'per_off_thana.area_id', '=', 'apps.per_office_thana' )
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
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
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

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']     = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $public_html = (string) view( 'REUSELicenseIssue::VSAT.Surrender.view', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
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
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['thana']           = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

            $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_cancellation as apps', 'apps.id', '=', 'process_list.ref_id' )
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

            $contact_data = ContactPerson::where( [ 'app_id'          => $applicationId,
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

                    $data['vsat_hub_info'] = VSATAmendmentTHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_license_list_equipment_info */
        $data['vsat_equipment_list'] = VSATAmendmentLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATServiceProviderInfo */
        $data['vsat_service_provider'] = VSATAmendmentServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from vsat_hub_info */
        $data['vsat_technical_specification'] = VSATAmendmentTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

        /** Fetch data from VSATLicenseIssue */
        $data['vsat_license_issue'] = \App\Modules\VSATLicenseIssue\Models\VSATLicenseIssue::where( ['id' => $data['appInfo']['id'] ] )->first();

        $data['pay_order_info'] = DB::table( 'pay_order_payment' )
            ->where( [
                'app_id'          => $data['appInfo']['id'],
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ] )->first();

        if ( ! empty( $data['pay_order_info']->pay_order_date ) ) {
            $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
        }

            $public_html = (string) view( 'REUSELicenseIssue::VSAT.Surrender.form-edit-v2', $data );
            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        $data['license_no']      = $request->get( 'license_no' );
        $process_type_id         = $currentInstance->process_type_id;
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $currentInstance->process_type_id;
//        dd($data['process_type_id']);
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions']     = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts']     = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']         = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        $issue_company_id      = VSATLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( $companyId != $issue_company_id ) {
            return response()->json(['responseCode' => -1, 'msg' => 'Try with valid Owner']);
        }


        $data['master_data'] = VSATLicenseMaster::where('license_no', $request->license_no)->first();

        if (!empty($data['master_data']->renew_tracking_no)) {
            $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin('vsat_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.renew_tracking_no', '=', 'apps.tracking_no' );
                    })
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( 14));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(14));
                    })
                ->where( 'ms.license_no', $request->license_no)
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 14)
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
        } else {
            $data['license_no']    = $request->license_no;
            $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                ->leftJoin( 'vsat_license_master as ms',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
                    } )
                ->leftJoin('process_status as ps',
                    function ( $join ) use ( $process_type_id) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw(13));
                    })
                ->leftJoin('sp_payment as sfp',
                    function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id');
                        $join->on( 'sfp.process_type_id', '=', DB::raw(13));
                    })
                ->where( 'ms.license_no', $request->license_no )
                ->where( 'ms.status', 1 )
                ->where('process_list.process_type_id', 13)
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

        }


//        $data['process_type_id'] = $this->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana']     = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
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

        if(!empty($data['master_data']->renew_tracking_no)){
            /** Fetch data from vsat_license_renew_equipment_list */
            $data['vsat_contact_person_list'] = VSATLicenseRenewContactPerson::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_license_renew_operator_info */
            $data['vsat_hub_info'] = VSATRenewHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_license_renew__list_equipment_info */
            $data['vsat_equipment_list'] = VSATLicenseRenewListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from VSATServiceProviderInfo */
            $data['vsat_service_provider'] = VSATRenewServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_license_renew_hub_info */
            $data['vsat_technical_specification'] = VSATRenewTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        }else{
            /** Fetch data from isp_license_equipment_list */
            $data['vsat_contact_person_list'] = VSATLicenseContactPerson::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_operator_info */
            $data['vsat_hub_info'] = VSATHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_license_list_equipment_info */
            $data['vsat_equipment_list'] = VSATLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from VSATServiceProviderInfo */
            $data['vsat_service_provider'] = VSATServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();
            /** Fetch data from vsat_hub_info */
            $data['vsat_technical_specification'] = VSATTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        }
        $public_html = (string) view( 'REUSELicenseIssue::VSAT.Surrender.search', $data );
        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }
}
