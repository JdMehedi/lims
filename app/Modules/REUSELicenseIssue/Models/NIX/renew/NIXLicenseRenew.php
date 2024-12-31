<?php
/**
 * Author: Md. Abdul Goni Rabbee
 * Date: 17 Nov, 2022
 */

namespace App\Modules\REUSELicenseIssue\Models\NIX\renew;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\Documents\Http\Controllers\DocumentsController;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
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

class NIXLicenseRenew extends Model implements FormInterface {

    use SPPaymentManager;
    use SPAfterPaymentManager;

    protected $table = 'nix_license_renew';
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
            return strval( view( "REUSELicenseIssue::NIX.Renew.master", $data ) );
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
            $appData     = NIXLicenseRenew::find( Encryption::decodeId($request->get('app_id')));
            $processData = ProcessList::where( [
                'process_type_id' => $this->process_type_id,
                'ref_id'          => $appData->id
            ] )->first();
        } else {
            $appData     = new NIXLicenseRenew();
            $processData = new ProcessList();
        }
        $appData->company_id   = CommonFunction::getUserCompanyWithZero();
        $appData->company_name = $request->get( 'company_name' );
        $appData->company_type = $request->get( 'company_type' );
        $appData->license_no   = $license_no;
        $appData->issue_date   = ! empty( $request->get( 'issue_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'issue_date' ) ) ) : null;
        $appData->expiry_date  = ! empty( $request->get( 'expiry_date' ) ) ? date( 'Y-m-d', strtotime( $request->get( 'expiry_date' ) ) ) : null;

        $appData->reg_office_district = $request->get( 'reg_office_district' );
        $appData->reg_office_thana    = $request->get( 'reg_office_thana' );
        $appData->reg_office_address  = $request->get( 'reg_office_address' );

        $appData->per_office_district = $request->get( 'op_office_district' );
        $appData->per_office_thana    = $request->get( 'op_office_thana' );
        $appData->per_office_address  = $request->get( 'op_office_address' );


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
        $appData->total_no_of_share   = $request->get('total_no_of_share');
        $appData->total_share_value   = $request->get('total_share_value');

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

        //Shareholder
        $appData->number_of_share                   = $request->get('number_of_share');
        $appData->shareholders_name                 = $request->get('shareholders_name');
        $appData->shareholders_nid_passport         = $request->get('shareholders_nid_passport');
        $appData->current_number_of_share           = $request->get('current_number_of_share');
        $appData->current_shareholders_name         = $request->get('current_shareholders_name');
        $appData->current_shareholders_nid_passport = $request->get('current_shareholders_nid_passport');
        if ( $request->hasFile( 'shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->shareholders_attachment = $path . $simple_file_name;
        }
        if ( $request->hasFile( 'current_shareholders_attachment' ) ) {
            $path             = CommonFunction::makeDir( 'resubmitApplication' );
            $simpleFile       = $request->file( 'current_shareholders_attachment' );
            $simple_file_name = trim( uniqid( 'CURRENT-SHAREHOLDER' . '-', true ) . '.' . $simpleFile->getClientOriginalExtension() );
            $simpleFile->move( $path, $simple_file_name );
            $appData->current_shareholders_attachment = $path . $simple_file_name;
        }
        $appData->save();
        if ( $appData->id ) {
            //shareholder data insert operation
            if ( $request->get( 'shareholderDataCount' ) > 0 ) {
                commonFunction::storeShareHolderPerson( $request, $this->process_type_id, $appData->id );
            }


            // contact person data insert operation
//            if ( $request->get( 'contactPersonDataCount' ) > 0 ) {
//                            $this->storeNixLicenseContactData( $request, $appData->id );
                commonFunction::storeContactPerson( $request, $this->process_type_id, $appData->id );
//            }

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
                    $processData->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);;
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


        //  Required Documents for attachment
        $doc_type_id = '';
        DocumentsController::storeAppDocuments( $this->process_type_id, $request->doc_type_key, $appData->id, $request );


        //=================================================payment code==========================
        $check_payment_type = false;
        if ( ( isset( $request->payment_type ) || $processData->status_id != $this->re_submit_status_id )) {

            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
            ];
            $contact_info         = [
                'contact_name'    => $request->get( 'contact_name' ),
                'contact_email'   => $request->get( 'contact_email' ),
                'contact_no'      => $request->get( 'contact_no' ),
                'contact_address' => $request->get( 'contact_address' ),
            ];
            $check_payment_type   = ( ! empty( $request->get( 'payment_type' ) ) && $request->get( 'payment_type' ) === 'pay_order' );
            $payment_id           = ! $check_payment_type ? $this->storeSubmissionFeeData( $appData->id, 1, $contact_info, $unfixed_amount_array, $request) : '';
        }


        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ( $request->get( 'actionBtn' ) == 'submit' && $processData->status_id != 2 ) {
            if ( empty( $processData->tracking_no ) ) {
                $trackingPrefix = 'NTTNR-' . date( 'Ymd' ) . '-';
                CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
            }
            if ( $request->has( 'payment_type' ) && $request->get( 'payment_type' ) !== 'pay_order' ) {
                DB::commit();

                // redirect to Sonali Payment Portal
                return SonaliPaymentController::RedirectToPaymentPortal( Encryption::encodeId( $payment_id ) );
            }
        }


        // Send Email for application re-submission
        if ( $processData->status_id == $this->re_submit_status_id ) {
            $userMobile = Auth::user()->user_mobile;
            $loginControllerInstance = new LoginController();

            $trackingNumber = self::where('id','=', $processData->ref_id)->value('tracking_no');
            //Send SMS
            $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'NIX License Renew', '{$trackingNumber}' => $trackingNumber], $userMobile);

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
                'process_type_name' => 'NIX License Renew',
                'remarks'           => '',
            ];

