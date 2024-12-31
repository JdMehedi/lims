<?php

namespace App\Modules\DynamicApiEngine\Http\Controllers\Traits;


trait RequestHeaderHandler
{

    /**
     * @return array
     */


    public function handleRequestHeaders()
    {
        //TODO:: if needed we can show header validation errors combine
        $this->validateRequestBodyContentType();
        $this->validateRequestMethod();
    }


    /**
     * @param $base_api_info
     * @return array
     * To validated request body content type is valid or not
     */
    function validateRequestBodyContentType()
    {
        $response = ['responseCode' => 1, 'message' => '', 'status' => '', 'data' => []];
        $registeredRequestContentType = isset(self::$base_api_info->request_content_type) ? self::$base_api_info->request_content_type : '';
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_change_key_case($requestHeaders, CASE_LOWER);
        $requestedContentType = isset($requestHeaders['content-type']) ? $requestHeaders['content-type'] : 'none';

        if (strpos($requestedContentType, 'multipart/form-data') !== false) {
            $requestedContentType = 'multipart/form-data';
        }

        if ($registeredRequestContentType != $requestedContentType) {
            self::apiInstantResponse(400, 'Expected content type ' . $registeredRequestContentType . ' . Given ' . $requestedContentType);
        }
    }


    /**
     * @param $base_api_info
     * @return array
     * To validated request method is valid or not
     */
    function validateRequestMethod()
    {
        $response = ['responseCode' => 1, 'message' => '', 'status' => '', 'data' => []];
        $registeredMethod = isset(self::$base_api_info->method) ? self::$base_api_info->method : '';
        if ($registeredMethod != self::$request->method()) {
            self::apiInstantResponse(400, 'Expected method ' . $registeredMethod . ' . Given ' . self::$request->method());
        }
    }


}
