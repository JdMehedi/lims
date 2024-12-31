<?php

namespace App\Modules\REUSELicenseIssue\Models\SCS\issue;

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
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Http\Controllers\SonaliPaymentController;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SCSLicenseIssue extends Model implements FormInterface
{
    protected $table = 'scs_license_issue';
    protected $guarded = ['id'];
    protected $process_type_id;
    private $shortfall_status_id = 5;
    private $re_submit_status_id = 2;
    private $draft_status_id = - 1;
    private $submitted_status_id = 1;

    use SPPaymentManager;
    use SPAfterPaymentManager;

    public function createIssueForm($currentInstance){
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $currentInstance->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( "REUSELicenseIssue::SCS.Issue.master", $data ) );
    }
    public function storeIssueForm( $request, $currentInstance ){

            $this->process_type_id = $currentInstance->process_type_id;

            if ( $request->get( 'app_id' ) ) {
                $app_id = Encryption::decodeId($request->get('app_id'));
                $appData     = SCSLicenseIssue::find( $app_id );
                $processData = ProcessList::where( [
                    'process_type_id' => $currentInstance->process_type_id,
                    'ref_id'          => $appData->id
                ] )->first();
            } else {
                $appData     = new SCSLicenseIssue();
                $processData = new ProcessList();
            }

            $appData = $this->storeNIXLicenseData( $appData, $request );

            if ( $appData->id ) {

                //shareholder data insert operation
                if ( $request->get( 'shareholderDataCount' ) > 0 ) {
//                            $this->storeNixLicenseShareHolder( $request, $appData->id );
                    CommonFunction::storeShareHolderPerson( $request, $currentInstance->process_type_id, $appData->id );
                }

                // contact person data insert operation
                if ( $request->get( 'contactPersonDataCount' ) > 0 ) {
//                            $this->storeNixLicenseContactData( $request, $appData->id );
                    CommonFunction::storeContactPerson( $request, $currentInstance->process_type_id, $appData->id,  );
                }

                //process list data insert
                $processData->company_id = CommonFunction::getUserCompanyWithZero();

                //Set category id for process differentiation   dd($request->all());
                $processData->cat_id = 1;
                if ( $request->get( 'actionBtn' ) == "draft" ) {
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

                        $processData->previous_hash = $processData->hash_value ?? "";
                        $processData->hash_value    = Encryption::encode( $resultData );

                    } else {
                        $processData->status_id = 1;
                        $processData->desk_id   = 3;
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
                    $trackingPrefix = 'NIX-' . date( 'Ymd' ) . '-';
                    CommonFunction::generateTrackingNumber( $trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table );
                }
                DB::commit();

            }
            // Send Email notification to user on application re-submit
            if ( $processData->status_id == 2 ) {
                $appInfo = [
                    'app_id'            => $processData->ref_id,
                    'status_id'         => $processData->status_id,
                    'process_type_id'   => $processData->process_type_id,
                    'tracking_no'       => $processData->tracking_no,
                    'process_type_name' => 'Nix License Issue',
                    'remarks'           => '',
                ];

                $receiverInfo = CommonFunction::getCompanyUsersEmailPhone();
                //send email for application re-submission...
                CommonFunction::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
            }

            DB::commit();
            if ( $processData->status_id == - 1 ) {
                Session::flash( 'success', 'Successfully updated the Application!' );
            } elseif ( $processData->status_id == 1 ) {
                Session::flash( 'success', 'Successfully Application Submitted !' );
            } elseif ( $processData->status_id == 2 ) {
                Session::flash( 'success', 'Successfully Application Re-Submitted !' );
            } else {
                Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]' );
            }
            return 'client/nix-license-issue/list/' . Encryption::encodeId( $this->process_type_id );
    }


    private function storeLicenseData($LicenseIssueObj, $request)
    {

        $LicenseIssueObj->org_nm   = $request->get('company_name');
        $LicenseIssueObj->org_type = $request->get('company_type');

        $LicenseIssueObj->reg_office_district = $request->get('reg_office_district');
        $LicenseIssueObj->reg_office_thana    = $request->get('reg_office_thana');
        $LicenseIssueObj->reg_office_address  = $request->get('reg_office_address');
        // $LicenseIssueObj->reg_office_address2 = $request->get('reg_office_address2');
        $LicenseIssueObj->op_office_district  = $request->get('op_office_district');
        $LicenseIssueObj->op_office_thana     = $request->get('op_office_thana');
        $LicenseIssueObj->op_office_address   = $request->get('op_office_address');
        // $LicenseIssueObj->op_office_address2  = $request->get('op_office_address2');

        $LicenseIssueObj->applicant_name      = $request->get('applicant_name');
        $LicenseIssueObj->applicant_mobile    = $request->get('applicant_mobile');
        $LicenseIssueObj->applicant_telephone = $request->get('applicant_telephone');
        $LicenseIssueObj->applicant_email     = $request->get('applicant_email');
        $LicenseIssueObj->applicant_district  = $request->get('applicant_district');
        $LicenseIssueObj->applicant_thana     = $request->get('applicant_thana');
        $LicenseIssueObj->applicant_address   = $request->get('applicant_address');
        // $LicenseIssueObj->applicant_address2  = $request->get('applicant_address2');


        $typeOfIspLicense                      = $request->get('type_of_isp_licensese');
        $LicenseIssueObj->isp_license_type     = $typeOfIspLicense;
        $LicenseIssueObj->isp_license_division = $request->get('isp_licensese_area_division');
        $LicenseIssueObj->isp_license_district = $request->get('isp_licensese_area_district');
        $LicenseIssueObj->isp_license_upazila  = $request->get('isp_licensese_area_thana');

        $LicenseIssueObj->location_of_ins_district = $request->get('location_of_ins_district');
        $LicenseIssueObj->location_of_ins_thana    = $request->get('location_of_ins_thana');
        $LicenseIssueObj->location_of_ins_address  = $request->get('location_of_ins_address');
        // $LicenseIssueObj->location_of_ins_address2 = $request->get('location_of_ins_address2');

        $LicenseIssueObj->home       = $request->get('home');
        $LicenseIssueObj->cyber_cafe = $request->get('cyber_cafe');
        $LicenseIssueObj->office     = $request->get('office');
        $LicenseIssueObj->others     = $request->get('others');

        $LicenseIssueObj->corporate_user = $request->get('corporate_user');
        $LicenseIssueObj->personal_user  = $request->get('personal_user');
        $LicenseIssueObj->branch_user    = $request->get('branch_user');
        // list_of_clients
        if ($request->hasFile('list_of_clients')) {
            $yearMonth = date('Y') . '/' . date('m') . '/';
            $path      = 'uploads/isp-license-issue/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('list_of_clients');
            $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $LicenseIssueObj->list_of_clients = $path . $file_path;
        }


        if ($typeOfIspLicense == 1) {
            $LicenseIssueObj->isp_license_division = null;
            $LicenseIssueObj->isp_license_district = null;
            $LicenseIssueObj->isp_license_upazila  = null;
        } elseif ($typeOfIspLicense == 2) {
            $LicenseIssueObj->isp_license_district = null;
            $LicenseIssueObj->isp_license_upazila  = null;
        } elseif ($typeOfIspLicense == 3) {
            $LicenseIssueObj->isp_license_upazila = null;
        }

        $LicenseIssueObj->business_plan       = $request->get('business_plan');
        $LicenseIssueObj->declaration_q1      = $request->get('declaration_q1');
        $LicenseIssueObj->declaration_q1_text = $request->get('declaration_q1_text');
        $LicenseIssueObj->declaration_q2      = $request->get('declaration_q2');
        $LicenseIssueObj->declaration_q2_text = $request->get('declaration_q2_text');
        $LicenseIssueObj->declaration_q3      = $request->get('declaration_q3');
        $LicenseIssueObj->status              = 1;
        $LicenseIssueObj->created_at          = Carbon::now();
        $LicenseIssueObj->company_id          = CommonFunction::getUserCompanyWithZero();
        $LicenseIssueObj->total_no_of_share   = $request->get('total_no_of_share');
        $LicenseIssueObj->total_share_value   = $request->get('total_share_value');
        //images
        if ($request->hasFile('declaration_q3_images')) {
            $yearMonth = date('Y') . '/' . date('m') . '/';
            $path      = 'uploads/isp-license-issue/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $_file_path = $request->file('declaration_q3_images');
            $file_path  = trim(uniqid('BTRC_LIMS-' . '-', true) . $_file_path->getClientOriginalName());
            $_file_path->move($path, $file_path);
            $LicenseIssueObj->declaration_q3_doc = $path . $file_path;
        }
        //images
        $LicenseIssueObj->save();

        return $LicenseIssueObj;
    }

    private function storeProcessListData( $request, $processListObj, $appData ) {

        $processListObj->company_id = CommonFunction::getUserCompanyWithZero();

        //Set category id for process differentiation
        $processListObj->cat_id = 1;
        if ( $request->get( 'actionBtn' ) === 'draft' ) {
            $processListObj->status_id = $this->draft_status_id;
            $processListObj->desk_id   = 0;
        } elseif ( $processListObj->status_id === $this->shortfall_status_id ) {
            // For shortfall
            $submission_sql_param = [
                'app_id'          => $appData->id,
                'process_type_id' => $this->process_type_id,
            ];

            $process_type_info = ProcessType::where( 'id', $this->process_type_id )
                                    ->orderBy( 'id', 'desc' )
                                    ->first( [
                                        'form_url',
                                        'process_type.process_desk_status_json',
                                        'process_type.name'
                                    ] );


            $resubmission_data              = $this->getProcessDeskStatus( 'resubmit_json', $process_type_info->process_desk_status_json, $submission_sql_param );
            $processListObj->status_id      = $resubmission_data['process_starting_status'];
            $processListObj->desk_id        = $resubmission_data['process_starting_desk'];
            $processListObj->process_desc   = 'Re-submitted form applicant';
            $processListObj->resubmitted_at = Carbon::now(); // application resubmission Date

            $resultData = "{$processListObj->id}-{$processListObj->tracking_no}{$processListObj->desk_id}-{$processListObj->status_id}-{$processListObj->user_id}-{$processListObj->updated_by}";

            $processListObj->previous_hash = $processListObj->hash_value ?? '';
            $processListObj->hash_value    = Encryption::encode( $resultData );

        } else {
            $processListObj->status_id = 1;
            $processListObj->submitted_at = Carbon::now();
            $processListObj->desk_id   = CommonFunction::getDynamicallyDeskUser($this->process_type_id);
        }

        $processListObj->ref_id          = $appData->id;
        $processListObj->process_type_id = $this->process_type_id;
        $processListObj->office_id       = 0;
        $jsonData['Applicant Name']      = Auth::user()->user_first_name;
        $jsonData['Company Name']        = $request->company_name;
        $jsonData['Email']               = Auth::user()->user_email;
        $jsonData['Phone']               = Auth::user()->user_mobile;
        $processListObj['json_object']   = json_encode( $jsonData );
        $processListObj->save();

        return $processListObj;

    }

    public function createForm($currentInstance): string
    {
        $companyId           = CommonFunction::getUserCompanyWithZero();
        $data['companyInfo'] = CompanyInfo::where( 'is_approved', 1 )->where( 'id', $companyId )->first();

        $data['process_type_id']  = $currentInstance->process_type_id;
        $data['acl_name']         = $currentInstance->acl_name;
        $data['application_type'] = ProcessType::Where( 'id', $currentInstance->process_type_id )->value( 'name' );
        $data['districts']        = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();
        $data['division']         = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

        $data['nationality'] = [ '' => 'Select' ] + Countries::where( 'country_status', 'Yes' )->where( 'nationality', '!=', '' )
                ->orderby( 'nationality' )->pluck( 'nationality', 'id' )->toArray();

        return strval( view( "REUSELicenseIssue::SCS.Issue.master", $data ) );
    }

    public function storeForm($request, $currentInstance): RedirectResponse
    {
        $this->process_type_id = $currentInstance->process_type_id;
        $app_id                = $request->get('app_id');

        //E-nothi information array
        $applicationInfo = [];
        $shareHolderArr = [];
        $contactArr = [];
        $documentsArr = [];

        if ($request->get('app_id')) {
            $appData     = SCSLicenseIssue::find(Encryption::decodeId($app_id));
            $processData = ProcessList::where([
                'process_type_id' => $currentInstance->process_type_id,
                'ref_id' => $appData->id
            ])->first();

           // dd( $processData);
        } else {
            $appData     = new SCSLicenseIssue();
            $processData = new ProcessList();
        }

        $appData = $this->storeLicenseData($appData, $request);

        if ($appData->id) {
            // Store ShareHolder Person
            if (intval($request->get('shareholderDataCount'))) {
                $shareHolderArr = CommonFunction::storeShareHolderPerson($request, $this->process_type_id, $appData->id);
            }

            // Store Contact Person
            $contactArr = CommonFunction::storeContactPerson($request, $this->process_type_id, $appData->id);

            //Dynamic Document Store
            $documentsArr = DocumentsController::storeAppDocuments($currentInstance->process_type_id, $request->doc_type_key, $appData->id, $request);

            // Store Process list Data
            $processData = $this->storeProcessListData($request, $processData, $appData);
        }

        //  Required Documents for attachment
        DocumentsController::storeAppDocuments($this->process_type_id, $request->doc_type_key, $appData->id, $request);

        //=================================================payment code==========================
        $check_payment_type = false;
        if ((isset($request->payment_type) || $processData->status_id != $this->re_submit_status_id) && !empty($appData->isp_license_type)) {
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

        //Generate new Tracking number
        if (in_array($request->get('actionBtn'), ['draft', 'submit']) && empty($appData->tracking_no)) {
            CommonFunction::generateUniqueTrackingNumber('SCS', $this->process_type_id, $processData->id, $this->table, 'ISS', $appData->id);
        }

        /** if application submitted and status is equal to draft then generate tracking number and payment initiate  ***/
        if ($request->get('actionBtn') == 'submit' && $processData->status_id == $this->draft_status_id && isset($payment_id)) {
            if (empty($appData->tracking_no)) {
//                $trackingPrefix = 'ISP-' . date('Ymd') . '-';
//                CommonFunction::updateAppTableByTrackingNo($processData->id, $appData->id, $this->table);
                #CommonFunction::InitialTractionGenerator($this->process_type_id,$processData->id);
                #CommonFunction::generateTrackingNumber($trackingPrefix, $this->process_type_id, $processData->id, $appData->id, $this->table);
//                CommonFunction::generateUniqueTrackingNumber('ISP',$this->process_type_id, $processData->id, $this->table, 'ISS',$appData->id);
            }


//


            if ($request->get('payment_type') !== 'pay_order') {
                DB::commit();

                // redirect to Sonali Payment Portal
               // return SonaliPaymentController::RedirectToPaymentPortal(Encryption::encodeId($payment_id));
            }

        }


        // // Send Email for application re-submission
        // if ($processData->status_id == $this->re_submit_status_id) {
        //     //Preparing E-nothi Data Start
        //     $processListInfo = ProcessList::where([
        //         'id' => $processData->id,
        //         'ref_id' => $appData->id
        //     ])->latest()->first([
        //         'tracking_no'
        //     ])->toArray();

        //     commonFunction::prepareApplicationSubmissionJsonObject($this->process_type_id, $appData->id, $processListInfo['tracking_no'], $processData->id);

        //     $userMobile = Auth::user()->user_mobile;
        //     $loginControllerInstance = new LoginController();
        //     $loginControllerInstance->SendSmsService('APP_RESUBMIT', ['{$serviceName}' => 'SCS License Issue', '{$trackingNumber}' => $processData->tracking_no], $userMobile);
        //     CommonFunction::sendEmailForReSubmission($processData);
        // }

        // // for Pay Order
        // if ($check_payment_type && in_array($request->get('actionBtn'), ['submit', 'Re-submit'])) {
        //     $unfixed_amount_array = [
        //         1 => 0, // Vendor-Service-Fee
        //         2 => $request->get('pay_amount'), // Govt-Service-Fee
        //         3 => 0, // Govt. Application Fee
        //         4 => 0, // Vendor-Vat-Fee
        //         5 => $request->get('vat_on_pay_amount'), // Govt-Vat-Fee
        //         6 => 0 //govt-vendor-vat-fee
        //     ];
        //     $contact_info         = [
        //         'contact_name' => $request->get('contact_name'),
        //         'contact_email' => $request->get('contact_email'),
        //         'contact_no' => $request->get('contact_no'),
        //         'contact_address' => $request->get('contact_address'),
        //     ];
        //     $this->storeSubmissionFeeDataV2($appData->id, 1, $contact_info, $unfixed_amount_array, $request);
        // }

        DB::commit();

        if (in_array($request->get('actionBtn'), ['submit', 'Re-submit'])){
            CommonFunction::DNothiRequest($processData->id);

        }

        if ($processData->status_id == $this->draft_status_id) {
            Session::flash('success', 'Successfully updated the Application!');
        } elseif ($processData->status_id == $this->submitted_status_id) {
            Session::flash('success', 'Successfully Application Submitted !');
        } elseif ($processData->status_id == $this->re_submit_status_id) {
            Session::flash('success', 'Successfully Application Re-Submitted !');
        } else {
            Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VA-1023]');
        }

        return redirect('client/scs-license-issue/list/' . Encryption::encodeId($this->process_type_id));
    }

    public function viewForm( $processTypeId, $appId ): JsonResponse {
        $this->process_type_id = $processTypeId;
        $decodedAppId          = Encryption::decodeId( $appId );
        $process_type_id       = $this->process_type_id;

        $data['process_type_id'] = $process_type_id;


        $data['appInfo'] = ProcessList::leftJoin( 'scs_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                          //'apps.per_office_address as op_office_address',
                                          'applicant_district.area_nm as applicant_district_en',
                                          'applicant_thana.area_nm as applicant_thana_en',
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
                                      ] );


        $data['appShareholderInfo'] = Shareholder::where( [ 'app_id' => $decodedAppId, 'process_type_id' => $this->process_type_id ] )->get();
        $data['appDynamicDocInfo']  = ApplicationDocuments::where( 'process_type_id', $process_type_id )->where( 'ref_id', $decodedAppId )->whereNotNull('uploaded_path')->get();

        $data['contact_person']        = ContactPerson::where( [
            'app_id'          => $decodedAppId,
            'process_type_id' => $this->process_type_id
        ] )->get();


        foreach ( $data['contact_person'] as $key => $item ) {

            $data['contact_person'][ $key ]['contact_district_name'] = DB::table( 'area_info' )->where( 'area_id', $item->district )->value( 'area_nm' );

            $data['contact_person'][ $key ]['contact_upazila_name'] = DB::table( 'area_info' )->where( 'area_id', $item->upazila )->value( 'area_nm' );
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



        $public_html = (string) view( 'REUSELicenseIssue::IIG.Issue.masterView', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }
public function editForm( $decoded_process_type_id, $applicationId ): JsonResponse {
    $data['process_type_id'] = $decoded_process_type_id;
    $data['vat_percentage']  = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
    $applicationId           = Encryption::decodeId( $applicationId );
    $process_type_id         = $decoded_process_type_id;


    $data['divisions'] = [ '' => 'Select' ] + Area::where( 'area_type', 1 )->orderBy( 'area_nm', 'asc' )->pluck( 'area_nm', 'area_id' )->toArray();
    $data['districts'] = [ '' => 'Select' ] + Area::where( 'area_type', 2 )->orderBy( 'area_nm', 'ASC' )->pluck( 'area_nm', 'area_id' )->toArray();

    $data['appInfo'] = ProcessList::leftJoin( 'scs_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                                      'apps.*',
                                     // 'apps.company_name as org_nm',
                                     // 'apps.company_type as org_type',
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
    $contact_data = ContactPerson::where( [
        'app_id'          => $applicationId,
        'process_type_id' => $process_type_id
    ] )->get();

    foreach ($contact_data as $index => $value) {
        if (public_path($value->image) && !empty($value->image)) {
            $value->image_real_path = $value->image;
            $value->image           = CommonFunction::imagePathToBase64(public_path($value->image));
        }
    }

    $data['contact_person'] = $contact_data;

    $data['nationality'] = ['' => 'Select'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
            ->orderby('nationality')->pluck('nationality', 'id')->toArray();

    $data['pay_order_info']= DB::table('pay_order_info')
        ->where('pay_order_info_id', $data['appInfo']->payment_id)
        ->get();

   // $data['payment_info'] = SonaliPayment::find($data['appInfo']->payment_id);

    $public_html = (string) view( 'REUSELicenseIssue::SCS.Issue.masterEdit', $data );

    return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
}
}
