<?php

namespace App\Modules\API\Http\Controllers\Enothi;

use App\Services\TokenServices;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class ApiController extends Controller
{
    public $debugMode = true;
    public $tokenService;

    public function __construct(TokenServices $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function checkTokenValid($bearerToken)
    {
        return $this->tokenService->checkTokenValidity($bearerToken);
    }
    public function checkAppUserIdValidity($AppUserID)
    {
        return $this->tokenService->checkAppUserIdValidity($AppUserID);
    }

    public function apiResponse($response = [], $statusCode = 404)
    {
        return response()->json($response, $statusCode, []);
    }


    public function responseWithError($message, $statusCode = HttpResponse::HTTP_BAD_REQUEST,$data = [], $errorCode = '',$responseType= '')
    {
        return [
            'responseTime' => Carbon::now()->timestamp,
            'responseType' => $responseType,
            'status' => $statusCode,
            'errorCode' => $errorCode,
            'response' => 'error',
            'msg' => $message,
            'data' => $data,
        ];
    }


    public function responseWithSuccess($data, $message = '', $statusCode = HttpResponse::HTTP_OK,$responseType='')
    {
        return [
            'responseTime' => Carbon::now()->timestamp,
            'responseType' => $responseType,
            'status' => $statusCode,
            'response' => 'success',
            'msg' => $message,
            'data' => $data,
        ];
    }

    public function responseWithSuccessForDashboard($data, $message = '', $statusCode = HttpResponse::HTTP_OK)
    {
        return [
            'status' => $statusCode,
            'response' => $data,
        ];
    }

    public function showErrorMessage(\Exception $e)
    {
        if ($this->debugMode) {
            return $e->getMessage().$e->getFile().$e->getLine();
        }
        return 'Sorry ! An internal error has occurred';
    }

    public function apiInstantResponse($status = 500, $message = '', $errorCode = 0)
    {
        http_response_code($status);
        header('Content-Type:application/json');
        $data = [
            'responseTime' => Carbon::now()->timestamp,
            'responseType' => 'Error',
            'status' => $status,
            'errorCode' => $errorCode,
            'msg' => $message,
            'data' => [],
        ];
        echo json_encode($data);
        exit;
    }
}
