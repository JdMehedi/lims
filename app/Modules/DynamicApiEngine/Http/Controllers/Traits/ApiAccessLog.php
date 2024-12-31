<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07-Mar-21
 * Time: 11:01 AM
 */

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;

use App\Modules\DynamicApiEngine\Http\Controllers\DynamicApiEngineController;
use App\Modules\DynamicApiEngine\Models\DynamicApiClientMapping;
use App\Modules\DynamicApiEngine\Models\DynamicApiClientRequestResponse;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

trait ApiAccessLog
{

    /**
     * Store request log
     */
    public function storeRequestDataLog()
    {

        $allRequest = json_encode(self::$request->all());
        $apiID = self::$base_api_info->id;
        $clientData = DynamicApiClientMapping::where('api_id', $apiID)->first(['client_id']);

        $reqLogObj = new DynamicApiClientRequestResponse();
        $reqLogObj->client_id = $clientData->client_id;
        $reqLogObj->api_id = $apiID;
        $reqLogObj->request_json = $allRequest;
        $reqLogObj->save();

        self::$apiReqRespLogTrackingId = $reqLogObj->id;

    }

    /**
     * Store response log
     */
    public function storeResponseDataLog($response_data)
    {

        $response = json_encode($response_data);

        DynamicApiClientRequestResponse::where('id', self::$apiReqRespLogTrackingId)
            ->update([
                'response_json' => $response,
                'updated_at' => date('Y-m-d h:i:s'),
            ]);
    }
}
