<?php

namespace App\Modules\API\Http\Controllers;

use App\Services\TokenServices;
use App\User;
use App\UserData;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class TokenController extends \App\Modules\API\Http\Controllers\Enothi\ApiController
{
    public $token;

    public function __construct(TokenServices $token)
    {
        $this->token = $token;
    }


    public function getToken(Request $request)
    {
        //dd($request->all());
        $username = $request->get('username');
        $password = $request->get('password');
        $clientid = $request->get('clientid');

        try{
            if (isset($username) && isset($password) && isset($clientid)) {
                $jwt_token = $this->token->generateToken($username, $password, $clientid);

                if($jwt_token != null){
                    return $this->apiResponse($jwt_token,200);
                }
                $response = $this->responseWithError('Invalid username or password!', HTTPResponse::HTTP_UNAUTHORIZED);

            } else {

                $response =  $this->responseWithError('Must provide username and password!',HTTPResponse::HTTP_BAD_REQUEST);
            }
        }catch(\Exception $e){
            Log::error($e->getTraceAsString());
            $response =  $this->responseWithError('Must provide username and password!',HTTPResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse($response, $response['status']);
    }

    public function getV2Token(Request $request)
    {
        $email = trim($request->get('email'));
        $mobile = trim($request->get('mobile'));
        $nid = trim($request->get('nid'));
        $dob = trim($request->get('dob'));
        $vumishebaAppID = trim($request->get('vumishebaAppID'));

        try{
            if (isset($email) && isset($mobile) && $email != '' && $mobile != ''){
                $usersObj = new UserData();
                $users = $usersObj->GetUserData($mobile,$email,$nid,$dob,$vumishebaAppID);

                if($users != null){
                    return $this->responseWithSuccess($users,'Information has been collected successfully!!!');
                }else{
                    return  $this->responseWithError('Must provide valid email and mobile!',HTTPResponse::HTTP_BAD_REQUEST);
                }
            }else{
                return  $this->responseWithError('Must provide email and mobile!',HTTPResponse::HTTP_BAD_REQUEST);
            }
        }catch(\Exception $e){
            return  $this->responseWithError('Must provide username and password!',HTTPResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
