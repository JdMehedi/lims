<?php

namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Modules\CompanyProfile\Models\CompanyInfo;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\UserLogs;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\Http;


class CommonController extends Controller
{
    public function activitiesSummary()
    {
        $userType = CommonFunction::getUserType();
        $user_logs = UserLogs::where('user_id', '=', CommonFunction::getUserId())
            ->where('updated_at', '<=', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))
            ->count();

        $totalNumberOfAction = ProcessHistory::join('process_type', 'process_type.id', '=',
            'process_list_hist.process_type_id')
            ->where('process_type.status', 1)
            ->where('process_type.active_menu_for', 'like', "%$userType%")
            ->where('updated_by', CommonFunction::getUserId())
            ->where('process_list_hist.updated_at', '<=', Carbon::now()->subMonth())
            ->groupBy('name')
            ->select(DB::raw('count(process_list_hist.process_type_id) as totalApplication'), 'name')
            ->get();

//        dd($totalNumberOfAction);

        $currentPendingYourDesk = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_type.status', 1)
            ->where('process_type.active_menu_for', 'like', "%$userType%")
            ->where('process_list.desk_id', '=', CommonFunction::getUserDeskIds())
            ->where('process_list.updated_at', '<=', Carbon::now()->subMonth()->format('Y-m-d H:i:s'))
            ->groupBy('process_type.name')
            ->select(DB::raw('count(process_list.process_type_id) as totalApplication'), 'process_type.name')
            ->get();

        $page_header = 'Activities Summary';
        return view('Settings::activities-summary.list', compact('user_logs', 'totalNumberOfAction', 'currentPendingYourDesk', 'page_header'));
    }
    public static function makeCurlRequest($url, $headers = []): array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return ['error:' => $error_msg, 'http_code' => intval($http_code), 'data' => ['']];
        } else {
            if ($http_code === 200) {
                // Convert XML to SimpleXMLElement
                $xmlObject = simplexml_load_string($result);

                if ($xmlObject === false) {
                    return ['error' => 'Invalid XML format', 'http_code' => intval($http_code), 'data' => ['']];
                }

                // Convert XML to JSON
                $jsonResponse = json_encode($xmlObject, JSON_PRETTY_PRINT);

                // Decode JSON to array (if needed)
                $response = json_decode($jsonResponse, true);
                return [
                    'http_code' => intval($http_code),
                    'data' => $response
                ];
            } else {
                return ['error' => 'Request failed', 'http_code' => intval($http_code), 'data' => ['']];
            }

        }
    }
    public function getRJSCInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $companyId = $request->query('company_id');
            $incorporate_details = CompanyInfo::where('id', $companyId)->first(['incorporation_num', 'incorporation_date']);

            $incorporation_num = $incorporate_details->incorporation_num ?? null;
            $incorporation_date = $incorporate_details->incorporation_date ?? null;

            // Format the date
            $formatted_date = $incorporation_date ? Carbon::parse($incorporation_date)->format('d-m-Y') : null;

            $params = [
                'aid' => config('constant.RJSC_AID'),
                'sid' => config('constant.RJSC_SID'),

            ];
            $params['INCORP_NUMBER'] = $incorporation_num;
            $params['INCORP_DATE'] = $formatted_date;

//            $params['INCORP_NUMBER'] = config('constant.rjsc_in_corp_number');
//            $params['INCORP_DATE'] = config('constant.rjsc_in_corp_date');
            $base_url = config('constant.RJSC_URL');
            // concatenate url with parameter
            $url = $base_url . '?' . http_build_query($params);
            $response = self::makeCurlRequest($url);
            if (!empty($response['data']['entity'])) {
                $entity = $response['data']['entity'];
                $content = view('shareholder_for_resubmit', compact('entity'))->render();
                return response()->json(['message' => $content]);
            } else {
                $error = $response['data']['msg'];
                $content = view('shareholder_for_resubmit', compact('error'))->render();
                return response()->json(['message' => $content]);
            }
        } catch (RequestException $e) {
            return response()->json(['message' => ''], 500);
        }
    }

    public function getAttachment($fileurl){
        $file = Encryption::decode($fileurl);
        $urlInfo = explode('@expiredtime@',$file);
        if (!Carbon::parse($urlInfo[1])->isPast()) {
           return response()->file(public_path($urlInfo[0]));
        }else{
            $response = "URL expired" ;
            return $response;
        }
    }

    public function migration(Request $request, $module_name = '')
    {

        if (CommonFunction::getUserType() == '1x101') {
            $migrationPath = $module_name ? "/app/Modules/$module_name/database/migrations" : '/database/migrations';

            Artisan::call('migrate', [
                '--path' => $migrationPath,
                '--force' => true,
                '--pretend' => true,
            ]);

            DB::table('migration_audit')->insert([
                'title' => 'Migration',
                'details' => Artisan::output(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ]);

            Artisan::call('migrate', [
                '--path' => $migrationPath,
                '--force' => true,
            ]);
            echo $module_name . ":   ";
            return Artisan::output();

        }

    }

}
