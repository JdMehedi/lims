<?php

namespace App\Services;

use App\Libraries\Encryption;
use \Firebase\JWT\JWT;


class TokenServices
{
    private $user;
    private $pass;
    private $key;
    private $privilege;


    public function __construct()
    {
        /*
        $this->user = config('constant.username');
        $this->pass = config('constant.password');
        $this->key = config('constant.key');
        */
        $this->key = config('constant.key');
    }

    public function setAccess($clientid){
        $access = config('constant.access');
        if(isset($access[$clientid])){
            $this->user = $access[$clientid]['username'];
            $this->pass = $access[$clientid]['password'];
            $this->privilege = $access[$clientid]['privilege'];
        }
    }


    public function generateToken($username, $password, $clientid)
    {
        try {
            $this->setAccess($clientid);
            if ($username == $this->user && $password == $this->pass) {
                $token = [
                    'username' => $username,
                    'password' => $password,
                    'clientid' => $clientid,
                    'privilege' => $this->privilege,
                    "exp" => time() + 24 * 60 * 60
                ];
                $jwt = JWT::encode($token, $this->key);
                $jwt_token['status'] = '200';
                $jwt_token['token_type'] = 'bearer';
                $jwt_token['expire_on'] = date("Y-m-d H:i:s", strtotime("+24 hours"));
                $jwt_token['token'] = $jwt;
                $jwt_token['msg'] = 'Successfully generated token';
                return $jwt_token;
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function checkTokenValidity($bearerToken)
    {
        try {
            if( !isset($bearerToken) || empty($bearerToken) ) return false;
            if (strpos($bearerToken, 'bearer ') != 0) return false;

            $token = str_replace("bearer ", "", $bearerToken);
            if (isset($token) && !empty($token)) {
                $user = JWT::decode($token, $this->key, ['HS256']);

                $this->setAccess($user->clientid);

                return ['status' => (($user->username == $this->user) && ($user->password == $this->pass)), 'privilege' => $user->privilege];
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function checkAppUserIdValidity($AppUserID)
    {
        try {
            $AppUserID = Encryption::decode($AppUserID);
            $AppUserData = explode(',',$AppUserID);
            $currentTime = time();

            if(isset($AppUserData[7])){
                return ($AppUserData[7] > $currentTime) ? true : false;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
