<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;


use App\Modules\DynamicApiEngine\Models\DynamicApiOperationsSet;
use Illuminate\Support\Facades\DB;

trait OperationSetsHandler
{
    use OperationalFunctions;

    /**
     * @param $base_api_info
     * @param $api_parameters
     */
    public function handleSetOfOperations()
    {
        $base_api_info = isset(self::$base_api_info) ? self::$base_api_info : [];

        $operations = DynamicApiOperationsSet::where('api_id', $base_api_info->id)->orderBy('priority', 'ASC')->get();
        if (count($operations) > 0) {
            DB::beginTransaction();
            foreach ($operations as $key => $operation) {
                if ($operation->operation_type == 'SQL') {
                    $this->handleSqlOperation($operation);
                } elseif ($operation->operation_type == 'LF') {
                    $this->handleLibraryFunctionOperation($operation);
                }
            }
            DB::commit();
        }
    }

    /**
     * @param $operation
     * @return mixed
     */
    public function handleSqlOperation($operation)
    {
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($operation->exe_SQL);
        $output_sql = $this->sqlGatepass($output_sql);
        if ($output_sql == false) {
            self::apiInstantResponse(500, 'Something wrong');
        }

//        $isValidSql = DB::statement($output_sql);
//        if (!$isValidSql) {
//            self::apiInstantResponse(500, 'Something wrong');
//        }

        $operationType = $this->checkOperationType($operation->exe_SQL);

        switch ($operationType) {
            case "INVALID":

                break;
            case "SQL_SELECT":
                $this->sqlSelectOperation($operation);

                break;
            case "SQL_INSERT":
                $this->sqlInsertOperation($operation);

                break;
            case "SQL_UPDATE":
                $this->sqlUpdateOperation($operation);

                break;
            case "FUNCTION":

                break;
            default:

        }

    }

    /**
     * @param $api_parameters
     * @param $sql
     * @return mixed
     */
    public function prepareSqlByParameterReplacingForOperation($sql)
    {
        $query = $sql;
        $replaceArray = [];

        /**
         * Searching for OPERATION_SET variables like: {#USER_DATA->user_sub_type#}
         */

        if (strpos($sql, '{#') !== false) {

            preg_match_all('/{#(.*?)#}/', $sql, $substringArray);

            $replaceArray = [];
            foreach ($substringArray[1] as $arrayVal) {

                if (strpos($arrayVal, '->') !== false) {
                    $arrayIndex = explode("->", $arrayVal)[0];
                    $objectKey = explode("->", $arrayVal)[1];
                    $replaceArrayKey = '{#' . $arrayVal . '#}';

                    if (!array_key_exists($arrayIndex, self::$operationObjArray)) {
                        continue;
                    }

                    $replaceArrayVal = self::$operationObjArray[$arrayIndex]->$objectKey;
                    $replaceArray[$replaceArrayKey] = $replaceArrayVal;
                } else {
                    $replaceArrayKey = '{#' . $arrayVal . '#}';
                    $replaceArray[$replaceArrayKey] = self::$operationObjArray[$arrayVal];
                }

            }
            $query = str_replace(array_keys($replaceArray), array_values($replaceArray), $sql);

        }


        /**
         * Searching for single dimensional variable, JSON BASED MULTI-DIMENSIONAL
         * Variables like bellow
         * ---------------------
         * General: {$variable_name}
         * Multi-Dimensional: {$obj->property_name}, {$obj->obj->property_name}
         * With JSON encode: _je{$variable_name}, _je{$obj->property_name}, _je{$obj->obj->property_name}
         */
        preg_match_all('/(_je)?{\$.*?(->.*?)?}/', $query, $matched_multi_dimensional_variables, PREG_PATTERN_ORDER);
        $matched_multi_dimensional_variables=$matched_multi_dimensional_variables[0];
        if (count($matched_multi_dimensional_variables) > 0) {
            $matched_multi_dimensional_variables = array_unique($matched_multi_dimensional_variables);// removing duplicate params
            //$matched_multi_dimensional_variables = [];
            //$matched_multi_dimensional_variables[0]='{$file_name}';
            //dd($matched_multi_dimensional_variables);
            foreach ($matched_multi_dimensional_variables as $matched_multi_dimensional_variable) {

                // Has JSON_ENCODE FLAG
                $has_json_encode_flag = strpos($matched_multi_dimensional_variable, '_je{$') !== false;

                // Replacing special delimiters
                $matched_multi_dimensional_variable_replaced = str_replace(array('_je{$', '{$', '}'), array('', '', ''), $matched_multi_dimensional_variable);

                // getting array index
                $arrayIndex = explode("->", $matched_multi_dimensional_variable_replaced)[0];

                // replace array key
                $replaceArrayKey = $matched_multi_dimensional_variable;

                $arrayKeyVal = $this->arrayManipulationForMultiDJsonReq($replaceArrayKey, self::$request->all()[$arrayIndex]);
                $arrayKeyVal = $has_json_encode_flag == true ? json_encode($arrayKeyVal) : $arrayKeyVal;
                if (is_array($arrayKeyVal)) {
                    self::apiInstantResponse(400, 'Not valid request data');
                }
                $replaceArray[$replaceArrayKey] = $arrayKeyVal;
            }
        }

        if (count($replaceArray)>0) {
            $query = str_replace(array_keys($replaceArray), array_values($replaceArray), $query);
        }
        return $query;
    }

