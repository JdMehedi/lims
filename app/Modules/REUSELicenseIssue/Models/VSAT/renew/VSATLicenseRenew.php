<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\VSAT\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\MNO\issue\MNOLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseContactPerson;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\helper_model\VSATTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\Settings\Models\Area;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VSATLicenseRenew extends Model implements FormInterface {
    protected $table = 'vsat_license_renew';
    protected $guarded = [ 'id' ];
    Protected $process_type_id;
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

        return strval( view( "REUSELicenseIssue::VSAT.Renew.search-blank", $data ) );
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
            $appData     = VSATLicenseRenew::find( Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new VSATLicenseRenew();
            $processData = new ProcessList();
        }
        $appData->company_id   = CommonFunction::getUserCompanyWithZero();
        $appData->org_nm = $request->get( 'company_name' );
        $appData->company_type = $request->get( 'company_type' );

        $appData->sattelite_type = $request->get( 'origin_or_satelite' );
        $appData->license_category = $request->get( 'license_category' );

        $appData->license_no   = $license_no;
        $appData->issue_date   = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $appData->expiry_date  = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;

        $appData->reg_office_district = $request->get( 'reg_office_district' );
        $appData->reg_office_thana    = $request->get( 'reg_office_thana' );
        $appData->reg_office_address  = $request->get( 'reg_office_address' );
        $appData->reg_office_address2  = $request->get( 'reg_office_address2' );

        $appData->per_office_district = $request->get( 'permanent_office_district' );
        $appData->per_office_thana    = $request->get( 'permanent_office_thana' );
        $appData->per_office_address  = $request->get( 'permanent_office_address' );

        $appData->op_office_district = $request->op_office_district;
        $appData->op_office_thana = $request->op_office_thana;
        $appData->op_office_address = $request->op_office_address;
        $appData->op_office_address2 = $request->op_office_address2;

        $appData->total_no_of_share = $request->total_no_of_share;
        $appData->total_share_value = $request->total_share_value;


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
                    $processData->desk_id   = 1;

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


        $this->storeServiceProvider( $appData->id, $request );

        /** Store into vsat_hub_operator_info table */
        $this->storeVSATHubOperatorInfo( $appData->id, $request );

        /** Store into vsat_technical_spec table */
        $this->storeTechnicalSpecification( $appData->id, $request );

        /** Store into vsat_list_of_equipment table */
        $this->storeListOfEquipment( $appData->id, $request );

        //  Required Documents for attachment
        $doc_type_id = '';
        DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );


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

        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id == 1 ) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'VSATR-' . date( 'Ymd' ) . '-';
                commonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, 'vsat_license_renew' );
            }

        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'VSAT License Issue',
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

        return redirect( 'client/vsat-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
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
        $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                'reg_off_district.area_nm as reg_off_dis_nm',
                'reg_off_thana.area_nm as reg_off_thana_nm',
                'apps.*',
                'apps.issue_date as license_issue_date',
                'apps.company_name as org_nm',
                'apps.company_type as org_type',
                'reg_off_district.area_nm as reg_office_district_en',
                'reg_off_thana.area_nm as reg_office_thana_en',
                'op_office_district.area_nm as op_office_district_en',
                'op_office_thana.area_nm as op_office_thana_en',
                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',
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
            'process_type_id' => $process_type_id,
            'status' => 0
        ] )->get();
        
        $data['shareholderInfoForRenew'] = Shareholder::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $process_type_id,
            'status' => 1
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

        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::VSAT.Renew.view', $data );

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
            $NixRenewData            = VSATLicenseRenew::find( $applicationId );
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['thana'] = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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

            $data['vsat_contact_person_list'] = VSATLicenseContactPerson::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_operator_info */
            $data['vsat_hub_info'] = VSATHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from vsat_license_list_equipment_info */
            $data['vsat_equipment_list'] = VSATLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            /** Fetch data from VSATServiceProviderInfo */
            $data['vsat_service_provider'] = VSATServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();
             /** Fetch data from vsat_hub_info */
            $data['vsat_technical_specification'] = VSATTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

            $data['pay_order_info'] = DB::table( 'pay_order_payment' )
            ->where( [
                'app_id'          => $data['appInfo']->id,
                'process_type_id' => $this->process_type_id,
                'payment_step_id' => 1
            ] )->first();

            if ( ! empty( $data['pay_order_info']->pay_order_date ) ) {
                $data['pay_order_info']->pay_order_formated_date = date_format( date_create( $data['pay_order_info']->pay_order_date ), 'Y-m-d' );
            }
            if ( ! empty( $data['pay_order_info']->bg_expire_date ) ) {
                $data['pay_order_info']->bg_expire_formated_date = date_format( date_create( $data['pay_order_info']->bg_expire_date ), 'Y-m-d' );
            }
            // dd($data['vsat_hub_info'],$data['appInfo']->ref_id);
            if ( empty( $NixRenewData->issue_tracking_no ) ) {
                $public_html = (string) view( "REUSELicenseIssue::VSAT.Renew.form-edit-v2", $data );
            } else {
                $public_html = (string) view( 'REUSELicenseIssue::VSAT.Renew.form-edit', $data );
            }

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData( $request, $currentInstance ): JsonResponse {
        // $this->process_type_id = $currentInstance->process_type_id;
        $this->process_type_id = 13; //because fetching data from issue table
        $data['license_no']    = $request->license_no;
        $issue_company_id      = VSATLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId             = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']   = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        if ( empty( $data['license_no'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Please provide valid license no' ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }


        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $process_type_id   = $currentInstance->process_type_id;
        $data['process_type_id'] = $process_type_id;

        $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['thana'] = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'vsat_license_master as ms', function ( $join )  {
                $join->on( 'ms.issue_tracking_no', '=', 'apps.tracking_no' );
            } )
            ->leftJoin( 'process_status as ps', function ( $join )  {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( 13) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join )  {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( 13 ) );       // comes for renew, so have to have payment for issue -> 1
            } )
            ->where( 'ms.license_no', $request->license_no )
            ->where( 'ms.status', 1 )
            ->where( 'process_list.process_type_id', 13 )                       // approved status can be renew
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
                'ms.license_issue_date',
                'ms.expiry_date',
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
        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
