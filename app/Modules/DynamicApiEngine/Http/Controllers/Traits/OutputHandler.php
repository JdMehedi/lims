<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;
use App\Modules\DynamicApiEngine\Models\DynamicApiOutputs;
use Illuminate\Support\Facades\DB;
trait OutputHandler
{


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleOutputData()
    {
        $api_output_info = DynamicApiOutputs::where([
            'api_id' => self::$base_api_info->id,
            'query_type' => '1'// master query
        ])->first();
        if (!isset($api_output_info)) {
            self::apiInstantResponse(400, 'Missing output instruction');
        }

        $output_sql = $api_output_info->exe_sql;
        $output_sql = $this->prepareSqlByParameterReplacingForOperation($output_sql);

        // Handling dynamic output json response
        $output_array = $this->dynamicResponseBuilder($output_sql);

        $encodedJSON = json_encode($output_array, JSON_UNESCAPED_UNICODE);
        $removed_extra_char_from_json = str_replace(array("\n", "\t", "\r", "\\"), '', $encodedJSON);
        $prepared_string_to_decode = preg_replace(array('/\s\s+/', '/\"\[\{\"/', '/\}\]\"/'), array(' ', '[{"', '}]'), $removed_extra_char_from_json);
        $data_array = json_decode($prepared_string_to_decode);

        if ($data_array == null) {
            $data_array = $output_array;
        }

        $this->storeResponseDataLog($data_array);

        return response()->json(ApiResponse::apiFinalResponse(200, 'SUCCESSFUL', $data_array), 200);
    }


    /**
     * @param $output_sql
     * @return mixed
     */
    public function dynamicResponseBuilder($output_sql)
    {
        $formatted_sql = $output_sql;
        preg_match('/#(.*?)#/', $output_sql, $subQueryArray);

        if ($subQueryArray == []
            || !isset($subQueryArray)) {
            $formatted_sql = $this->prepareSqlByParameterReplacingForOperation($output_sql);
            return DB::select(DB::raw($formatted_sql));
        }

        $preg_matchSearchBy = $subQueryArray[0];  // returns like #SQ_1#
        $preg_matchSearchResult = $subQueryArray[1]; // returns like SQ_1

        preg_match('/SQ_(\d+)/', $preg_matchSearchResult, $subQueryIDArray);  //  '/SQ_(.d?)/'

        if (isset($subQueryIDArray[1])) {
            $subQueryID = $subQueryIDArray[1]; // returns like 1
            $sql = DynamicApiOutputs::where([
                'id' => $subQueryID,
                'api_id' => self::$base_api_info->id,
                'query_type' => '2'// sub query
            ])->first();

            if (!isset($sql->exe_sql)) {
                $result = str_replace($preg_matchSearchBy, addslashes('NOT FOUND'), $output_sql);
                return $this->dynamicResponseBuilder($result);
            }

            $formatted_sql = $this->prepareSqlByParameterReplacingForOperation($sql->exe_sql);
        }
        $output_array = DB::select(DB::raw($formatted_sql));

        $result = str_replace($preg_matchSearchBy, addslashes(json_encode($output_array, JSON_UNESCAPED_UNICODE)), $output_sql);
        return $this->dynamicResponseBuilder($result);

    }

}
