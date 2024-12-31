<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\ICX\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\HelpText;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseMaster;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Users\Models\Countries;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ICXLicenseRenew extends Model implements FormInterface {
    protected $table = 'icx_license_renew';
    protected $guarded = [ 'id' ];

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
            $data['process_type_id'] = $process_type_id = $currentInstance->process_type_id;
            $companyId               = CommonFunction::getUserCompanyWithZero();
            $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
            $data['process_type_id'] = $currentInstance->process_type_id;
            $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

            $process_type_id   = $currentInstance->process_type_id;
            $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            //Add extra for nationality to avoid error in shareholder sub-view
            $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                    ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

            $data['shareholder_check'] = HelpText::where('module','icx-license-renew')
            ->where('field_id','shareholder_check')->first();
            return strval( view( "REUSELicenseIssue::ICX.Renew.form",$data  ) );
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
            $appData     = ICXLicenseRenew::find( Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new ICXLicenseRenew();
            $processData = new ProcessList();
        }
        $appData->company_id   = CommonFunction::getUserCompanyWithZero();
        $existedData = ICXLicenseMaster::leftJoin( 'icx_license_issue as apps', 'apps.tracking_no', '=', 'icx_license_master.issue_tracking_no' )
            ->where( [ 'icx_license_master.license_no' => $license_no ] )->first();

        $appData->org_nm = $request->get( 'company_name' );
        $appData->org_type = $request->get( 'company_type' );
        $appData->license_no   = $license_no;
        $appData->issue_date   = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $appData->expiry_date  = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;

        $appData->issue_tracking_no = ! empty( $existedData['issue_tracking_no'] ) ? $existedData['issue_tracking_no'] : null;
        $appData->expiry_date       = ! empty( $existedData['expiry_date'] ) ? $existedData['expiry_date'] : null;
        $appData->issue_date       = ! empty( $existedData['license_issue_date'] ) ? $existedData['license_issue_date'] : null;

        $appData->reg_office_district = $request->get( 'reg_office_district' );
        $appData->reg_office_thana    = $request->get( 'reg_office_thana' );
        $appData->reg_office_address  = $request->get( 'reg_office_address' );

        $appData->op_office_district = $request->get( 'op_office_district');
        $appData->op_office_thana    = $request->get( 'op_office_thana' );
        $appData->op_office_address  = $request->get( 'op_office_address' );

        $appData->total_no_of_share  = $request->get( 'total_no_of_share');
        $appData->total_share_value  = $request->get( 'total_share_value');


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
        //images
        if ($request->hasFile('declaration_q3_images')) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path = 'uploads/icx-license-renew/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('declaration_q3_images');
            $file_path = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $appData->declaration_q3_doc = $path . $file_path;
        }
        //images
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
                }
            }
            $processData->ref_id          = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->office_id       = 0; // $request->get('pref_reg_office');
            $jsonData['Applicant Name']   = Auth::user()->user_first_name;
            // need to change
            $jsonData['Company Name']   = $request->company_org_name;
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
                $trackingPrefix = 'ICXR-' . date( 'Ymd' ) . '-';
                commonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, 'icx_license_renew' );
            }

        }
        // Send Email notification to user on application re-submit
        if ( $processData->status_id == 2 ) {
            $appInfo = [
                'app_id'            => $processData->ref_id,
                'status_id'         => $processData->status_id,
                'process_type_id'   => $processData->process_type_id,
                'tracking_no'       => $processData->tracking_no,
                'process_type_name' => 'ICX License Renew',
                'remarks'           => '',
            ];

            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
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

        return redirect( 'client/icx-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm( $processTypeId, $applicationId ): JsonResponse {

        $decodedAppId = Encryption::decodeId( $applicationId );

        $data['process_type_id'] = $processTypeId;

        $data['appInfo'] = ProcessList::leftJoin( 'icx_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $processTypeId ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $processTypeId ) );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $processTypeId ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $processTypeId ) );
            } )
            ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
            ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
            ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
            ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
            ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
            ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
            ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
            ->where( 'process_list.ref_id', $decodedAppId )
            ->where( 'process_list.process_type_id', $processTypeId )
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
                'apps.op_office_address as  ',

                'applicant_district.area_nm as applicant_district_en',
                'applicant_thana.area_nm as applicant_thana_en',

                'apps.*',
                'apps.org_nm as org_nm',
                'apps.org_type as org_type',
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

        $data['appShareholderInfo'] = self::Join( 'shareholders', 'shareholders.app_id', '=', 'icx_license_renew.id' )
            ->where( [
                'icx_license_renew.id'        => $decodedAppId,
                'shareholders.process_type_id' => $processTypeId
            ] )
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


        $data['appDynamicDocInfo'] = ApplicationDocuments::where( 'process_type_id', $processTypeId )
            ->where( 'ref_id', $decodedAppId )
            ->whereNotNull('uploaded_path')
            ->get();


        $data['contact_person'] = ContactPerson::where( [
            'app_id'          => $data['appInfo']['id'],
            'process_type_id' => $processTypeId
        ] )->get();

        foreach ( $data['contact_person'] as $key => $item ) {
            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );
            $data['contact_person'][ $key ]['contact_upazila_name']  = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
        }


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
        $public_html       = (string) view( 'REUSELicenseIssue::ICX.Renew.view', $data );

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
            $IcxRenewData            = ICXLicenseRenew::find( $applicationId );
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['thana']           = [ '' => 'Select' ] + Area::where( 'area_type', 3 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

            $data['appInfo'] = ProcessList::leftJoin( 'icx_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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

            if ( empty( $IcxRenewData->issue_tracking_no ) ) {
                $public_html = (string) view( 'REUSELicenseIssue::ICX.Renew.form-edit-v2', $data );
            } else {
                $public_html = (string) view( 'REUSELicenseIssue::ICX.Renew.form-edit', $data );
            }

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData($request, $currentInstance): JsonResponse{
        $data['license_no']      = $request->get( 'license_no' );
        $issue_company_id      = ICXLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $issue_process_type_id = 33;
        $data['divisions']     = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts']     = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['shareholder_check'] = HelpText::where('module','icx-license-renew')
        ->where('field_id','shareholder_check')->first();
        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
        ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        // if license no empty then redirect to application black form for store renew data
        if ( empty( $data['license_no'] ) ) {
            $public_html = (string) view( 'REUSELicenseIssue::ICX.Renew.form', $data );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }

        $data['appInfo'] = ProcessList::leftJoin( 'icx_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'icx_license_master as ns', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ns.issue_tracking_no', '=', 'apps.tracking_no' );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $issue_process_type_id ) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( $issue_process_type_id ) );       // comes for renew, so have to have payment for issue -> 1
            } )
            ->where( 'ns.license_no', $request->license_no )
            ->where( 'process_list.process_type_id', $issue_process_type_id )
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
                'apps.permanent_office_district as op_office_district',
                'apps.permanent_office_thana as op_office_thana',
                'apps.permanent_office_address as op_office_address',
                'apps.applicant_mobile_no as applicant_mobile',
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
                'apps.company_org_name as org_nm'
            ] );



        if ( empty( $data['appInfo'] ) ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Data not found on provided license number' ] );
            // $public_html = (string) view( 'REUSELicenseIssue::ICX.Renew.form', $data );

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

        $contact_data = ContactPerson::where( [ 'app_id'          => $data['appInfo']->ref_id,
            'process_type_id' => $data['appInfo']->process_type_id
        ] )->get();

        foreach ( $contact_data as $index => $value ) {
            if ( public_path( $value->image ) && ! empty( $value->image ) ) {
                $value->image_real_path = $value->image;
                $value->image           = CommonFunction::imagePathToBase64( public_path( $value->image ) );
            }
        }
        $data['contact_person'] = $contact_data;

        $public_html = (string) view( 'REUSELicenseIssue::ICX.Renew.search', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

    }

    public function getProcessDeskStatus($payment_json_name, $json_data, $sql_params)
    {
        $decoded_json = json_decode($json_data, true);
        if (!isset($decoded_json[$payment_json_name]) or empty($decoded_json[$payment_json_name])) {
            throw new Exception('Proper Json data found for this payment processing. Please configure proper json data.');
        }

        if (!isset($decoded_json[$payment_json_name]['process_starting_desk_sql']) or empty($decoded_json[$payment_json_name]['process_starting_desk_sql'])) {
            throw new Exception('Process desk SQL not found for this payment processing. Please configure proper json data.');
        }

        if (!isset($decoded_json[$payment_json_name]['process_starting_status_sql']) or empty($decoded_json[$payment_json_name]['process_starting_status_sql'])) {
            throw new Exception('Process status SQL not found for this payment processing. Please configure proper json data.');
        }

        $process_desk_sql = $decoded_json[$payment_json_name]['process_starting_desk_sql'];
        $process_desk_sql = str_replace("{app_id}", $sql_params['app_id'], $process_desk_sql);

        $process_status_sql = $decoded_json[$payment_json_name]['process_starting_status_sql'];
        $process_status_sql = str_replace("{app_id}", $sql_params['app_id'], $process_status_sql);

        $process_desk_result = DB::select(DB::raw($process_desk_sql));
        $process_status_result = DB::select(DB::raw($process_status_sql));

        if (!isset($decoded_json[$payment_json_name]['process_starting_user_sql']) or empty($decoded_json[$payment_json_name]['process_starting_user_sql'])) {
            $process_starting_user = 0;
        } else {
            $process_user_sql = $decoded_json[$payment_json_name]['process_starting_user_sql'];
            $process_user_sql = str_replace("{app_id}", $sql_params['app_id'], $process_user_sql);

            $process_user_result = DB::select(DB::raw($process_user_sql));
            $process_starting_user = (int) $process_user_result[0]->process_starting_user;
        }

        $data = [
            'process_starting_desk' => (int)$process_desk_result[0]->process_starting_desk,
            'process_starting_status' => (int) $process_status_result[0]->process_starting_status,
            'process_starting_user' => $process_starting_user
        ];

        return $data;
    }
}
