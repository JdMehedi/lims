<?php

namespace App\Modules\REUSELicenseIssue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;

use App\Models\ConfigSetting;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\REUSELicenseIssue\Models\BPO\Amendment\Amendment;
use App\Modules\REUSELicenseIssue\Models\BPO\surrender\CallCenterSurrender;
use App\Modules\REUSELicenseIssue\Models\BWA\amendment\BWALicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\BWA\issue\BWALicenseIssue;
use App\Modules\REUSELicenseIssue\Models\BWA\renew\BWALicenseRenew;
use App\Modules\REUSELicenseIssue\Models\BWA\surrender\BWALicenseSurrender;

use App\Modules\REUSELicenseIssue\Models\ICX\amendment\ICXLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\ICX\issue\ICXLicenseIssue;
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
use App\Modules\REUSELicenseIssue\Models\IPTSP\amendment\IPTSPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\IPTSP\issue\IPTSPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\IPTSP\renew\IPTSPLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\IPTSP\surrender\IPTSPLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\ISP\amendment\ISPLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\BPO\renew\CallCenterRenew;

use App\Modules\REUSELicenseIssue\Models\ISP\issue\ISPLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\ISP\renew\ISPLicenseRenew;

use App\Modules\REUSELicenseIssue\Models\ISP\surrender\ISPLicenseSurrender;
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
use App\Modules\REUSELicenseIssue\Models\BPO\issue\CallCenterNew;
use App\Modules\REUSELicenseIssue\Models\NIX\surrender\NIXLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\NTTN\amendment\NTTNLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\NTTN\issue\NTTNLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\NTTN\renew\NTTNLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\NTTN\surrender\NTTNLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\SCS\amendment\SCSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\SCS\issue\SCSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\SCS\renew\SCSLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\SCS\surrender\SCSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\Special\issue\SpecialLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\Special\renew\SpecialLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\Special\amendment\SpecialLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\Special\surrender\SpecialLicenseSurrender;
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
use App\Modules\REUSELicenseIssue\Models\VSAT\issue\VSATLicenseIssue;

use App\Modules\REUSELicenseIssue\Models\VSAT\renew\VSATLicenseRenew;
use App\Modules\REUSELicenseIssue\Models\VSAT\surrender\VSATLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VTS\amendment\VTSLicenseAmendment;
use App\Modules\REUSELicenseIssue\Models\VTS\issue\VTSLicenseIssue;
use App\Modules\REUSELicenseIssue\Models\VTS\surrender\VTSLicenseSurrender;
use App\Modules\REUSELicenseIssue\Models\VTS\renew\VTSLicenseRenew;
use App\Modules\REUSELicenseIssue\Request\ICXLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\ICXLicenseRenewRequest;


use App\Modules\REUSELicenseIssue\Request\BPOLicenseRenewRequest;

use App\Modules\REUSELicenseIssue\Request\IIGLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\IPTSPLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\IPTSPLicenseIssueRequest;

use App\Modules\REUSELicenseIssue\Request\IPTSPLicenseSurrenderRequest;

use App\Modules\REUSELicenseIssue\Request\IPTSPLicenseRenewRequest;

