<?php
function keyValuePayer($perm){
    $temp = preg_replace("/[^a-zA-Z 0-9 ,]+/", "", $perm);
    $result = explode(',', $temp);
    $array = [];
    $array2 = [];
    foreach ($result as $key=>$val){
        if($key%2 == 0){
            $array[$val] = $val;
        }else{
            $array2[] = $val;
        }
    }
    $keyValue = array_combine( $array, $array2 );
    return  collect($keyValue);
}