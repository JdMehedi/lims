<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;
use App\Modules\DynamicApiEngine\Models\DynamicApiParameters;
use App\Modules\DynamicApiEngine\Models\DynamicApiParametersValidation;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
//use SimpleXMLElement;

trait RequestParameterHandler
{

    protected static $parameterValidationErrors = [];

    public function handleRequestParameters()
    {
        # API parameters is exists
        $this->checkRegisteredParameter();
        # API parameter mentioned validation
        $this->checkParameterGatePass();
    }

    /**
     * @return array
     */

    public function checkRegisteredParameter()
    {
//        $xml = new SimpleXMLElement(file_get_contents("php://input"));
//        $input_params = simplexml_load_string($xml->asXML());
//        foreach($input_params as $key=>$input_param){
//            self::$request->add([$key, (string)$input_param]);
//        }
//
//        print_r(json_decode(file_get_contents("php://input"), true));exit;
//        dd(self::$request);
        $apiID = isset(self::$base_api_info->id) ? self::$base_api_info->id : 0;
        $apiRegisteredParameters = DynamicApiParameters::where('api_id', $apiID)
            ->whereIn('request_parameter_name', array_keys(self::$request->all()))
            ->get(['request_parameter_name'])->toArray();

        if (count($apiRegisteredParameters) < count(self::$request->all())) {
            $apiRegisteredParametersSingleDArray = array_column($apiRegisteredParameters, 'request_parameter_name');
            $unmatchedArray = array_diff(array_keys(self::$request->all()), $apiRegisteredParametersSingleDArray);
            $unmatchedArrayToString = implode(', ', $unmatchedArray);

            self::apiInstantResponse(400, 'Unregistered parameter found ( ' . $unmatchedArrayToString . ' )');
        }
    }

    /**
     * @param NULL
     * @return mixed
     */
    public function getApiParameters()
    {
        $apiID = isset(self::$base_api_info->id) ? self::$base_api_info->id : 0;
        self::$api_parameters = DynamicApiParameters::where('api_id', $apiID)->get();
    }

    /**
     */
    public function checkParameterGatePass()
    {
        $paramDataFromRequest = self::$request->all();
        $apiID = isset(self::$base_api_info->id) ? self::$base_api_info->id : 0;
        $parameters = DynamicApiParametersValidation::leftjoin('d_api_request_parameter', 'd_api_request_parameter.id', '=', 'd_api_request_parameter_validation.request_parameter_id')
            ->where('api_id', $apiID)
            ->get([
                'api_id',
                'request_parameter_name',
                'validation_method',
                'message',
                'exe_sql'
            ]);

        if ($parameters->isEmpty() == true) {
            return true;
        }


        foreach ($parameters as $key => $param) {

            $this->checkParameterValidation($paramDataFromRequest, $param);

        }


        if (self::$parameterValidationErrors != []) {
            self::apiInstantResponse(400, 'Parameter validation exception occurred', [], 'Error', self::$parameterValidationErrors);
        }

        return true;

    }


    /**
     * @param $paramDataFromRequest
     * @param $param
     * @param $api_parameters
     * @return bool
     */
    public function checkParameterValidation($paramDataFromRequest, $param)
    {

        $caseName = $param->validation_method;
        $firstValue = 0;
        $secondValue = 0;
        if (strpos($param->validation_method, ':') !== false) {
            $explodedArray = explode(':',$caseName);
            $caseName = $explodedArray[0];
            $firstValue = $explodedArray[1];
            $secondValue = isset($explodedArray[2]) ? $explodedArray[2] : 0;
        }

        switch ($caseName) {

            case 'REQUIRED':
                if (!isset($paramDataFromRequest[$param->request_parameter_name]) || $paramDataFromRequest[$param->request_parameter_name] == "") {
                    array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                }
                break;

            case 'SQL':
                $response = $this->paramValidationSqlCheck($param->exe_sql);
                if(!isset($response) || $response == [] || $response == 0){
                    array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                }
                break;

            case 'EMAIL':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $paramDataFromRequest[$param->request_parameter_name])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'STRING':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if (is_numeric($paramDataFromRequest[$param->request_parameter_name])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'NUMBER':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if (gettype($paramDataFromRequest[$param->request_parameter_name]) !='integer') {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'URL':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if (filter_var($paramDataFromRequest[$param->request_parameter_name], FILTER_VALIDATE_URL) == false) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'JSON':
//                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
//                    if ( ! preg_match('/\{.*\:\{.*\:.*\}\}/', $paramDataFromRequest[$param->request_parameter_name])) {
//                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
//                    }
//                }
                break;

            case 'BOOLEAN':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( ! in_array($paramDataFromRequest[$param->request_parameter_name], [0,1,'true','false','True','False','TRUE','FALSE'])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'ALPHA':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( ! ctype_alpha ($paramDataFromRequest[$param->request_parameter_name])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'ALPHA_NUM':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( ! ctype_alnum ($paramDataFromRequest[$param->request_parameter_name])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'ALPHA_DASH':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( ! preg_match('/^[A-Za-z0-9_]+$/', $paramDataFromRequest[$param->request_parameter_name])) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'MAX':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( strlen($paramDataFromRequest[$param->request_parameter_name]) >  $firstValue) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'MIN':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( strlen($paramDataFromRequest[$param->request_parameter_name]) <  $firstValue) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'LENGTH':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if ( strlen($paramDataFromRequest[$param->request_parameter_name]) !=  $firstValue) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            case 'LENGTH_BETWEEN':
                if ($this->isParameterExists($paramDataFromRequest, $param->request_parameter_name)) {
                    if (strlen($paramDataFromRequest[$param->request_parameter_name]) < $firstValue || strlen($paramDataFromRequest[$param->request_parameter_name]) > $secondValue) {
                        array_push(self::$parameterValidationErrors, ["field_name" => $param->request_parameter_name, "message" => $param->message]);
                    }
                }
                break;

            default:
                // return true;

        }
        // return true;
    }

    /**
     * @param $paramDataFromRequest
     * @param $paramName
     * @return bool
     */

    public function isParameterExists($paramDataFromRequest, $paramName)
    {
        if (!isset($paramDataFromRequest[$paramName])
            || $paramDataFromRequest[$paramName] == "") {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @param $sql
     * @return mixed
     */

    public function paramValidationSqlCheck($sql){
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($sql);
        $output_sql = $this->sqlGatepass($output_sql);

        $allowed_query_keywords = ['DELETE ', 'INSERT ', 'UPDATE '];
        $keyword = strtoupper(substr($sql, 0, 6));
        if (array_search($keyword, $allowed_query_keywords) == true ) {
            self::apiInstantResponse(401, 'Unauthorized statement found for parameter validation');
        }

        $response = DB::select(DB::raw($output_sql));

        if(empty($response)){ return 0; }

        // if(isset($response[0]) && array_values((array)$response[0])[0] == 0){
        //     return 0;
        // }

        return $response;
    }

}
