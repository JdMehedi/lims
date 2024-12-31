<?php

namespace App\Libraries;

use App\ActionInformation;
use App\Models\AuditLog;
use App\Models\ConfigSetting;
use App\Models\DNothiHistoryLogs;
use App\Models\ENothiSubmissionInfo;
use App\Models\User;
use App\Modules\CompanyAssociation\Models\CompanyAccessPrivileges;
use App\Modules\CompanyAssociation\Models\CompanyAssociation;
use App\Modules\CompanyAssociation\Models\CompanyAssociationMaster;
use App\Modules\CompanyAssociation\Models\InactiveCompanyUser;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\CompanyProfile\Models\CompanyType;
use App\Modules\CompanyProfile\Models\RegistrationOffice;
use App\Modules\Documents\Models\ApplicationDocuments;
use App\Modules\Emonitoring\Models\EmonitoringApps;
use App\Modules\Emonitoring\Models\EmonitoringReqLog;
use App\Modules\Files\Controllers\FilesController;
use App\Modules\IndustryNew\Controllers\IndustryNewController;
use App\Modules\IndustryNew\Models\IndustryNew;
use App\Modules\ProcessPath\Models\ProcessFavoriteList;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessPath;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\ReportsV2\Models\ReportRecentActivates;
use App\Modules\REUSELicenseIssue\Models\BPO\Amendment\Amendment;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\BPO\issue\ProposalArea;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;
use App\Modules\REUSELicenseIssue\Models\BPO\surrender\CallCenterSurrender;
use App\Modules\REUSELicenseIssue\Models\BWA\amendment\BWALicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\BWA\issue\BWALicenseIssue;
use App\Modules\REUSELicenseIssue\Models\BWA\renew\BWALicenseRenew;
use App\Modules\REUSELicenseIssue\Models\BWA\surrender\BWALicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ContactPerson;
use App\Modules\REUSELicenseIssue\Models\ICX\amendment\ICXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ICX\renew\ICXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ICX\surrender\ICXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IGW\amendment\IGWLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IGW\issue\IGWLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IGW\renew\IGWLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IGW\surrender\IGWLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IIG\amendment\IIGLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IIG\issue\IIGLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IIG\renew\IIGLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IIG\surrender\IIGLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPAmendmentConnectedISPInfo;
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssueConnectedISPInfo;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenewConnectedISPInfo;
use App\Modules\REUSELicenseIssue\Models\IPTSP\surrender\IPTSPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\IPTSP\surrender\IPTSPLicenseSurrenderConnectedISPInfo;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendmentEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendmentTariffChart;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseTariffChart;
use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenewTariffChart;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrenderEquipmentList;
use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrenderTariffChart;
use App\Modules\REUSELicenseIssue\Models\ITC\amendment\ITCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ITC\issue\ITCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ITC\renew\ITCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\ITC\surrender\ITCLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNO\amendment\MNOLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\MNO\issue\MNOLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\MNO\renew\MNOLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNO\surrender\MNOLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\MNP\amendment\MNPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\MNP\issue\MNPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\MNP\renew\MNPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\MNP\surrender\MNPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NIX\amendment\NIXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NIX\issue\NIXLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NIX\renew\NIXLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NIX\surrender\NIXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NTTN\amendment\NTTNLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\renew\NTTNLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NTTN\surrender\NTTNLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SCS\amendment\SCSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SCS\renew\SCSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SCS\surrender\SCSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\Shareholder;
use App\Modules\REUSELicenseIssue\Models\SS\amendment\SSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SS\issue\SSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SS\renew\SSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SS\surrender\SSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TC\amendment\TCLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TC\issue\TCLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TC\renew\TCLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TC\surrender\TCLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\TVAS\amendment\TVASLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\TVAS\issue\TVASLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\TVAS\renew\TVASLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\TVAS\surrender\TVASLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VSAT\amendment\VSATLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATHubOperatorInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseListOfEquipment;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATServiceProviderInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATTechnicalSpecification;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATHubOperatorRenewInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATLicenseListOfEquipmentRenew;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATServiceProviderRenewInfo;
use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATTechnicalSpecificationRenew;
use App\Modules\REUSELicenseIssue\Models\VSAT\surrender\VSATLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VTS\amendment\VTSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\renew\VTSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VTS\surrender\VTSLicenseSurrender;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\EmailQueue;
use App\Modules\Settings\Models\EmailTemplates;
use App\Modules\Settings\Models\IndustrialCityList;
use App\Modules\Settings\Models\Logo;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\RegulatoryAgency;
use App\Modules\SonaliPayment\Http\Controllers\PaymentPanelController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\Settings\Models\Area;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use App\Modules\VisaAssistance\Models\SponsorsDirectors;
use App\Modules\Web\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Libraries\ImageProcessing;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use PhpParser\Node\Stmt\Switch_;
use Psy\Util\Str;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;


class CommonFunction {

    /*************************************
     * Starting OSS Common functions
     *************************************/

    /**
     * @param Carbon|string $updated_at
     * @param string $updated_by
     *
     * @return string
     * @internal param $Users->id /string $updated_by
     */

    public static function showErrorPublic( $param, $msg = 'Sorry! Something went wrong! ' ) {
        $j = strpos( $param, '(SQL:' );
        if ( $j > 15 ) {
            $param = substr( $param, 8, $j - 9 );
        } else {
            //
        }

        return $msg . $param;
    }