use App\Modules\REUSELicenseIssue\Request\ISPLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\ISPLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\ISPLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\NIXLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\NIXLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\NIXLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\BPOLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\ICXLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\ICXLicenseSurrenderRequest;
use App\Modules\REUSELicenseIssue\Request\IGWLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\IIGLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\ITCLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\MNOLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\NIXLicenseSurrenderRequest;
use App\Modules\REUSELicenseIssue\Request\NTTNLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\NTTNLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\NTTNLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\NTTNLicenseSurrenderRequest;
use App\Modules\REUSELicenseIssue\Request\SCSLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\TVASLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\TVASLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\TVASLicenseSurrenderRequest;
use App\Modules\REUSELicenseIssue\Request\VSATLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\VSATLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\SCSLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\VSATLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\VTSLicenseAmendmentRequest;
use App\Modules\REUSELicenseIssue\Request\VTSLicenseIssueRequest;
use App\Modules\REUSELicenseIssue\Request\VTSLicenseRenewRequest;
use App\Modules\REUSELicenseIssue\Request\VTSLicenseSurrenderRequest;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\AnnualFeeInfo;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Models\SpPaymentAmountConf;
use App\Modules\SonaliPayment\Services\SPAfterPaymentManager;
use App\Modules\SonaliPayment\Services\SPPaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class ReuseController extends Controller {
    use SPPaymentManager;
    use SPAfterPaymentManager;

    public $process_type_id;
    public $acl_name;
    public $process_info;

    public function __construct( $process_type_id = 0, $process_info = '' ) {
        $this->process_type_id = $process_type_id;
        $this->acl_name        = $process_info ? $process_info->acl_name : '';
        $this->process_info    = $process_info;
    }

    public function processContentAddForm() {

        switch ( $this->process_type_id ) {
            case 1:
                return ( new ISPLicenseIssue() )->createForm( $this );
            case 2:
                return ( new ISPLicenseRenew() )->createForm( $this );
            case 3:
                return ( new ISPLicenseAmendment() )->createForm( $this );
            case 4:
                return ( new ISPLicenseSurrender() )->createForm( $this );
            case 5:
                return ( new CallCenterNew() )->createForm( $this );
            case 6:
                return ( new CallCenterRenew() )->createForm( $this );
            case 7:
                return ( new Amendment() )->createForm( $this );
            case 8:
                return ( new CallCenterSurrender() )->createForm( $this );
            case 9:
                return ( new NIXLicenseIssue() )->createForm( $this );
            case 10:
                return ( new NIXLicenseRenew() )->createForm( $this );
            case 11:
                return ( new NIXLicenseAmendment() )->createForm( $this );
            case 12:
                return ( new NIXLicenseSurrender() )->createForm( $this );
            case 13:
                return ( new VSATLicenseIssue() )->createForm( $this );
            case 14:
                return ( new VSATLicenseRenew() )->createForm( $this );
            case 15:
                return ( new VSATLicenseAmendment() )->createForm( $this );
            case 16:
                return ( new VSATLicenseSurrender() )->createForm( $this );
            case 17:
                return ( new IIGLicenseIssue() )->createForm( $this );
            case 18:
                return ( new IIGLicenseRenew() )->createForm( $this );
            case 19:
                return ( new IIGLicenseAmendment() )->createForm( $this );
            case 20:
                return ( new IIGLicenseSurrender() )->createForm( $this );
            case 21:
                return ( new IPTSPLicenseIssue() )->createForm( $this );
            case 22:
                return ( new IPTSPLicenseRenew() )->createForm( $this );
            case 23:
                return ( new IPTSPLicenseAmendment() )->createForm( $this );
            case 24:
                return ( new IPTSPLicenseSurrender() )->createForm( $this );
            case 25:
                return ( new TVASLicenseIssue() )->createForm( $this );
            case 26:
                return ( new TVASLicenseRenew() )->createForm( $this );
            case 27:
                return ( new TVASLicenseAmendment() )->createForm( $this );
            case 28:
                return ( new TVASLicenseSurrender() )->createForm( $this );
            case 29:
                return ( new VTSLicenseIssue() )->createForm( $this );
            case 30:
                return ( new VTSLicenseRenew() )->createForm( $this );
            case 33:
                return ( new ICXLicenseIssue() )->createForm( $this );
            case 34:
                return ( new ICXLicenseRenew() )->createForm( $this );
            case 35:
                return ( new ICXLicenseAmendment() )->createForm( $this );
            case 36:
                return ( new ICXLicenseSurrender() )->createForm( $this );
            case 37:
                return ( new IGWLicenseIssue() )->createForm( $this );
            case 38:
                return ( new IGWLicenseRenew() )->createForm( $this );
            case 39:
                return ( new IGWLicenseAmendment() )->createForm( $this );
            case 40:
                return ( new IGWLicenseSurrender() )->createForm( $this );
            case 50:
                return ( new NTTNLicenseIssue() )->createForm( $this );
            case 51:
                return ( new NTTNLicenseRenew() )->createForm( $this );
            case 52:
                return ( new NTTNLicenseAmendment() )->createForm( $this );
            case 53:
                return ( new NTTNLicenseSurrender() )->createForm( $this );
            case 54:
                return ( new ITCLicenseIssue() )->createForm( $this );
            case 55:
                return ( new ITCLicenseRenew() )->createForm( $this );
            case 56:
                return ( new ITCLicenseAmendment() )->createForm( $this );
            case 57:
                return ( new ITCLicenseSurrender() )->createForm( $this );
            case 58:
            return ( new MNOLicenseIssue() )->createForm( $this );
            case 59:
                return ( new MNOLicenseRenew() )->createForm( $this );
            case 60:
                return ( new MNOLicenseAmendment() )->createForm( $this );
            case 61:
                return ( new MNOLicenseSurrender() )->createForm( $this );
            case 62:
                return ( new SCSLicenseIssue() )->createForm( $this );
            case 63:
                return ( new SCSLicenseRenew() )->createForm( $this );
            case 64:
                return ( new SCSLicenseAmendment() )->createForm( $this );
            case 65:
                return ( new SCSLicenseSurrender() )->createForm( $this );
            case 66:
                return ( new TCLicenseIssue() )->createForm( $this );
            case 67:
                return ( new TCLicenseRenew() )->createForm( $this );
            case 68:
                return ( new TCLicenseAmendment() )->createForm( $this );
            case 69:
                return ( new TCLicenseSurrender() )->createForm( $this );
            case 70:
                return ( new MNPLicenseIssue() )->createForm( $this );
            case 71:
                return ( new MNPLicenseRenew() )->createForm( $this );
            case 72:
                return ( new MNPLicenseAmendment() )->createForm( $this );
            case 73:
                return ( new MNPLicenseSurrender() )->createForm( $this );
            case 74:
                return ( new BWALicenseIssue() )->createForm( $this );
            case 75:
                return ( new BWALicenseRenew() )->createForm( $this );
            case 76:
                return ( new BWALicenseAmendment() )->createForm( $this );
            case 77:
                return ( new BWALicenseSurrender() )->createForm( $this );
            case 78:
                return ( new SSLicenseIssue() )->createForm( $this );
            case 79:
                return ( new SSLicenseRenew() )->createForm( $this );
            case 80:
                return ( new SSLicenseAmendment() )->createForm( $this );
            case 81:
                return ( new SSLicenseSurrender() )->createForm( $this );
            case 82:
                return ( new VTSLicenseRenew() )->createForm( $this );
            case 83:
                return ( new VTSLicenseAmendment() )->createForm( $this );
            case 84:
                return ( new VTSLicenseSurrender() )->createForm( $this );
            default:
                return false;
        }
    }

    /**
     * @param $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function processContentStore($request ) {
        // Set permission mode and check ACL
        $app_id = ( ! empty( $request->get( 'app_id' ) ) ? Encryption::decodeId( $request->get( 'app_id' ) ) : '' );
        $mode   = ( ! empty( $request->get( 'app_id' ) ) ? '-E-' : '-A-' );

        if ( ! ACL::getAccsessRight( $this->acl_name, $mode, $app_id ) ) {
            abort( '400', "You have no access right! Please contact with system admin if you have any query." );
        }

        // Request Validation
        // $this->validateRequest( $request );

        try {
            DB::beginTransaction();
            switch ( $this->process_type_id ) {
                case 1:
                    return ( new ISPLicenseIssue() )->storeForm( $request, $this );
                case 2:
                    return ( new ISPLicenseRenew() )->storeForm( $request, $this );
                case 3:
                    return ( new ISPLicenseAmendment() )->storeForm( $request, $this );
                case 4:
                    return ( new ISPLicenseSurrender() )->storeForm( $request, $this );
                case 5:
                    return ( new CallCenterNew() )->storeForm( $request, $this );
                case 6:
                    return ( new CallCenterRenew() )->storeForm( $request, $this );
                case 7:
                    return ( new Amendment() )->storeForm( $request, $this );
                case 8:
                    return ( new CallCenterSurrender() )->storeForm( $request, $this );
                case 9:
                    return ( new NIXLicenseIssue() )->storeForm( $request, $this );
                case 10:
                    return ( new NIXLicenseRenew() )->storeForm( $request, $this );
                case 11:
                    return ( new NIXLicenseAmendment() )->storeForm( $request, $this );
                case 12:
                    return ( new NIXLicenseSurrender() )->storeForm( $request, $this );
                case 13:
                    return ( new VSATLicenseIssue() )->storeForm( $request, $this );
                case 14:
                    return ( new VSATLicenseRenew() )->storeForm( $request, $this );
                case 15:
                    return ( new VSATLicenseAmendment() )->storeForm( $request, $this );
                case 16:
                    return ( new VSATLicenseSurrender() )->storeForm( $request, $this );
                case 17:
                    return ( new IIGLicenseIssue())->storeForm( $request, $this );
                case 18:
                    return ( new IIGLicenseRenew())->storeForm( $request, $this );
                case 19:
                    return ( new IIGLicenseAmendment())->storeForm( $request, $this );
                case 20:
                    return ( new IIGLicenseSurrender())->storeForm( $request, $this );
                case 21:
                    return ( new IPTSPLicenseIssue() )->storeForm( $request, $this );
                case 22:
                    return ( new IPTSPLicenseRenew() )->storeForm( $request, $this );
                case 23:
                    return ( new IPTSPLicenseAmendment() )->storeForm( $request, $this );
                case 24:
                    return ( new IPTSPLicenseSurrender() )->storeForm( $request, $this );
                case 25:
                    return ( new TVASLicenseIssue() )->storeForm( $request, $this );
                case 26:
                    return ( new TVASLicenseRenew() )->storeForm( $request, $this );
                case 27:
                    return ( new TVASLicenseAmendment() )->storeForm( $request, $this );
                case 28:
                    return ( new TVASLicenseSurrender() )->storeForm( $request, $this );
                case 29:
                    return ( new VTSLicenseIssue() )->storeForm( $request, $this );
                case 30:
                    return ( new VTSLicenseRenew() )->storeForm( $request, $this );
                case 33:
                    return ( new ICXLicenseIssue() )->storeForm( $request, $this );
                case 34:
                    return ( new ICXLicenseRenew() )->storeForm( $request, $this );
                case 35:
                    return ( new ICXLicenseAmendment() )->storeForm( $request, $this );
                case 36:
                    return ( new ICXLicenseSurrender() )->storeForm( $request, $this );
                case 37:
                    return ( new IGWLicenseIssue() )->storeForm( $request, $this );
                case 38:
                    return ( new IGWLicenseRenew() )->storeForm( $request, $this );
                case 39:
                    return ( new IGWLicenseAmendment() )->storeForm( $request, $this );
                case 40:
                    return ( new IGWLicenseSurrender() )->storeForm( $request, $this );
                case 50:
                    return ( new NTTNLicenseIssue() )->storeForm( $request, $this );
                case 51:
                    return ( new NTTNLicenseRenew() )->storeForm( $request, $this );
                case 52:
                    return ( new NTTNLicenseAmendment() )->storeForm( $request, $this );
                case 53:
                    return ( new NTTNLicenseSurrender() )->storeForm( $request, $this );
                case 54:
                    return ( new ITCLicenseIssue() )->storeForm( $request, $this );
                case 55:
                    return ( new ITCLicenseRenew() )->storeForm( $request, $this );
                case 56:
                    return ( new ITCLicenseAmendment() )->storeForm( $request, $this );
                case 57:
                    return ( new ITCLicenseSurrender() )->storeForm( $request, $this );
                case 58:
                    return ( new MNOLicenseIssue() )->storeForm( $request, $this );
                case 59:
                    return ( new MNOLicenseRenew() )->storeForm( $request, $this );
                case 60:
                    return ( new MNOLicenseAmendment() )->storeForm( $request, $this );
                case 61:
                    return ( new MNOLicenseSurrender() )->storeForm( $request, $this );
                case 62:
                    return ( new SCSLicenseIssue() )->storeForm( $request, $this );
                case 63:
                    return ( new SCSLicenseRenew() )->storeForm( $request, $this );
                case 64:
                    return ( new SCSLicenseAmendment() )->storeForm( $request, $this );
                case 65:
                    return ( new SCSLicenseSurrender() )->storeForm( $request, $this );
                case 66:
                    return ( new TCLicenseIssue() )->storeForm( $request, $this );

                case 67:
                    return ( new TCLicenseRenew() )->storeForm( $request, $this );
                case 68:
                    return ( new TCLicenseAmendment() )->storeForm( $request, $this );
                case 69:
                    return ( new TCLicenseSurrender() )->storeForm( $request, $this );
                case 70:
                    return ( new MNPLicenseIssue() )->storeForm( $request, $this );

                case 71:
                    return ( new MNPLicenseRenew() )->storeForm( $request, $this );
                case 72:
                    return ( new MNPLicenseAmendment() )->storeForm( $request, $this );
                case 73:
                    return ( new MNPLicenseSurrender() )->storeForm( $request, $this );
                case 74:
                    return ( new BWALicenseIssue() )->storeForm( $request, $this );

                case 75:
                    return ( new BWALicenseRenew() )->storeForm( $request, $this );
                case 76:
                    return ( new BWALicenseAmendment() )->storeForm( $request, $this );
                case 77:
                    return (new BWALicenseSurrender())->storeForm($request, $this);
                case 78:
                    return ( new SSLicenseIssue() )->storeForm( $request, $this );
                case 79:
                    return ( new SSLicenseRenew() )->storeForm( $request, $this );
                case 80:
                    return ( new SSLicenseAmendment() )->storeForm( $request, $this );
                case 81:
                    return ( new SSLicenseSurrender() )->storeForm( $request, $this );
                case 82:
                    //
                case 83:
                    return ( new VTSLicenseAmendment() )->storeForm( $request, $this );
                case 84:
                    return ( new VTSLicenseSurrender() )->storeForm( $request, $this );
                default:
                    return false;
            }


        } catch ( \Exception $e ) {
            DB::rollback();
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash( 'error', CommonFunction::showErrorPublic( $e->getMessage() ) . '[IN-1025]' );

            return redirect()->back()->withInput();
        }
    }

    public function processContentEdit( $applicationId, $openMode = '', $request ) {
        if ( ! $request->ajax() ) {
            return 'Sorry! this is a request without proper way.';
        }

        if ( ! ACL::getAccsessRight( $this->acl_name, '-A-' ) ) {
            return response()->json( [
                'responseCode' => 1,
                'html'         => 'You have no access right! Contact with system admin for more information'
            ] );
        }

        $decoded_process_type_id = Encryption::decodeId( $this->process_type_id );
        try {
            switch ( intval( $decoded_process_type_id ) ) {
                case 1:
                    return ( new ISPLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 2:
                    return ( new ISPLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 3:
                    return ( new ISPLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 4:
                    return ( new ISPLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 5:
                    return ( new CallCenterNew() )->editForm( $decoded_process_type_id, $applicationId );
                case 6:
                    return ( new CallCenterRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 7:
                    return ( new Amendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 8:
                    return ( new CallCenterSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 9:
                    return ( new NIXLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 10:
                    return ( new NIXLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 11:
                    return ( new NIXLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 12:
                    return ( new NIXLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 13:
                    return ( new VSATLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 14:
                    return ( new VSATLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 15:
                    return ( new VSATLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 16:
                    return ( new VSATLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 17:
                    return ( new IIGLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 18:
                    return ( new IIGLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 19:
                    return ( new IIGLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 20:
                    return ( new IIGLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 21:
                    return ( new IPTSPLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 22:
                    return ( new IPTSPLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 23:
                    return ( new IPTSPLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 24:
                    return ( new IPTSPLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 25:
                    return ( new TVASLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 26:
                    return ( new TVASLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 27:
                    return ( new TVASLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 28:
                    return ( new TVASLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 29:
                    return ( new VTSLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 30:
                    return ( new VTSLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 33:
                    return ( new ICXLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 34:
                    return ( new ICXLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 35:
                    return ( new ICXLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 36:
                    return ( new ICXLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 37:
                    return ( new IGWLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 38:
                    return ( new IGWLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 39:
                    return ( new IGWLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 40:
                    return ( new IGWLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 50:
                    return ( new NTTNLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 51:
                    return ( new NTTNLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 52:
                    return ( new NTTNLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 53:
                    return ( new NTTNLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 54:
                    return ( new ITCLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 55:
                    return ( new ITCLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 56:
                    return ( new ITCLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 57:
                    return ( new ITCLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 58:
                    return ( new MNOLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 59:
                    return ( new MNOLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 60:
                    return ( new MNOLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 61:
                    return ( new MNOLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 62:
                    return ( new SCSLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 63:
                    return ( new SCSLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 64:
                    return ( new SCSLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 65:
                    return ( new SCSLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 66:
                    return ( new TCLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 67:
                    return ( new TCLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );

                case 68:
                    return ( new TCLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 69:
                    return ( new TCLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 70:
                    return ( new MNPLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 71:
                    return ( new MNPLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 72:
                    return ( new MNPLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 73:
                    return ( new MNPLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 74:
                    return ( new BWALicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 75:
                    return ( new BWALicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 76:
                    return ( new BWALicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 77:
                    return (new BWALicenseSurrender())->editForm( $decoded_process_type_id, $applicationId );
                case 78:
                    return ( new SSLicenseIssue() )->editForm( $decoded_process_type_id, $applicationId );
                case 79:
                    return ( new SSLicenseRenew() )->editForm( $decoded_process_type_id, $applicationId );
                case 80:
                    return ( new SSLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 81:
                    return ( new SSLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                case 82:
                    //
                case 83:
                    return ( new VTSLicenseAmendment() )->editForm( $decoded_process_type_id, $applicationId );
                case 84:
                    return ( new VTSLicenseSurrender() )->editForm( $decoded_process_type_id, $applicationId );
                default:
                    return false;
            }
        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash( 'error', CommonFunction::showErrorPublic( $e->getMessage() ) . '[IN-1025]' );

            return redirect()->back()->withInput();
        }
    }

    public function precessContentView( $appId, $openMode = '', $request, $process_info = '' ) {
        if ( ! ACL::getAccsessRight( $this->acl_name, '-V-' ) ) {
            return response()->json( [
                'responseCode' => 0,
                'html'         => '<h4>You have no access right! Contact with system admin for more information. [BRC-974]</h4>'
            ] );
        }

        if($process_info->is_special==1){

           if($process_info->type==1){
            return ( new SpecialLicenseIssue() )->viewForm( $this->process_type_id, $appId );
           }elseif($process_info->type==2){
            return ( new SpecialLicenseRenew() )->viewForm( $this->process_type_id, $appId );
           }elseif($process_info->type==3){
             return ( new SpecialLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
           }elseif($process_info->type==4){
             return ( new SpecialLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
           }

        }
        switch ( intval( $this->process_type_id ) ) {
            case 1:
                return ( new ISPLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 2:
                return ( new ISPLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 3:
                return ( new ISPLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 4:
                return ( new ISPLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 5:
                return ( new CallCenterNew() )->viewForm( $this->process_type_id, $appId );
            case 6:
                return ( new CallCenterRenew() )->viewForm( $this->process_type_id, $appId );
            case 7:
                return ( new Amendment() )->viewForm( $this->process_type_id, $appId );
            case 8:
                return ( new CallCenterSurrender() )->viewForm( $this->process_type_id, $appId );
            case 9:
                return ( new NIXLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 10:
                return ( new NIXLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 11:
                return ( new NIXLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 12:
                return ( new NIXLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 13:
                return ( new VSATLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 14:
                return ( new VSATLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 15:
                return ( new VSATLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 16:
                return ( new VSATLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 17:
                return ( new IIGLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 18:
                return ( new IIGLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 19:
                return ( new IIGLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 20:
                return ( new IIGLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 21:
                return ( new IPTSPLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 22:
                return ( new IPTSPLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 23:
                return ( new IPTSPLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 24:
                return ( new IPTSPLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 25:
                return ( new TVASLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 26:
                return ( new TVASLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 27:
                return ( new TVASLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 28:
                return ( new TVASLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 29:
                return ( new VTSLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 30:
                return ( new VTSLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 33:
                return ( new ICXLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 34:
                return ( new ICXLicenseRenew() )->viewForm( $this->process_type_id, $appId );

            case 35:
                return ( new ICXLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 36:
                return ( new ICXLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 37:
                return ( new IGWLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 38:
                return ( new IGWLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 39:
                return ( new IGWLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 40:
                return ( new IGWLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 50:
                return ( new NTTNLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 51:
                return ( new NTTNLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 52:
                return ( new NTTNLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 53:
                return ( new NTTNLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 54:
                return ( new ITCLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 55:
                return ( new ITCLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 56:
                return ( new ITCLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 57:
                return ( new ITCLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 58:
                return ( new MNOLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 59:
                return ( new MNOLicenseRenew() )->viewForm( $this->process_type_id, $appId );

            case 60:
                return ( new MNOLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 61:
                return ( new MNOLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 62:
                return ( new SCSLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 63:
                return ( new SCSLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 64:
                return ( new SCSLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 65:
                return ( new SCSLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 66:
                return ( new TCLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 67:
                return ( new TCLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 68:
                return ( new TCLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 69:
                return ( new TCLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 70:
                return ( new MNPLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 71:
                return ( new MNPLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 72:
                return ( new MNPLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 73:
                return ( new MNPLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 74:
                return ( new BWALicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 75:
                return ( new BWALicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 76:
                return ( new BWALicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 77:
                return (new BWALicenseSurrender())->viewForm( $this->process_type_id, $appId );
            case 78:
            return ( new SSLicenseIssue() )->viewForm( $this->process_type_id, $appId );
            case 79:
                return ( new SSLicenseRenew() )->viewForm( $this->process_type_id, $appId );
            case 80:
                return ( new SSLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 81:
                return ( new SSLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            case 82:
                //
            case 83:
                return ( new VTSLicenseAmendment() )->viewForm( $this->process_type_id, $appId );
            case 84:
                return ( new VTSLicenseSurrender() )->viewForm( $this->process_type_id, $appId );
            default:
                return false;
        }
    }

    public function preview() {


        switch ( $this->process_type_id ) {
            case 1:
                $public_html = view( 'REUSELicenseIssue::ISP.Issue.masterPreview' );
                break;
            case 2:
                $public_html = view( 'REUSELicenseIssue::ISP.Renew.preview' );
                break;
            case 3:
                $public_html = view( 'REUSELicenseIssue::ISP.Amendment.masterPreview' );
                break;
            case 4:
                $public_html = view( 'REUSELicenseIssue::ISP.Surrender.preview' );
                break;
            case 5:
                $public_html = view( 'REUSELicenseIssue::BPO.Issue.masterPreview' );
                break;
            case 6:
                $public_html = view( 'REUSELicenseIssue::BPO.Renew.preview' );
                break;
            case 7:
                $public_html = (string) view( 'REUSELicenseIssue::BPO.Amendment.masterPreview' );
                break;
            case 8:
                $public_html = (string) view( 'REUSELicenseIssue::BPO.Surrender.preview' );
                break;
            case 9:
                $public_html = view( 'REUSELicenseIssue::NIX.Issue.masterPreview' );
                break;
            case 10:
                $public_html = view( 'REUSELicenseIssue::NIX.Renew.masterPreview' );
                break;
            case 11:
                $public_html = view( 'REUSELicenseIssue::NIX.Amendment.masterPreview' );
                break;
            case 12:
                $public_html = view( 'REUSELicenseIssue::NIX.Surrender.preview' );
                break;

            case 13:
                $public_html = view( 'REUSELicenseIssue::VSAT.Issue.masterPreview' );
                break;
            case 14:
                $public_html = view( 'REUSELicenseIssue::VSAT.Renew.preview' );
                break;
            case 15:
                $public_html = view( 'REUSELicenseIssue::VSAT.Amendment.masterPreview' );
                break;
            case 16:
                $public_html = view( 'REUSELicenseIssue::VSAT.Surrender.preview' );
                break;
            case 17:
                $public_html = view( 'REUSELicenseIssue::IIG.Issue.masterPreview' );
                break;
            case 18:
                $public_html = view( 'REUSELicenseIssue::IIG.Renew.masterPreview' );
                break;

            case 19:
                $public_html = view( 'REUSELicenseIssue::IIG.Amendment.masterPreview' );
                break;
            case 20:
                $public_html = view( 'REUSELicenseIssue::IIG.Surrender.preview' );
                break;
            case 21:
                $public_html = view( 'REUSELicenseIssue::IPTSP.Issue.masterPreview' );
                break;
            case 22:
                $public_html = view( 'REUSELicenseIssue::IPTSP.Renew.preview' );
                break;
            case 23:
                $public_html = view( 'REUSELicenseIssue::IPTSP.Amendment.masterPreview' );
                break;
            case 24:
                $public_html = view( 'REUSELicenseIssue::IPTSP.Surrender.preview' );
                break;
            case 25:
                $public_html = view( 'REUSELicenseIssue::TVAS.Issue.masterPreview' );
                break;
            case 26:
                $public_html = view( 'REUSELicenseIssue::TVAS.Renew.preview' );
                break;
            case 27:
                $public_html = view( 'REUSELicenseIssue::TVAS.Amendment.masterPreview' );
                break;
            case 28:
                $public_html = view( 'REUSELicenseIssue::TVAS.Surrender.preview' );
                break;
            case 29:
                $public_html = view( 'REUSELicenseIssue::VTS.Issue.masterPreview' );
                break;
            case 30:
                $public_html = view( 'REUSELicenseIssue::VTS.Renew.preview' );
                break;
            case 33:
                $public_html = view( 'REUSELicenseIssue::ICX.Issue.masterPreview' );
                break;
            case 34:
                $public_html = view( 'REUSELicenseIssue::ICX.Renew.preview' );
                break;
            case 35:
                $public_html = view( 'REUSELicenseIssue::ICX.Amendment.masterPreview' );
                break;
            case 36:
                $public_html = view( 'REUSELicenseIssue::ICX.Surrender.preview' );
                break;
            case 37:
                $public_html = view( 'REUSELicenseIssue::IGW.Issue.preview' );
                break;
            case 38:
                $public_html = view( 'REUSELicenseIssue::IGW.Renew.preview' );
                break;
            case 39:
                $public_html = view( 'REUSELicenseIssue::IGW.Amendment.preview' );
                break;
            case 40:
                $public_html = view( 'REUSELicenseIssue::IGW.Surrender.preview' );
                break;

            case 50:
                $public_html = view( 'REUSELicenseIssue::NTTN.Issue.masterPreview' );
                break;
            case 51:
                $public_html = view( 'REUSELicenseIssue::NTTN.Renew.preview' );
                break;
            case 52:
                $public_html = view( 'REUSELicenseIssue::NTTN.Amendment.masterPreview' );
                break;

            case 53:
                $public_html = view( 'REUSELicenseIssue::NTTN.Surrender.preview' );
                break;
            case 54:
                $public_html = view( 'REUSELicenseIssue::ITC.Issue.masterPreview' );
                break;

            case 55:
                $public_html = view( 'REUSELicenseIssue::ITC.Renew.preview' );
                break;

            case 56:
                $public_html = view( 'REUSELicenseIssue::ITC.Amendment.masterPreview' );
                break;

            case 57:
                $public_html = view( 'REUSELicenseIssue::ITC.Surrender.preview' );
                break;

            case 58:
                $public_html = view( 'REUSELicenseIssue::MNO.Issue.masterPreview' );
                break;

            case 59:
                $public_html = view( 'REUSELicenseIssue::MNO.Renew.preview' );
                break;

            case 60:
                $public_html = view( 'REUSELicenseIssue::MNO.Amendment.masterPreview' );
                break;

            case 61:
                $public_html = view( 'REUSELicenseIssue::MNO.Surrender.preview' );
                break;

            case 62:
                $public_html = view( 'REUSELicenseIssue::SCS.Issue.masterPreview' );
                break;

            case 63:
                $public_html = view( 'REUSELicenseIssue::SCS.Renew.preview' );
                break;

            case 64:
                $public_html = view( 'REUSELicenseIssue::SCS.Amendment.masterPreview' );
                break;

            case 65:
                $public_html = view( 'REUSELicenseIssue::SCS.Surrender.preview' );
                break;

            case 66:
                $public_html = view( 'REUSELicenseIssue::TC.Issue.preview' );
                break;

            case 67:
                $public_html = view( 'REUSELicenseIssue::TC.Renew.preview' );
                break;

            case 68:
                $public_html = view( 'REUSELicenseIssue::TC.Amendment.masterPreview' );
                break;

            case 69:
                $public_html = view( 'REUSELicenseIssue::TC.Surrender.preview' );
                break;

            case 70:
                $public_html = view( 'REUSELicenseIssue::MNP.Issue.preview' );
                break;

            case 71:
                $public_html = view( 'REUSELicenseIssue::MNP.Renew.preview' );
                break;

            case 72:
                $public_html = view( 'REUSELicenseIssue::MNP.Amendment.masterPreview' );
                break;

            case 73:
                $public_html = view( 'REUSELicenseIssue::MNP.Surrender.preview' );
                break;

            case 74:
                $public_html = view( 'REUSELicenseIssue::BWA.Issue.masterPreview' );
                break;

            case 75:
                $public_html = view( 'REUSELicenseIssue::BWA.Renew.preview' );
                break;

            case 76:
                $public_html = view( 'REUSELicenseIssue::BWA.Amendment.masterPreview' );
                break;

            case 77:
                $public_html = view( 'REUSELicenseIssue::BWA.Surrender.preview' );
                break;
            case 78:
                $public_html = view( 'REUSELicenseIssue::SS.Issue.masterPreview' );
            break;
            case 79:
                $public_html = view( 'REUSELicenseIssue::SS.Renew.preview' );
                break;

            case 80:
                $public_html = view( 'REUSELicenseIssue::SS.Amendment.masterPreview' );
                break;

            case 81:
                //

            case 82:
                //

            case 83:
                $public_html = view( 'REUSELicenseIssue::VTS.Amendment.masterPreview' );
                break;
            case 84:
                $public_html = view( 'REUSELicenseIssue::VTS.Surrender.masterPreview' );
                break;
            default:
                return false;
        }

        return $public_html;
    }

    public function unfixedAmountsForGovtServiceFee( $isp_license_type, $payment_step_id, $app_id = 0, $process_type_id = 0, $division_id = 0 ) {
        date_default_timezone_set( "Asia/Dhaka" );
        $this->process_type_id = $process_type_id;
        $vat_percentage        = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
        if ( empty( $vat_percentage ) ) {
            DB::rollBack();
            Session::flash( 'error', 'Please, configure the value for VAT.[INR-1026]' );

            return redirect()->back()->withInput();
        }
        $unfixed_amount_array = [];
        switch ($this->process_type_id) {
            case 1:
                $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
                    'process_type_id' => $this->process_type_id,
                    'payment_step_id' => $payment_step_id,
                    'license_type_id' => $isp_license_type,
                    'status'          => 1,
                ] )->first();

                if ( $payment_step_id == 1 ) {
                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => 0, //govt-annual-fee
                        8  => 0, //govt-delay-fee
                        9  => 0, //govt-annual-vat-feef
                        10 => 0 //govt-delay-vat-fee
                    ];
                } elseif ( $payment_step_id == 2 ) {

                    $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 3,
                        'license_type_id' => $isp_license_type,
                        'status'          => 1,
                    ] )->first();

                    //TODO:: delay fee calculation
                    $submissionPaymentData = SonaliPayment::where( [
                        'app_id'          => $app_id,
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 1,
                        'payment_status'  => 1
                    ] )->first( [ 'updated_at' ] ); // Submission payment date

                    $delay_fee                 = 0;
                    $delay_vat_fee             = 0;
                    $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date( 'Y-m-d', strtotime( $submissionPaymentData->updated_at ) ) : date( 'Y-m-d' );
                    $currentDateTime           = date( 'Y-m-d', strtotime( '-1 year' ) );

                    if ( $currentDateTime > $submissionPaymentDateTime ) {
                        $yarly_delay_fee = ( ( $SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount ) * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $submissionPaymentDateTime ) );
                        $delay_day_count = abs($date_diff->format( '%r%a' ));
                        $delay_fee       = $delay_day_count * $daily_delay_fee;
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $spPaymentAmountforAnnualFee->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];

                } elseif ( in_array( $payment_step_id, [ 3, 4, 5, 6 ] ) ) {
                    //TODO::Delay fee calculation
                    $annualFeeData = AnnualFeeInfo::where( [
                        'process_type_id' => $this->process_type_id,
                        'app_id'          => $app_id,
                        'status'          => 0
                    ] )->first();

                    $delay_fee       = 0;
                    $delay_vat_fee   = 0;
                    $paymentLastDate = strval( $annualFeeData->payment_due_date );
                    $currentDateTime = date( 'Y-m-d' );
                    if ( $currentDateTime > $paymentLastDate ) {
                        $yarly_delay_fee = ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $paymentLastDate ) );
                        $delay_day_count = abs($date_diff->format( '%r%a' ));
                        $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => 0, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => 0, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];
                }
                break;
            case 2:
                $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
                    'process_type_id' => $this->process_type_id,
                    'payment_step_id' => $payment_step_id,
                    'license_type_id' => $isp_license_type,
                    'status'          => 1,
                ] )->first();

                if ( $payment_step_id == 1 ) {
                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => 0, //govt-annual-fee
                        8  => 0, //govt-delay-fee
                        9  => 0, //govt-annual-vat-feef
                        10 => 0 //govt-delay-vat-fee
                    ];
                } elseif ( $payment_step_id == 2 ) {

                    $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 3,
                        'license_type_id' => $isp_license_type,
                        'status'          => 1,
                    ] )->first();

                    //TODO:: delay fee calculation
                    $submissionPaymentData = SonaliPayment::where( [
                        'app_id'          => $app_id,
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 1,
                        'payment_status'  => 1
                    ] )->first( [ 'updated_at' ] ); // Submission payment date

                    $delay_fee                 = 0;
                    $delay_vat_fee             = 0;
                    $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date( 'Y-m-d', strtotime( $submissionPaymentData->updated_at ) ) : date( 'Y-m-d' );
                    $currentDateTime           = date( 'Y-m-d', strtotime( '-1 year' ) );

                    if ( $currentDateTime > $submissionPaymentDateTime ) {
                        $yarly_delay_fee = ( ( $SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount ) * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $submissionPaymentDateTime ) );
                        $delay_day_count = abs($date_diff->format( '%r%a' ));
                        $delay_fee       = $delay_day_count * $daily_delay_fee;
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $spPaymentAmountforAnnualFee->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];

                } elseif ( in_array( $payment_step_id, [ 3, 4, 5, 6 ] ) ) {
                    //TODO::Delay fee calculation
                    $annualFeeData = AnnualFeeInfo::where( [
                        'process_type_id' => $this->process_type_id,
                        'app_id'          => $app_id,
                        'status'          => 0
                    ] )->first();

                    $delay_fee       = 0;
                    $delay_vat_fee   = 0;
                    $paymentLastDate = strval( $annualFeeData->payment_due_date );
                    $currentDateTime = date( 'Y-m-d' );
                    if ( $currentDateTime > $paymentLastDate ) {
                        $yarly_delay_fee = ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $paymentLastDate ) );
                        $delay_day_count = abs($date_diff->format( '%r%a'));
                        $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => 0, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => 0, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];
                }
                break;
            case 21:
                $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
                    'process_type_id' => $this->process_type_id,
                    'payment_step_id' => $payment_step_id,
                    'license_type_id' => $isp_license_type,
                    'status'          => 1,
                ] )->When(in_array($division_id, [2, 14]), function ($query) use ($division_id) {
                    return $query->where('division_id', $division_id);
                })->first();

                if ( $payment_step_id == 1 ) {
                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => 0, //govt-annual-fee
                        8  => 0, //govt-delay-fee
                        9  => 0, //govt-annual-vat-feef
                        10 => 0 //govt-delay-vat-fee
                    ];
                } elseif ( $payment_step_id == 2 ) {

                    $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 3,
                        'license_type_id' => $isp_license_type,
                        'status'          => 1,
                    ] )->first();

                    //TODO:: delay fee calculation
                    $submissionPaymentData = SonaliPayment::where( [
                        'app_id'          => $app_id,
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 1,
                        'payment_status'  => 1
                    ] )->first( [ 'updated_at' ] ); // Submission payment date

                    $delay_fee                 = 0;
                    $delay_vat_fee             = 0;
                    $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date( 'Y-m-d', strtotime( $submissionPaymentData->updated_at ) ) : date( 'Y-m-d' );
                    $currentDateTime           = date( 'Y-m-d', strtotime( '-1 year' ) );

                    if ( $currentDateTime > $submissionPaymentDateTime ) {
                        $yarly_delay_fee = ( ( $SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount ) * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $submissionPaymentDateTime ) );
                        $delay_day_count = abs($date_diff->format( '%r%a'));
                        $delay_fee       = $delay_day_count * $daily_delay_fee;
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $spPaymentAmountforAnnualFee->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];

                } elseif ( in_array( $payment_step_id, [ 3, 4, 5, 6 ] ) ) {
                    //TODO::Delay fee calculation
                    $annualFeeData = AnnualFeeInfo::where( [
                        'process_type_id' => $this->process_type_id,
                        'app_id'          => $app_id,
                        'status'          => 0
                    ] )->first();

                    $delay_fee       = 0;
                    $delay_vat_fee   = 0;
                    $paymentLastDate = strval( $annualFeeData->payment_due_date );
                    $currentDateTime = date( 'Y-m-d' );
                    if ( $currentDateTime > $paymentLastDate ) {
                        $yarly_delay_fee = ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $paymentLastDate ) );
                        $delay_day_count = abs($date_diff->format( '%r%a'));
                        $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => 0, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => 0, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];
                }
                break;
            case 22:
                $SpPaymentAmountConfData = SpPaymentAmountConf::where( [
                    'process_type_id' => $this->process_type_id,
                    'payment_step_id' => $payment_step_id,
                    'license_type_id' => $isp_license_type,
                    'status'          => 1,
                ] )->first();

                if ( $payment_step_id == 1 ) {
                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => 0, //govt-annual-fee
                        8  => 0, //govt-delay-fee
                        9  => 0, //govt-annual-vat-feef
                        10 => 0 //govt-delay-vat-fee
                    ];
                } elseif ( $payment_step_id == 2 ) {

                    $spPaymentAmountforAnnualFee = SpPaymentAmountConf::where( [
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 3,
                        'license_type_id' => $isp_license_type,
                        'status'          => 1,
                    ] )->first();

                    //TODO:: delay fee calculation
                    $submissionPaymentData = SonaliPayment::where( [
                        'app_id'          => $app_id,
                        'process_type_id' => $this->process_type_id,
                        'payment_step_id' => 1,
                        'payment_status'  => 1
                    ] )->first( [ 'updated_at' ] ); // Submission payment date

                    $delay_fee                 = 0;
                    $delay_vat_fee             = 0;
                    $submissionPaymentDateTime = !empty($submissionPaymentData->updated_at) ? date( 'Y-m-d', strtotime( $submissionPaymentData->updated_at ) ) : date( 'Y-m-d' );
                    $currentDateTime           = date( 'Y-m-d', strtotime( '-1 year' ) );

                    if ( $currentDateTime > $submissionPaymentDateTime ) {
                        $yarly_delay_fee = ( ( $SpPaymentAmountConfData->pay_amount + $spPaymentAmountforAnnualFee->pay_amount ) * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $submissionPaymentDateTime ) );
                        $delay_day_count = abs($date_diff->format( '%r%a'));
                        $delay_fee       = $delay_day_count * $daily_delay_fee;
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => $SpPaymentAmountConfData->pay_amount, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $spPaymentAmountforAnnualFee->pay_amount, //1st year govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $spPaymentAmountforAnnualFee->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];

                } elseif ( in_array( $payment_step_id, [ 3, 4, 5, 6 ] ) ) {
                    //TODO::Delay fee calculation
                    $annualFeeData = AnnualFeeInfo::where( [
                        'process_type_id' => $this->process_type_id,
                        'app_id'          => $app_id,
                        'status'          => 0
                    ] )->first();

                    $delay_fee       = 0;
                    $delay_vat_fee   = 0;
                    $paymentLastDate = strval( $annualFeeData->payment_due_date );
                    $currentDateTime = date( 'Y-m-d' );
                    if ( $currentDateTime > $paymentLastDate ) {
                        $yarly_delay_fee = ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100; // 15% delay fee after all
                        $daily_delay_fee = $yarly_delay_fee / 365;
                        $date_diff       = date_diff( date_create( $currentDateTime ), date_create( $paymentLastDate ) );
                        $delay_day_count = abs($date_diff->format( '%r%a'));
                        $delay_fee       = $delay_day_count * $daily_delay_fee; // 15% delay fee after all
                        $delay_vat_fee   = ($delay_fee * $vat_percentage) / 100; // 15% vat over delay fee
                    }

                    $unfixed_amount_array = [
                        1  => 0, // Vendor-Service-Fee
                        2  => 0, // Govt-Service-Fee
                        3  => 0, // Govt. Application Fee
                        4  => 0, // Vendor-Vat-Fee
                        5  => 0, // Govt-Vat-Fee
                        6  => 0, //govt-vendor-vat-fee
                        7  => $SpPaymentAmountConfData->pay_amount, //govt-annual-fee
                        8  => $delay_fee, //govt-delay-fee
                        9  => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, //govt-annual-vat-fee
                        10 => $delay_vat_fee //govt-delay-vat-fee
                    ];
                }
                break;
        }

        return $unfixed_amount_array;
    }

    public function unfixedAmountsForGovtApplicationFee( $isp_license_type, $payment_step_id, $process_type_id = 0, $division_id = 0 ) {

        $this->process_type_id = $process_type_id;
        $vat_percentage        = Configuration::where( 'caption', 'GOVT_VENDOR_VAT_FEE' )->value( 'value' );
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


        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => $SpPaymentAmountConfData->pay_amount, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => ( $SpPaymentAmountConfData->pay_amount * $vat_percentage ) / 100, // Govt-Vat-Fee
            6 => 0 //govt-vendor-vat-fee
        ];

        return $unfixed_amount_array;
    }


    public function guidelines() {
        return redirect()->away( env( 'GUIDELINE_URL', 'http://btrc.portal.gov.bd/sites/default/files/files/btrc.portal.gov.bd/page/1c1ea1c0_f8ef_4cdf_9005_d8a34b9ca554/2022-08-22-04-48-d209d32d1b4d7b5b7031b990b6681752.pdf' ) );
    }

    public function getPaymentDataByLicense( $request ) {
        $payment_type    = $request->payment_type;
        $license_type    = $request->license_type;
        $process_type_id = $request->process_type_id;
        $division_id = $request->division_id;

        if ( ! $payment_type || ! $license_type || ! $process_type_id ) {
            return response()->json( [
                'responseCode' => - 1,
                'msg'          => 'Process type, Payment type and license type need to be provided.',
                'data'         => []
            ] );
        }

        $unfixed_amount_array = $this->unfixedAmountsForGovtServiceFee( $license_type, $payment_type, '', $process_type_id, $division_id );
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

    public function addRow( Request $request ) {
        $data['row_id'] = intval( $request->lastRowId ) + 1;
        $data['type']   = $request->tableType;
        $public_html    = (string) view( 'common.subviews.table_row', $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function addRowVSAT( Request $request ) {
        $data['row_id'] = intval( $request->lastRowId ) + 1;
        $data['type']   = $request->tableType;

        $public_html = (string) view( "REUSELicenseIssue::VSAT.Issue.table_template.table_row", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function addRowVSATAmendment( Request $request ) {
        $data['row_id'] = intval( $request->lastRowId ) + 1;
        $data['type']   = $request->tableType;
        $public_html    = (string) view( "REUSELicenseIssue::VSAT.Amendment.table_template.table_row", $data );

        return response()->json( [ 'responseCode' => 1, 'html' => $public_html ] );
    }

    public function addRowIPTSPAmendment(Request $request){
        $data['row_id'] = intval($request->lastRowId) + 1;
        $data['type'] = $request->tableType;
        $public_html = (string)view("REUSELicenseIssue::IPTSP.Amendment.table_template.table_row", $data);
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }

    public function fetchAppData( $request ) {
        if ( ! $request->ajax() ) {
            return 'Sorry! this is a request without proper way.';
        }

        if ( ! ACL::getAccsessRight( $this->acl_name, '-A-' ) ) {
            return response()->json( [
                'responseCode' => 1,
                'html'         => 'You have no access right! Contact with system admin for more information. [ISPR-003]'
            ] );
        }
        try {
            switch ( $this->process_type_id ) {
                case  2:
                    return ( new ISPLicenseRenew() )->fetchData( $request, $this );
                case 3:
                    return ( new ISPLicenseAmendment() )->fetchData( $request, $this );
                case 4:
                    return ( new ISPLicenseSurrender() )->fetchData( $request, $this );
                case 6:
                    return ( new CallCenterRenew() )->fetchData( $request, $this );
                case 7:
                    return ( new Amendment() )->fetchData( $request, $this );
                case 8:
                    return ( new CallCenterSurrender() )->fetchData( $request, $this );
                case 10:
                    return ( new NIXLicenseRenew() )->fetchData( $request, $this );
                case 11:
                    return ( new NIXLicenseAmendment() )->fetchData( $request, $this );
                case 12:
                    return ( new NIXLicenseSurrender() )->fetchData( $request, $this );
                case 14:
                    return ( new VSATLicenseRenew() )->fetchData( $request, $this );
                case 15:
                    return ( new VSATLicenseAmendment() )->fetchData( $request, $this );
                case 16:
                    return ( new VSATLicenseSurrender() )->fetchData( $request, $this );
                case 18:
                    return ( new IIGLicenseRenew() )->fetchData( $request, $this );
                case 19:
                    return ( new IIGLicenseAmendment() )->fetchData( $request, $this );
                case 20:
                    return ( new IIGLicenseSurrender() )->fetchData( $request, $this );
                case 22:
                    return ( new IPTSPLicenseRenew() )->fetchData( $request, $this );
                case 23:
                    return ( new IPTSPLicenseAmendment() )->fetchData( $request, $this );
                case 24:
                    return ( new IPTSPLicenseSurrender() )->fetchData( $request, $this );
                case 27:
                    return ( new TVASLicenseAmendment() )->fetchData( $request, $this );
                case 28:
                    return ( new TVASLicenseSurrender() )->fetchData( $request, $this );
                case 26:
                    return ( new TVASLicenseRenew() )->fetchData( $request, $this );
                case 30:
                    return ( new VTSLicenseRenew() )->fetchData( $request, $this );
                case 34:
                    return ( new ICXLicenseRenew() )->fetchData( $request, $this );
                case 35:
                    return ( new ICXLicenseAmendment() )->fetchData($request, $this );
                case 36:
                    return ( new ICXLicenseSurrender() )->fetchData( $request, $this );
                case 38:
                    return ( new IGWLicenseRenew() )->fetchData( $request, $this );
                case 39:
                    return ( new IGWLicenseAmendment() )->fetchData( $request, $this );
                case 40:
                    return ( new IGWLicenseSurrender() )->fetchData( $request, $this );
                case 51:
                    return ( new NTTNLicenseRenew() )->fetchData( $request, $this );
                case 52:
                    return ( new NTTNLicenseAmendment() )->fetchData( $request, $this );
                case 53:
                    return ( new NTTNLicenseSurrender() )->fetchData( $request, $this );
                case 55:
                    return ( new ITCLicenseRenew() )->fetchData( $request, $this );
                case 56:
                    return ( new ITCLicenseAmendment() )->fetchData( $request, $this );
                case 57:
                    return ( new ITCLicenseSurrender() )->fetchData( $request, $this );
                case 59:
                    return ( new MNOLicenseRenew() )->fetchData( $request, $this );
                case 60:
                    return ( new MNOLicenseAmendment() )->fetchData( $request, $this );
                case 61:
                    return ( new MNOLicenseSurrender() )->fetchData( $request, $this );
                case 63:
                    return ( new SCSLicenseRenew() )->fetchData( $request, $this );
                case 64:
                    return ( new SCSLicenseAmendment() )->fetchData( $request, $this );
                case 65:
                    return ( new SCSLicenseSurrender() )->fetchData( $request, $this );
                case 67:
                    return ( new TCLicenseRenew() )->fetchData( $request, $this );
                case 68:
                    return ( new TCLicenseAmendment() )->fetchData( $request, $this );
                case 69:
                    return ( new TCLicenseSurrender() )->fetchData( $request, $this );
                case 71:
                    return ( new MNPLicenseRenew() )->fetchData( $request, $this );
                case 72:
                    return ( new MNPLicenseAmendment() )->fetchData( $request, $this );
                case 73:
                    return ( new MNPLicenseSurrender() )->fetchData( $request, $this );
                case 75:
                    return ( new BWALicenseRenew() )->fetchData( $request, $this );
                case 76:
                    return ( new BWALicenseAmendment() )->fetchData( $request, $this );
                case 77:
                    return ( new BWALicenseSurrender() )->fetchData( $request, $this );
                case 79:
                    return ( new SSLicenseRenew() )->fetchData( $request, $this );
                case 80:
                    return ( new SSLicenseAmendment() )->fetchData( $request, $this );
                case 81:
                    return ( new SSLicenseSurrender() )->fetchData( $request, $this );
                case 82:
                    //
                case 83:
                    return ( new VTSLicenseAmendment() )->fetchData( $request, $this );
                case 84:
                    return ( new VTSLicenseSurrender() )->fetchData( $request, $this );
                default:
                    return false;
            }
        } catch ( \Exception $e ) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");

            return response()->json( [
                'responseCode' => 1,
                'html'         => CommonFunction::showErrorPublic( $e->getMessage() ) . ' [ISPR-004]'
            ] );
        }
    }

    public function validateRequest( Request $request ) {

        if ( $request->get( 'actionBtn' ) != 'submit' ) {
            return false;
        }

        $instance = null;
        switch ( $this->process_type_id ) {
            case 1:
                $instance = new ISPLicenseIssueRequest( $request );
                break;
            case 2:
                $instance = new ISPLicenseRenewRequest( $request );
                break;
            case 3:
                $instance = new ISPLicenseAmendmentRequest( $request );
                break;
            case 4:
                return true;
            case 5:
                $instance = new BPOLicenseIssueRequest( $request );
                break;
            case 6:
                $instance = new BPOLicenseRenewRequest( $request );
                break;
            case 7:
                return true;
            case 8:
                return true;
            case 9:
                $instance = new NIXLicenseIssueRequest( $request );
                break;
            case 10:
                $instance = new NIXLicenseRenewRequest( $request );
                break;
            case 11:
                $instance = new NIXLicenseAmendmentRequest( $request );
                break;
            case 12:
                $instance = new NIXLicenseSurrenderRequest( $request );
                break;
            case 13:
                $instance = new VSATLicenseIssueRequest( $request );
                break;
            case 14:
                $instance = new VSATLicenseRenewRequest( $request );
                break;
            case 15:
                $instance = new VSATLicenseAmendmentRequest( $request );
                break;
            case 16:
                //
            case 17:
                $instance = new IIGLicenseIssueRequest( $request );
                break;
            case 18:
                $instance = new IIGLicenseRenewRequest( $request );
                break;
            case 19:
                //
            case 20:
                //
            case 21:
                $instance = new IPTSPLicenseIssueRequest( $request );
                break;
            case 22:
                $instance = new IPTSPLicenseRenewRequest( $request );
                break;
            case 23:
                $instance = new IPTSPLicenseAmendmentRequest( $request );
                break;
            case 24:
                $instance = new IPTSPLicenseSurrenderRequest( $request );
                break;
            case 25:
                $instance = new TVASLicenseIssueRequest( $request );
                break;
            case 26:
                $instance = new TVASLicenseRenewRequest( $request );
                break;
            case 27:
                return true;
            case 28:
                $instance = new TVASLicenseSurrenderRequest( $request );
                break;
            case 29:
                $instance = new VTSLicenseIssueRequest( $request );
                break;
            case 30:
                $instance = new VTSLicenseRenewRequest( $request );
                break;
            case 33:
                $instance = new ICXLicenseIssueRequest( $request );
                break;
            case 34:
                $instance = new ICXLicenseRenewRequest( $request );
                break;
            case 35:
                $instance = new ICXLicenseAmendmentRequest( $request );
                break;
            case 36:
                $instance = new ICXLicenseSurrenderRequest( $request );
                break;
            case 37:
                // $instance = new ICXLicenseSurrenderRequest( $request );
                // break;
            case 38:
                $instance = new IGWLicenseRenewRequest( $request );
                break;
            case 39:
                $instance = new IGWLicenseAmendmentRequest( $request );
                break;
            case 40:
                //
            case 50:
                $instance = new NTTNLicenseIssueRequest( $request );
                break;
            case 51:
                $instance = new NTTNLicenseRenewRequest( $request );
                break;
            case 52:
                $instance = new NTTNLicenseAmendmentRequest( $request );
                break;
            case 53:
                $instance = new NTTNLicenseSurrenderRequest( $request );
                break;
            case 54:
                //
            case 55:
                $instance = new ITCLicenseRenewRequest( $request );
                break;
            case 56:
                //
            case 57:
                //
            case 58:
                //
            case 59:
                $instance = new MNOLicenseRenewRequest( $request );
                break;
            case 60:
                //
            case 61:
                //
            case 62:
                $instance = new SCSLicenseIssueRequest( $request );
                break;
            case 63:
                //
            case 64:
                //
            case 65:
                //
            case 66:
                //
            case 67:
                //
            case 68:
                //
            case 69:
                //
            case 70:
                //
            case 71:
                //
            case 72:
                //
            case 73:
                //
            case 74:
                //
            case 75:
                //
            case 76:
                //
            case 77:
                //
            case 78:
                //
            case 79:
                //
            case 80:
                //
            case 81:
                //
            case 82:
                //
            case 83:
                $instance = new VTSLicenseAmendmentRequest( $request );
                break;
            case 84:
                $instance = new VTSLicenseSurrenderRequest( $request );
                break;
        }

        return $this->validate( $request, $instance->rules(), $instance->messages() );

    }

    public function checkApplicationLimitByArea( Request $request ) {

        $ispLicenseType = $request->get( 'ispLicenseType' );
        $areaId         = $request->get( 'areaId' );
        $app_id         = $request->get('app_id');

        if ($ispLicenseType == 1){
            return response()->json( [ 'responseCode' => 0, 'message' => 'Not eligible for nationwide' ] );
        }

        try {
            $totalAppByLicenseType = DB::table( 'isp_license_issue as apps' )
                                       ->leftJoin('process_list as pl', 'pl.ref_id', '=', 'apps.id')
                                       ->when( $ispLicenseType == 2, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 2);
                                           $query->where( 'apps.isp_license_division', $areaId );
                                       } )
                                        ->when( $ispLicenseType == 3, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 3);
                                            $query->where( 'apps.isp_license_district', $areaId);
                                        } )
                                        ->when( $ispLicenseType == 4, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 4);
                                           $query->where( 'apps.isp_license_upazila', $areaId);
                                       } )
                                        ->when( !empty($app_id), function ($query) use ($app_id) {
                                            $app_id = Encryption::decodeId($app_id);
                                            $query->where( 'apps.id','!=', $app_id);
                                        } )
                                        ->where([['pl.status_id', '!=', '-1'],['pl.process_type_id', '=', 1]])
                                        ->count();
            $totalApplicationByType = DB::table( 'isp_license_issue as apps' )
                                       ->leftJoin('process_list as pl', 'pl.ref_id', '=', 'apps.id')
                                       ->when( $ispLicenseType == 2, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 2);
                                           $query->whereNull( 'apps.license_no');
                                           $query->where( 'apps.isp_license_division', $areaId );
                                       } )
                                        ->when( $ispLicenseType == 3, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 3);
                                            $query->whereNull( 'apps.license_no');
                                            $query->where( 'apps.isp_license_district', $areaId);
                                        } )
                                        ->when( $ispLicenseType == 4, function ( $query ) use ( $areaId ) {
                                           $query->where( 'apps.isp_license_type', 4);
                                            $query->whereNull( 'apps.license_no');
                                           $query->where( 'apps.isp_license_upazila', $areaId);
                                       } )
                                        ->when( !empty($app_id), function ($query) use ($app_id) {
                                            $app_id = Encryption::decodeId($app_id);
                                            $query->whereNull( 'apps.license_no');
                                            $query->where( 'apps.id','!=', $app_id);
                                        } )
                                        ->where([['pl.status_id', '!=', '-1'],['pl.process_type_id', '=', 1]])
                                        ->count();

            $totalMasterLicenseCount = DB::table( 'isp_license_master as apps' )
                ->when( $ispLicenseType == 2, function ( $query ) use ( $areaId ) {
                    $query->where( 'apps.isp_license_type', 2);
                    $query->where( 'apps.isp_license_division', $areaId );
                } )
                ->when( $ispLicenseType == 3, function ( $query ) use ( $areaId ) {
                    $query->where( 'apps.isp_license_type', 3);
                    $query->where( 'apps.isp_license_district', $areaId);
                } )
                ->when( $ispLicenseType == 4, function ( $query ) use ( $areaId ) {
                    $query->where( 'apps.isp_license_type', 4);
                    $query->where( 'apps.isp_license_upazila', $areaId);
                } )
                ->whereRaw('license_no IS NOT NULL')
                ->count();


            $areaType         = intval( $ispLicenseType ) - 1;
            $applicationLimit = DB::table( 'area_info' )
                                  ->where( 'area_type', '=', $areaType )
                                  ->where( 'area_id', $areaId )
                                  ->value( 'app_limit' );

            $license_ratio_value = ConfigSetting::where([
                "label" => 'license_ratio_value',
                "status" => 1
            ])->value('value');
            $limit = max(($applicationLimit - $totalMasterLicenseCount), 0) * intval($license_ratio_value);
            if ( $totalAppByLicenseType - $totalMasterLicenseCount > $limit || $totalMasterLicenseCount >= $applicationLimit|| $totalApplicationByType >= $limit) {
                return response()->json( [
                    'responseCode' => 1,
                    'message'      => 'According to the BTRC guideline you are not allowed to apply for this category license in this designated area.'
                ] );
            }
            return response()->json( [ 'responseCode' => 0, 'message' => 'Application limit not exceeded' ] );

        } catch ( \Exception $e ) {
            return response()->json( [ 'responseCode' => 2, 'message' => 'Something went wrong.[CA-345]' ] );
        }
    }
}
