<?php


namespace App\Libraries;


class NIDverification
{
    const LOGIN_REQUEST = 'LOGIN_REQUEST';

    const VERIFY_NID = 'VERIFY_NID';

    const VERSION = '1.0';

    private $nid_server_address;

    public function __construct()
    {
        $this->nid_server_address = config('app.NID_SERVER');
        $this->nid_token_server = config('app.NID_TOKEN_SERVER');
        $this->nid_client_id = config('app.NID_SERVER_CLIENT_ID');
        $this->nid_reg_key = config('app.NID_SERVER_REG_KEY');
        $this->grant_type = config('app.NID_GRANT_TYPE');
    }

    public function getAuthToken()
    {
        try {

            $access_data = [
                "client_id" => $this->nid_client_id,
                "client_secret" => $this->nid_reg_key,
                "grant_type" => $this->grant_type
            ];
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($access_data));
            curl_setopt($curl_handle, CURLOPT_URL, $this->nid_token_server);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $result = curl_exec($curl_handle);
            if (curl_errno($curl_handle)) {
                $data = ['responseCode' => 0, 'msg' => curl_error($curl_handle), 'data' => ''];
                curl_close($curl_handle);
                return json_encode($data);
            }
            curl_close($curl_handle);

            if (!$result || !property_exists(json_decode($result), 'access_token')) {
                $data = ['responseCode' => 0, 'msg' => 'API connection failed!', 'data' => ''];
                return json_encode($data);
            }

            $decoded_json = json_decode($result, true);
            return $decoded_json['access_token'];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyNID($nid_data)
    {
        if (empty($nid_data)) {
            return $this->returnResponse('error', 400, [], 'Given NID data is not valid. Please make request with valid data');
        }

        $nid_auth_token = $this->getAuthToken();
        if (empty($nid_auth_token)) {
            return $this->returnResponse('error', 400, [], 'NID auth token not found! Please try again');
        }

        $userNid = $nid_data['nid_number'];
        $userDOB = date("Y-m-d", strtotime($nid_data['user_DOB']));

        if (strlen($userNid) == 13) { //13 to 17 digit nid convert
            $year = date('Y', strtotime($nid_data['user_DOB']));
            $userNid = $year . $userNid;
        }

        $json = '{"dateOfBirth":"' . $userDOB . '","nid":"' . $userNid . '"}';
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->nid_server_address,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $nid_auth_token",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $decoded_output = json_decode($response);
            // return $decoded_output;


            if ($decoded_output->status == '200') {
                $status = 'success';
                $statusCode = $decoded_output->status;
                $responseData = json_decode(json_encode($decoded_output->data), true);
                $message = 'Valid NID';
            } else {
                $status = 'error';
                $statusCode = $decoded_output->status;
                $responseData = [];
                $message = 'Invalid NID';
            }

            return $this->returnResponse($status, $statusCode, $responseData, $message);
        } catch (\Exception $e) {
            return $this->returnResponse('error', $e->getCode(), [], $e->getMessage());
        }
    }

    public static function obj2ArrayForNID($nidData)
    {
        try {
            $parAddress = NIDVerification::explodeAddress(CommonFunction::convertUTF8($nidData->permanentAddress));
            $preAddress = NIDVerification::explodeAddress(CommonFunction::convertUTF8($nidData->presentAddress));
            $date_birth = explode('T', $nidData->dob);
            $response = [
                'full_name_bangla' => CommonFunction::convertUTF8($nidData->name),
                'full_name_english' => $nidData->nameEn,
                'father_name' => CommonFunction::convertUTF8($nidData->father),
                'mother_name' => CommonFunction::convertUTF8($nidData->mother),
                'birth_date' => $date_birth[0],
                'per_village_ward' => $parAddress['village_ward'],
                'per_police_station' => $parAddress['thana'],
                'per_district' => $parAddress['district'],
                'per_post_code' => $parAddress['post_code'],
                'per_post_office' => $parAddress['post_office'], // Added Post office
                'village_ward' => $preAddress['village_ward'],
                'police_station' => $preAddress['thana'],
                'district' => $preAddress['district'],
                'post_code' => $preAddress['post_code'],
                'post_office' => $preAddress['post_office'], // Added Post office
                'national_id' => $nidData->nid,
                'gender' => $nidData->gender,
                'spouse_name' => isset($nidData->spouse) ? CommonFunction::convertUTF8($nidData->spouse) : '',
                'marital_status' => '', //$nidData->maritialStatus
                'mobile' => '', //$nidData->mobileNo,
                'occupation' => '', //CommonFunction::convertUTF8($nidData->occupation),
            ];
            return $response;
        } catch (ValidationException $e) {
            echo $e;
            return null;
        } catch (\Exception $e) {
            echo $e;
            return null;
        }
    }


    public static function explodeAddress($address)
    {
        $adl = 4;
        $permanentAddress = explode(',', $address);
        if (count($permanentAddress) <= 1) {
            return [
                'village_ward' => $address,
                'thana' => '',
                'district' => '',
                'post_code' => '',
            ];
        }
        if (count($permanentAddress) > 1) {
            $data['district'] = trim($permanentAddress[count($permanentAddress) - 1]);
        } else {
            $data['district'] = '';
        }
        if (count($permanentAddress) > 3) {
            $data['thana'] = trim($permanentAddress[count($permanentAddress) - 2]);
            $per_post_office = trim($permanentAddress[count($permanentAddress) - 3]);
            $per_post_codes = explode('-', $per_post_office);
            if (count($per_post_codes) > 1) {
                $data['post_code'] = trim($per_post_codes[count($per_post_codes) - 1]);
                if (!is_numeric(CommonFunction::convert2English($data['post_code'])) && count($permanentAddress) > 4) {

                    $per_post_office = trim($permanentAddress[count($permanentAddress) - 4]);
                    $per_post_codes = explode('-', $per_post_office);
                    if (is_numeric(CommonFunction::convert2English(trim($per_post_codes[count($per_post_codes) - 1])))) {
                        $data['post_code'] = trim($per_post_codes[count($per_post_codes) - 1]);
                        $data['thana'] = trim($permanentAddress[count($permanentAddress) - 3]);
                        $adl = 5;
                    }
                }
                $data['post_code'] = CommonFunction::convert2English($data['post_code']);
            } else {
                $data['post_code'] = '';
            }
            // Added post office
            $data['post_office'] = str_replace('ডাকঘর:', '', $per_post_codes[0]);
        } else {
            $data['thana'] = '';
            $data['post_code'] = '';
            // Added post office
            $data['post_code'] = '';
        }

        $data['village_ward'] = $permanentAddress[0];
        for ($i = 1; $i <= count($permanentAddress) - $adl; $i++) {
            $data['village_ward'] .= ', ' . $permanentAddress[$i];
        }

        if ($adl == 5) {
            $data['village_ward'] .= ', ' . trim($permanentAddress[count($permanentAddress) - 2]);
        }
        return $data;
    }


    private function returnResponse($status, $statusCode, array $data = [], $message = 'Sorry, Something went wrong!')
    {
        return $response_data = [
            'status' => $status,
            'statusCode' => intval($statusCode),
            'data' => $data,
            'message' => $message
        ];
    }
}
