<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07-Mar-21
 * Time: 11:01 AM
 */

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;

use App\Modules\DynamicApiEngine\Http\Controllers\DynamicApiEngineController;
use App\Modules\DynamicApiEngine\Models\DynamicApiClients;
use Illuminate\Support\Facades\Storage;

trait ClientAuthentication
{

    public function validateClient()
    {
        $clientID = trim(isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "");
        $clientSecret = trim(isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "");
        $grantType = self::$requestData->get('grant_type');
       // Storage::put('attempt1.txt', $grantType);

        $apiClientData = DynamicApiClients::where([
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'grant_type' => $grantType,
            'status' => 1])->first();

        if (!isset($apiClientData)) {
            DynamicApiEngineController::apiTokenResponse('Invalid client credentials, please recheck.', 404);
        }

        self::$clientData = $apiClientData;
        self::$clientID = $apiClientData->client_id;
        self::$clientSecret = $apiClientData->client_secret;
    }
}
