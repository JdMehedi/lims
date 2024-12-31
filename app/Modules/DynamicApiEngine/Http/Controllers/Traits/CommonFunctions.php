<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;


use App\Modules\DynamicApiEngine\Models\DynamicApiList;
use http\Env\Request;

trait CommonFunctions
{


    /**
     * @param $request
     * @return mixed
     */
    public function getApiInformation($request)
    {
        $apiListData = DynamicApiList::where('key', $request->segment(2))->where('is_active', 1)->first();
        if (!isset($apiListData)) {
            self::apiInstantResponse(404, 'API configuration not found');
        }
        return $apiListData;
    }

    /**
     * @param $sql
     * @return false|string
     */

    public function sqlGatepass($sql)
    {
        $base_api_info = isset(self::$base_api_info) ? self::$base_api_info : [];
        $allowed_sql_keys = is_array(json_decode($base_api_info->allowed_sql_keys)) ? json_decode($base_api_info->allowed_sql_keys) : [];

        $sql = trim($sql);
        if (strlen($sql) < 8) {
            self::apiInstantResponse(401, 'Unauthorized statement found');
            // return false;
        }
        $allowed_query_keywords = $allowed_sql_keys;
        // $allowed_query_keywords = ['SELECT ', 'INSERT ', 'UPDATE '];
        $keyword = strtoupper(substr($sql, 0, 6));

        $replaceableArray = array('/\b;base64\b/');
        $removedSpecialChar = preg_replace($replaceableArray, array(''), $sql);
        $has_semicolon = strpos($removedSpecialChar, ';');

        if (array_search($keyword, $allowed_query_keywords) !== false and $has_semicolon == false) {
            return $sql;
        } else {
            self::apiInstantResponse(401, 'Unauthorized statement found');
            //  return false;
        }
    }

    /**
     * @param $api_parameters
     * @param $sql
     * @return string|string[]
     */

    public function prepareSqlByParameterReplacing($api_parameters, $sql)
    {
        $search_by = $replace_with = [];
        foreach ($api_parameters as $k => $parameter) {
            $search_by[$parameter->request_parameter_name] = '{$' . $parameter->request_parameter_name . '}';
            $replace_with[$parameter->request_parameter_name] = isset(self::$request[$parameter->request_parameter_name]) ? self::$request[$parameter->request_parameter_name] : '';
        }
        return str_replace($search_by, $replace_with, $sql);
    }
}
