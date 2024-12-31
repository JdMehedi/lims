<?php

namespace App\Libraries;

use Carbon\Carbon;

class Utility
{
    private static function getToken() {
        $sms_json_filepath = public_path('smsjson/token.json');
        $directoryPath = pathinfo($sms_json_filepath, PATHINFO_DIRNAME);
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
        if(file_exists($sms_json_filepath)){
            $sms_token_json = file_get_contents($sms_json_filepath);
            $sms_token_json = json_decode($sms_token_json, true);
            if(!empty($sms_token_json["token_expire_time_str"]) && $sms_token_json["token_expire_time_str"] > strtotime(now())){
                $data         = [
                    'responseCode' => 1,
                    'msg'          => 'Success',
                    'data'         => isset($sms_token_json['access_token']) ? $sms_token_json['access_token'] : '',
                    'expires_in'   => isset($sms_token_json['expires_in']) ? $sms_token_json['expires_in'] : ''
                ];
                return json_encode( $data );
            }
        }


        $sms_api_url_for_token = env( 'SMS_API_BASE_URL_FOR_TOKEN','https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token' );
        $sms_client_id         = env( 'SMS_CLIENT_ID', 'btrc-lims-uat' );
        $sms_client_secret     = env( 'SMS_CLIENT_SECRET','e6877e8b-aa9f-45bf-8927-52203e4c8337' );
        $sms_grant_type        = env( 'SMS_GRANT_TYPE' , 'client_credentials' );

        try {
            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( array(
                'client_id'     => $sms_client_id,
                'client_secret' => $sms_client_secret,
                'grant_type'    => $sms_grant_type
            ) ) );
            curl_setopt( $curl, CURLOPT_URL, "$sms_api_url_for_token" );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $curl, CURLOPT_MAXREDIRS, 10 );
            curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
            $result = curl_exec( $curl );

            if ( ! $result || ! property_exists( json_decode( $result ), 'access_token' ) ) {
                $data = [ 'responseCode' => 0, 'msg' => 'SMS API connection failed!', 'data' => '' ];

                return json_encode( $data );
            }
            curl_close( $curl );
            $formatResponse = $decoded_json = json_decode( $result, true );
            $formatResponse = $formatResponse + ['token_expire_time_str' => strtotime(now()->addMinutes(3))];
            $formatResponse = json_encode($formatResponse, JSON_PRETTY_PRINT);
            file_put_contents($sms_json_filepath, '{}');
            file_put_contents($sms_json_filepath, $formatResponse);

            $data         = [
                'responseCode' => 1,
                'msg'          => 'Success',
                'data'         => isset($decoded_json['access_token']) ? $decoded_json['access_token'] : '',
                'expires_in'   => isset($decoded_json['expires_in']) ? $decoded_json['expires_in'] : ''
            ];
            return json_encode( $data );

        } catch ( Exception $e ) {
            return false;
        }
    }

    private static function getCURLResult( $curloptURL, $method, $fieldData ) {

        $onlyToken = self::getToken();

        if ( isset( $onlyToken ) ) {

            $onlyToken = json_decode($onlyToken);
            if($onlyToken->responseCode != 1){
                return false;
            }
            $token = isset($onlyToken->data) ? $onlyToken->data : null;

            $curl = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_URL            => "$curloptURL",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "$method",
                CURLOPT_POSTFIELDS     => $fieldData,
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Bearer $token",
                    "Content-Type: application/json",
                    "Content-Type: text/plain"
                ),
            ) );
            $response = curl_exec( $curl );
            curl_close( $curl );
            return $response;
        } else {
            return false;
        }
    }

    public static function sendFastSMS( $mobile_no, $message) {
        $validated_mobile_no = self::formatMobileNumberWithValidation( $mobile_no ); //if valid it returns 8801xxxxxxxxx
        if ( $validated_mobile_no == false ) {
            return false;
        }
        $sms_api_url_for_send = env('SMS_API_BASE_URL_FOR_SEND', 'https://api-k8s.oss.net.bd/api/broker-service/sms/send_sms');

        try {
            $fieldData  = json_encode( [ 'msg' => $message, 'destination' => $validated_mobile_no ] );
            $method     = 'POST';
            $curloptURL = $sms_api_url_for_send;
            $response   = self::getCURLResult( $curloptURL, $method, $fieldData );
            $decoded_response = json_decode( $response, true );

            if ( $response && isset($decoded_response['status']) && $decoded_response['status'] === 200 ) {
                return $decoded_response;
            } else {
                return false;
            }

        } catch ( Exception $e ) {
            return false;
        }
    }

    private static function formatMobileNumberWithValidation( $mobile_no ) {
        $mobile_no_formated  = str_replace( "+88", "88", "$mobile_no" );
        $firstValidationFlag = ( preg_match( "/^(88)?0?1[0-9]{3}(\-)?[0-9]{6}$/", $mobile_no_formated ) );

        if ( $firstValidationFlag ) {
            $removedDash = str_replace( "-", "", $mobile_no );
            if ( substr( $removedDash, 0, 2 ) == '01' ) {
                return '88' . $removedDash;
            } else if ( substr( $removedDash, 0, 1 ) == '1' ) {
                return '880' . $removedDash;
            } else if ( substr( $removedDash, 0, 1 ) == '+' ) {
                return str_replace( "+", "", $removedDash );
            } else {
                return $removedDash;
            }
        } else {
            return false;
        }
    }
}
