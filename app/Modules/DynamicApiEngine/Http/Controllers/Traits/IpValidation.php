<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 07-Mar-21
 * Time: 11:01 AM
 */

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;

use App\Libraries\UtilFunction;
use App\Modules\DynamicApiEngine\Http\Controllers\DynamicApiEngineController;
use App\Modules\DynamicApiEngine\Models\DynamicApiClients;

trait IpValidation
{

    public function validateIP()
    {
        $base_api_info = isset(self::$base_api_info) ? self::$base_api_info : [];
        $request_data = isset(self::$request) ? self::$request : [];

        $api_Client_info = DynamicApiClients::leftjoin('d_api_client_mapping', 'd_api_client_mapping.client_id', '=', 'd_api_clients.id')
            ->leftjoin('d_api_list', 'd_api_list.id', '=', 'd_api_client_mapping.api_id')
            ->where('d_api_list.id', $base_api_info->id)
            ->first(['d_api_clients.*']);
        //  dd($api_Client_info);

        $allowedIPs = explode(',', $api_Client_info->allowed_ips);

        $isValidIP = false;
        foreach ($allowedIPs as $ip) {
            // if ($request_data->ip() == $ip){
            if (UtilFunction::getVisitorRealIP() == $ip) {
                $isValidIP = true;
                break;
            }
        }

        if ($isValidIP == false) {
            // self::apiInstantResponse(400, 'Unauthorized request ip ( ' . $request_data->ip() . ' ) found');

            self::apiInstantResponse(400, 'Unauthorized request ip ( ' . UtilFunction::getVisitorRealIP() . ' ) found');
        }
    }
}
