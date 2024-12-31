<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LoginController;
use App\Libraries\CommonFunction;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        /*
         * Redirect URL must should be call outside of checkAdmin middleware
         */
        $user_type = Auth::user()->user_type;
        $user = explode("x", $user_type); // $user[0] array index stored the users level id

        $security_check_time = Session::get('security_check_time');
        $current_time = Carbon::now();
        $difference_in_minute = $current_time->diffInMinutes($security_check_time);

        /*
         * Some common conditions will be checked periodically. (Ex: after every 3 minutes and after login)
         * If there is a condition that needs to be checked for each URL,
         * then it has to be given above this condition.
         */
        if ($difference_in_minute >= 3 or (Session::get('is_first_security_check') == 0)) {

            Session::put('is_first_security_check', 1);
            $security_check_time = Carbon::now();
            Session::put('security_check_time', $security_check_time);

            // check the user is approved
            if (Auth::user()->is_approved == 0) {
                return redirect()
                    ->intended('/dashboard')
                    ->with('error', 'You are not approved user ! Please contact with system admin');
            }

            // while user try to login
            $LgController = new LoginController;
            if (!$LgController->_checkSecurityProfile($request)) {
                Auth::logout();
                /*
                 *
                 * MOBILE LOGOUT CHECK
                 *
                 */
                return redirect('/login')
                    ->with('error', 'Security profile does not support in this time for operation.');
            }

        }

        // But, for others module/application it is mandatory
        if (CommonFunction::checkEligibility() != 1 and (in_array($user_type, ['5x505']))) {
            Session::flash('error', 'You are not eligible for apply ! [CAM1020]');
            return redirect('dashboard');
        }

        $uri = $request->segment(1);
        if($uri == 'client' || $uri == 'vue'){
            $uri = $request->segment(2);
        }

        switch (true) {
            case ($uri == 'dashboard' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nix-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nix-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nix-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nix-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bpo-or-call-center-new-app' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bpo-or-call-center-renew-app' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bpo-or-call-center-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bpo-or-call-center-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vsat-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vsat-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vsat-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vsat-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'isp-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iig-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iig-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iig-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iig-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iptsp-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iptsp-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iptsp-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'iptsp-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vts-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vts-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vts-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'vts-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tvas-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tvas-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tvas-license-ammendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tvas-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'ss-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'ss-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'ss-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'ss-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'igw-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'igw-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'igw-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'igw-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'icx-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'icx-license-renew' and (in_array($user[0], [1, 2, 3, 4, 5, 6]))):
            case ($uri == 'icx-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'icx-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nttn-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nttn-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nttn-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'nttn-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'itc-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'itc-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'itc-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'itc-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mno-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mno-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mno-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mno-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'scs-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'scs-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'scs-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'scs-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tc-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tc-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tc-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'tc-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mnp-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mnp-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mnp-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'mnp-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bwa-license-issue' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bwa-license-renew' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bwa-license-amendment' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'bwa-license-cancellation' and (in_array($user[0], [1,10, 2, 3, 4, 5, 6]))):
            case ($uri == 'users' and (in_array($user[0], [1,10, 2, 4, 5, 6, 8]))):
            case ($uri == 'company-association' and (in_array($user[0], [1, 2, 4, 5, 6, 7, 8]))):
            case ($uri == 'documents' and (in_array($user[0], [1,10, 2, 4, 5, 6]))):
            case ($uri == 'process-path' and (in_array($user[0], [1]))):
            case ($uri == 'industry-new' and (in_array($user[0], [1, 4, 5, 6]))):
            case ($uri == 'company-profile' and (in_array($user[0], [5]))):
            case ($uri == 'industry-re-registration' and (in_array($user[0], [1, 4, 5, 6]))):
            case ($uri == 'spg' and (in_array($user[0], [1, 4,5, 6]))):
            case ($uri == 'ipn' and (in_array($user[0], [1,2]))):
            case ($uri == 'settings' and (in_array($user[0], [1,10,2]))):
                return $next($request);
            default:
                Session::flash('error', 'Invalid URL ! error code(' . $uri . '-' . $user[0] . ')');
                return redirect('dashboard');
        }
    }
}
