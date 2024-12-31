<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers;

use App\Modules\DynamicApiEngine\Http\Controllers\Traits\ClientAuthentication;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\TokenGeneration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DynamicApiAuthenticationController extends Controller
{

    use ClientAuthentication, TokenGeneration;

    protected static $clientID = null;
    protected static $clientSecret = null;
    protected static $clientData = [];
    protected static $requestData = [];


    public function __construct(Request $request)
    {

        self::$requestData = $request;
    }


    /**
     */
    public function handleTokenGenerationService()
    {
        try {

            # Checking client validation
            $this->validateClient();
            # API token generation
            $this->generateToken();


        } catch (\Exception $e) {
            DynamicApiEngineController::apiTokenResponse('Something went wrong'. $e->getMessage() . ' ------ ' . $e->getLine() . ' ---- ' . $e->getFile() . ' ---- ', 500, '', '', '');
        }
    }

}
