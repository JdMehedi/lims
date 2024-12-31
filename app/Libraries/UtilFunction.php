<?php

/**
 * Created by Zaman
 * Date: 2/7/2017
 * Time: 9:51 PM
 */

namespace App\Libraries;

use App\Modules\Reports\Models\FavReports;
use App\Modules\Reports\Models\Reports;
use App\Modules\Reports\Models\ReportsMapping;
use App\Modules\Users\Models\FailedLogin;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;

//use Session;

class UtilFunction
{
    public static function processVerifyData($applicationInfo)
    {
        $data = '#ID' . $applicationInfo->id . '#S' . $applicationInfo->status_id . '#D' . $applicationInfo->desk_id .
            '#U' . $applicationInfo->user_id . '#P' . $applicationInfo->office_id . '#T' . $applicationInfo->tracking_no .
            '#C' . $applicationInfo->created_by . '#L' . $applicationInfo->locked_by;
        return $data;
    }

    public static function getVisitorRealIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];


        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        // if above code is not working properly then
        /*
        if(!empty($_SERVER['HTTP_X_REAL_IP'])){
            $ip=$_SERVER['HTTP_X_REAL_IP'];
        }
        */
        return $ip;

    }

    /*
     * Insert login info in user_logs table
     */
    public static function entryAccessLog()
    {

        // access_log table.
        $str_random =  Str::random(10);
        $insert_id = DB::table('user_logs')->insertGetId(
            array(
                'user_id' => Auth::user()->id,
                'login_dt' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'ip_address' => UtilFunction::getVisitorRealIP(),
                'access_log_id' => $str_random
            )
        );

        Session::put('access_log_id', $str_random);
    }

    /*
     * Entry access for Logout
     * update logout time in user_logs table
     */
    public static function entryAccessLogout()
    {
        $access_log_id = Session::get('access_log_id');
        DB::table('user_logs')->where('access_log_id', $access_log_id)->update(['logout_dt' => date('Y-m-d H:i:s')]);
    }

    /*
 * Store all failed login history
 */
    public static function _failedLogin($data=[])
    {
        $ip_address = UtilFunction::getVisitorRealIP();
        $user_email = $data['user_email'];
        FailedLogin::create(['remote_address' => $ip_address, 'user_email' => $user_email]);
    }
    public static function isAllowedToViewFvrtReport($report_id)
    {
        if (in_array(Auth::user()->user_type, ['1x101','10x101', '15x151'])) // report admin
        {
            return true;
        }
        $is_fvrt = FavReports::where('report_id', $report_id)
            ->where('user_id', Auth::user()->id)
            ->count();
        if ($is_fvrt > 0) {
            $is_publish = Reports::where([
                'report_id' => $report_id,
                'status' => 1
            ])->count();
            $is_assigned = ReportsMapping::where([
                'report_id' => $report_id,
                'user_type' => Auth::user()->user_type
            ])->count();
            if ($is_publish == 0 || $is_assigned == 0) {
                return false;
            }
        }
        return true;
    }
}
