<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07-Mar-21
 * Time: 11:01 AM
 */

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;

use App\Modules\DynamicApiEngine\Http\Controllers\DataEncryptor;
use App\Modules\DynamicApiEngine\Http\Controllers\DynamicApiEngineController;
use App\Modules\DynamicApiEngine\Models\DynamicApiClientMapping;
use App\Modules\DynamicApiEngine\Models\DynamicApiClients;
use \Firebase\JWT\JWT;

trait ApiTokenValidation
{

    public function validateApiToken()
    {
        //$fh = fopen(storage_path().'/test1.txt','rw+');
        //fwrite($fh,json_encode(apache_request_headers()));
        $bearerToken = null;
        $request_headers = apache_request_headers();
        $request_headers = array_change_key_case($request_headers,CASE_LOWER);
        if (isset($request_headers['authorization'])) {
            $bearerToken = $request_headers['authorization'];
        }
        if ($bearerToken == null) {
            DynamicApiEngineController::apiInstantResponse(400, 'Bearer token not found, please send bearer token with Authorization header.');
        }
        if (strpos($bearerToken, 'Bearer') === false and strpos($bearerToken, 'bearer') === false) {
            DynamicApiEngineController::apiInstantResponse(400, 'Invalid token passed, please check the documentation.');
        }
        $EncryptedToken = str_replace(array("Bearer ", "bearer "), array("", ""), $bearerToken);
        $token = (new DataEncryptor())->decrypt($EncryptedToken);

        if (isset($token) && !empty($token)) {

            try {
                $encryptionKey = env('D_API_TOKEN_ENCRYPTION_KEY','AGDFRTUK@REYU');
                $userCredentials = JWT::decode($token, $encryptionKey, ['HS256']);
                $apiClientCounter = DynamicApiClients::where(
                    ['client_id' => $userCredentials->client_id, 'client_secret' => $userCredentials->client_secret, 'status' => 1]
                )->count();
                if ($apiClientCounter == 0) {
                    DynamicApiEngineController::apiInstantResponse(400, 'Invalid passed, please re-check.');
                }

                $apiClientMappingCounter = DynamicApiClientMapping::where(
                    ['client_id' => $userCredentials->client_tbl_id, 'api_id' => self::$base_api_info->id, 'status' => 1]
                )->count();
                if ($apiClientMappingCounter == 0) {
                    DynamicApiEngineController::apiInstantResponse(400, 'API access not granted, please contact with the system admin.');
                }
                return true;
            } catch (\Exception $e) {
                DynamicApiEngineController::apiInstantResponse(401, 'Token expired, please re-generate token and send with request header.');
            }
        }

        DynamicApiEngineController::apiInstantResponse(400, 'Invalid token passed, please recheck.');
    }
}
