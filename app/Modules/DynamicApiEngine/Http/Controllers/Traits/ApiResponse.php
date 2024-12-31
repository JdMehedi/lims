<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;


trait ApiResponse
{

    public static function apiFinalResponse($status = '', $message = '', $responseData = [],
                                            $responseType = 'Success', $errorDetails = [])
    {

        if ($responseType == 'Success') {
            $response = [
                'responseBody' => [
                    'status' => $status,
                    'message' => $message,
                    'responseData' => $responseData,
                ]
            ];
        } else {
            $response = [
                'responseBody' => [
                    'status' => $status,
                    'message' => $message,
                    'responseData' => $responseData,
                    'error_details' => $errorDetails
                ]
            ];
        }

        return $response;
    }

    public static function apiInstantResponse($status = 500, $message = '', $responseData = [],
                                              $responseType = 'Success', $errorDetails = [])
    {
        http_response_code($status);
        header('Content-Type:application/json');
        echo json_encode(self::apiFinalResponse($status, $message, $responseData, $responseType, $errorDetails));
        exit;
    }


    /**
     * @param $message
     * @param int $status
     * @param string $jwtToken
     * @param string $expireIn
     * @param string $tokenType
     */
    public static function apiTokenResponse($message, $status = 500, $jwtToken = '', $expireIn = '', $tokenType = '')
    {
        http_response_code($status);
        header('Content-Type:application/json');
        $response = [
            'message' => $message,
            'access_token' => $jwtToken,
            'expires_in' => $expireIn,
            'token_type' => $tokenType,
            'scope' => null,
            'expire_on' => date("Y-m-d H:i:s", strtotime("+" . $expireIn . " seconds"))
        ];
        echo json_encode($response);
        exit;
    }

}