//            $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
            //send email for application re-submission...
            CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
        }

        // for Pay Order
        if ( $check_payment_type && $request->get( 'actionBtn' ) == 'submit' ) {
            $unfixed_amount_array = [
                1 => 0, // Vendor-Service-Fee
                2 => 0, // Govt-Service-Fee
                3 => 0, // Govt. Application Fee
                4 => 0, // Vendor-Vat-Fee
                5 => 0, // Govt-Vat-Fee
                6 => 0, //govt-vendor-vat-fee
                7 => 0, //govt-annual-fee
                8 => 0, //govt-delay-fee
                9 => 0, //govt-annual-vat-feef
                10 => 0 //govt-delay-vat-fee
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

        return redirect( 'client/nix-license-renew/list/' . Encryption::encodeId( $this->process_type_id ) );
    }

    public function viewForm($processTypeId, $applicationId): JsonResponse
    {

        $decodedAppId    = Encryption::decodeId( $applicationId );
        $process_type_id = $processTypeId;

        $data['process_type_id'] = $process_type_id;
        $data['appInfo']         = ProcessList::leftJoin( 'nix_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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

                'per_off_district.area_nm as op_office_district_en',
                'per_off_thana.area_nm as op_office_thana_en',
                'apps.per_office_address as op_office_address',

                'apps.*',
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


//                    if ( $data['appInfo']->status_id == 15 ) { // 15 = Approved for license payment
//                        $data['payment_step_id'] = 1;
//                        $data['unfixed_amounts'] = $this->unfixedAmountsForGovtServiceFee( $data['payment_step_id'] );
//                    }

        $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
        $data['latter'] = DB::table('pdf_print_requests_queue')
        ->where('process_type_id', $data['appInfo']->process_type_id)
        ->where('app_id', $data['appInfo']->ref_id)
        ->pluck('certificate_link', 'pdf_diff')
        ->toArray();

        $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterView', $data );

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
            $NixRenewData            = NIXLicenseRenew::find( $applicationId );
            $data['divisions']       = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
            $data['districts']       = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

            $data['appInfo'] = ProcessList::leftJoin( 'nix_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    'apps.per_office_district as op_office_district',
                    'apps.per_office_thana as op_office_thana',
                    'apps.per_office_address as op_office_address',

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

            if ( empty( $NixRenewData->issue_tracking_no ) ) {
                $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterEditV2', $data );
            } else {
                $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterEdit', $data );
            }

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function fetchData($request, $currentInstance): JsonResponse{
        $data['license_no']      = $request->get( 'license_no' );
        $companyId               = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo']     = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();
        $data['process_type_id'] = $currentInstance->process_type_id;
        $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );

        $issue_process_type_id = 9;
        $data['divisions']     = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['districts']     = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();


        // if license no empty then redirect to application black form for store renew data
        if ( empty( $data['license_no'] ) ) {
            $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterSearchBlank', $data );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
        }
        $issue_company_id      = NIXLicenseIssue::where('license_no', $request->license_no)->value('company_id');
        if ( $companyId != $issue_company_id ) {
            return response()->json( [ 'responseCode' => - 1, 'msg' => 'Try with valid Owner' ] );
        }
        $data['appInfo'] = ProcessList::leftJoin( 'nix_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
            ->leftJoin( 'nix_license_master as ns', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ns.issue_tracking_no', '=', 'apps.tracking_no' );
            } )
            ->leftJoin( 'process_status as ps', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'ps.id', '=', 'process_list.status_id' );
                $join->on( 'ps.process_type_id', '=', DB::raw( $issue_process_type_id ) );
            } )
            ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $issue_process_type_id ) {
                $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                $join->on( 'sfp.process_type_id', '=', DB::raw( 1 ) );       // comes for renew, so have to have payment for issue -> 1
            } )
            ->where( 'ns.license_no', $request->license_no )
            ->where( 'process_list.process_type_id', 9 )
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
                'apps.company_name as org_nm',
                'apps.company_type as org_type',
                'apps.per_office_district as op_office_district',
                'apps.per_office_thana as op_office_thana',
                'apps.per_office_address as op_office_address',

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

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        if ( empty( $data['appInfo'] ) ) {
            $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterSearchBlank', $data );

            return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

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

        $public_html = (string) view( 'REUSELicenseIssue::NIX.Renew.masterSearch', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );

    }
}
