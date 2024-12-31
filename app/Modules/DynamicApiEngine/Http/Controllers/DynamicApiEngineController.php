<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\ApiResponse;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\CommonFunctions;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\OperationSetsHandler;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\OutputHandler;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\RequestHeaderHandler;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\RequestParameterHandler;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\ApiTokenValidation;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\IpValidation;
use App\Modules\DynamicApiEngine\Http\Controllers\Traits\ApiAccessLog;
use Illuminate\Http\Request;

/**
 * AVAILABLE PATTERNS
 * =====================
 *
 * INPUT VARIABLES
 * ---------------------
 * General: {$variable_name}
 * Multi-Dimensional: {$obj->property_name}, {$obj->obj->property_name}
 * With JSON encode: _je{$variable_name}, _je{$obj->property_name}, _je{$obj->obj->property_name}
 *
 * SET OF OPERATION VARIABLES
 * --------------------------
 * General: {#obj->property_name}
 * With JSON encode: _je{#obj->property_name}
 */
class DynamicApiEngineController extends Controller
{
    use OperationSetsHandler, CommonFunctions, ApiResponse, RequestHeaderHandler, RequestParameterHandler, OutputHandler, ApiTokenValidation, IpValidation, ApiAccessLog;

    public static $operationObjArray = [];
    public static $apiReqRespLogTrackingId = 0;
    protected static $base_api_info = [];
    protected static $api_parameters = [];
    protected static $request = [];
    protected static $temp_variable_for_dynamic_method_param = '';


    public function __construct(Request $request)
    {
        self::$request = $request;
        self::$base_api_info = $this->getApiInformation($request);
    }


    /**
     * Handle all operations
     */
    public function mainEngine()
    {
        try {

            # IP validation
            $this->validateIP();

            # Token validation
            $this->validateApiToken();

            # Store request log
            $this->storeRequestDataLog();

            # Request header handler
            $this->handleRequestHeaders();

            # Request parameter handler
            $this->handleRequestParameters();

            # request operation set execution
            $this->handleSetOfOperations();

            # final output generation
            return $this->handleOutputData();

        } catch (\Exception $e) {
            self::apiInstantResponse(500, 'Something went wrong' . $e->getMessage() . ' ------ ' . $e->getLine() . ' ---- ' . $e->getFile() . ' ---- ');
        }
    }
}

