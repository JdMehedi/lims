<?php

namespace App\Http\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\Osspid;
use App\Libraries\UtilFunction;
use App\Modules\API\Http\Controllers\Traits\Notification;
use App\Modules\API\Models\OssAppUser;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\EmailQueue;
use App\Modules\Settings\Models\Flat;
use App\Modules\Settings\Models\MaintenanceModeUser;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\FailedLogin;
use App\Modules\Users\Models\NhaApplicant;
use App\Modules\Users\Models\SecurityProfile;
use App\Modules\Users\Models\UserDevice;
use App\Modules\Users\Models\UserLogs;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Captcha\Facades\Captcha;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;

class  LoginController extends Controller
{

    use Notification;

    /*
     * Login process check function
     */
    public function reCaptcha()
    {
        return Captcha::img();
    }

//    public function index(){
//        if (Auth::check()) {
//            return redirect("dashboard");
//        }
//        return view('home');
//    }

    public function check(Request $request, Users $usersModel)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|max:30',
        ];

        if (Session::get('hit') >= 3) {

//            $rules['g_recaptcha_response'] = 'required';

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $data = ['responseCode' => 0, 'msg' => 'Please check the captcha.'];
                return response()->json($data);
            }

//            ------old captcha
//            $rules['captcha'] = 'required|captcha';
//
//            $validator = Validator::make($request->all(), $rules);
//            if ($validator->fails()) {
//                $data = ['responseCode' => 0, 'msg' => 'Invalid Captcha Code'];
//                return response()->json($data);
//            }
        } else {
            $this->validate($request, $rules);
        }

        if (!$this->_checkAttack($request)) {
            $msg = Session::get("error");
            Session::flash('error', 'Invalid login information!![HIT3TIMES]');
            $data = ['responseCode' => 0, 'msg' => $msg, 'redirect_to' => ''];
        } else {
            $response = $this->commonLoginCheck($request, $usersModel, 1, '', true);

            if ($response['result']) {
                Session::flash('success', $response['msg']);
                $this->sendPushNotification($request->email);
                $data = ['responseCode' => 1, 'msg' => $response['msg'], 'redirect_to' => $response['redirect_to']];
            } else {
                Session::flash('error', $response['msg']);
                $data = ['responseCode' => 0, 'msg' => $response['msg'], 'redirect_to' => $response['redirect_to'],
                    'hit' => Session::get('hit')];
            }
        }
        return response()->json($data);
    }

    /*
     * check for attack
     */
    private function _checkAttack($request)
    {
        try {
            $ip_address = UtilFunction::getVisitorRealIP();
            $user_email = $request->get('email');
            $count = FailedLogin::where('remote_address', "$ip_address")
                ->where('is_archive', 0)
                ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -20 MINUTE)'))
                ->count();
            if ($count > 20) {
                Session::flash('error', 'Invalid Login session. Please try after 10 to 20 minute [LC6091],
                Please contact with system admin.');
                return false;
            } else {
                $count = FailedLogin::where('remote_address', "$ip_address")
                    ->where('is_archive', 0)
                    ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -60 MINUTE)'))
                    ->count();
                if ($count > 40) {
                    Session::flash('error', 'Invalid Login session. Please try after 30 to 60 minute [LC6092],
                    Please contact with system admin.');
                    return false;
                } else {
                    $count = FailedLogin::where('user_email', $user_email)
                        ->where('is_archive', 0)
                        ->where('created_at', '>', DB::raw('DATE_ADD(now(),INTERVAL -10 MINUTE)'))
                        ->count();
//                    if ($count > 6) {
//                        Session::flash('error', 'Invalid Login session. Please try after 5 to 10 minute 1002,
//                        Please contact with system admin.');
//                        return false;
//                    }
                }
            }

        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Session::flash('error', 'Login session exception. Please try after 5 to 10 minute 1003,
            Please contact with system admin.');
            return false;
        }
        return true;
    }

    public static function killUserSession($user_id, $loginType=0)
    {
        try {
            $sessionID = Users::where('id', $user_id)->value('login_token');
            if (!empty($sessionID)) {
                if ($loginType == 2){ // OTP login
                    $sessionID = $sessionID;
                    Session::getHandler()->destroy($sessionID);
                }else{
                    $sessionID = Encryption::decode($sessionID);
                    Session::getHandler()->destroy($sessionID);
                }

            }
            Users::where('id', $user_id)->update(['login_token' => '']);
        } catch (\Exception $e) {
            Users::where('id', $user_id)->update(['login_token' => '']);
        }
    }

    public function _checkSecurityProfile($request = [], $ip_param = '')
    {
        $security_id = Auth::user()->security_profile_id;
        if (empty($security_id)) {
            $security_id = UserTypes::where('id', Auth::user()->user_type)->value('security_profile_id');
        }


        if ($security_id) {
            $security = SecurityProfile::where(['id' => $security_id])
                ->where('active_status', 'yes')
                ->first([
                'allowed_remote_ip',
                'week_off_days',
                'work_hour_start',
                'work_hour_end',
                'alert_message',
                'active_status',
            ]);
//            dd($security);

            if (empty($security)) {
                return true;
            }else{
                if ($ip_param) {
                    $ip = $ip_param;
                } else {
                    $ip = UtilFunction::getVisitorRealIP();
                }
                if ($ip == '127.0.0.1' || $ip == '::1') {
                    $ip = '0.0.0.0';
                }
                $net = '0.0.0.0';
                $nets = explode('.', $ip);
                $today = strtoupper(date('D'));
//                dd($weekName);
                if (count($nets) == 4) {
                    $net = $nets[0] . '.' . $nets[1] . '.' . $nets[2] . '.0';
                }
//                dd($net);

                /*
                 * if IP address is equal to '' or '0.0.0.0.' or IP address is in allowed ip
                 */
                if ($security->allowed_remote_ip == ''
                    || $security->allowed_remote_ip == '0.0.0.0'
                    || !(strpos($security->allowed_remote_ip, $net) === false)
                    || !(strpos($security->allowed_remote_ip, $ip) === false)) {

//                    dd(strpos(strtoupper($security->week_off_days), $today));
                    /*
                     * It today is not weekly off day
                     */
                    if (strpos(strtoupper($security->week_off_days), $today) === false) {

                        /*
                         * if current time is greater than work_hour_start and less than work_hour_end
                         */
                        date_default_timezone_set('Asia/Dhaka');
                        if (time() >= strtotime($security->work_hour_start) && time() <= strtotime($security->work_hour_end)) {
                            return true;
                        }
                    }
                }
            }
        }
        Session::flash('error', $security->alert_message);
        return false;
    }

    /*
     * protect login for special types users
     */
    public function _protectLogin($type = false)
    {
        if ($type == '10x414') {// For UDC users
            Auth::logout();
            Session::flash('error', 'You are not allowed to login using this type of login method');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Insert login info in user_logs table
     */
//    public function entryAccessLog()
//    {
//        // access_log table.
//        $str_random =  Str::random(10);
//        $insert_id = DB::table('user_logs')->insertGetId(
//            array(
//                'user_id' => Auth::user()->id,
//                'login_dt' => date('Y-m-d H:i:s'),
//                'updated_at' => date('Y-m-d H:i:s'),
//                'ip_address' => UtilFunction::getVisitorRealIP(),
//                'access_log_id' => $str_random
//            )
//        );
//
//        Session::put('access_log_id', $str_random);
//    }

//    /*
//     * Store all failed login history
//     */
//    private function _failedLogin($request)
//    {
//        $ip_address = UtilFunction::getVisitorRealIP();
//        $user_email = $request->get('email');
//        FailedLogin::create(['remote_address' => $ip_address, 'user_email' => $user_email]);
//    }

    /*
     * Caption set up
     */
    private function _setCaption($usersModel)
    {
        /*
         * for user caption (like: Bank name/agency name/udc name etc)
         */

        $caption_name = '';
        $userAdditionalInfo = $usersModel->getUserSpecialFields(Auth::user());
        if (count($userAdditionalInfo) >= 1 && $userAdditionalInfo[0]['value']) {
            $caption_name .= ' - ';
            if (Auth::user()->user_type == '7x711'
                || Auth::user()->user_type == '7x712'
                || Auth::user()->user_type == '7x713') {
                $caption_name .= UserTypes::where('id', Auth::user()->user_type)->pluck('type_name') . ', ';
            }

            $caption_name .= $userAdditionalInfo[0]['value']; //$userAdditionalInfo[0]['caption'] . ': ' .
            if (strlen($caption_name) > 45) {
                $caption_name = substr($caption_name, 0, 43) . '..';
            }
        } else {
            $caption_name .= ' - ' . Auth::user()->user_email;
        }
        Session::put('caption_name', $caption_name);
    }

    /*
     * User's session set up
     */
    public function _setSession()
    {
        try {
            if (Auth::user()->is_approved == 1
                && Auth::user()->user_status == 'active') {
                Session::put('lang', Auth::user()->user_language);
                App::setLocale(Session::get('lang'));
                Session::put('user_pic', Auth::user()->user_pic);
                Session::put('hit', 0);

                //Set last login time in session
                $last_login_time = UserLogs::leftJoin('users', 'users.id', '=', 'user_logs.user_id')
                    ->where('user_logs.user_id', '=', Auth::user()->id)
                    ->orderBy('user_logs.id', 'desc')
                    ->skip(1)->take(1)
                    ->first(['user_logs.login_dt']);
                $lastLogin = date("d-M-Y h:i:s");
                if ($last_login_time) {
                    $lastLogin = date("d-M-Y h:i:s", strtotime($last_login_time->login_dt));
                }
                Session::put('last_login_time', $lastLogin);

                // for checkAdmin middleware checking
                $security_check_time = Carbon::now();
                Session::put('security_check_time', $security_check_time);
                Session::put('is_first_security_check', 0);

                // for company association selection
                Session::put('is_working_company_selected', 0);

                // for user report module
                Session::put('sess_user_id', Auth::user()->id);
                Session::put('sess_user_type', Auth::user()->user_type);
                Session::put('sess_district', Auth::user()->district);
                Session::put('sess_thana', Auth::user()->thana);


                // Get the users who have delegated to me
//                $delegated_users_to_me = UsersModel::where('delegate_to_user_id', Auth::user()->id)->lists('id')->all();

                // Get the delegated desks by delegated users
//                $delegated_desk_to_me = UserDesk::whereIn('user_id', $delegated_users_to_me)->first([DB::raw('group_concat(desk_id) as user_desk')]);

//                $delegated_desk_to_me_exploded = array();
//                if ($delegated_desk_to_me->user_desk != null) {
//                    $delegated_desk_to_me_exploded = explode(',', $delegated_desk_to_me->user_desk);
//                }

                // To set user desk
                $my_desk_ids = Users::where('id', Auth::user()->id)->value('desk_id');
                $my_desk_ids_exploded = explode(',', $my_desk_ids);
                $all_desk_to_me = implode(',', array_unique($my_desk_ids_exploded));
                Session::put('user_desk_ids', $all_desk_to_me);
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Invalid session ID!');
            return false;
        }
        return true;
    }

    /*
     * Entry access for Logout
     * update logout time in user_logs table
     */
//    public function entryAccessLogout()
//    {
//        $access_log_id = Session::get('access_log_id');
//        DB::table('user_logs')->where('access_log_id', $access_log_id)->update([
//            'logout_dt' => date('Y-m-d H:i:s'),
//            'updated_at' => date('Y-m-d H:i:s')
//        ]);
//    }

    public function logout()
    {
        if (Auth::user()) {
            Users::where('id', Auth::user()->id)->update(['login_token' => '']);
        }
        UtilFunction::entryAccessLogout();
        Session::getHandler()->destroy(Session::getId());
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }

//    public function loadLoginForm()
//    {
//        $osspid = new Osspid(array(
//            'client_id' => config('osspid.osspid_client_id'),
//            'client_secret_key' => config('osspid.osspid_client_secret_key'),
//            'callback_url' => config('app.project_root') . '/osspid-callback'
//        ));
////        dd($osspid);
//
//        $redirect_url = $osspid->getRedirectURL();
//        return strval(view('public_home.login-credential', compact('redirect_url')));
//    }

    public function loadLoginOtpForm()
    {
        return strval(view('public_home.otp'));
    }

    public function otpLoginEmailValidationWithTokenProvide(Request $request)
    {

        try {


            $email = trim($request->get('email_address'));
//        $otpBy = trim($request->get('otp'));

            /*
             * User given data is OK
             */
            if ($email) {
                $user = Users::where('user_email', $email)
                    ->where('is_approved', '=', 1)
                    ->where('user_status', '=', 'active')->first();

                /*
                 * User is valid
                 */
                if ($user) {

                    $login_token = rand(1111, 9999);
                    $expire_time_config = Configuration::where('caption', 'otp_expire_after')->value('value') ?? "+3 min";
                    $otp_expire_time = date('Y-m-d H:i:s', strtotime($expire_time_config));
                    Users::where('id', $user->id)->update(['login_token' => $login_token, 'otp_expire_time'=>$otp_expire_time]);




                    $receiverInfo[] = [
                        'user_email' => $email,
                        'user_mobile' => $user->user_mobile
                    ];

                    $appInfo = [
                        'one_time_password' => $login_token
                    ];

                   $id =  CommonFunction::sendEmailSMS('ONE_TIME_PASSWORD', $appInfo, $receiverInfo);
                   $data = ['responseCode' => 1, 'msg' => 'Valid email', 'user_email'=>$email, 'queue_id'=> Encryption::encodeId($id)];
                    return response()->json($data);
                } else {
                    $data = ['responseCode' => 0, 'msg' => 'Invalid email'];
                    return response()->json($data);
                }
            } else {
                $data = ['responseCode' => 0, 'msg' => 'Invalid email'];
                return response()->json($data);
            }
        } catch (Exception $exception) {
            $data = ['responseCode' => 0, 'msg' => 'Sorry! Something is Wrong.' . $exception->getMessage()];
            return response()->json($data);
        }
    }

    public function checkOtpLogin(Request $request, Users $usersModel)
    {
        $user_otp = $request->otp;
//        $rules = [
//            'email' => 'required|email',
//            'login_token' => 'required',
//        ];
//        if (Session::get('hit') >= 3) {
//            $rules = ['captcha' => 'required|captcha'];
//        }
//        $messages = [
//            'captcha' => 'Invalid :attribute code'
//        ];
//        $this->validate($request, $rules, $messages);

//        if (!$this->_checkAttack($request)) {
//            Session::flash('error', 'Invalid login information!![HIT3TIMES]');
//            $data = ['responseCode' => 0, 'msg' => '', 'redirect_to' => ''];
//        } else {

            $response = $this->commonLoginCheck($request, $usersModel, 2,
                trim($user_otp), true);

            if($response['msg'] == 'OTP Time Expired!.Please Try again'){
                Session::flash('error', $response['msg']);
                $data = ['responseCode' => 0, 'msg' => "OTP Time Expired!.Please Try again", 'redirect_to' => $response['redirect_to']];
            }
            elseif ($response['result']) {
                 Session::flash('success', $response['msg']);
                 $data = ['responseCode' => 1, 'msg' => $response['msg'], 'redirect_to' => $response['redirect_to']];
            } else {
                Session::flash('error', $response['msg']);
    //                $data = ['responseCode' => 0, 'msg' => $response['msg'],'redirect_to' => $response['redirect_to']];
                $data = ['responseCode' => 0, 'msg' => "Invalid OTP -----", 'redirect_to' => $response['redirect_to']];
            }
//        }
        return response()->json($data);
    }

    /*
     * loginType (1) = Login By Credential
     * loginType (2) = Login By OTP
     */
    private function commonLoginCheck($request, $usersModel, $loginType = 0, $otp = '', $is_ajax_request = false)
    {
        try {
            $data = [
                'user_email' => $request->get('email')
            ];

            // General login
            if ($loginType == 1) {
                $remember_me = $request->has('remember_me') ? true : false;
                $loggedin = Auth::attempt(
                    ['user_email' => $request->get('email'),
                        'password' => $request->get('password')
                    ], $remember_me);
            } // Login with OTP
            else if ($loginType == 2) {
                $user_mobile = $request->mobile;
                $currentTime = new Carbon;
//                dump($request->get('email_address'));
                $user = $usersModel::where('user_mobile', $user_mobile)->first();

                //dd(Encryption::dataDecode($user->auth_token),$otp,$user);
                if (empty($user) || (Encryption::dataDecode($user->auth_token) != $otp)) {
                    $response = array('result' => false, 'msg' => 'Invalid OTP........',
                        'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                    return $response;
                } elseif ($currentTime >= $user->otp_expire_time) {
                    $response = array('result' => false, 'msg' => 'OTP Time Expired!.Please Try again',
                        'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                    return $response;
                }

                $loggedin = Auth::loginUsingId($user->id);

                if (!$loggedin) {
                    $response = array('result' => false, 'msg' => 'Login failed. Please reload the page and
                    try again.', 'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                    return $response;
                }

            }

            // if user mail && password is true
            if ($loggedin) {
                // Check Maintenance Mode
                if ($this->checkMaintenanceModeForUser() === true) {
                    $error_msg = session()->get('error');
                    Auth::logout();
                    return array('result' => false, 'msg' => $error_msg, 'redirect_to' => '',
                        'is_ajax_request' => $is_ajax_request);
                    return redirect()->to('/login');
                }

                $userTypeRootStatus = $this->_checkUserTypeRootActivation(Auth::user()->user_type, $is_ajax_request);

                if ($userTypeRootStatus['result'] == false) {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    return array('result' => false, 'msg' => $userTypeRootStatus['msg'], 'redirect_to' => '',
                        'is_ajax_request' => $is_ajax_request);
                }

                if (Auth::user()->is_approved != 1) {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    return array('result' => false, 'msg' => 'The user is not approved, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>',
                        'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                }
                if (Auth::user()->is_approved == 1 && Auth::user()->user_status != 'active') {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    return array('result' => false, 'msg' => 'The user is not active, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>',
                        'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                }

                // if this user is not verified in system then go back
                if (Auth::user()->user_verification == 'no') {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    return array('result' => false, 'msg' => 'The user is not verified in ' . config('app.project_name') . ', please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>',
                        'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                }


                if (!$this->_checkSecurityProfile($request)) {
                    Auth::logout();
                    $error = (Session::has('error')) ? Session('error') : 'Security profile does not support login from this network';
                    return array('result' => false, 'msg' => $error, 'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
                }


                $loginAccess = $this->_protectLogin(Auth::user()->user_type); //login protected for UDC
                if ($loginAccess == false) {
                    //For any user type we can protect login from here
                    $error = (Session::has('error')) ? Session('error') : 'You are not allowed to login using this type of login method';
                    return array('result' => false, 'msg' => $error, 'redirect_to' => '/login', 'is_ajax_request' => $is_ajax_request);
                }


                if ($this->_setSession() == false) {
                    return array('result' => false, 'msg' => 'Session expired', 'redirect_to' => '/login',
                        'is_ajax_request' => $is_ajax_request);
                }

                if (Auth::user()->first_login == 0) {
                    Users::where('id', Auth::user()->id)->update(['first_login' => 1]);
                }

                if (Auth::user()->is_approved == 1) {
                    // Kill previous session and set a new session.
                    $this->killUserSession(Auth::user()->id, $loginType);
                    Users::where('id', Auth::user()->id)->update(['login_token' => Encryption::encode(Session::getId())]);

                    // Set delegated user id in session && redirect to delegation remove page
                    if (in_array(Auth::user()->user_type, ['4x404'])) {
                        if (Auth::user()->delegate_to_user_id != 0) {
                            Session::put('sess_delegated_user_id', Auth::user()->delegate_to_user_id);
                            return array('result' => true, 'msg' => 'Logged in successfully, Welcome to ' . config('app.project_name'), 'redirect_to' => '/users/delegate', 'is_ajax_request' => $is_ajax_request);
                        }
                    }

                    CommonFunction::GlobalSettings();

                    $user_type = UserTypes::where('id', Auth::user()->user_type)->first();
                    $uuid = Str::uuid()->toString();
                    if(Auth::user()->mobile_auth_token==null && env('IS_MOBILE')) {
                        Users::where('id', Auth::user()->id)->update(['mobile_auth_token' => $uuid]); // update UUID
                    }
                    if(Auth::user()->mobile_auth_token) {
                        $uuid = Auth::user()->mobile_auth_token;
                    }

                    if (($user_type->auth_token_type == 'mandatory') || ($user_type->auth_token_type == 'optional' && Auth::user()->auth_token_allow == 1)) {

                        Users::where('id', Auth::user()->id)->update(['auth_token' => 'will get a code soon']);
                        return array('result' => true, 'msg' => 'Logged in successfully, Please verify the 2nd steps.', 'redirect_to' => '/users/two-step', 'is_ajax_request' => $is_ajax_request);
                    } else {
                        UtilFunction::entryAccessLog();
                        $this->newDeviceDetection();
                        // $this->_setCaption($usersModel);
                        // CommonFunction::setPermittedMenuInSession();

                        if(Auth::user()->user_type=='11x101'){
                            $redirect_url = '/reportv2';
                        }else{
                            $redirect_url = '/dashboard';
                        }
                        
                        $encodeId = Encryption::encode(Auth::user()->id);
                        $queryParams = [
                            'token' => $uuid,
                            'user_id' => $encodeId
                        ];
                        if(env('IS_MOBILE')) {
                            Session::put('url_token_id', $uuid);
                            Session::put('url_user_id', $encodeId);
                            $redirect_url .= '?'.http_build_query($queryParams);
                        }
                        if (in_array(Auth::user()->user_type, ['5x505'])) {
                            $companyIds = CommonFunction::getUserAllCompanyIdsWithZero();
                            // If the user have only one company then set all automatically
                            if (count($companyIds) > 1) {
                                $redirect_url = '/company-association/select-company';
                                if(env('IS_MOBILE')) {
                                    $redirect_url .= '?'.http_build_query($queryParams);
                                }
//                                return \redirect()->to('company-association/select-company');
                            }
                        }
                        return array('result' => true, 'msg' => 'Logged in successfully, Welcome to ' . config('app.project_name'), 'redirect_to' => $redirect_url, 'is_ajax_request' => $is_ajax_request);
                    }
                }
            } else {
                if (Session::has('hit')) {
                    Session::put('hit', Session::get('hit') + 1);
                } else {
                    Session::put('hit', 1);
                }

                UtilFunction::_failedLogin($data);
                return array('result' => false, 'msg' => 'Invalid email or password', 'redirect_to' => '1',
                    'is_ajax_request' => $is_ajax_request);
            }
        } catch (\Exception $e) {
            Auth::logout();
            return array('result' => false, 'msg' => $e->getMessage(), '', $e->getLine(),
                $e->getFile(), 'redirect_to' => '/', 'is_ajax_request' => $is_ajax_request);
        }
    }

    public function _checkUserTypeRootActivation($userType = null, $is_ajax_request)
    {
        // for checking user type status
        $userTypeInfo = UserTypes::where('id', $userType)->first();
        if ($userTypeInfo->status != "active") {
            return array('result' => false, 'msg' => 'The user type is not active, please contact with system admin.', 'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
        }

//        if (in_array($userType, ["5x505", "6x606"])) {
//            $companyIds = CommonFunction::getUserAllCompanyIdsWithZero();
//            $CheckActiveCompany = CompanyInfo::whereIn('id', $companyIds)
//                ->where('is_rejected', 'no')
//                ->where('company_status', 1)
//                ->where('is_approved', 1)
//                ->count();
//            if (!$CheckActiveCompany > 0) {
//                return array('result' => false, 'msg' => 'Your company is not active, please contact with system admin. [R00013].', 'redirect_to' => '', 'is_ajax_request' => $is_ajax_request);
//            }
//        }

        //        It will comment out after running mongoDB
        //        require_once(public_path()."/url_webservice/set-mongo-auth.php");
        return array('result' => true);
    }

    public function newDeviceDetection()
    {

        try {
            $agent = new Agent();
            $os = $agent->platform();
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = $agent->browser();

            $userDevice = UserDevice::
            where([
                'user_id' => Auth::user()->id,
                'os' => $os,
                'browser' => $browser,
                'ip' => $ip
            ])->count();

            if ($userDevice == 0) {

                $deviceData = new UserDevice();
                $deviceData->user_id = Auth::user()->id;
                $deviceData->os = $os;
                $deviceData->ip = $ip;
                $deviceData->browser = $browser;
                $deviceData->save();

                $receiverInfo[] = [
                    'user_email' => Auth::user()->user_email,
                    'user_mobile' => Auth::user()->user_mobile
                ];

                $appInfo = [
                    'device' => $os
                ];
                CommonFunction::sendEmailSMS('DEVICE_DETECTION', $appInfo, $receiverInfo);
            }

            return true;

        } catch (\Exception $e) {
            Session::flash('error', 'Device detection error!');
            return false;
        }


    }

    /*
    * forget-password
    */
    public function forgetPassword()
    {
        return view('public_home.forget-password');
    }

    //For Forget Password functionality
    //For Forget Password functionality
    public function resetForgottenPass(Request $request)
    {

        $rules['user_email'] = 'required|email';
//        $rules['g-recaptcha-response'] = 'required';
//        $messages['g-recaptcha-response.required'] = 'Please check the captcha.';
//        $this->validate($request, $rules, $messages);
        $this->validate($request, $rules);

        try {
            $email = $request->get('user_email');
            $users = DB::table('users')
                ->where('user_email', $email)
                ->first();
            if (empty($users)) {
                \Session::flash('error', 'No user with this email is existed in our current database. Please sign-up first');
                return Redirect('forget-password')->with('status', 'error');

            }

            if ($users->user_status == 'inactive'
                && $users->user_verification == 'no') {
                \Session::flash('error', 'This user is not active and email is not verified yet. Please contact with system admin');
                return Redirect('forget-password')->with('status', 'error');
            }

            DB::beginTransaction();

            $token_no = hash('SHA256', "-" . $email . "-");

            $update_token_in_db = array(
                'user_hash' => $token_no,
            );

            DB::table('users')
                ->where('user_email', $email)
                ->update($update_token_in_db);

            $encrytped_token = Encryption::encode($token_no);
            $verify_link = 'verify-forgotten-pass/' . ($encrytped_token);

            $receiverInfo[] = [
                'user_email' => $users->user_email,
                'user_mobile' => $users->user_mobile
            ];

            $appInfo = [
                'reset_password_link' => url($verify_link)
            ];

            CommonFunction::sendEmailSMS('PASSWORD_RESET_REQUEST', $appInfo, $receiverInfo);

            DB::commit();

            \Session::flash('success', 'Please check your email to verify Password Change');
            return redirect('/login')->withInput();
        } catch (Exception $exception) {
            DB::rollback();
            Session::flash('error', 'Sorry! Something is Wrong.' . $exception->getMessage());
            return Redirect::back()->withInput();
        }

    }

    // Forgotten Password reset after verification
    // Forgotten Password reset after verification
    function verifyForgottenPass($token_no)
    {
        $TOKEN_NO = Encryption::decode($token_no);
        $user = Users::where('user_hash', $TOKEN_NO)->first();
        if (empty($user)) {
            \Session::flash('error', 'Invalid token! No such user is found. Please sign up first.');
            return redirect('login');
        }
        return view('public_home.verify-new-password', compact('token_no'));


    }

    public function checkMaintenanceModeForUser()
    {
        $maintenance_data = MaintenanceModeUser::where('id', 1)->first([
            'id',
            'allowed_user_types',
            'allowed_user_ids',
            'alert_message',
            'operation_mode'
        ]);

        // 2 is maintenance mode
        if ($maintenance_data->operation_mode == 2) {
            $allowed_user_types = explode(',', $maintenance_data->allowed_user_types);
            $allowed_user_ids = explode(',', $maintenance_data->allowed_user_ids);
            if (in_array(Auth::user()->user_type, $allowed_user_types)
                or in_array(Auth::user()->id, $allowed_user_ids)) {
                return false;
            }

            Session::flash('error', $maintenance_data->alert_message);
            return true;
        }
        return false;
    }

    public function allClassRoute()
    {
        $controllers = [];

        foreach (Route::getRoutes()->getRoutes() as $key => $route) {
            $action = $route->getAction();

            if (array_key_exists('controller', $action)) {
                // You can also use explode('@', $action['controller']); here
                // to separate the class name from the method
                $controllers[] = $action['controller'];
            }
        }
    }

    public function type_wise_details(Request $request)
    {
        $data = $request->get('type_id');
        $serviceDetails = ServiceDetails::where('process_type_id', $data)->orderby('id', 'desc')->first(['terms_and_conditions']);
        $contents = view('Settings::service_info.service-info_view', compact(
            'serviceDetails'))->render();
        $data = ['responseCode' => 1, 'data' => $contents];
        return response()->json($data);
    }

    public function sendPushNotification($email)
    {
        $ossAppUser = OssAppUser::where('user_id', $email)->first(['token']);
        $agent = new Agent();
        $os = $agent->platform();
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = $agent->browser();
        $time = Carbon::now();
        if ($ossAppUser) {
            $this->apiSendNotification($ossAppUser->token, 'Logged in ', 'You are logged in from ' . $os .
                ', IP :' . $ip . ', Browser : ' . $browser . ' at ' . $time);
        }
    }

    public function StoreForgottenPass(Request $request)
    {


        $dataRule = [
//            'user_old_password' => 'required',
            'user_new_password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/'
            ],
            'user_confirm_password' => [
                'required',
                'same:user_new_password',
            ]
        ];

        $validator = Validator::make($request->all(), $dataRule);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $new_password = $request->get('user_new_password');
        $user = Users::where('user_hash', Encryption::decode($request->token))->first();
        $user->password = Hash::make($new_password);
        $user->user_hash = '';
        $user->save();

        \Session::flash('success', 'Your password has been changed successfully! Please login with the new password.');
        return redirect('login');
    }

    public function OtpResent(Request $request)
    {

        try {

            $email = trim($request->get('email_address'));
            $otpBy = trim($request->get('otp'));

            /*
             * User given data is OK
             */
            if ($email) {
                $user = Users::where('user_email', $email)
                    ->where('is_approved', '=', 1)
                    ->where('user_status', '=', 'active')->first();

                /*
                 * User is valid
                 */
                if ($user) {

                    $login_token = rand(1111, 9999);
                    $expire_time_config = Configuration::where('caption', 'otp_expire_after')->value('value') ?? "+3 min";
                    $otp_expire_time = date('Y-m-d H:i:s', strtotime($expire_time_config));
                    Users::where('id', $user->id)->update(['login_token' => $login_token, 'otp_expire_time'=>$otp_expire_time]);




                    $receiverInfo[] = [
                        'user_email' => $email,
                        'user_mobile' => $user->user_mobile
                    ];

                    $appInfo = [
                        'one_time_password' => $login_token
                    ];

                    $id =  CommonFunction::sendEmailSMS('ONE_TIME_PASSWORD', $appInfo, $receiverInfo);
                    $data = ['responseCode' => 1, 'msg' => 'Valid email', 'user_email'=>$email, 'queue_id'=> Encryption::encodeId($id)];
                    return response()->json($data);
                } else {
                    $data = ['responseCode' => 0, 'msg' => 'Invalid email'];
                    return response()->json($data);
                }
            } else {
                $data = ['responseCode' => 0, 'msg' => 'Invalid email'];
                return response()->json($data);
            }
        } catch (Exception $exception) {
            $data = ['responseCode' => 0, 'msg' => 'Sorry! Something is Wrong.' . $exception->getMessage()];
            return response()->json($data);
        }
    }

//    public function OtpResent(Request $request)
//    {
//
//        $email = trim($request->get('email_address'));
//        $otpBy = trim($request->get('otp'));
//        if ($email) {
//            $user = Users::where('user_email', $email)
//                ->where('is_approved', '=', 1)
//                ->where('user_status', '=', 'active')->first();
//
//
//            /*
//             * User given data is OK
//             */
//
//            /*
//             * User is valid
//             */
//            if ($user) {
//                $login_token = rand(1111, 9999);
//                $expire_time_config = Configuration::where('caption', 'otp_expire_after')->value('value') ?? "+3 min";
//                $otp_expire_time = date('Y-m-d H:i:s', strtotime($expire_time_config));
//                Users::where('id', $user->id)->update(['login_token' => $login_token, 'otp_expire_time' => $otp_expire_time]);
//
//                $data = ['responseCode' => 1, 'msg' => 'Valid nid', 'user_number' => $user->user_mobile, 'msg' => 'Your OTP has been sent successfully please wait!', 'otp_expired' => $user->otp_expire_time];
//                // sms or email for otp
//
//                $params = array([
//                    'caption' => '',
//                    'emailYes' => '1',
//                    'emailTemplate' => 'Users::message',
//                    'emailBody' => 'OTP is: ' . $login_token . ' for your account on NHA online system',
//                    'emailSubject' => 'OTP login information',
//                    'emailHeader' => 'OTP login information',
//                    'emailAdd' => $email ? $email : '',
//                    'mobileNo' => '',
//                    'smsYes' => '0',
//                    'smsBody' => '',
//
//                ]);
//                CommonFunction::sendMessageFromSystem($params);
//
//                ////////// sms sending start//////////
//                $smsBody = 'OTP is: ' . $login_token . ' for your account on NHA online system';
//                $sms = $smsBody;
//
//                $mobile_no = $user->user_mobile;
//
//                $mobileNo = str_replace("+88", "", "$mobile_no");
//
//                $sms_api_url = env('sms_api_url', 'https://api-k8s.oss.net.bd/api/broker-service/sms/send_sms');
//                $sms_client_id = env('sms_client_id', 'nha-client');
//                $sms_client_secret = env('sms_client_secret', '0f07173c-fe21-4598-bb2c-42527fbef1f4');
//
//                $sms_static_token = env('sms_static_token', '24f95f36-7954-47a0-99af-ace56d9bf53d');
//                $sms_idp_url = env('sms_idp_url', 'https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token');
//
//
//                $curl = curl_init();
//                curl_setopt($curl, CURLOPT_POST, 1);
//                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
//                    'client_id' => $sms_client_id,
//                    'client_secret' => $sms_client_secret,
//                    'grant_type' => 'client_credentials'
//                )));
//                curl_setopt($curl, CURLOPT_URL, "$sms_idp_url");
//                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//                $result = curl_exec($curl);
//                if (!$result) {
//                    $data = ['responseCode' => 0, 'msg' => 'SMS API connection failed!'];
//                    return response()->json($data);
//                }
//                curl_close($curl);
//                $decoded_json = json_decode($result, true);
////print_r($decoded_json);
////exit();
//                $token = $decoded_json['access_token'];
//
///////////////////// SMS sending start
//                $curl = curl_init();
//                curl_setopt_array($curl, array(
//                    CURLOPT_URL => "$sms_api_url",
//                    CURLOPT_RETURNTRANSFER => true,
//                    CURLOPT_ENCODING => "",
//                    CURLOPT_MAXREDIRS => 10,
//                    CURLOPT_TIMEOUT => 0,
//                    CURLOPT_FOLLOWLOCATION => true,
//                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                    CURLOPT_CUSTOMREQUEST => "POST",
//                    CURLOPT_POSTFIELDS => "{\n\t    \"token\": \"$sms_static_token\",\n\t    \"msg\": \"$sms\",\n\t    \"destination\": \"$mobileNo\"\n\t\n}\n",
//                    CURLOPT_HTTPHEADER => array(
//                        "Authorization: Bearer $token",
//                        "Content-Type: application/json",
//                        "Content-Type: text/plain"
//                    ),
//                ));
//                $response = curl_exec($curl);
//                curl_close($curl);
//                $decoded_response = json_decode($response, true);
//                $sms_id = $decoded_response['data']['id'];
//
//                $emailQueue = new EmailQueue();
//                $emailQueue->process_type_id = 0; // there is no service id
//                $emailQueue->app_id = 0; // there is no app id
////            $emailQueue->user_id = $userID; // there is no app id
//                $emailQueue->sms_content = $smsBody;
//                $emailQueue->sms_to = $mobile_no;
//                $emailQueue->attachment = '';
//                $emailQueue->secret_key = '';
//                $emailQueue->pdf_type = '';
//                $emailQueue->sms_status = 1;
//                $emailQueue->response = $response;
//                $emailQueue->save();
//                $otp_expired_time = Users::where('id', $user->id)->value('otp_expire_time');
//
////                                sms sending end
//                $data = ['responseCode' => 1, 'msg' => 'Re-send successfully. Please check your phone number', 'user_number' => $user->user_mobile, 'otp_expired' => $otp_expired_time, 'sms_id' => $sms_id];
//                return response()->json($data);
//
//
//            } else {
//                $data = ['responseCode' => 0, 'msg' => 'Invalid credentials'];
//                return response()->json($data);
//            }
//        }
//    }

    public function checkSMSstatus(Request $request)
    {


//        dump($request->all());
        $email_sms_queue = EmailQueue::where('id', Encryption::decodeId($request->email_id))->first();


        if($email_sms_queue->email_status == 1){
            $data = ['responseCode' => 1, 'sms_status' => $email_sms_queue->email_status, 'msg' => 'Your OTP has been sent please check your device'];
            return response()->json($data);
        }else{
            $data = ['responseCode' => 1, 'sms_status' => $email_sms_queue->email_status, 'msg' => 'Sending Please wait.'];
            return response()->json($data);
        }


    }


}