    public static function isMobileLogin() {
        $tokenId = request()->get('token');
        $userId = request()->get('user_id');
        if (Session::has('url_token_id') && Session::has('url_user_id')) {
            $tokenId = Session::has('url_token_id');
            $userId = Session::has('url_user_id');
        }
        if(!empty($tokenId) && !empty($userId)) {
            $deUserId = Encryption::decode($userId);
            $uuid = $tokenId;
            $user = Users::query()
                ->where('mobile_auth_token', $uuid)
                ->where('id', $deUserId)
                ->first();

            if($user) {
                return $deUserId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function updateScriptPara( $sql, $data ) {
        $start = strpos( $sql, '{$' );
        while ( $start > 0 ) {
            $end = strpos( $sql, '}', $start );
            if ( $end > 0 ) {
                $filed = substr( $sql, $start + 2, $end - $start - 2 );
                $sql   = substr( $sql, 0, $start ) . $data[ $filed ] . substr( $sql, $end + 1 );
            }
            $start = strpos( $sql, '{$' );
        }

        return $sql;
    }


    public static function updatedOn( $updated_at = '' ) {
        $update_was = '';
        if ( $updated_at && $updated_at > '0' ) {
            $update_was = Carbon::createFromFormat( 'Y-m-d H:i:s', $updated_at )->diffForHumans();
        }

        return $update_was;
    }

    public static function getUserId() {

        if ( Auth::user() ) {
            return Auth::user()->id;
        } else {
            return 0;
        }
    }

    public static function getUserType() {

        if ( Auth::user() ) {
            return Auth::user()->user_type;
        } else {
            // return 1;
            dd( 'Invalid User Type' );
        }
    }

    public static function GlobalSettings() {
        $logoInfo = Logo::orderBy( 'id', 'DESC' )->first( [
            'logo',
            'title',
            'manage_by',
            'help_link'
        ] );

        Session::put( 'logo', 'assets/images/OSS_Framework_Logo.webp' );

        if ( $logoInfo != "" ) {
            if ( file_exists( url( $logoInfo->logo ) ) ) {
                Session::put( 'logo', $logoInfo->logo );
            }
            Session::put( 'title', $logoInfo->title );
            Session::put( 'manage_by', $logoInfo->manage_by );
            Session::put( 'help_link', $logoInfo->help_link );
        }
    }


    public static function getUserCompanyWithZero() {
        if ( Auth::user() ) {
            return Auth::user()->working_company_id;
        } else {
            return 0;
        }
    }

    public static function getUserAllCompanyIdsWithZero() {
        if ( Auth::user() ) {
            $companyIds = CompanyAssociationMaster::where( 'request_from_user_id', Auth::user()->id )
                                                  ->where( 'is_active', 1 )
                                                  ->where( 'status', 25 )
                                                  ->where( 'is_archive', 0 )
                                                  ->pluck( 'company_id' )
                                                  ->toArray();

            //            dd($companyIds, Auth::user()->id);
            return $companyIds;
        } else {
            return [ - 1 ];
        }
    }

    public static function getUserCompanyByUserId( $userId ) {
        $user = Users::find( $userId );
        if ( $user ) {
            return $user->working_company_id;
        } else {
            return 0;
        }
    }


    public static function redirectToLogin() {
        echo "<script>location.replace('users/login');</script>";
    }

    public static function formateDate( $date = '' ) {
        return date( 'd.m.Y', strtotime( $date ) );
    }

    public static function convertUTF8( $string ) {
        //        $string = 'u0986u09a8u09c7u09beu09dfu09beu09b0 u09b9u09c7u09beu09b8u09beu0987u09a8';
        $string = preg_replace( '/u([0-9a-fA-F]+)/', '&#x$1;', $string );

        return html_entity_decode( $string, ENT_COMPAT, 'UTF-8' );
    }

    /* This function determines if an user is an admin or sub-admin
     * Based On User Type
     *  */
    public static function isAdmin() {
        $user_type = Auth::user()->user_type;
        /*
         * 1x101 for System Admin
         * 5x501 for Agency Admin
         */
        if ( $user_type == '1x101' ) {
            return true;
        } else {
            return false;
        }
    }

    public static function changeDateFormat( $datePicker, $mysql = false, $with_time = false ) {
        try {
            if ( $mysql ) {
                if ( $with_time ) {
                    return Carbon::createFromFormat( 'Y-m-d H:i:s', $datePicker )->format( 'd-M-Y' );
                } else {
                    return Carbon::createFromFormat( 'd-M-Y', $datePicker )->format( 'Y-m-d' );
                }
            } else {
                return Carbon::createFromFormat( 'Y-m-d', $datePicker )->format( 'd-M-Y' );
            }
        } catch ( \Exception $e ) {
            if ( config( 'app.debug' ) ) {
                Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            } else {
                return $datePicker; //'Some errors occurred (code:793)';
            }
        }
    }

    // Get age from birth date
    public static function age( $birthDate ) {
        $year = '';
        if ( $birthDate ) {
            $year = Carbon::createFromFormat( 'Y-m-d', $birthDate )->diff( Carbon::now() )->format( '%y years, %m months and %d days' );
        }

        return $year;
    }

    public static function getUserDeskIds() {

        if ( Auth::user() ) {
            $deskIds = Auth::user()->desk_id;

            return explode( ',', $deskIds );
        } else {
            // return 1;
            return [];
        }
    }

    public static function getDeskName( $desk_id ) {
        if ( Auth::user() ) {
            return UserDesk::where( 'id', $desk_id )->value( 'desk_name' );
        } else {
            return '';
        }
    }

    public static function getUserOfficeIds() {

        if ( Auth::user() ) {
            $officeIds     = Auth::user()->office_ids;
            $userOfficeIds = explode( ',', $officeIds );

            return $userOfficeIds;
        } else {
            // return 1;
            dd( 'Invalid User status' );
        }
    }

    public static function getDelegatedUserDeskOfficeIds() {

        $userId                 = CommonFunction::getUserId();
        $delegated_usersArr     = Users::where( 'delegate_to_user_id', $userId )
                                       ->get( [
                                           'id as user_id',
                                           'desk_id',
                                           'office_ids'
                                       ] );
        $delegatedDeskOfficeIds = array();
        foreach ( $delegated_usersArr as $value ) {

            $userDesk                                  = explode( ',', $value->desk_id );
            $userOffice                                = explode( ',', $value->office_ids );
            $tempArr                                   = array();
            $tempArr['user_id']                        = $value->user_id;
            $tempArr['desk_ids']                       = $userDesk;
            $tempArr['office_ids']                     = $userOffice;
            $delegatedDeskOfficeIds[ $value->user_id ] = $tempArr;
        }

        return $delegatedDeskOfficeIds;
    }

    public static function getSelfAndDelegatedUserDeskOfficeIds() {

        $userId             = CommonFunction::getUserId();
        $delegated_usersArr = Users::where( 'delegate_to_user_id', $userId )
                                   ->orWhere( 'id', $userId )
                                   ->get( [
                                       'id as user_id',
                                       'desk_id',
                                       'office_ids'
                                   ] );
        //        dd($delegated_usersArr);
        $delegatedDeskOfficeIds = array();
        foreach ( $delegated_usersArr as $value ) {

            $userDesk                                  = explode( ',', $value->desk_id );
            $userOffice                                = explode( ',', $value->office_ids );
            $tempArr                                   = array();
            $tempArr['user_id']                        = $value->user_id;
            $tempArr['desk_ids']                       = $userDesk;
            $tempArr['office_ids']                     = $userOffice;
            $delegatedDeskOfficeIds[ $value->user_id ] = $tempArr;
        }

        return $delegatedDeskOfficeIds;
    }

    public static function hasDeskOfficeWisePermission( $desk_id, $office_id ) {
        $getSelfAndDelegatedUserDeskOfficeIds = CommonFunction::getSelfAndDelegatedUserDeskOfficeIds();
        foreach ( $getSelfAndDelegatedUserDeskOfficeIds as $selfDeskId => $value ) {
            if ( in_array( $desk_id, $value['desk_ids'] ) && in_array( $office_id, $value['office_ids'] ) ) {
                return true;
            }
        }

        return false;
    }

    public static function convert2English( $ban_number ) {
        $eng = [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ];
        $ban = [ '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' ];

        return str_replace( $ban, $eng, $ban_number );
    }


    public static function getNotice( $flag = 0 ) {
        if ( $flag == 1 ) {
            $list = DB::select( DB::raw( "SELECT date_format(updated_at,'%d %M, %Y') `Date`,heading,details,importance,id, case when importance='Top' then 1 else 0 end Priority FROM notice where status='public' or status='private' order by Priority desc, updated_at desc LIMIT 10" ) );
        } else {
            $list = DB::select( DB::raw( "SELECT date_format(updated_at,'%d %M, %Y') `Date`,heading,details,importance,id, case when importance='Top' then 1 else 0 end Priority FROM notice where status='public' order by Priority desc, updated_at desc LIMIT 10" ) );
        }

        return $list;
    }


    public static function getCompanyNameById( $id ) {
        if ( $id ) {
            $name = CompanyInfo::where( 'id', $id )->value( 'org_nm' );

            return $name;
        } else {
            return 'N/A';
        }
    }

    public static function getBSCICOfficeName( $id ) {
        if ( $id ) {
            return IndustrialCityList::where( 'id', $id )->value( 'name_en' );
        } else {
            return 'N/A';
        }
    }

    public static function getDistrictFirstTwoChar( $district_id ) {
        $districtName = Area::where( 'area_type', 2 )->where( 'area_id', $district_id )->value( 'area_nm' );

        return strtoupper( substr( $districtName, 0, 2 ) );
    }

    public static function getCompanyUserType() {
        $type = CompanyAssociationMaster::where( 'company_id', Auth::user()->working_company_id )
                                        ->where( 'request_from_user_id', Auth::user()->id )
                                        ->where( 'is_active', 1 )
                                        ->where( 'status', 25 )
                                        ->where( 'is_archive', 0 )
                                        ->value( 'type' );

        return $type;
    }

    /**
     * Count all application those are awaiting for processing by desk user.
     * Conditions:
     * 1. The application must be within the desk of the desk officer
     * 2. The application must be within the office of the desk officer
     * 3. The desk officer of the application must be the current desk officer or unassigned
     * 4. The application's desk ID cannot be zero
     * 5. The application Status ID cannot be contained in -1, 5 (Draft, Shortfall)
     *
     * @return mixed
     */
    public static function pendingApplication() {
        $userDeskIds   = CommonFunction::getUserDeskIds();
        $userOfficeIds = CommonFunction::getUserOfficeIds();
        $user_id       = CommonFunction::getUserId();

        return ProcessList::whereIn( 'desk_id', $userDeskIds )
                          ->whereIn( 'office_id', $userOfficeIds )
                          ->where( function ( $query2 ) use ( $user_id ) {
                              $query2->where( 'user_id', $user_id )
                                     ->orWhere( 'user_id', 0 );
                          } )
                          ->where( 'desk_id', '!=', 0 )
                          ->whereNotIn( 'status_id', [ - 1, 5 ] )
                          ->count();
    }

    /**
     * @param string $caption
     * @param array $appInfo
     * @param array $receiverInfo
     *
     * @return mixed
     * @throws \Throwable
     */
    public static function sendEmailSMS( $caption = '', $appInfo = [], $receiverInfo = [], $pdfLink = '' ) {

        try {
            $template = EmailTemplates::where( 'caption', $caption )->first();

            //            if (isset($appInfo['process_type_id']) && in_array($appInfo['process_type_id'], [101, 102, 103, 104, 105, 106])) { //Eliminating service type from email content for these service
            //                $template->email_content = str_replace('Service Type: {$serviceSubName}<br/>', '', $template->email_content);
            //            }

            if ( ! in_array( $caption, [
                'TWO_STEP_VERIFICATION',
                'ACCOUNT_ACTIVATION',
                'CONFIRM_ACCOUNT',
                'APPROVE_USER',
                'REJECT_USER',
                'PASSWORD_RESET_REQUEST',
                'APP_APPROVE_PIN_NUMBER',
                'ASK_FOR_ADVICE_FROM_ADVISOR',
                'USER_VERIFICATION_EMAIL',
                'NEW_PASSWORD',
                'PASSWORD_RESET_REQUEST',
                'DEVICE_DETECTION',
                'ONE_TIME_PASSWORD'
            ] ) ) {

                $template->email_content = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->email_content );
                $template->email_content = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->email_content );
                //                $template->email_content = str_replace('{$serviceSupperName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace( '{$serviceSubName}', $appInfo['process_type_name'], $template->email_content );
                $template->email_content = str_replace( '{$remarks}', $appInfo['remarks'], $template->email_content );
                $template->sms_content   = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->sms_content );
                //                $template->sms_content = str_replace('{$serviceSupperName}', $appInfo['process_supper_name'], $template->sms_content);
                $template->sms_content = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content );
            } elseif ( $caption == 'APP_ISSUED_LETTER' ) {
                $template->email_content = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->email_content );
                $template->email_content = str_replace( '{$serviceSubName}', $appInfo['process_type_name'], $template->email_content );
                $template->email_content = str_replace( '{$attachment}', $appInfo['attachment_certificate_name'], $template->email_content );
                $template->sms_content   = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->sms_content );
                $template->sms_content   = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content );
            } elseif ( $caption == 'CONFIRM_ACCOUNT' ) {
                $template->email_content = str_replace( '{$verificationLink}', $appInfo['verification_link'], $template->email_content );
            } elseif ( $caption == 'DEVICE_DETECTION' ) {
                $template->email_content = str_replace( '{$device}', $appInfo['device'], $template->email_content );
                $template->email_subject = str_replace( '{$device}', $appInfo['device'], $template->email_subject );
            } elseif ( $caption == 'ONE_TIME_PASSWORD' ) {
                $template->email_content = str_replace( '{$code}', $appInfo['one_time_password'], $template->email_content );
            } else if ( $caption == 'TWO_STEP_VERIFICATION' ) {
                $template->email_content = str_replace( '{$code}', $appInfo['code'], $template->email_content );
                $template->sms_content   = str_replace( '{$code}', $appInfo['code'], $template->sms_content );


                if ( $appInfo['verification_type'] == 'mobile_no' ) {
                    $template->email_active_status = 0;
                    $template->sms_active_status   = 1;
                } else {
                    $template->email_active_status = 1;
                    $template->sms_active_status   = 0;
                }
            } elseif ( $caption == 'REJECT_USER' ) {
                $template->email_content = str_replace( '{$rejectReason}', $appInfo['reject_reason'], $template->email_content );
            } elseif ( $caption == 'PASSWORD_RESET_REQUEST' ) {
                $template->email_content = str_replace( '{$reset_password_link}', $appInfo['reset_password_link'], $template->email_content );
            }

            if ( $caption == 'PROCEED_TO_MEETING' ) {
                $template->email_content = str_replace( '{$meetingDate}', $appInfo['meeting_date'], $template->email_content );
                $template->email_content = str_replace( '{$meetingTime}', $appInfo['meeting_time'], $template->email_content );
            } elseif ( in_array( $caption, [ 'APP_APPROVE_AND_PAYMENT', 'MC_APP_APPROVE_AND_PAYMENT' ] ) ) {
                $template->email_content = str_replace( '{$govtFees}', $appInfo['govt_fees'], $template->email_content );
            } elseif ( $caption == 'APP_GOV_PAYMENT_SUBMIT' ) {
                $template->email_content = str_replace( '{$govtFees}', $appInfo['govt_fees'], $template->email_content );
                $template->email_content = str_replace( '{$govtFeesOnlyAmount}', $appInfo['govt_fees_amount'], $template->email_content );
                $template->email_content = str_replace( '{$paymentDate}', $appInfo['payment_date'], $template->email_content );
                $template->sms_content   = str_replace( '{$govtFeesOnlyAmount}', $appInfo['govt_fees_amount'], $template->sms_content );
            } elseif ( $caption == 'IMMIGRATION' ) {
                $template->email_content = str_replace( '{$name}', $appInfo['name'], $template->email_content );
                $template->email_content = str_replace( '{$nationality}', $appInfo['nationality'], $template->email_content );
                $template->email_content = str_replace( '{$passportNumber}', $appInfo['passport_number'], $template->email_content );
                $template->email_content = str_replace( '{$designation}', $appInfo['designation'], $template->email_content );
                $template->email_content = str_replace( '{$visaType}', $appInfo['visa_type'], $template->email_content );
                $template->email_content = str_replace( '{$airportName}', $appInfo['airport_name'], $template->email_content );
                $template->email_content = str_replace( '{$airportAddress}', $appInfo['airport_address'], $template->email_content );
            } elseif ( $caption == 'EMBASSY_HIGH_COMMISSION' ) {
                $template->email_content = str_replace( '{$name}', $appInfo['name'], $template->email_content );
                $template->email_content = str_replace( '{$nationality}', $appInfo['nationality'], $template->email_content );
                $template->email_content = str_replace( '{$passportNumber}', $appInfo['passport_number'], $template->email_content );
                $template->email_content = str_replace( '{$designation}', $appInfo['designation'], $template->email_content );
                $template->email_content = str_replace( '{$visaType}', $appInfo['visa_type'], $template->email_content );
                $template->email_content = str_replace( '{$highCommissionName}', $appInfo['high_commission_name'], $template->email_content );
                $template->email_content = str_replace( '{$highCommissionAddress}', $appInfo['high_commission_address'], $template->email_content );
            } elseif ( $caption == 'WP_ISSUED_LETTER_STAKEHOLDER' ) {
                $template->email_content = str_replace( '{$name}', $appInfo['name'], $template->email_content );
                $template->email_content = str_replace( '{$designation}', $appInfo['designation'], $template->email_content );
                $template->email_content = str_replace( '{$nationality}', $appInfo['nationality'], $template->email_content );
                $template->email_content = str_replace( '{$passportNumber}', $appInfo['passport_number'], $template->email_content );
            } elseif ( $caption == 'ASK_FOR_ADVICE_FROM_ADVISOR' ) {
                $business_type = CompanyType::where( 'id', $appInfo['business_type'] )->first( [ 'name_bn' ] );
                $country_name  = Countries::where( 'id', $appInfo['country_id'] )->first( [ 'nicename' ] );

                $template->email_content = str_replace( '{$name}', $appInfo['name'], $template->email_content );
                $template->email_content = str_replace( '{$organization_name}', $appInfo['organization_name'], $template->email_content );
                $template->email_content = str_replace( '{$business_type}', $business_type['name_bn'], $template->email_content );
                $template->email_content = str_replace( '{$country}', $country_name['nicename'], $template->email_content );
                $template->email_content = str_replace( '{$user_mobile}', $appInfo['mobile_no'], $template->email_content );
                $template->email_content = str_replace( '{$email_address}', $appInfo['email'], $template->email_content );
                $template->email_content = str_replace( '{$questions}', $appInfo['question'], $template->email_content );
            } elseif ( $caption == 'OP_ISSUED_LETTER_STAKEHOLDER' ) {
                $template->email_content = str_replace( '{$organizationName}', $appInfo['organization_name'], $template->email_content );
            }
            //            elseif ($caption == 'REJECT_USER') {
            //                $template->email_content = str_replace('{$rejectReason}', $appInfo['reject_reason'], $template->email_content);
            //            }
            elseif ( $caption == 'VRN_ISSUED_LETTER_STAKEHOLDER' ) {
                $template->email_content = str_replace( '{$name}', $appInfo['name'], $template->email_content );
                $template->email_content = str_replace( '{$nationality}', $appInfo['nationality'], $template->email_content );
                $template->email_content = str_replace( '{$passportNumber}', $appInfo['passport_number'], $template->email_content );
                $template->email_content = str_replace( '{$designation}', $appInfo['designation'], $template->email_content );
                $template->email_content = str_replace( '{$visaType}', $appInfo['visa_type'], $template->email_content );
            } elseif ( $caption == 'APP_APPROVE_PIN_NUMBER' ) {
                $template->email_content = str_replace( '{$pinNumber}', $appInfo['code'], $template->email_content );
                $template->sms_content   = str_replace( '{$pinNumber}', $appInfo['code'], $template->sms_content );
            } elseif ( $caption == 'APP_APPROVE' ) {
                $template->email_content = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->email_content );
                $template->email_content = str_replace( '{$companyName}', $appInfo['org_nm'], $template->email_content );
                $template->email_content = str_replace( '{$licenseNo}', $appInfo['license_no'], $template->email_content );
                $template->email_content = str_replace( '{$pdfLink}', $appInfo['pdf_link'], $template->email_content );
                if(empty($appInfo['pdfLink2'])){
                    $template->email_content = str_replace( 'Issue Letter Link:', '', $template->email_content );
                }
                $template->email_content = str_replace( '{$pdfLink2}', $appInfo['pdfLink2'], $template->email_content );
                $template->sms_content   = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->sms_content );
                $template->sms_content   = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content );
            } elseif ( $caption == 'APP_SHORTFALL' ) {
                    $template->email_content = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->email_content );
                    $template->email_content = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->email_content );
                    $template->email_content = str_replace( '{$remarks}', $appInfo['remarks'], $template->email_content );
                    $template->email_content = str_replace( '{$pdfLinks}', $pdfLink, $template->email_content );
                    $template->sms_content   = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->sms_content );
                    $template->sms_content   = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content );
            }  elseif ( $caption == 'APP_CANCEL' ) {
                $template->email_content = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->email_content );
                $template->email_content = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->email_content );
                $template->email_content = str_replace( '{$pdfLinks}', $pdfLink, $template->email_content );

                $template->sms_content   = str_replace( '{$serviceName}', $appInfo['process_type_name'], $template->sms_content );
                $template->sms_content   = str_replace( '{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content );
        } elseif ( $caption == 'APP_SUBMIT' ) {
                $template->email_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            } elseif ( $caption == 'APP_RESUBMIT' ) {
                $template->email_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            }  elseif ( $caption == 'APP_REJECT' ) {
                $template->email_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            } elseif ( $caption == 'APP_PAYMENT_REQUEST' ) {
                $template->email_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace( '{$pdfLinks}', $pdfLink, $template->email_content );
//                $template->email_content = str_replace('{$amount}', $appInfo['total_amount'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            }  elseif ( $caption == 'APP_PAYMENT_REQUEST_FOR_ANNUAl_OR_BG_FEE' ) {
                $template->email_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace( '{$pdfLinks}', $pdfLink, $template->email_content );
//                $template->email_content = str_replace('{$amount}', $appInfo['total_amount'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            }elseif ( $caption == 'APPROVED_MAIL_FOR_DESK_OFFICER' ) {
                $template->email_content = str_replace('{$trackingNo}', $appInfo['tracking_no'], $template->email_content);
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace('{$companyName}', $appInfo['org_nm'], $template->email_content);
                $template->email_content = str_replace('{$licenseNo}', $appInfo['license_no'], $template->email_content);
                $template->sms_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->sms_content);
                $template->sms_content = str_replace('{$trackingNumber}', $appInfo['tracking_no'], $template->sms_content);
            } elseif ( $caption == 'APP_LICENSE_EXPIRATION_NOTIFICATION' ) {
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace('{$companyName}', $appInfo['org_nm'], $template->email_content);
                $template->email_content = str_replace('{$licenseNo}', $appInfo['license_no'], $template->email_content);
                $template->email_content = str_replace('{$issueDate}', $appInfo['issue_date'], $template->email_content);
                $template->email_content = str_replace('{$expiryDate}', $appInfo['expiry_date'], $template->email_content);
            } elseif ( $caption == 'DESK_PROCESS_MAIL_TO_DESK_OFFICER') {
                $template->email_content = str_replace('{$trackingNo}', $appInfo['tracking_no'], $template->email_content);
//                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
//                $template->email_content = str_replace('{$companyName}', $appInfo['org_nm'], $template->email_content);
//                $template->email_content = str_replace('{$licenseNo}', $appInfo['license_no'], $template->email_content);
//                $template->sms_content = str_replace('{$licenseNumber}', $appInfo['process_type_name'], $template->sms_content);
//                $template->sms_content = str_replace('{$expiryDays}', $appInfo['tracking_no'], $template->sms_content);
            } elseif ( $caption == 'MAIL_TO_OFFICERS') {
                $template->email_content = str_replace('{$trackingNo}', $appInfo['tracking_no'], $template->email_content);
//                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
//                $template->email_content = str_replace('{$companyName}', $appInfo['org_nm'], $template->email_content);
//                $template->email_content = str_replace('{$licenseNo}', $appInfo['license_no'], $template->email_content);
//                $template->sms_content = str_replace('{$licenseNumber}', $appInfo['process_type_name'], $template->sms_content);
//                $template->sms_content = str_replace('{$expiryDays}', $appInfo['tracking_no'], $template->sms_content);
            } elseif ( $caption == 'APP_BG_EXPIRATION_NOTIFICATION') {
                $template->email_content = str_replace('{$serviceName}', $appInfo['process_type_name'], $template->email_content);
                $template->email_content = str_replace('{$companyName}', $appInfo['org_nm'], $template->email_content);
                $template->email_content = str_replace('{$licenseNo}', $appInfo['license_no'], $template->email_content);
                $template->email_content = str_replace('{$date}', $appInfo['issue_date'], $template->email_content);
            }
            $smsBody = $template->sms_content;
            $header  = $template->email_subject;
            $param   = $template->email_content;
            $caption = $template->caption;

            $email_content            = view( "Users::message", compact( 'header', 'param' ) )->render();
            $ccEmailFromConfiguration = CommonFunction::ccEmail();
            $NotificationWebService   = new NotificationWebService();

            if ( $template->email_active_status == 1 || $template->sms_active_status == 1 ) {  // checking whether template status is on/off for email and sms
                $emailQueueData = [];
//                $emptyArr = [];
                foreach ( $receiverInfo as $itemKey => $receiver ) {
                    $emailQueue                    = [];
                    $emailQueue['process_type_id'] = isset( $appInfo['process_type_id'] ) ? $appInfo['process_type_id'] : 0;
                    $emailQueue['app_id']          = isset( $appInfo['app_id'] ) ? $appInfo['app_id'] : 0;
                    $emailQueue['status_id']       = isset( $appInfo['status_id'] ) ? $appInfo['status_id'] : 0;
                    $emailQueue['caption']         = $caption;
                    $emailQueue['email_content']   = $email_content;
                    if ( $template->email_active_status == 1 ) {
                        $emailQueue['email_to'] = $receiver['user_email'];
                        $emailQueue['email_cc'] = ! empty( $template->email_cc ) ? $template->email_cc : $ccEmailFromConfiguration;
                    }
                    $emailQueue['email_subject'] = $header;
                    if ( ! empty( trim( $receiver['user_mobile'] ) ) && $template->sms_active_status == 1 ) {
                        $emailQueue['sms_content'] = $smsBody;
                        $emailQueue['sms_to']      = substr( trim( $receiver['user_mobile'] ), - 11 );

                        // Instant SMS Sending
//                        $sms_sending_response = $NotificationWebService->sendSms($receiver['user_mobile'], $smsBody);
//                        $emailQueue['sms_response'] = $sms_sending_response['msg'];
//                        if ($sms_sending_response['status'] === 1) {
//                            $emailQueue['sms_status'] = 1;
//                            $emailQueue['sms_response_id'] = $sms_sending_response['message_id'];
//                        }
                        // End of Instant SMS Sending
                    }
                    $emailQueue['attachment']                  = isset( $appInfo['attachment'] ) ? $appInfo['attachment'] : '';
                    $emailQueue['attachment_certificate_name'] = isset( $appInfo['attachment_certificate_name'] ) ? $appInfo['attachment_certificate_name'] : '';
                    $emailQueue['created_at']                  = date( 'Y-m-d H:i:s' );
                    $emailQueue['updated_at']                  = date( 'Y-m-d H:i:s' );

                    // Instant Email sending
                    if ( empty( $emailQueue['attachment_certificate_name'] ) && $template->email_active_status == 1 ) {
//                        $emptyArr[$itemKey]['email_content'] = [
//                            'header_text' => config( 'app.project_name' ),
//                            'recipient'   => $receiver['user_email'],
//                            'subject'     => $header,
//                            'bodyText'    => '',
//                            'bodyHtml'    => $email_content,
//                            'email_cc'    => $emailQueue['email_cc']
//                        ];
//                        $emptyArr[$itemKey]['email_queue'] = $emailQueue;
                        $email_sending_response       = $NotificationWebService->sendEmail( [
                            'header_text' => config( 'app.project_name' ),
                            'recipient'   => $receiver['user_email'],
                            'subject'     => $header,
                            'bodyText'    => '',
                            'bodyHtml'    => $email_content,
                            'email_cc'    => $emailQueue['email_cc']
                        ] );
                        $emailQueue['email_response'] = $email_sending_response['msg'];
                        if ( $email_sending_response['status'] === 1 ) {
                            $emailQueue['email_status']      = 1;
                            $emailQueue['email_response_id'] = $email_sending_response['message_id'];
                        }else{
                            $emailQueue['email_status']      = 0;
                            $emailQueue['email_response_id'] = "";
                        }
                    }
                    // End of Instant Email sending

                    $emailQueueData[] = $emailQueue;
                }
//                \App\Jobs\SendQueueEmail::dispatch($emptyArr);

                EmailQueue::insert( $emailQueueData );
                $lastId = DB::getPdo()->lastInsertId();

                return $lastId;
//                return 1;
            }

            return true;
        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash( 'error', CommonFunction::showErrorPublic( $e->getMessage() ) . ' [CM-1005]' );

            return Redirect::back()->withInput();
        }
    }

    public static function requestPinNumber( $app_id, $process_type_id ) {
        $user_id = CommonFunction::getUserId();
        $users   = Users::where( 'id', $user_id )->first();
        $code    = rand( 1000, 9999 );
        //        $token = $code .$user_id;
        //        $encrypted_pin = Encryption::encode($token);
        $data = [
            'code'            => $code,
            'app_id'          => $app_id,
            'process_type_id' => $process_type_id
        ];

        Users::where( 'user_email', $users->user_email )->update( [ 'pin_number' => $code ] );

        $receiverInfo[] = [
            'user_email'  => $users->user_email,
            'user_mobile' => $users->user_mobile
        ];

        CommonFunction::sendEmailSMS( 'APP_APPROVE_PIN_NUMBER', $data, $receiverInfo );

        return true;
    }

    public static function checkFavoriteItem( $process_id ) {
        $result = ProcessFavoriteList::where( 'process_id', $process_id )
                                     ->where( 'user_id', CommonFunction::getUserId() )
                                     ->count();

        return $result;
    }

    public static function ccEmail() {
        return Configuration::where( 'caption', 'CC_EMAIL' )->value( 'value' );
    }

    public static function getUserFullName() {
        if ( Auth::user() ) {
            return Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;
        } else {
            return 'Invalid Login Id';
        }
    }


    public static function convert_number_to_words( $number ) {
        $common      = new CommonFunction;
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if ( ! is_numeric( $number ) ) {
            return false;
        }

        if ( ( $number >= 0 && (int) $number < 0 ) || (int) $number < 0 - PHP_INT_MAX ) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );

            return false;
        }

        if ( $number < 0 ) {
            return $negative . $common->convert_number_to_words( abs( $number ) );
        }

        $string = $fraction = null;

        if ( strpos( $number, '.' ) !== false ) {
            list( $number, $fraction ) = explode( '.', $number );
        }

        switch ( true ) {
            case $number < 21:
                $string = $dictionary[ $number ];
                break;
            case $number < 100:
                $tens   = ( (int) ( $number / 10 ) ) * 10;
                $units  = $number % 10;
                $string = $dictionary[ $tens ];
                if ( $units ) {
                    $string .= $hyphen . $dictionary[ $units ];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string    = $dictionary[ $hundreds ] . ' ' . $dictionary[100];
                if ( $remainder ) {
                    $string .= $conjunction . $common->convert_number_to_words( $remainder );
                }
                break;
            default:
                $baseUnit     = pow( 1000, floor( log( $number, 1000 ) ) );
                $numBaseUnits = (int) ( $number / $baseUnit );
                $remainder    = $number % $baseUnit;
                $string       = $common->convert_number_to_words( $numBaseUnits ) . ' ' . $dictionary[ $baseUnit ];
                if ( $remainder ) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $common->convert_number_to_words( $remainder );
                }
                break;
        }

        if ( null !== $fraction && is_numeric( $fraction ) ) {
            $string .= $decimal;
            $words  = array();
            foreach ( str_split( (string) $fraction ) as $number ) {
                $words[] = $dictionary[ $number ];
            }
            $string .= implode( ' ', $words );
        }

        return $string;
    }


    public static function generateRegistrationNumber( $ref_id, $registrationPrefix, $table, $process_type_id ) {

        if ( $process_type_id == 1 ) {
            DB::statement( "update  $table, $table as table2  SET $table.regist_no=(
                                                            select concat('$registrationPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.regist_no,-7,7) )+1,1),7,'0')
                                                                          ) as regist_no
                                                             from (select * from $table ) as table2
                                                             where table2.id!='$ref_id'
                                                        )
                                                      where $table.id='$ref_id' and table2.id='$ref_id'" );
        } else {
            DB::statement( "update  $table, $table as table2  SET $table.regist_no=(
                                                            select concat('$registrationPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.regist_no,-8,8) )+1,1),8,'0')
                                                                          ) as regist_no
                                                             from (select * from $table ) as table2
                                                             where table2.id!='$ref_id'
                                                        )
                                                      where $table.id='$ref_id' and table2.id='$ref_id'" );
        }
    }

    public static function getOfficeShortCode( $office_id ) {
        if ( $office_id ) {
            return IndustrialCityList::where( 'id', $office_id )->value( 'office_short_code' );
        } else {
            return '';
        }
    }

    public static function checkEligibility() {
        //        $companyIds = explode(',', Auth::user()->company_ids);
        //        $inactiveUser = DB::table('inactive_company_users')->where('user_id', Auth::user()->id)
        //            ->whereIn("company_id", $companyIds)->pluck('company_id');
        //        dd($inactiveUser);

        //        $companyIds = explode(',', Auth::user()->company_ids);
        $companyId = Auth::user()->working_company_id;
        $data      = 0;
        if ( $companyId > 0 ) {
            //            $data = CompanyInfo::where('id', $companyId)->count();
            $data = CompanyInfo::where( 'id', $companyId )->where( 'company_status', 1 )->count();
        }
        if ( $data > 0 ) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function geCompanyUsersEmailPhone( $company_id ) {
        return Users::where( 'working_company_id', $company_id )
                    ->whereIn( 'user_type', [ '5x505', '6x606' ] )
                    ->where( 'user_status', 'active' )
                    ->get( [ 'user_email', 'user_mobile' ] );
    }

    public static function getCompanyUsersEmailPhone() {
        $company_id = CommonFunction::getUserCompanyWithZero();

        return Users::where( 'working_company_id', $company_id )
                    ->whereIn( 'user_type', [ '5x505' ] )
                    ->where( 'user_status', 'active' )
                    ->get( [ 'user_email', 'user_mobile' ] );
    }

    public static function storeReportRecentActivates( $report_id, $type = '' ) {
        $user_id                = Auth::user()->id;
        $insertData             = ReportRecentActivates::firstOrNew( [
            'user_id'   => $user_id,
            'report_id' => $report_id
        ] );
        $insertData->type       = $type;
        $insertData->is_active  = 1;
        $insertData->updated_at = Carbon::now();
        $insertData->save();
    }


    public static function vulnerabilityCheck( $value, $type = 'integer' ) {
        if ( $type == 'integer' ) {
            $intData = is_numeric( $value );
            if ( $intData && ( ! preg_match( '/[\'^£$%&*().}{@#~?><>,|=_+¬-]/', $value ) ) ) {
                return $value;
            }
        }
        if ( $type == 'string' ) {
            if ( ! preg_match( '/[\'^£$%&*().!}{@#~?><>,|=_+¬-]/', $value ) ) {
                return $value;
            }
        }
        abort( 404 );
    }

    public static function getAppRedirectPathByJson( $json ) {
        $openMode = 'edit';
        $form_id  = json_decode( $json, true );
        $url      = ( isset( $form_id[ $openMode ] ) ? explode( '/', trim( $form_id[ $openMode ], "/" ) ) : '' );
        $view     = ( $url[1] == 'edit' ? 'view-app' : 'view' ); // view page
        $edit     = ( $url[1] == 'edit' ? 'edit-app' : 'view' ); // edit page
        $array    = [
            'view' => $view,
            'edit' => $edit
        ];

        return $array;
    }

    public static function showAuditLog( $updated_at = '', $updated_by = '' ) {
        $update_was = 'Unknown';
        if ( $updated_at && $updated_at > '0' ) {
            $update_was = Carbon::createFromFormat( 'Y-m-d H:i:s', $updated_at )->diffForHumans();
        }

        $user_name = 'Unknown';
        if ( $updated_by ) {
            $name = User::where( 'id', $updated_by )->first( [
                'user_first_name',
                'user_middle_name',
                'user_last_name'
            ] );
            if ( $name ) {
                $user_name = $name->user_first_name . ' ' . $name->user_middle_name . ' ' . $name->user_last_name;
            }
        }

        return '<span class="help-block">সর্বশেষ সংশোধন : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
    }


    public static function convert2Bangla( $eng_number ) {
        $eng = [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ];
        $ban = [ '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' ];

        return str_replace( $eng, $ban, $eng_number );
    }

    public static function DelegateUserInfo( $desk_id ) {
        $userID = CommonFunction::getUserId();

        $delegateUserInfo = Users::where( 'delegate_to_user_id', $userID )
                                 ->first( [
                                     'id',
                                     DB::raw( "CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name) as user_full_name" ),
                                     'user_email',
                                     'user_pic',
                                     'designation'
                                 ] );

        return $delegateUserInfo;
    }



    /*************************************
     * Ending OSS Common functions
     *************************************/


    /*****************************************************************
     *
     * Please, write all project basis function in below
     *
     *****************************************************************/
    public static function apiResponse( $status = 500, $isError = true, $message = '', $data = [] ) {
        http_response_code( $status );
        header( 'Content-Type:application/json' );

        $responseData = [
            'serviceType'  => 'BTRC-LIMS-API-SERVICE',
            'responseTime' => Carbon::now()->timestamp,
            'status'       => $status,
            'responseCode' => $status,
            'error'        => $isError,
            'message'      => $message,
            'data'         => $data
        ];

        echo json_encode( $responseData, $status );
        exit;
    }

    public static function imagePathToBase64( $path ) {
        if ( file_exists( $path ) ) {
            $type = pathinfo( $path, PATHINFO_EXTENSION );
            $data = file_get_contents( $path );

        } else {
            $noImgpath = 'assets/images/demo-user.jpg';
            $type      = 'png';
            $data      = file_get_contents( $noImgpath );
        }
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );

        return $base64;
    }


    public static function makeDir( $folderName ) {
        $yearMonth = date( 'Y' ) . '/' . date( 'm' ) . '/';
        $path      = "uploads/$folderName/$yearMonth";
        if ( ! file_exists( $path ) ) {
            mkdir( $path, 0777, true );
        }

        return $path;
    }


    public static function InitialTractionGenerator($process_type_id,$process_list_id){
        ProcessList::where([
            'process_type_id' => $process_type_id,
            'id' => $process_list_id
        ])->update(['initial_pay' => 1]);
    }

    public static function generateTrackingNumber( $trackingPrefix, $processTypeId, $processId, $appId, $BaseTable ) {
        // Insert tracking no in process list table
        DB::statement( "update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-7,7) )+1,1),7,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processId' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processId' and table2.id='$processId'" );

        // Insert tracking no in Base table
        DB::statement( "update  $BaseTable, $BaseTable as table2  SET $BaseTable.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-7,7) )+1,1),7,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processId' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where $BaseTable.id='$appId' and table2.id='$appId'" );
    }

    public static function generateUniqueTrackingNumber($trackingNumberPrefix, $processTypeId, $processId, $applicationTable, $serviceShortName, $appId){
//        $applicationNumberCount = DB::statement("SELECT * FROM $applicationTable");
        $result = DB::select(DB::raw("SELECT id FROM $applicationTable ORDER BY id DESC LIMIT 1"));
        $lastApplicationId = $result[0]->id;
        $applicationCount = strval($lastApplicationId);

//        dd($lastApplicationId, $applicationCount);

//        $applicationLimitLength = strlen($applicationCount);
////        $timestamp = time();
////        $currentDate = date('Y-m-d');
////        $timestamp = strtotime($currentDate);
//        $currentDate = date('d-m-Y');
//        $timestamp = str_replace("-", "", $currentDate);
//        $desiredLength = 7;
//        $additionalDigits = $desiredLength - $applicationLimitLength;
//        if ($additionalDigits > 0) {
//            $applicationCount = str_repeat('0', $additionalDigits) . $applicationCount;
//        }
//
//        $trackingNumber =  $trackingNumberPrefix."-".$timestamp. "-".$serviceShortName."-".$applicationCount ;


        $currentDate = date('d-m-Y');
        $timestamp = str_replace("-", "", $currentDate);
        $paddedNumber = str_pad($applicationCount, 7, "0", STR_PAD_LEFT);
        $trackingNumber = $trackingNumberPrefix."-".$timestamp. "-".$serviceShortName."-".$paddedNumber ;

        // Insert tracking no in process list table
        DB::statement( "update  process_list SET process_list.tracking_no='$trackingNumber'
                                                      where process_list.process_type_id='$processTypeId' and process_list.id='$processId'" );

        // Insert tracking no in Base table
        DB::statement( "update  $applicationTable SET $applicationTable.tracking_no='$trackingNumber'
                                                      where $applicationTable.id='$appId'" );

        return $trackingNumber;

    }


    public static function updateAppTableByTrackingNo($processId, $appId, $BaseTable ) {
        // get tracking no from process list table
        $tracking_no = ProcessList::where('id', $processId)->value('tracking_no');
        // updated Base table by tracking no
        DB::statement("update $BaseTable SET $BaseTable.tracking_no='$tracking_no' WHERE $BaseTable.id = '$appId'");
    }


    public static function storeContactPerson( $request, $process_type_id, $refId ) {
        if ( isset( $request->contact_person_name ) && count( $request->contact_person_name ) > 0 ) {
            ContactPerson::where( [ 'app_id' => $refId, 'process_type_id' => $process_type_id ] )->delete();
            $path = CommonFunction::makeDir( 'contact' );
            $contactArr = [];
            foreach ( $request->contact_person_name as $index => $value ) {
                $contactPersonObj                  = new ContactPerson();
                $contactPersonObj->app_id          = $refId;
                $contactPersonObj->process_type_id = $process_type_id;
                $contactPersonObj->name            = $value;
                $contactPersonObj->designation     = $request->contact_designation[ $index ] ?? null;
                $contactPersonObj->mobile          = $request->contact_mobile[ $index ] ?? null;
                $contactPersonObj->email           = $request->contact_person_email[ $index ] ?? null;
                $contactPersonObj->website         = $request->contact_website[ $index ] ?? null;
                $contactPersonObj->district        = $request->contact_district[ $index ] ?? 0;
                $contactPersonObj->upazila         = $request->contact_thana[ $index ] ?? 0;
                $contactPersonObj->address         = $request->contact_person_address[ $index ] ?? null;
                $contactPersonObj->address2        = $request->contact_person_address2[ $index ] ?? null;
               // dd($request->correspondent_contact_photo_base64[ $index ]);
                if ( ! empty( $request->correspondent_contact_photo_base64[ $index ] ) ) {
                    $splited                  = explode( ',', substr( $request->correspondent_contact_photo_base64[ $index ], 5 ), 2 );
                    if (array_key_exists("1",$splited)){
                    $imageData                = $splited[1];
                    $base64ResizeImage        = base64_encode( ImageProcessing::resizeBase64Image( $imageData, 300, 300 ) );
                    $base64ResizeImage        = base64_decode( $base64ResizeImage );
                    $correspondent_photo_name = trim( uniqid( 'BSCIC_IR-' . '-', true ) . '.' . 'jpeg' );
                    file_put_contents( $path . $correspondent_photo_name, $base64ResizeImage );
                    $contactPersonObj->image = $path . $correspondent_photo_name;
                    }

                } else {
                    if ( empty( $appData->auth_person_pic ) ) {
                        $contactPersonObj->image = Auth::user()->user_pic;
                    }
                }

                $contactPersonObj->created_at = date( 'Y-m-d H:i:s' );
                $contactPersonObj->save();
                $singleContactData = $contactPersonObj->toArray();
                $contactArr[] =$singleContactData;
            }
            return $contactArr;
        }
    }

    public static function storeShareHolderPerson( $request, $processTypeId, $refId ) {
        Shareholder::where( [ 'app_id' => $refId, 'process_type_id' => $processTypeId ] )->delete();
        $path = CommonFunction::makeDir( 'shareholder' );
        $shareHolderArr= [];
        try {
        foreach ( $request->shareholder_name as $key => $data ) {
            $shareHolderData                  = new Shareholder();
            $shareHolderData->app_id          = $refId;
            $shareHolderData->process_type_id = $processTypeId;
            $shareHolderData->name            = $request->shareholder_name[ $key ] ?? null;
            $shareHolderData->nationality     = $request->shareholder_nationality[ $key ] ?? 0;
            $shareHolderData->passport        = $request->shareholder_passport[ $key ] ?? null;
            $shareHolderData->nid             = $request->shareholder_nid[ $key ] ?? null;
            $shareHolderData->dob             = ! empty( $request->shareholder_dob[ $key ] ) ? date( 'Y-m-d', strtotime( $request->shareholder_dob[ $key ] ) ) : null;
            $shareHolderData->designation     = $request->shareholder_designation[ $key ] ?? null;
            $shareHolderData->mobile          = $request->shareholder_mobile[ $key ] ?? null;
            $shareHolderData->email           = $request->shareholder_email[ $key ] ?? null;
            $shareHolderData->share_percent   = $request->shareholder_share_of[ $key ] ?? 0.0;
            $shareHolderData->no_of_share     = $request->no_of_share[ $key ] ?? 0;
            $shareHolderData->share_value     = $request->share_value[ $key ] ?? 0;
            //dd($request->correspondent_photo_base64[ $key ] );
            if ( ! empty( $request->correspondent_photo_base64[ $key ] ) ) {
                $splited                  = explode( ',', substr( $request->correspondent_photo_base64[ $key ], 5 ), 2 );
                $imageData                = $splited[1];
                $base64ResizeImage        = base64_encode( ImageProcessing::resizeBase64Image( $imageData, 300, 300 ) );
                $base64ResizeImage        = base64_decode( $base64ResizeImage );
                $correspondent_photo_name = trim( uniqid( 'BSCIC_IR-' . '-', true ) . '.' . 'jpeg' );
                file_put_contents( $path . $correspondent_photo_name, $base64ResizeImage );
                $shareHolderData->image = $path . $correspondent_photo_name;

            } else {
                if ( empty( $appData->auth_person_pic ) ) {
                    $shareHolderData->image = Auth::user()->user_pic;
                }
            }
            $shareHolderData->created_at = \Illuminate\Support\Carbon::now();
            $shareHolderData->save();
            $singleshareHolderData = $shareHolderData->toArray();
            $shareHolderArr[] =$singleshareHolderData;
            }
            if(!empty($request->number_of_share)){
                foreach ($request->number_of_share as $key => $data) {
                    $shareHolderData = new Shareholder();
                    $shareHolderData->app_id = $refId;
                    $shareHolderData->process_type_id = $processTypeId;
                    $shareHolderData->number_of_share = $request->number_of_share[$key] ?? null;
                    $shareHolderData->shareholders_name = $request->shareholders_name[$key] ?? null;
                    $shareHolderData->shareholders_nid_passport = $request->shareholders_nid_passport[$key] ?? null;
                    $shareHolderData->current_number_of_share = $request->current_number_of_share[$key] ?? null;
                    $shareHolderData->current_shareholders_name = $request->current_shareholders_name[$key] ?? null;
                    $shareHolderData->current_shareholders_nid_passport = $request->current_shareholders_nid_passport[$key] ?? null;
                    $shareHolderData->status = 1;

                    // Save files if provided
                    if ($request->hasFile("shareholders_attachment.$key")) {
                        $path = CommonFunction::makeDir('resubmitApplication');
                        $simpleFile = $request->file("shareholders_attachment.$key");
                        $simple_file_name = trim(uniqid('SHAREHOLDER' . '-', true) . '.' . $simpleFile->getClientOriginalExtension());
                        $simpleFile->move($path, $simple_file_name);
                        $shareHolderData->shareholders_attachment = $path . $simple_file_name;
                    }
                    if ($request->hasFile("current_shareholders_attachment.$key")) {
                        $path = CommonFunction::makeDir('resubmitApplication');
                        $simpleFile = $request->file("current_shareholders_attachment.$key");
                        $simple_file_name = trim(uniqid('CURRENT-SHAREHOLDER' . '-', true) . '.' . $simpleFile->getClientOriginalExtension());
                        $simpleFile->move($path, $simple_file_name);
                        $shareHolderData->current_shareholders_attachment = $path . $simple_file_name;
                    }

                    $shareHolderData->save();
                    $singleshareHolderData = $shareHolderData->toArray();
                    $shareHolderArr[] =$singleshareHolderData;
                }
            }

        }catch ( \Exception $e ) {
            dd( $e->getMessage(), $e->getTrace(), $e->getFile() );
        }

//        dd($key,$shareHolderArr);

        return $shareHolderArr;
    }

    public static function sendEmailForReSubmission( $processInfo ) {
        $appInfo = [
            'app_id'            => $processInfo->ref_id,
            'status_id'         => $processInfo->status_id,
            'process_type_id'   => $processInfo->process_type_id,
            'tracking_no'       => $processInfo->tracking_no,
            'process_type_name' => 'ISP License Issue',
            'remarks'           => '',
        ];

//        $receiverInfo = self::getCompanyUsersEmailPhone();
        $receiverInfo = [
            array(
                'user_mobile' => Auth::user()->user_mobile,
                'user_email' => Auth::user()->user_email
            )
        ];
        //send email for application re-submission...
        self::sendEmailSMS( 'APP_RESUBMIT', $appInfo, $receiverInfo );
    }

    public static function setFlashMessageByStatusId( $status_id ) {
        $msg_type = [
            1  => 'Successfully Application Submitted !',
            -1 => 'Successfully Application Updated !',
            2  => 'Successfully Application Re-Submitted !'
        ];

        if ( empty( $msg_type[ $status_id ] ) ) {
            Session::flash( 'error', 'Failed due to Application Status Conflict. Please try again later! [ISPR-007]' );
            return;
        }
        Session::flash( 'success', $msg_type[ $status_id ] );
    }

    public static function sendSMSEmailForApprove($userMobile, $receiverInformation, $appInfo, $processInfo, $serviceName, $modelName){
        $appInfo["license_no"] = $modelName::where([
            'id' => $processInfo->ref_id,
        ])->value('license_no');
        $loginControllerInstance = new LoginController();
        $loginControllerInstance->SendSmsService('APP_APPROVE', ['{$serviceName}' => $serviceName, '{$trackingNumber}' => $processInfo->tracking_no, '{$companyName}'=> $appInfo['org_nm'] , '{$licenseNo}'=> $appInfo["license_no"]], $userMobile);
        //TODO:: run cron job for generate certificate
        $pdfLink = PdfPrintRequestQueue::where(['app_id' => $processInfo->ref_id, 'process_type_id' => $processInfo->process_type_id, 'pdf_diff'=> 0])->value('certificate_link');
        $pdfLink2 = PdfPrintRequestQueue::where(['app_id' => $processInfo->ref_id, 'process_type_id' => $processInfo->process_type_id, 'pdf_diff'=> 3])->value('certificate_link');
        $appInfo["pdf_link"] = $pdfLink;
        $appInfo["pdfLink2"] = $pdfLink2;
        CommonFunction::sendEmailSMS( 'APP_APPROVE', $appInfo, $receiverInformation );

        //Send mail to Director General, Director, and Chairman
        if(in_array($processInfo->process_type_id, [5, 6])){
            $officersInfo = ConfigSetting::whereIn(
                'label', ['director_general', 'director', 'chairman', 'office_copy']
            )->where([
                'status' => 1
            ])->get()->toArray();
            //Send mail to Director General, Director, and Chairman
            $receiverInformationforDGDC = [];
            foreach ($officersInfo as $key => $officerInfo) {
                $receiverInformation = [
                    'user_mobile' => '',
                    'user_email' => $officerInfo['value']
                ];
                array_push($receiverInformationforDGDC, $receiverInformation);
            }

            CommonFunction::sendEmailSMS('APP_APPROVE', $appInfo, $receiverInformationforDGDC);
        }

    }

    public static function getReceiverInfo($modelName, $processInfo, $applicant_mobile='applicant_mobile',$applicant_email='applicant_email' ){
       $processData =  ProcessList::where(['ref_id' => $processInfo->ref_id , 'process_type_id' => $processInfo->process_type_id])->first();
       $userJson = json_decode($processData->json_object, true);
        return $receiverInformation = [
            array(
                'user_mobile' => $userJson['Phone'],
                'user_email' => $userJson['Email']
            )
        ];
    }

    public static function checkExistModuleApplication($processTypeId, $companyId, $licenseType) {
        // skip for draft (-1) and shortfall (5)

        if($processTypeId==1){ // ISP Issue

            $app_count = ProcessList::leftjoin('isp_license_issue','isp_license_issue.id','process_list.ref_id')
            ->where([['process_list.process_type_id', $processTypeId], ['process_list.company_id', $companyId]])
            ->whereNotIn('process_list.status_id', [-1])
            ->where('isp_license_issue.isp_license_type', $licenseType)
            ->whereNull('isp_license_issue.cancellation_tracking_no')
            ->count();

        }else{
            $app_count = ProcessList::where([['process_type_id', $processTypeId], ['company_id', $companyId]])
            ->whereNotIn('status_id', [-1])
            ->count();
        }
        if ($app_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function prepareApplicationSubmissionJsonObject($process_type_id, $application_id, $tracking_no, $process_list_id){
        $doc_info = [];
        $data = [];
        switch ($process_type_id){
            case 1:
                $data['appInfo'] = ProcessList::leftJoin('isp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('pay_order_payment as pop', function ($join) use ($process_type_id) {
                        $join->on('pop.app_id', '=', 'process_list.ref_id');
                        $join->on('pop.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->leftJoin('area_info as contact_district', 'contact_district.area_id', '=', 'apps.cntct_prsn_district')
                    ->leftJoin('area_info as contact_thana', 'contact_thana.area_id', '=', 'apps.cntct_prsn_upazila')
                    ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
                    ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
                    ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
                    ->leftJoin('area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district')
                    ->leftJoin('area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
                        'process_list.bulk_status',
                        'ps.status_name',
                        'process_type.form_url',
                        'reg_office_district.area_nm as reg_office_district_en',
                        'reg_office_thana.area_nm as reg_office_thana_en',
                        'op_office_district.area_nm as op_office_district_en',
                        'op_office_thana.area_nm as op_office_thana_en',
                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_thana.area_nm as applicant_thana_en',
                        'contact_district.area_nm as contact_dis_nm',
                        'contact_thana.area_nm as contact_thana_nm',
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
                    ])->toArray();
                //Fetch data from isp_license_equipment_list
                $data['isp_equipment_list'] = ISPLicenseEquipmentList::where(['isp_license_issue_id' => $data['appInfo']['id']])->get()->toArray();

                //Fetch data from isp_license_tariff_chart
                $data['isp_tariff_chart_list'] = ISPLicenseTariffChart::where(['isp_license_issue_id' => $data['appInfo']['id']])->get()->toArray();
                break;
            case 2:
                $data['appInfo'] = ProcessList::leftJoin('isp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('pay_order_payment as pop', function ($join) use ($process_type_id) {
                        $join->on('pop.app_id', '=', 'process_list.ref_id');
                        $join->on('pop.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
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
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
                //       Fetch data from isp_license_equipment_list
                $data['isp_equipment_list'] = ISPLicenseRenewEquipmentList::where(['isp_license_id' => $data['appInfo']['id']])->get();

                //Fetch data from isp_license_tariff_chart
                $data['isp_tariff_chart_list'] = ISPLicenseRenewTariffChart::where(['isp_license_id' => $data['appInfo']['id']])->get();
                break;
            case 3:
                $data['appInfo'] = ProcessList::leftJoin( 'isp_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                    ] );

                /** Fetch data from isp_license_equipment_list */
                $data['isp_equipment_list'] = ISPLicenseAmendmentEquipmentList::where( [ 'isp_license_issue_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from isp_license_tariff_chart */
                $data['isp_tariff_chart_list'] = ISPLicenseAmendmentTariffChart::where( [ 'isp_license_issue_id' => $data['appInfo']['id'] ] )->get();
                break;
            case 4:
                $data['appInfo'] = ProcessList::leftJoin( "isp_license_surrender as apps", 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as location_of_ins_district', 'location_of_ins_district.area_id', '=', 'apps.location_of_ins_district' )
                    ->leftJoin( 'area_info as location_of_ins_thana', 'location_of_ins_thana.area_id', '=', 'apps.location_of_ins_thana' )
                    ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                    ->leftJoin('area_info as isp_license_division_info', 'isp_license_division_info.area_id', '=', 'apps.isp_license_division')
                    ->leftJoin('area_info as isp_license_district_info', 'isp_license_district_info.area_id', '=', 'apps.isp_license_district')
                    ->leftJoin('area_info as isp_license_upazila_info', 'isp_license_upazila_info.area_id', '=', 'apps.isp_license_upazila')
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'location_of_ins_district.area_nm as location_of_ins_district_en',
                        'location_of_ins_thana.area_nm as location_of_ins_thana_en',
                        'apps.*',
                    ] );

                /** Fetch data from isp_license_equipment_list */
                $data['isp_equipment_list'] = ISPLicenseSurrenderEquipmentList::where( [ 'isp_license_surrender_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from isp_license_tariff_chart */
                $data['isp_tariff_chart_list'] = ISPLicenseSurrenderTariffChart::where( [ 'isp_license_surrender_id' => $data['appInfo']['id'] ] )->get();
                break;
            case 13:
                $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->leftJoin('area_info as contact_district', 'contact_district.area_id', '=', 'apps.cntct_prsn_district')
                    ->leftJoin('area_info as contact_thana', 'contact_thana.area_id', '=', 'apps.cntct_prsn_upazila')
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'contact_district.area_nm as contact_dis_nm',
                        'contact_thana.area_nm as contact_thana_nm',
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

                /** Fetch data from vsat_operator_info */
                $data['vsat_hub_info'] = VSATHubOperatorInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from vsat_license_list_equipment_info */
                $data['vsat_equipment_list'] = VSATLicenseListOfEquipment::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from VSATServiceProviderInfo */
                $data['vsat_service_provider'] = VSATServiceProviderInfo::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from vsat_hub_info */
                $data['vsat_technical_specification'] = VSATTechnicalSpecification::where( [ 'vsat_license_issue_id' => $data['appInfo']['id'] ] )->get();
                break;
            case 14:
                $data['appInfo'] = ProcessList::leftJoin( 'vsat_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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

                /** Fetch data from vsat_operator_info */
                $data['vsat_hub_info'] = VSATHubOperatorRenewInfo::where( [ 'vsat_license_renew_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from vsat_license_list_equipment_info */
                $data['vsat_equipment_list'] = VSATLicenseListOfEquipmentRenew::where( [ 'vsat_license_renew_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from VSATServiceProviderInfo */
                $data['vsat_service_provider'] = VSATServiceProviderRenewInfo::where( [ 'vsat_license_renew_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from vsat_hub_info */
                $data['vsat_technical_specification'] = VSATTechnicalSpecificationRenew::where( [ 'vsat_license_renew_id' => $data['appInfo']['id'] ] )->get();

                /** Fetch data from VSATLicenseIssue */
                $data['vsat_license_issue'] = \App\Modules\VSATLicenseIssue\Models\VSATLicenseIssue::where( ['id' => $data['appInfo']['id'] ] )->first();
                break;
            case 21:
                $data['appInfo'] = ProcessList::leftJoin('iptsp_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
//                'apps.org_per_district as org_per_pdistrict',

                    ]);

                if($data['appInfo']->bulk_status != '1'){
                    $cover_ot_dis    = json_decode($data['appInfo']->cover_ot_dis);
                    //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                    $cover_ot_dis                    = Area::whereIn('area_id', $cover_ot_dis)->first([
                        DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_ot_dis_name')
                    ]);
                    $data['appInfo']['cover_ot_dis'] = $cover_ot_dis->cover_ot_dis_name;

                    $cover_dis = json_decode($data['appInfo']->cover_dis);
                    //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                    $cover_dis                    = Area::whereIn('area_id', $cover_dis)->first([
                        DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_dis_name')
                    ]);
                    $data['appInfo']['cover_dis'] = $cover_dis->cover_dis_name;

                    /** Fetch data from iptsp_Connected_ISP */
                    $data['iptsp_connected_isp_info'] = IPTSPLicenseIssueConnectedISPInfo::where(['iptsp_license_issue_id' => $data['appInfo']['id']])->get();
                }

                $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_district)->first([
                    'area_nm'
                ]);
                $data['org_permanent_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_upazila)->first([
                    'area_nm'
                ]);
                $data['applicant_district']     = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
                    'area_nm'
                ]);
                if($data['appInfo']->bulk_status != '1') {
                    $data['applicant_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_upazila)->first([
                        'area_nm'
                    ]);
                    $data['signatory_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_district)->first([
                        'area_nm'
                    ])->area_nm;


                    $data['signatory_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_thana)->first([
                        'area_nm'
                    ])->area_nm;
                }
                if($data['appInfo']->bulk_status != '1') {
                    if (!empty($data['appInfo']->isptspli_area_div)) {
                        $data['license_type_division'] = DB::table('area_info')->where('area_id', $data['appInfo']->isptspli_area_div)->first([
                            'area_nm'
                        ])->area_nm;
                    }
                }
                if($data['appInfo']->bulk_status != '1') {
                    $data['districts'] = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                    $data['division'] = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                    $data['multiDistricts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                }
                break;
            case 22:
                $data['appInfo'] = ProcessList::leftJoin('iptsp_license_renew as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
//                'apps.org_per_district as org_per_pdistrict',

                    ]);

                $cover_ot_dis    = json_decode($data['appInfo']->cover_ot_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_ot_dis                    = Area::whereIn('area_id', $cover_ot_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_ot_dis_name')
                ]);
                $data['appInfo']['cover_ot_dis'] = $cover_ot_dis->cover_ot_dis_name;

                $cover_dis = json_decode($data['appInfo']->cover_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_dis                    = Area::whereIn('area_id', $cover_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_dis_name')
                ]);
                $data['appInfo']['cover_dis'] = $cover_dis->cover_dis_name;

                /** Fetch data from iptsp_Connected_ISP */
                $data['iptsp_connected_isp_info'] = IPTSPLicenseRenewConnectedISPInfo::where(['iptsp_license_issue_id' => $data['appInfo']['id']])->get();

                $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_district)->first([
                    'area_nm'
                ]);
                $data['org_permanent_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_upazila)->first([
                    'area_nm'
                ]);
                $data['applicant_district']     = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
                    'area_nm'
                ]);

                $data['applicant_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_upazila)->first([
                    'area_nm'
                ]);
                $data['signatory_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_district)->first([
                    'area_nm'
                ])->area_nm;

                $data['signatory_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_thana)->first([
                    'area_nm'
                ])->area_nm;

                if (!empty($data['appInfo']->isptspli_area_div)) {
                    $data['license_type_division'] = DB::table('area_info')->where('area_id', $data['appInfo']->isptspli_area_div)->first([
                        'area_nm'
                    ])->area_nm;
                }

                $data['districts']      = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['division']       = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['multiDistricts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                break;
            case 23:
                $data['appInfo'] = ProcessList::leftJoin('iptsp_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
//                'apps.org_per_district as org_per_pdistrict',

                    ]);

                $cover_ot_dis    = json_decode($data['appInfo']->cover_ot_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_ot_dis                    = Area::whereIn('area_id', $cover_ot_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_ot_dis_name')
                ]);
                $data['appInfo']['cover_ot_dis'] = $cover_ot_dis->cover_ot_dis_name;

                $cover_dis = json_decode($data['appInfo']->cover_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_dis                    = Area::whereIn('area_id', $cover_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_dis_name')
                ]);
                $data['appInfo']['cover_dis'] = $cover_dis->cover_dis_name;

                /** Fetch data from iptsp_Connected_ISP */
                $data['iptsp_connected_isp_info'] = IPTSPAmendmentConnectedISPInfo::where(['iptsp_license_issue_id' => $data['appInfo']['id']])->get();

                $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_district)->first([
                    'area_nm'
                ]);
                $data['org_permanent_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_upazila)->first([
                    'area_nm'
                ]);
                $data['applicant_district']     = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
                    'area_nm'
                ]);

                $data['applicant_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_upazila)->first([
                    'area_nm'
                ]);
                $data['signatory_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_district)->first([
                    'area_nm'
                ])->area_nm;

                $data['signatory_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_thana)->first([
                    'area_nm'
                ])->area_nm;

                if (!empty($data['appInfo']->isptspli_area_div)) {
                    $data['license_type_division'] = DB::table('area_info')->where('area_id', $data['appInfo']->isptspli_area_div)->first([
                        'area_nm'
                    ])->area_nm;
                }

                $data['districts']      = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['division']       = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['multiDistricts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                break;
            case 24:
                $data['appInfo'] = ProcessList::leftJoin('iptsp_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.id', $process_list_id)
                    ->where('process_list.process_type_id', $process_type_id)
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
                    ]);

                $cover_ot_dis    = json_decode($data['appInfo']->cover_ot_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_ot_dis                    = Area::whereIn('area_id', $cover_ot_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_ot_dis_name')
                ]);
                $data['appInfo']['cover_ot_dis'] = $cover_ot_dis->cover_ot_dis_name;

                $cover_dis = json_decode($data['appInfo']->cover_dis);
                //$data['appInfo']['cover_ot_dis_select2'] = Area::whereIn('area_id',$cover_ot_dis)->pluck('area_nm','area_id');
                $cover_dis                    = Area::whereIn('area_id', $cover_dis)->first([
                    DB::raw('group_concat(area_nm SEPARATOR ", ") as cover_dis_name')
                ]);
                $data['appInfo']['cover_dis'] = $cover_dis->cover_dis_name;

                /** Fetch data from iptsp_Connected_ISP */
                $data['iptsp_connected_isp_info'] = IPTSPLicenseSurrenderConnectedISPInfo::where(['iptsp_license_issue_id' => $data['appInfo']['id']])->get();

                $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_district)->first([
                    'area_nm'
                ]);
                $data['org_permanent_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->org_per_upazila)->first([
                    'area_nm'
                ]);
                $data['applicant_district']     = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
                    'area_nm'
                ]);

                $data['applicant_upazila']  = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_upazila)->first([
                    'area_nm'
                ]);
                $data['signatory_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_district)->first([
                    'area_nm'
                ])->area_nm;

                $data['signatory_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->cs_thana)->first([
                    'area_nm'
                ])->area_nm;

                if (!empty($data['appInfo']->isptspli_area_div)) {
                    $data['license_type_division'] = DB::table('area_info')->where('area_id', $data['appInfo']->isptspli_area_div)->first([
                        'area_nm'
                    ])->area_nm;
                }

                $data['districts']      = ['' => 'Select'] + Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['division']       = ['' => 'Select'] + Area::where('area_type', 1)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                $data['multiDistricts'] = Area::where('area_type', 2)->orderBy('area_nm', 'ASC')->pluck('area_nm', 'area_id')->toArray();
                break;
            case 5:
                $data['appInfo'] = ProcessList::leftJoin( 'call_center_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
                    ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                    ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                        $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
                    } )
                    ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                    } )
                    ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
                    ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
                    ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.noc_district' )
                    ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.noc_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
                    ->first( [
                        'process_list.id as process_list_id',
                        'process_list.desk_id',
                        'process_list.process_type_id',
                        'process_list.status_id',
                        'process_list.ref_id',
                        'process_list.bulk_status as pr_bulk_status',
                        'process_list.bulk_object as pr_bulk_object',
                        'process_list.tracking_no',
                        'process_list.company_id',
                        'process_list.process_desc',
                        'process_list.submitted_at',
                        'ps.status_name',
                        'process_type.form_url',
                        'reg_off_district.area_nm as reg_office_district_en',
                        'reg_off_thana.area_nm as reg_office_thana_en',
                        'noc_dis.area_nm as op_office_district_en',
                        'noc_thana.area_nm as op_office_thana_en',
                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_thana.area_nm as applicant_thana_en',
                        'apps.*',
                        'apps.company_name as org_nm',
                        'apps.company_type as org_type',
                        'apps.noc_address as op_office_address',
                        'apps.noc_address2 as op_office_address2',

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
                $proposal_area_data = ProposalArea::where( [ 'ref_id' => $application_id ] )->get();
                foreach ( $proposal_area_data as $index => $value ) {
                    $disInfo = Area::where( 'area_id', $value->proposal_district )->first( [
                        'area_id',
                        'area_nm'
                    ] );
                    $value->proposal_district = $disInfo->area_nm;
                    $thanaInfo = Area::where( 'area_id', $value->proposal_thana )->first( [
                        'area_id',
                        'area_nm'
                    ] );
                    $value->proposal_thana  = $thanaInfo->area_nm;
                }
                $data['proposal_area'] = $proposal_area_data;
                break;

            case 25:
                $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_issue as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.per_office_district' )
                    ->leftJoin( 'area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.per_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'reg_off_district.area_nm as reg_office_district_en',
                        'reg_off_thana.area_nm as reg_office_thana_en',
                        'noc_dis.area_nm as op_office_district_en',
                        'noc_thana.area_nm as op_office_thana_en',
                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_thana.area_nm as applicant_thana_en',
                        'apps.*',
                        'apps.company_name as org_nm',
                        'apps.company_type as org_type',
                        'apps.per_office_address as op_office_address',
                        'apps.per_office_address2 as op_office_address2',

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
                    break;
            case 26:
                $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_renew as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
                    ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
                    ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.per_office_district' )
                    ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.per_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'apps.per_office_address as op_office_address',

                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_thana.area_nm as applicant_thana_en',

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
                    break;
            case 27:
                $data['appInfo'] = ProcessList::leftJoin( 'tvas_license_amendment as apps', 'apps.id', '=', 'process_list.ref_id' )
                    ->leftJoin( 'process_type', 'process_type.id', '=', 'process_list.process_type_id' )
                    ->leftJoin( 'sp_payment as sfp', function ( $join ) use ( $process_type_id ) {
                        $join->on( 'sfp.app_id', '=', 'process_list.ref_id' );
                        $join->on( 'sfp.process_type_id', '=', DB::raw( $process_type_id ) );
                    } )
                    ->leftJoin( 'process_status as ps', function ( $join ) use ( $process_type_id ) {
                        $join->on( 'ps.id', '=', 'process_list.status_id' );
                        $join->on( 'ps.process_type_id', '=', DB::raw( $process_type_id ) );
                    } )
                    ->leftJoin( 'area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district' )
                    ->leftJoin( 'area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana' )
                    ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
                    ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->leftJoin( 'user_desk', 'user_desk.id', '=', 'process_list.desk_id' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'applicant_district.area_nm as applicant_dis_nm',
                        'applicant_thana.area_nm as applicant_thana_nm',
                        'reg_off_district.area_nm as reg_off_dis_nm',
                        'reg_off_thana.area_nm as reg_off_thana_nm',
                        'op_office_district.area_nm as op_office_district_nm',
                        'op_office_thana.area_nm as op_office_thana_nm',
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
                    break;
            case 28:
                $data['appInfo']  = ProcessList::leftJoin( 'tvas_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as per_off_district', 'per_off_district.area_id', '=', 'apps.op_office_district' )
                    ->leftJoin( 'area_info as per_off_thana', 'per_off_thana.area_id', '=', 'apps.op_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                    break;
            case 29:
                $data['appInfo'] = ProcessList::leftJoin('vts_license_issue as apps', 'apps.id', '=', 'process_list.ref_id')
                    ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->leftJoin('sp_payment as sfp', function ($join) use ($process_type_id) {
                        $join->on('sfp.app_id', '=', 'process_list.ref_id');
                        $join->on('sfp.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                        $join->on('ps.id', '=', 'process_list.status_id');
                        $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                    })
                    ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                    ->leftJoin('area_info as reg_off_district', 'reg_off_district.area_id', '=', 'apps.reg_office_district')
                    ->leftJoin('area_info as reg_off_thana', 'reg_off_thana.area_id', '=', 'apps.reg_office_thana')
                    ->leftJoin('area_info as noc_dis', 'noc_dis.area_id', '=', 'apps.op_office_district')
                    ->leftJoin('area_info as noc_thana', 'noc_thana.area_id', '=', 'apps.op_office_thana')
                    ->leftJoin('area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district')
                    ->leftJoin('area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana')
                    ->where('process_list.ref_id', $application_id)
                    ->where('process_list.process_type_id', $process_type_id)
                    ->where('process_list.id', $process_list_id)
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
                        'apps.*',
                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_thana.area_nm as applicant_thana_en',
                        'reg_off_district.area_nm as reg_office_district_en',
                        'reg_off_thana.area_nm as reg_office_thana_en',
                        'noc_dis.area_nm as op_office_district_en',
                        'noc_thana.area_nm as op_office_thana_en',
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

                $data['org_permanent_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_permanent_district)->first([
                    'area_nm'
                ]);

                $data['org_permanent_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->org_permanent_upazila)->first([
                    'area_nm'
                ]);

                $data['applicant_district'] = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_district)->first([
                    'area_nm'
                ]);

                $data['applicant_upazila'] = DB::table('area_info')->where('area_id', $data['appInfo']->applicant_thana)->first([
                    'area_nm'
                ]);
                break;
            case 30:
                $data['appInfo'] = ProcessList::leftJoin( "vts_license_renew as apps", 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
                    ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
                    ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
                    ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_upazila', 'applicant_upazila.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'apps.op_office_address',

                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_upazila.area_nm as applicant_thana_en',

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
                    break;
            case 83:
                $data['appInfo'] = ProcessList::leftJoin( "vts_license_amendment as apps", 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as reg_office_district', 'reg_office_district.area_id', '=', 'apps.reg_office_district' )
                    ->leftJoin( 'area_info as reg_office_thana', 'reg_office_thana.area_id', '=', 'apps.reg_office_thana' )
                    ->leftJoin( 'area_info as op_office_district', 'op_office_district.area_id', '=', 'apps.op_office_district' )
                    ->leftJoin( 'area_info as op_office_thana', 'op_office_thana.area_id', '=', 'apps.op_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_upazila', 'applicant_upazila.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                        'apps.op_office_address',

                        'applicant_district.area_nm as applicant_district_en',
                        'applicant_upazila.area_nm as applicant_thana_en',

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
                break;
            case 84:
                $data['appInfo']         = ProcessList::leftJoin( 'vts_license_surrender as apps', 'apps.id', '=', 'process_list.ref_id' )
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
                    ->leftJoin( 'area_info as per_off_district', 'per_off_district.area_id', '=', 'apps.op_office_district' )
                    ->leftJoin( 'area_info as per_off_thana', 'per_off_thana.area_id', '=', 'apps.op_office_thana' )
                    ->leftJoin( 'area_info as applicant_district', 'applicant_district.area_id', '=', 'apps.applicant_district' )
                    ->leftJoin( 'area_info as applicant_thana', 'applicant_thana.area_id', '=', 'apps.applicant_thana' )
                    ->where( 'process_list.ref_id', $application_id )
                    ->where( 'process_list.process_type_id', $process_type_id )
                    ->where('process_list.id', $process_list_id)
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
                break;
            default:
        }

        $data['appShareholderInfo'] = Shareholder::where(['app_id' => $application_id, 'process_type_id' => $process_type_id])->get()->toArray();
        $doc_info  = ApplicationDocuments::where('process_type_id', $process_type_id)->where('ref_id', $application_id)->orderBy('created_at', 'desc')->get()->toArray();
        $data['contact_person']        = ContactPerson::where([
            'app_id' => $application_id,
            'process_type_id' => $process_type_id
        ])->get();
        foreach ($data['contact_person'] as $key => $item) {
            $data['contact_person'][$key]['contact_district_name'] = DB::table('area_info')->where('area_id', $item->district)->value('area_nm');
            $data['contact_person'][$key]['contact_upazila_name'] = DB::table('area_info')->where('area_id', $item->upazila)->value('area_nm');
        }
        $data['contact_person'] = $data['contact_person']->toArray();
        $data['service_name'] = ProcessType::where('id', $process_type_id)->value('name');
        $info_array = $data;
        $jsonApplicationInfo = json_encode($info_array);

        if($info_array['appInfo']['status_id'] == 2){
            $enothiSubmissionInfo = ENothiSubmissionInfo::where([
                'app_id' => $application_id,
                'process_list_id' => $process_list_id,
                'process_type_id' => intval($process_type_id)
            ])->first();
        }else{
            $enothiSubmissionInfo  =  new ENothiSubmissionInfo();
        }

        $enothiSubmissionInfo->process_type_id = $process_type_id;
        $enothiSubmissionInfo->app_id = $application_id;
        $enothiSubmissionInfo->company_id = CommonFunction::getUserCompanyWithZero();
        $enothiSubmissionInfo->user_id = Auth::user()->id;
        $enothiSubmissionInfo->process_list_id = $process_list_id;
        $enothiSubmissionInfo->tracking_no = $tracking_no;
        $enothiSubmissionInfo->json_object = $jsonApplicationInfo;

        $enothiSubmissionInfo->application_name = $info_array['appInfo']['org_nm'];
        $enothiSubmissionInfo->application_subject = $info_array['appInfo']['tracking_no'].'-'.$info_array['service_name'].'-'.uniqid();
        $enothiSubmissionInfo->feedback_url = env('PROJECT_ROOT').'/api/feedback/'.$process_type_id.'/'.$application_id.'/'.$tracking_no;
        $enothiSubmissionInfo->mobile_no = Auth::user()->user_mobile;
        $enothiSubmissionInfo->attachment_json = json_encode($doc_info);
        $enothiSubmissionInfo->process_type_name = $info_array['service_name'];
        $enothiSubmissionInfo->status = $info_array['appInfo']['status_id'] == 2 ? 2 : 0;
        $enothiSubmissionInfo->save();
    }

    public static function getDynamicallyDeskUser($process_type_id) {
        if ( !empty($process_type_id) ) {
            $process_desk = ProcessPath::where('process_type_id', $process_type_id)->where('status_from', 1)->first('desk_from');
            return $process_desk->desk_from;
        } else {
            return '';
        }
    }
    public static function warningProcessType($process_type_id) {
        $issue_warning_bool = 0;
        $issue_warning_msg = "";
        $ISSUE_WARNING_PROCESS_TYPE = env('ISSUE_WARNING_PROCESS_TYPE');
        $ISSUE_WARNING_PROCESS_TYPE_ARRAY = [];
        if(!empty($ISSUE_WARNING_PROCESS_TYPE)){
            $ISSUE_WARNING_PROCESS_TYPE_ARRAY = explode(",", $ISSUE_WARNING_PROCESS_TYPE);
        }
        if(in_array($process_type_id, $ISSUE_WARNING_PROCESS_TYPE_ARRAY)){
            if($process_type_id == 1){
                $issue_warning_msg = "Submission of new application for ISP is closed currently.";
            }elseif($process_type_id == 2){
                $issue_warning_msg = "Submission of renew application for ISP is closed currently.";
            }elseif($process_type_id == 3){
                $issue_warning_msg = "Submission of amendment application for ISP is closed currently.";
            }elseif($process_type_id == 4){
                $issue_warning_msg = "Submission of surrender application for ISP is closed currently.";
            }elseif($process_type_id == 5){
                $issue_warning_msg = "Submission of new application for BPO/Call Center Registration is closed currently.";
            }elseif($process_type_id == 6){
                $issue_warning_msg = "Submission of renew application for BPO/Call Center Registration is closed currently.";
            }elseif($process_type_id == 7){
                $issue_warning_msg = "Submission of amendment application for BPO/Call Center Registration is closed currently.";
            }elseif($process_type_id == 8){
                $issue_warning_msg = "Submission of surrender application for BPO/Call Center Registration is closed currently.";
            }elseif($process_type_id == 9){
                $issue_warning_msg = "Submission of new application for NIX is closed currently.";
            }elseif($process_type_id == 10){
                $issue_warning_msg = "Submission of renew application for NIX is closed currently.";
            }elseif($process_type_id == 11){
                $issue_warning_msg = "Submission of amendment application for NIX is closed currently.";
            }elseif($process_type_id == 12){
                $issue_warning_msg = "Submission of surrender application for NIX is closed currently.";
            }elseif($process_type_id == 13){
                $issue_warning_msg = "Submission of new application for VSAT is closed currently.";
            }elseif($process_type_id == 14){
                $issue_warning_msg = "Submission of renew application for VSAT is closed currently.";
            }elseif($process_type_id == 15){
                $issue_warning_msg = "Submission of amendment application for VSAT is closed currently.";
            }elseif($process_type_id == 16){
                $issue_warning_msg = "Submission of surrender application for VSAT is closed currently.";
            }elseif($process_type_id == 21){
                $issue_warning_msg = "Submission of new application for IPTSP is closed currently.";
            }elseif($process_type_id == 22){
                $issue_warning_msg = "Submission of renew application for IPTSP is closed currently.";
            }elseif($process_type_id == 23){
                $issue_warning_msg = "Submission of amendment application for IPTSP is closed currently.";
            }elseif($process_type_id == 24){
                $issue_warning_msg = "Submission of surrender application for IPTSP is closed currently.";
            }elseif($process_type_id == 25){
                $issue_warning_msg = "Submission of new application for TVAS is closed currently.";
            }elseif($process_type_id == 26){
                $issue_warning_msg = "Submission of renew application for TVAS is closed currently.";
            }elseif($process_type_id == 27){
                $issue_warning_msg = "Submission of amendment application for TVAS is closed currently.";
            }elseif($process_type_id == 28){
                $issue_warning_msg = "Submission of surrender application for TVAS is closed currently.";
            }elseif($process_type_id == 50){
                $issue_warning_msg = "Submission of new application for NTTN is closed currently.";
            }elseif($process_type_id == 51){
                $issue_warning_msg = "Submission of renew application for NTTN is closed currently.";
            }elseif($process_type_id == 52){
                $issue_warning_msg = "Submission of amendment application for NTTN is closed currently.";
            }elseif($process_type_id == 53){
                $issue_warning_msg = "Submission of surrender application for NTTN is closed currently.";
            }elseif($process_type_id == 29){
                $issue_warning_msg = "Submission of new application for VTS is closed currently.";
            }elseif($process_type_id == 30){
                $issue_warning_msg = "Submission of renew application for VTS is closed currently.";
            }elseif($process_type_id == 83){
                $issue_warning_msg = "Submission of amendment application for VTS is closed currently.";
            }elseif($process_type_id == 84){
                $issue_warning_msg = "Submission of surrender application for VTS is closed currently.";
            }
            $issue_warning_bool = 1;
             return [
                'issue_warning_bool' => $issue_warning_bool,
                'html'         => $issue_warning_msg
             ];
        }
        return false;
    }
    public static function PaymentInformations($service_id, $processData, $viewFile){
        $paymentInformation = new PaymentPanelController();
        $s_id =  Encryption::encodeId($service_id);
        $p_id = Encryption::encodeId($processData->ref_id);

        $paymentJsonData = $paymentInformation->getViewPaymentPanel($s_id, $p_id);
        $PaymentResult = $paymentJsonData->getData(true);

        $PaymentData = $PaymentResult['response'];
        $pos = strpos($viewFile, '</div>', strpos($viewFile, 'id="payment_panel"'));
        $htmlContent = '';
        if ($pos !== false) {
            $htmlContent = substr_replace($viewFile, $PaymentData, $pos, 0);
        }

       return $htmlContent;
    }
    public static function DNothiRequest($processId, $actionButton) {
        $processData = ProcessList::where('id', $processId)->first();
        if(in_array($processData->status_id, [1,2,16,25,65, 62]) ){
            try {
            $service_id = $processData->process_type_id;
            $applicationId = $processData->ref_id;
            $encodedApplicationId = Encryption::encodeId($applicationId);

            $invokers = [
                1 => new ISPLicenseIssue(),
                2 => new ISPLicenseRenew(),
                3 => new ISPLicenseAmendment(),
                4 => new ISPLicenseSurrender(),
                5 => new CallCenterNew(),
                6 => new CallCenterRenew(),
                7 => new Amendment(),
                8 => new CallCenterSurrender(),
                9 => new NIXLicenseIssue(),
                10 => new NIXLicenseRenew(),
                11 => new NIXLicenseAmendment(),
                12 => new NIXLicenseSurrender(),
                13 => new VSATLicenseIssue(),
                14 => new VSATLicenseRenew(),
                15 => new VSATLicenseAmendment(),
                16 => new VSATLicenseSurrender(),
                17 => new IIGLicenseIssue(),
                18 => new IIGLicenseRenew(),
                19 => new IIGLicenseAmendment(),
                20 => new IIGLicenseSurrender(),
                21 => new IPTSPLicenseIssue(),
                22 => new IPTSPLicenseRenew(),
                23 => new IPTSPLicenseAmendment(),
                24 => new IPTSPLicenseSurrender(),
                25 => new TVASLicenseIssue(),
                26 => new TVASLicenseRenew(),
                27 => new TVASLicenseAmendment(),
                28 => new TVASLicenseSurrender(),
                29 => new VTSLicenseIssue(),
                30 => new VTSLicenseRenew(),
                83 => new VTSLicenseAmendment(),
                84 => new VTSLicenseSurrender(),
                33 => new ICXLicenseIssue(),
                34 => new ICXLicenseRenew(),
                35 => new ICXLicenseAmendment(),
                36 => new ICXLicenseSurrender(),
                37 => new IGWLicenseIssue(),
                38 => new IGWLicenseRenew(),
                39 => new IGWLicenseAmendment(),
                40 => new IGWLicenseSurrender(),
                50 => new NTTNLicenseIssue(),
                51 => new NTTNLicenseRenew(),
                52 => new NTTNLicenseAmendment(),
                53 => new NTTNLicenseSurrender(),
                54 => new ITCLicenseIssue(),
                55 => new ITCLicenseRenew(),
                56 => new ITCLicenseAmendment(),
                57 => new ITCLicenseSurrender(),
                58 => new MNOLicenseIssue(),
                59 => new MNOLicenseRenew(),
                60 => new MNOLicenseAmendment(),
                61 => new MNOLicenseSurrender(),
                62 => new SCSLicenseIssue(),
                63 => new SCSLicenseRenew(),
                64 => new SCSLicenseAmendment(),
                65 => new SCSLicenseSurrender(),
                66 => new TCLicenseIssue(),
                67 => new TCLicenseRenew(),
                68 => new TCLicenseAmendment(),
                69 => new TCLicenseSurrender(),
                70 => new MNPLicenseIssue(),
                71 => new MNPLicenseRenew(),
                72 => new MNPLicenseAmendment(),
                73 => new MNPLicenseSurrender(),
                74 => new BWALicenseIssue(),
                75 => new BWALicenseRenew(),
                76 => new BWALicenseAmendment(),
                77 => new BWALicenseSurrender(),
                78 => new SSLicenseIssue(),
                79 => new SSLicenseRenew(),
                80 => new SSLicenseAmendment(),
                81 => new SSLicenseSurrender(),
            ];

            $model = $invokers[$service_id];

            $jsonResponse = $model->viewForm($service_id, $encodedApplicationId);

            $responseData = $jsonResponse->getData(true);

            $viewFile = $responseData['html'];
            $htmlContent =  self::PaymentInformations($service_id, $processData, $viewFile);
            $process_name = ProcessType::where('id', $processData->process_type_id)->value('name');

            $client_id = config('constant.DOPTOR_CLIENT_ID');
            $user_name = config('constant.DOPTOR_USER_NAME');
            $password = config('constant.DOPTOR_PASSWORD');
            $url = config('constant.DOPTOR_TOKEN_URL');
            $doptor_apikey = config('constant.DOPTOR_APIKEY');

            $body = [
                'client_id' => $client_id,
                'username' => $user_name,
                'password' => $password,
            ];

            $headers = array(
                'apikey: '.$doptor_apikey,
                'Content-Type: application/x-www-form-urlencoded',
            );
            $doptor_request_data = array_merge($body, $headers);
            $response = self::makeCurlRequest($url, $headers, $body, 'POST');

            // get office unit
            $url = config('constant.DOPTOR_OFFICE_UNIT_URL');
            $param = 4714;
            $url = $url . '=' . $param;
            $body = [];
//                dd(1,$response['data']['status']);
                if($response['data']['status'] == "error"){
                    CommonFunction::DNothiHistoryLog([
                        'request_data' => json_encode($doptor_request_data),
                        'response_data'=>json_encode($response['data']),
                        'response_message'=>"Token did not get",
                        'response_code'=>$response['http_code'],
                        'process_list_id' => $processData->id,
                        'api_type' => $processData->status_id,
                        'process_type_id' => $processData->process_type_id,
                        'table_name' => $process_name,
                        'created_by' => Auth::id(),
                    ]);
                    DB::table('process_list')
                        ->where('id', $processData->id)
                        ->update([
                            'nothi_status' => $processData->status_id,
                        ]);
                    DB::commit();
                    return false;
                }
            $headers = array(
                'api-version: 1',
                'apikey: '.$doptor_apikey,
                'Authorization: Bearer '.$response['data']['data']['token'],
                'Content-Type: application/x-www-form-urlencoded',
            );
                $getting_prapok_request = array_merge($body, $headers);


                $response_unit = self::makeCurlRequest($url, $headers, $body, 'GET');
            $filteredData = array_filter($response_unit['data'], function($item) {
                return $item['unitNameBn'] === 'লিগ্যাল এন্ড লাইসেন্সিং বিভাগ' && $item['nameBn'] === 'মহাপরিচালক';
            });
            $prapok_id = !empty($filteredData) ? reset($filteredData)['id'] : " ";
            if ($prapok_id){
                self::requestSend($processData, $process_name, $htmlContent, $prapok_id, $actionButton);
            }else{
//                CommonFunction::DNothiHistoryLog([
//                    'request_data' => json_encode($getting_prapok_request),
//                    'response_data'=>json_encode($response_unit['data']),
//                    'response_message'=>"Something went wrong",
//                    'response_code'=>$response['http_code'],
//                    'process_list_id' => $processData->id,
//                    'api_type' => $processData->status_id,
//                    'process_type_id' => $processData->process_type_id,
//                    'table_name' => $process_name,
//                    'created_by' => Auth::id(),
//                ]);
//                DB::commit();
                return false;
            }

            }catch(Exception $e){
                Log::error('Exception caught', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

    }

    public static function requestSend($processData, $process_name, $htmlContent, $prapok_id, $actionButton)
    {
        $result = json_decode($processData->json_object, true);
        try {
            $token = config('constant.NOTHI_NIFI_TOKEN');
            $office_id = config('constant.NOTHI_NIFI_OFFICE_ID');
            $service_id = config('constant.NOTHI_SERVICE_ID');
            $api_client = config('constant.BTRC_API_CLIENT');

            $url = config('constant.NOTHI_NIFI_SUBMISSION_URL');
            $applicant_name = $result['Applicant Name'] ?? '';
            $mobile_no = $result['Phone'] ?? '';
            // service name should be here with date
            $application_subject = $process_name . '-' .date( 'Y-m-d H:i:s' );
            if ($processData->process_type_id < 10){
                $process_type_id = '0'.$processData->process_type_id;
            }else{
                $process_type_id = $processData->process_type_id;
            }
            $decision = [];
            $body = [
                'office_id' => $office_id,
                'user_id' => 0,
                'nothi_service_id' => $service_id . $process_type_id,
                'nothi_service_name' => $process_name,
                'api_client' => $api_client,
                'data' => "$htmlContent",
                'applicant_name' => $applicant_name,
                'mobile_no' => $mobile_no,
                'application_subject' => $application_subject,
                'national_identity_no' => '',
                'receivers' => [
                    'prapok' => $prapok_id
                ],
                'decision' => json_encode($decision),
                'attachments' => []
            ];
//            dump($actionButton);
            // Conditionally add nothi_receipt_no if $statusId == 2
            if ($actionButton == 'Re-submit'|| $actionButton == 'payment') {
                $body['dak_received_no'] = $processData->nothi_receipt_no;
            }
            $headers = array(
                'Authorization: Basic '.$token,
                'Content-Type: application/json'
            );
                $request_data = array_merge($body, $headers);
//            dd($url,$body,$headers);
            $response = self::makeCurlRequest($url, $headers, $body, 'POST', 1);
            if(!empty($response)) {
                if($response['http_code'] == 200){
                    $message = 'Response successfully';
                }else{
                    $message = 'Something wrong';
                }
                $nothiReceiptNo = isset($response['data']['data']['dak_received_no']) ? $response['data']['data']['dak_received_no'] : '';
                try {
                CommonFunction::DNothiHistoryLog([
                        'request_data' => json_encode($request_data),
                        'response_data' => json_encode($response['data']),
                        'nothi_receipt_no' => $nothiReceiptNo,
                        'response_message' => $message,
                        'process_list_id' => $processData->id,
                        'process_type_id' => $processData->process_type_id,
                        'api_type' => $processData->status_id,
                        'response_code' => $response['http_code'],
                        'table_name' => $process_name,
                        'created_by' => Auth::id(),
                    ]);

                    DB::table('process_list')
                        ->where('id', $processData->id)
                        ->update([
                            'nothi_receipt_no' => $nothiReceiptNo,
                            'nothi_status' => $processData->status_id,
                        ]);
                    DB::commit();
                } catch (\Exception $e) {
                    Log::info('Dak Received No: ' . $nothiReceiptNo);
                    DB::rollBack();
                    throw $e;
                }
            }else{
                CommonFunction::DNothiHistoryLog([
                    'request_data' => json_encode($request_data),
                    'response_data'=>json_encode($response['data']),
                    'nothi_receipt_no' => isset($response['data']['data']['dak_received_no'])?$response['data']['data']['dak_received_no']:'',
                    'response_message'=>"Something went wrong",
                    'response_code'=>$response['http_code'],
                    'process_list_id' => $processData->id,
                    'api_type' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'table_name' => $process_name,
                    'created_by' => Auth::id(),
                ]);
                DB::commit();
            }
            return $response;
        } catch (\Exception $e) {
            CommonFunction::DNothiHistoryLog([
                'request_data'=>json_encode($request_data),
                'response_data'=>json_encode($e->getMessage()),
                'response_message'=>"Something went wrong",
                'response_code'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                'table_name' => $process_name,
                'process_list_id' => $processData->id,
                'api_type' => $processData->status_id,
                'process_type_id' => $processData->process_type_id,
                'created_by'=>Auth::id(),
            ]);
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getLine(). $e->getFile(). $e->getMessage()) );
        }

    }

    public static function makeCurlRequest($url, $headers = [], $body = [], $method = 'GET', $flag = 0) {
        $curl = curl_init();
        // Set method-specific options
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            if($flag == 1){
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
            }else{
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
            }
        }

        // Set common options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Error handling
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return ['error:' => $error_msg, 'http_code' => intval($http_code), 'data' => ['']];
        } else {
            $json_start = strpos($result, '{"status":"success"');
            $json_data = substr($result, $json_start);
            $response = json_decode($json_data, true);
            curl_close($curl);
            return ['http_code' => intval($http_code), 'data' => $response];

        }
    }


    public static function DNothiHistoryLog($data){
        DNothiHistoryLogs::create($data);
    }

}
