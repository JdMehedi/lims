<?php

namespace App\Modules\Web\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
Use App\Models\Templates;
use App\Modules\Users\Models\Users;
use App\Models\EmailSms;
use App\Libraries\Utility;
use App\Libraries\CommonFunction;
use App\Http\Controllers\LoginController as LogController;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use function view;

class LoginController extends Controller
{
	function loginView() {
		return view('public_home.login');
	}

	function loginWithMobileView() {
		return view('public_home.login-with-mobile');
	}

    function loginWithOTPView() {
        return view('public_home.otp-phone');
    }

    public function sendOtp(Request $request ) {
        try {
            $currentTime = Carbon::now();
            date_default_timezone_set('Asia/Dhaka');
            $mobile = trim( $request->get( 'mobile' ) );
            if ( ! $mobile ) {
                CommonFunction::apiResponse( 401, false, 'Mobile need to be given', '' );
            }
            $pin = rand( 1000, 9999 );
            $ifExists = Users::where( [
                'user_mobile' => $mobile
            ] )->orderby( 'id', 'desc' )->first();



            if ( $ifExists ) {
                self::SendSmsService( 'ONE_TIME_PASSWORD', [ '##PIN##' => $pin ], $mobile );

                Users::where( 'user_mobile', $mobile )->orderBy('id','desc')->limit(1)->update( [
                    'auth_token'        => Encryption::dataEncode($pin),
                    'otp_expire_time'   => $currentTime->addMinutes(3)
                ]);
                Session::flash('success', 'OTP has been sent');
            } else {
                Session::flash('error', 'User not found');
                CommonFunction::apiResponse( 401, false, 'User not found', '' );
            }
            CommonFunction::apiResponse( 200, false, 'Otp sent successfully', '' );
        } catch ( \Exception $e ) {
            CommonFunction::apiResponse( 401, false, 'Something went wrong', $e->getMessage() );
        }
    }

    public static function SendSmsService($SmsCaption,$replaceArray,$mobile) {
        $templateData = Templates::where(['caption' => $SmsCaption, 'sms_active_status' => 1])->first();
        $message = str_replace(array_keys($replaceArray), array_values($replaceArray), $templateData->sms_content);
        $smsServiceResp = Utility::sendFastSMS($mobile, $message);
        $sms_status = 1;
        if ($smsServiceResp == false) {$sms_status = 0;}

        $smsLogObj = new EmailSms();
        $smsLogObj->caption = $SmsCaption;
        $smsLogObj->sms_content = $message;
        $smsLogObj->sms_to = trim($mobile);
        $smsLogObj->sms_status = $sms_status;
        $smsLogObj->save();
    }
}
