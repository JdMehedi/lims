<?php


namespace App\Modules\DynamicApiEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class accessPermissions
{

    private static $allowed_user_type_array = ['1x101'];

    public static function checkUserTypePermission($authData){
        $authUserType = isset($authData->user_type) ? $authData->user_type : '';
        if(!in_array($authUserType,self::$allowed_user_type_array)){
            return false;
        }
        return true;
    }
}