<?php

namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\UtilFunction;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use DB;
use Illuminate\Support\Facades\Log;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $data = (object)[
                //	'user_type' => '12x432',
                'user_nid' => $user->getId(),
                'user_email' => $user->getEmail(),
                'user_full_name' => $user->getName(),
                'user_pic' => $user->avatar_original,
                'password' => Hash::make('Google'),
                'is_approved' => 1,
                'first_login' => 1,
                'last_login_type' => 'google',
                'security_profile_id' => 1
            ];
            $getAlreadyUser = Users::where('user_email', $user->getEmail())->first();

            if ($getAlreadyUser == '') {
                Session::put('oauth_token', $user->token);
                Session::put('oauth_data', $data);
                return redirect()->to('client/signup/identity-verify');
            } else {
//                dd($getAlreadyUser);
                Auth::loginUsingId($getAlreadyUser->id);
                $loginCheck = new LoginController();

                if ($loginCheck->checkMaintenanceModeForUser() === true) {
                    Auth::logout();
                    $error_msg = session()->get('error');
                    Session::flash('error', $error_msg. '[GL1020]');
                    return redirect()->to('/login');
                }
                $userTypeRootStatus = $loginCheck->_checkUserTypeRootActivation(Auth::user()->user_type, $is_ajax_request = true);
                if ($userTypeRootStatus['result'] == false) {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    Session::flash('error', $userTypeRootStatus['msg']);
                    return redirect()->to('/login');
                }



                // if this user is approved but not active currently, then logout
                if ($getAlreadyUser->is_approved != 1) {
                    //                    $loginCheck->_failedLogin($data);
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    Session::flash('error', 'The user is not approved, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
                    return redirect()->to('/login');
                }

                // if this user is approved but not active currently, then logout
                if ($getAlreadyUser->is_approved == 1 && $getAlreadyUser->user_status != 'active') {
                    //                    $loginCheck->_failedLogin($data);
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    Session::flash('error', 'The user is not active, please contact with system admin/ <a href="/users/support" target="_blank">Help line.</a>');
                    return redirect()->to('/login');
                }
                // if this user is not verified in system then go back
                if ($getAlreadyUser->user_verification == 'no') {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    Session::flash('error', 'The user is not verified in ' . config('app.project_name') . ', please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
                    return redirect()->to('/login');
                }


                // Check security profile of user e.g : IP, working hour etc.

                if ($loginCheck->_checkSecurityProfile() == false) {
                    $error_msg = session()->get('error');
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    return redirect()->to('/login')->with('error', $error_msg);
                }


                // to protect restricted user
                if ($loginCheck->_protectLogin(Auth::user()->user_type) === false) {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    //                    Session::flash('error', 'The login type is inactive or restricted for this user !');
                    return redirect()->to('/login');
                }

                if ($loginCheck->_setSession() == false) {
                    Auth::logout();
                    UtilFunction::_failedLogin($data);
                    Session::flash('error', 'Session expired !');
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

                CommonFunction::GlobalSettings();

                $getAlreadyUser->save();
                UtilFunction::entryAccessLog();
                if (in_array(Auth::user()->user_type, ['5x505'])) {
                    $companyIds = CommonFunction::getUserAllCompanyIdsWithZero();
                    // If the user have only one company then set all automatically
                    if (count($companyIds) > 1) {
                        return \redirect()->to('company-association/select-company');
                    }
                }
                return redirect()->to('/dashboard');
            }
        } catch (\Exception $e) {
            Log::error("Exception occurred: {$e->getMessage()} at {$e->getFile()}:{$e->getLine()}");
            Auth::logout();
            return redirect()->to('/login');
        }
    }
}
