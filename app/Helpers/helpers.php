<?php

function getList($sql)
{
    $values = array();
    $i = 0;
    if (strtoupper(substr($sql . '      ', 0, 7)) == 'SELECT ' && strpos(';', $sql) <= 1) {
        $rs = DB::select($sql);
        $fields = array_keys((array)$rs[0]);
        if (count($fields) > 1) $i = 1;
        foreach ($rs as $row) {
            $aaa = $fields[$i];
            $bbb = $fields[0];
            $values[$row->$bbb] = $row->$aaa;
        }
    } else {
        $val = explode(',', $sql);
        for ($i = 0; $i < count($val); $i++) {
            $values[$val[$i]] = $val[$i];
        }
    }
    $data = collect($values);
    return $data;
}

function createHTMLTable($arr, $max_length = 25)
{
    $arr = json_decode(json_encode($arr), true);
    $table = '<table class="table basicDataTable dataTable" id="dTable_' . time() . '" style="margin-top:0px !important;"><thead>';
    if (isset($arr[0])) {
        $keys = array_keys($arr[0]);
        $table .= '<tr>';
        foreach ($keys as $value) {
            $table .= '<th>' . $value . '</th>';
        }
        $table .= '</tr></thead><tbody>';
        foreach ($arr as $value) {
            $table .= '<tr>';
            foreach ($value as $value2) {
                $table .= '<td>' . formatTDValue($value2, $max_length) . '</td>';
            }
            $table .= '</tr>';
        }
    }
    $table .= '</tbody></table>';
    return $table;
}

function formatTDValue($cell, $maxwd = 25)
{
    if ($cell) {
        if (strlen($cell) > $maxwd) {
            mb_internal_encoding("UTF-8");
            $cell_data = mb_substr($cell, 0, $maxwd - 2);
            return '<span title="' . $cell . '">' . $cell_data . '...</span>';
        } elseif (strlen($cell) > 12) {
            return $cell;
        } else if (is_float($cell)) {
            return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell, 2) . '</span>';
        } elseif (is_numeric($cell)) {
            if ($cell < 10000) {
                return $cell;
            } elseif ($cell < 1000000) {
                return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell, 0) . '</span>';
            } else {
                return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell / 1000000, 2) . 'M</span>';
            }
        } else {
            return $cell;
        }
    } else {
        return '&nbsp;';
    }
}

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

function defaultToken(){
    $password = config('constant.password');
    $username = config('constant.username');

    $hashUser = base64_encode($username);
    $hashpass = base64_encode($password);
    $token = $hashUser . $hashpass;
    return $token;
}

function getNotificationToken($user, $pass){
    $hashUser = base64_encode($user);
    $hashpass = base64_encode($pass);
    $token = $hashUser . $hashpass;
    return $token;
}