    /**
     * @param $replaceArrayKey
     * @param $fullReq
     * @return mixed
     * {$data->param->group_payment_id}
     */

    public function arrayManipulationForMultiDJsonReq($replaceArrayKey, $fullReq)
    {
        preg_match('/->(.*?.)(->)/', $replaceArrayKey, $subArray);

        if (count($subArray) == 0) {
            preg_match('/->(.*?.)}/', $replaceArrayKey, $subArrayFinalLayer);

            if ($subArrayFinalLayer == [] || !isset($subArrayFinalLayer)) {
                return $fullReq;
            }

            $onlyKey = $subArrayFinalLayer[1];
            $replaceSearch = $subArrayFinalLayer[0];

            if (!array_key_exists($onlyKey, $fullReq)) {
                self::apiInstantResponse(404, 'Object ( ' . $onlyKey . ' ) not found in request');
            }
            $fullReq = $fullReq[$onlyKey];

            $afterSubtractedKey = str_replace($replaceSearch, ' ', $replaceArrayKey);
            return $this->arrayManipulationForMultiDJsonReq($afterSubtractedKey, $fullReq);
        }

        $onlyKey = $subArray[1];
        $replaceSearch = $subArray[0];

        if (!array_key_exists($onlyKey, $fullReq)) {
            self::apiInstantResponse(404, 'Object ( ' . $onlyKey . ' ) not found in request');
        }
        $fullReq = $fullReq[$onlyKey];
        $afterSubtractedKey = str_replace($replaceSearch, '->', $replaceArrayKey);

        return $this->arrayManipulationForMultiDJsonReq($afterSubtractedKey, $fullReq);

    }

    /**
     * @param $validatableString
     * @return mixed|string
     */
    public static function checkOperationType($validatableString)
    {
        $type = [0 => "INVALID", 1 => "SQL_SELECT", 2 => "SQL_INSERT", 3 => "SQL_UPDATE"];
        $index = 0;

        if (!isset($validatableString) || $validatableString == null) {
            $index = 0;
        }

        if ($validatableString != null) {
            if (substr(strtoupper($validatableString), 0, 6) == 'SELECT') {
                $index = 1;
            }
            if (substr(strtoupper($validatableString), 0, 6) == 'INSERT') {
                $index = 2;
            }
            if (substr(strtoupper($validatableString), 0, 6) == 'UPDATE') {
                $index = 3;
            }
        }

        return $type[$index];

    }

    /**
     * @param $api_parameters
     * @param $operation
     */
    public function sqlSelectOperation($operation)
    {
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($operation->exe_SQL);
        $output_sql = $this->sqlGatepass($output_sql);

        DB::statement($output_sql);

        $result = DB::select(DB::raw($output_sql));
        if (isset($result[0])) {
            $array_key = str_replace(" ", "_", $operation->key);
            return self::$operationObjArray[$array_key] = $result[0];
        }
    }


    /**
     * @param $operation
     * @return mixed
     */
    public function sqlInsertOperation($operation)
    {
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($operation->exe_SQL);
        $output_sql = $this->sqlGatepass($output_sql);
       // DB::beginTransaction();

        $is_inserted =  DB::statement($output_sql);
        if ($is_inserted == false) {
            DB::rollback();
            self::apiInstantResponse(500, 'Something wrong with inserting data');
        }

        $result = db::select(db::raw('SELECT LAST_INSERT_ID() as id'));// now build a array like select query using the last_insert_id

       // DB::commit();

        if (isset($result[0]->id)) {
            $array_key = str_replace(" ", "_", $operation->key);
            return self::$operationObjArray[$array_key] = $result[0];
        }

    }

    /**
     * @param $operation
     * @return mixed
     */

    public function sqlUpdateOperation($operation)
    {
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($operation->exe_SQL);
        $output_sql = $this->sqlGatepass($output_sql);

//        $replacedEndLineWithSpace = str_replace("\n"," ",$output_sql);
//        $formattedSQL = preg_replace('/\s\s+/', ' ', $replacedEndLineWithSpace); // removed more then one white space
//        $tableName = explode(' ',$formattedSQL)[1];

        $result = DB::statement($output_sql);

        if ($result == false){
            DB::rollback();
            self::apiInstantResponse(500, 'Could perform to update');
        }

    }

    /**
     * @param $operation
     */
    public function handleLibraryFunctionOperation($operation)
    {
        $methodName = $operation->exe_function;
        self::$temp_variable_for_dynamic_method_param = $operation;
        eval('$this->' . $methodName . ';');
    }

}