//             $companyId               = CommonFunction::getUserCompanyWithZero();
//             $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
// //            $data['companyInfo'] = null;
//             $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
//             $data['division']        = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
//             $data['nationality']     = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
//                     ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();
//             $data['process_type_id'] = $process_type_id = $this->process_type_id;
//             $public_html             = strval( view( 'REUSELicenseIssue::MNO.Renew.search-blank', $data ) );

//             return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }

        $data['companyUserType'] = CommonFunction::getCompanyUserType();


        $shareholders_data = Shareholder::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $this->process_type_id
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

        $contact_data = ContactPerson::where([
            'app_id' => $data['appInfo']->ref_id,
            'process_type_id' => $this->process_type_id // for mno license issue
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

        $data['vsat_contact_person_list'] = VSATLicenseContactPerson::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        /** Fetch data from vsat_operator_info */
        $data['vsat_hub_info'] = VSATHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        /** Fetch data from vsat_license_list_equipment_info */
        $data['vsat_equipment_list'] = VSATLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        /** Fetch data from VSATServiceProviderInfo */
        $data['vsat_service_provider'] = VSATServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        /** Fetch data from vsat_hub_info */
        $data['vsat_technical_specification'] = VSATTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']->ref_id ] )->get();

        $public_html = (string) view( 'REUSELicenseIssue::VSAT.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }


    private function storeServiceProvider( $appDataId, $request ) {
        if ( isset( $request->service_provider ) && count( $request->service_provider ) > 0 ) {
            VSATServiceProviderInfo::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->service_provider as $index => $value ) {
                $serviceProviderObj                        = new VSATServiceProviderInfo();
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
            VSATHubOperatorInfo::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->vsat_place_name as $index => $value ) {
                $hubOperatorObj                        = new VSATHubOperatorInfo();
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
            VSATTechnicalSpecification::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->technical_name as $index => $value ) {
                $technicalSpecObj                        = new VSATTechnicalSpecification();
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
            VSATLicenseListOfEquipment::where( 'vsat_license_issue_id', $appDataId )->delete();
            foreach ( $request->list_equipment as $index => $value ) {
                $listOfEquipmentObj                        = new VSATLicenseListOfEquipment();
                $listOfEquipmentObj->vsat_license_issue_id = $appDataId;
                $listOfEquipmentObj->equipment_name        = $value;
                $listOfEquipmentObj->storage_capacity      = $request->list_storage[ $index ];
                $listOfEquipmentObj->data                  = $request->list_data[ $index ];
                $listOfEquipmentObj->created_at            = date( 'Y-m-d H:i:s' );
                $listOfEquipmentObj->save();
            }
        }
    }

}
