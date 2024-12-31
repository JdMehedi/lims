<?php

namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryptor;
use App\Libraries\Osspid;
use App\Libraries\UtilFunction;
use App\Modules\Users\Models\Users;
use App\Modules\Settings\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Str;
use Mews\Captcha\Facades\Captcha;


class OSSPIDLoginController extends Controller
{

    protected $osspid;

    public function __construct()
    {
        // For multi client
        if (!is_object($this->osspid)) {
            $this->osspid = new Osspid(array(
                'client_id' => config('osspid.osspid_client_id'),
                'client_secret_key' => config('osspid.osspid_client_secret_key'),
                'callback_url' => config('app.project_root') . '/osspid-callback'
            ));
        }

    }

    public function osspidCallback(Request $request)
    {
        try {
            $oauth_encrypted_data = $request->get('oauth_data');
            $oauth_token = $request->get('oauth_token');
            Session::put('oauth_token', $oauth_token);
            $encryptor = new Encryptor();
            $oauth_data = json_decode($encryptor->decrypt($oauth_encrypted_data));


            // In case of invalid access token
            if ($oauth_token == '') {
                Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDC101]');
                return redirect()->to('/login');
            }

            // In case of invalid oauth data
            if (strlen($oauth_token) == 0) {
                Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDC102]');
                return redirect()->to('/login');
            }
            $user_full_name = $oauth_data->user_full_name;
            $email = $oauth_data->user_email;
            // Validate oauth token with server
            $verifyOauthToken = $this->osspid->verifyOauthToken($oauth_token, $email);


