<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;

use Illuminate\Support\Facades\DB;

trait OperationalFunctions
{

    public function LF_JsonEncodeParameters(){

       $request_data = self::$request->all();
       $pdf_type = $request_data['data']['pdf_type'];
       $ref_id   = $request_data['data']['ref_id'];
       $param    = json_encode(self::$request->all());
       $timestamp = date("Y-m-d H:i:s");

       $sql = "INSERT INTO pdf_generator (pdf_type, ref_id, STATUS, param,file_name,created_at) VALUES ('".$pdf_type."', '".$ref_id."', '0', '".$param."','NULL', '".$timestamp."')";
       $result =  DB::statement($sql);

       if($result == true){
           $key_name = self::$temp_variable_for_dynamic_method_param->key;
           self::$operationObjArray[$key_name] = $param;
       }else{
           self::apiInstantResponse(500, 'Something wrong');
       }

    }

}
