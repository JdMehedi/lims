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
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;

trait TokenGeneration
{

    public function generateToken()
    {
      //  Storage::put('attempt1.txt', json_encode($_SERVER));

        $key = env('D_API_TOKEN_ENCRYPTION_KEY','AGDFRTUK@REYU');//self::$clientData->token_encryption_key;
        $expireIn = intval(self::$clientData->token_expire_time);
        $tokenType = "bearer";
        $credentialData = array(
            'client_tbl_id' => self::$clientData->id,
            'client_id' => self::$clientID,
            'client_secret' => self::$clientSecret,
            "exp" => time() + $expireIn
        );
        $jwtToken = JWT::encode($credentialData, $key);

        $customEncryptedToken = (new DataEncryptor())->encrypt($jwtToken);

        DynamicApiEngineController::apiTokenResponse('SUCCESS', 200, $customEncryptedToken, $expireIn, $tokenType);//TODO:: this method can be called directly by using use keyword and add that trait in this trait

    }
}