            if ($verifyOauthToken) {
                //Function to request for increasing oAuth token expire time
//                $this->osspid->requestForIncreaseOauthTokenExpireTime($oauth_token, $email);
                $getAlreadyUser = Users::where('user_email', $email)->first();

                /*
                 * if this is new user then go to signup page
                 */
                if ($getAlreadyUser == '') {
                    Session::put('oauth_data', $oauth_data);
                    Session::put('oauth_token', $oauth_token);
//                    return redirect()->to('/osspid_signUp/');
                    return redirect()->to('client/signup/identity-verify');
                } else if ($getAlreadyUser->user_status == 'rejected') {
                    Session::put('oauth_data', $oauth_data);
                    Session::put('oauth_token', $oauth_token);
//                    return redirect()->to('/osspid_signUp/');
                    return redirect()->to('client/signup/identity-verify');
                } /*
                 * if this is old user then login
                 * check all issue like user type check, security profile check, session set etc
                 * if everything ok then go to dashboard else back to login
                 */
                else {

                    // user sign in
                    $loggedin = Auth::loginUsingId($getAlreadyUser->id);

                    if (!$loggedin) {
                        Session::flash('error', 'Login failed. Please reload the page and try again.');
                        return redirect()->to('/login');
                    }
                    $loginCheck = new LoginController();

                    if ($loginCheck->checkMaintenanceModeForUser() === true) {
                        $error_msg = session()->get('error');
                        $this->osspidLogout();
                        Session::flash('error', $error_msg. '[MMFU101]');
                        return redirect()->to('/login');
                    }

//                     User type root active status and company activate status checking
                    $userTypeRootStatus = $loginCheck->_checkUserTypeRootActivation(Auth::user()->user_type, $is_ajax_request = true);
                    if ($userTypeRootStatus['result'] == false) {
                        $this->osspidLogout();
                        Session::flash('error', $userTypeRootStatus['msg']);
                        return redirect()->to('/login');
                    }

                    // user old session destroy and setting new session
                    $loginCheck->killUserSession(Auth::user()->id);

                    // if this user is approved but not active currently, then logout
                    if (Auth::user()->is_approved != 1) {
                        $this->osspidLogout();
                        Session::flash('error', 'The user is not approved, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
                        return redirect()->to('/login');
                    }

                    // if this user is approved but not active currently, then logout
                    if (Auth::user()->is_approved == 1 && Auth::user()->user_status != 'active') {
                        $this->osspidLogout();
                        Session::flash('error', 'The user is not active, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
                        return redirect()->to('/login');
                    }
                    // if this user is not verified in system then go back
                    if (Auth::user()->user_verification == 'no') {
                        $this->osspidLogout();
                        Session::flash('error', 'The user is not verified in ' . config('app.project_name') . ', please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
                        return redirect()->to('/login');
                    }
                    // Check security profile of user e.g : IP, working hour etc.
                    if ($loginCheck->_checkSecurityProfile() == false) {
                        $error_msg = session()->get('error');
                        $this->osspidLogout();
                        return redirect()->to('/login')->with('error', $error_msg);
                    }

                    // Check Maintenance Mode
//                    if ($loginCheck->checkMaintenanceModeForUser() === true) {
//                        $error_msg = session()->get('error');
//                        $this->osspidLogout();
//                        Session::flash('error', $error_msg);
//                        return redirect()->to('/login');
//                    }

                    // to protect restricted user
                    if ($loginCheck->_protectLogin(Auth::user()->user_type) === false) {
                        $this->osspidLogout();
//                        Session::flash('error', 'The login type is inactive or restricted for this user !');
                        return redirect()->to('/login');
                    }


                    // put user info in session
                    if ($loginCheck->_setSession() == false) {
                        $this->osspidLogout();
                        Session::flash('error', 'Session expired');
                        return redirect()->to('/login');
                    }
                    // Kill previous session and set a new session.
                    $loginCheck->killUserSession(Auth::user()->id);
                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());

                    // Set delegated user id in session && redirect to delegation remove page
                    if (in_array(Auth::user()->user_type, ['4x404'])) {
                        if (Auth::user()->delegate_to_user_id != 0) {
                            Session::put('sess_delegated_user_id', Auth::user()->delegate_to_user_id);
                            return \redirect()->to('/users/delegate');
                        }
                    }


                    // Login user and redirect to dashboard/profile
                     CommonFunction::GlobalSettings();

                    $redirectPath = '/dashboard';
                    $sessionMsg = "Logged in successfully, Welcome to " . config('app.project_name');

                    if ($getAlreadyUser->first_login == 0) {
                        $getAlreadyUser->first_login = 1;
//                        $sessionMsg = '<strong>Dear user,</strong><br><br>
//                <p>We noticed that your profile setting does not complete yet 100%.<br/>
//                    Update your <strong>User name,
//                        Profile Image, Designation, Signature and other useful information
//                    </strong>.
//                    You can not apply any type of registration without proper informational profile.
//                    <br><br>Thanks<br>' . config('app.project_name') . '</p>';
                    }

                    $getAlreadyUser->save();

                    /*
                     * If the user is connected to only one company, that company will be considered
                     * as his working company and login with user type of that company.
                     * If there are multiple companies, they will be taken to the specified page
                     * to select the working company.
                     */
                    if (in_array(Auth::user()->user_type, ['5x505'])) {

                        $companyIds = CommonFunction::getUserAllCompanyIdsWithZero();
                        // If the user have only one company then set all automatically
                        if (count($companyIds) > 1) {
                            return \redirect()->to('company-association/select-company');
                        }
                    }

                    Session::flash('success', $sessionMsg);

                    return redirect()->to($redirectPath);
                }
            } else {
                Session::flash('error', 'Invalid oauth token.' . $verifyOauthToken);
                return redirect()->to('/login');
            }

        } catch (\Exception $e) {
            Auth::logout();
            return redirect()->to('/login');
        }
    }




    // For First Client
    public function osspidLogout()
    {
        $oauth_token = Session::get('oauth_token');

        // Logout from the OSS-PID

        if (Auth::user()) {
            $this->osspid->logoutFromOsspid($oauth_token, Auth::user()->user_email);
            Users::where('id', Auth::user()->id)->update(['login_token' => '']);
        }
        Session::flush();
        Auth::logout();
        UtilFunction::entryAccessLogout();
        Session::getHandler()->destroy(Session::getId());
        Session::flush();
        Auth::logout();
        return redirect('/');

//        $oauth_token = Session::get('oauth_token');
//        if ($oauth_token == '') {
//            Auth::logout();
//            return redirect()->to('/login');
//        }
//        // Logout from the OSS-PID
//
//
//        if (Auth::user()) {
//            $this->osspid->logoutFromOsspid($oauth_token, Auth::user()->user_email);
//            UsersModel::where('id', Auth::user()->id)->update(['login_token' => '']);
//        }
//
//        $this->entryAccessLogout();
//        Session::getHandler()->destroy(Session::getId());
//        Session::flush();
//        Auth::logout();
//
//        return redirect()->to('/login');
    }



    /*
     * Login process check function
     */
    public function reCaptcha()
    {
        return Captcha::img();
    }

}
