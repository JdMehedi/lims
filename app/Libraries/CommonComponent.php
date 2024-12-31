<?php
/*!
 * commonComponent.php
 * Version: 1.0
 * PHP 7.1 is required
 * Copyright 2020 Reyad
 */


use App\Libraries\Encryption;
use App\Libraries\ETINverification;
use App\Libraries\ImageProcessing;
use App\Libraries\NIDverification;
use App\Modules\Settings\Models\AppDocuments;
use App\Modules\Settings\Models\DocInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Auth;
use App\Modules\ProcessPath\Models\ProcessList;
use Carbon\Carbon;


class CommonComponent extends DBClass
{
    private $passport_verify_url;

    public function __construct()
    {
        $this->passport_verify_url = config('app.PASSPORT_VERIFY_URL');
    }

    public function addRow($elementArray, $list, $tableData = [])
    {


        $this->tableData = $tableData;

        $tableId = $list[0];
        $templateRow = $list[1];
        $tablefooterStatus = $list[2]??null;
        $section_heading = $list[3]??null;

        $table_head = '<thead><tr class="' . $section_heading . '">';
        $table_foot = '<tfoot><tr>';
        foreach ($elementArray as $row) {
            $label = $row['label'];
            $required_star = "";
            if (isset($row['required'])) {
                $required_star = "required-star";
            }
            $table_head .= '<th> ' . $label . ' <span class="' . $required_star . '"></span></th>';
            $table_foot .= '<td> ' . $label . ' <span class="' . $required_star . '"></span></td>';
        }
        $table_foot .= '<td><a class="btn btn-xs btn-primary addTableRows" onclick="addTableRowCommon(\'' . $tableId . '\', \'' . $templateRow . '\');"><i class="fa fa-plus"></i></a></td>';
        $table_head .= '<th>#</th></tr><thead>';
        $table_foot .= '</tr><tfoot>';
        if ($tablefooterStatus !='yes') {
            $table_foot = '';
        }
//        dd($table_head);
        $table_body = '';

        $dataRange = count($this->tableData) > 0 ? $this->tableData : [1];

        foreach ($dataRange as $dataKes => $row) { // for loop if exesting:
            if (count($this->tableData) > 0) {

//                $_id = array_shift($row); // id remove in main array
                $array = array_values($row); // the data only key value  form database need specific value send
                $templateRow = substr($templateRow, 0, -1) . $dataKes;  // replace last auto increment key
            }
            $table_body .= '<tr class="trCountValue" id="' . $templateRow . '">';

            foreach ($elementArray as $key => $item) {
                $value = $array[$key] ?? '';
                $element = $this->setElement($item, $value, $dataKes, $key, $_id ?? '');
                $table_body .= '<td>' . $element . '</td>';
            }


            if (count($this->tableData) > 0) { //first element alays add row icon
                if ($dataKes == 0) {
                    $table_body .= '<td><a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow(\'' . $tableId . '\', \'' . $templateRow . '\');"><i class="fa fa-plus"></i></a></td>';
                } else {
                    $table_body .= '<td><a class="btn btn-xs addTableRows btn-danger" onclick="removeTableRowCommon(\'' . $tableId . '\', \'' . $templateRow . '\')"><i class="fa fa-times"></i></a></td>';
                }
            } else {
                $table_body .= '<td><a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow(\'' . $tableId . '\', \'' . $templateRow . '\');"><i class="fa fa-plus"></i></a></td>';
            }
            $table_body .= '</tr>';
        }

        $table_body = '<tbody>' . $table_body . '</tbody>';

        return '<table id="' . $tableId . '" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">'
            . $table_head . $table_body . $table_foot . '</table>';


    }

    private function setElement($item, $inputValue, $dataKes, $key, $_id)
    {
        $type = $item['type'];
        $placeholder = $item['placeholder'] ?? null;
//        $name = $item['name'] ?? null;
        $name = $item['field_id'] ?? null;
        $js_fun = $item['js_function'] ?? null;
        $element_id = $item['id'] ?? $item['field_id'];
        $class = $item['class'] ?? null;
        $required = '';
        if (isset($item['required'])) {
            $required = "required";
        }

        switch ($type) {
            case 'input':
                $hidden_input = '';
//                if (count($this->tableData) > 0 && $key == 0) { // for static hidden input for each row if data exest
//                    $hidden_element_name = explode('_', $name)[0] . '_id' ?? $name;
//                    $hidden_input = '<input maxlength="20" value="' . $_id . '" class="input-sm " name="' . $hidden_element_name . '[' . $dataKes . ']" type="hidden">';
//                }
                $result = ' ' . $hidden_input . ' <input maxlength="200" value="' . $inputValue . '"
                placeholder="' . $placeholder . '" class="form-control input-sm ' . $class . ' ' . $element_id . ' ' . $required . '"
                id="' . $element_id . '_' . $dataKes . '" name="' . $name . '[' . $dataKes . ']" ' . $js_fun . ' type="text">';
                break;
                 case 'textarea':
                $hidden_input = '';
                $result = '<textarea  placeholder="' . $placeholder . '" class="form-control ' . $class . ' ' . $element_id . ' ' . $required . '"
                id="' . $element_id . '_' . $dataKes . '" name="' . $name . '[' . $dataKes . ']" ' . $js_fun . '>'.$inputValue.'</textarea>';
                break;
            case 'select':
                $option = '<option value="">Select one</option>';
                if (isset($item['options']) && !empty($item['options'])) {
                    foreach ($item['options'] as $key => $value) {
                        $option .= '<option value="' . $key . '">' . $value . '</option>';
                    }
                } else {
                    $list = $this->getSelectValues($item);
                    if ($list) {
                        foreach ($list as $key => $value) {
                            $selected = "";
                            if ($inputValue == $key) {
                                $selected = "selected";
                            }
                            $option .= '<option ' . $selected . ' value="' . $key . '">' . $value . '</option>';
                        }
                    }

                }
                $result = '<select class="form-control input-sm ' . $class . ' " ' . $js_fun . '  id="' . $element_id . '_' . $dataKes . '
                " name="' . $name . '[' . $dataKes . ']" ' . $required . '>' . $option . ' </select>';
                break;

            case 'checkbox':
                $result = '<input type="checkbox"  value="' . $inputValue . ' " placeholder="' . $placeholder . '"
                class="onlyNumber ' . $class . ' ' . $element_id . ' ' . $required . '"
                id="' . $element_id . '_' . $dataKes . ' " name="' . $name . '[' . $dataKes . ']">';
                break;

            case 'radio':
                $result = '<input type="radio"  value="' . $inputValue . ' " placeholder="' . $placeholder . '"
                class=" ' . $class . ' ' . $element_id . ' ' . $required . ' "
                id="' . $element_id . '_' . $dataKes . ' " name="' . $name . '[' . $dataKes . ']">';
                break;

            case 'input_number':
                $result = '<input maxlength="200" value="' . $inputValue . '" placeholder="' . $placeholder . '"
                class="form-control input-sm onlyNumber ' . $class . ' ' . $element_id . ' ' . $required . '"
                id="' . $element_id . '_' . $dataKes . ' " name="' . $name . '[' . $dataKes . ']" type="number">';
                break;
            case 'input_date':
            if ($inputValue != '0000-00-00' && !empty($inputValue) ) {
                $inputValue =date('d-m-Y', strtotime($inputValue));
            }
                $result = '
                <div class="input-group date datetimepicker4" id="datepicker0" data-target-input="nearest">
                <input value="' . $inputValue . '" placeholder="' . $placeholder . '"
                class="form-control ' . $class . ' ' . $element_id . ' ' . $required . '"
                id="' . $element_id . '_' . $dataKes . ' " name="' . $name . '[' . $dataKes . ']" type="text" datepicker-append-to-body = "true">
                <div class="input-group-append"
                    data-target="#datepicker0"
                    data-toggle="datetimepicker">
                    <div class="input-group-text"><i
                            class="fa fa-calendar"></i></div>
                </div>';

//                $result = '<div class="datepicker input-group date tabInputDate">
//                <input value="' . $inputValue . '" placeholder="' . $placeholder . '"
//                class="form-control ' . $class . ' ' . $element_id . ' ' . $required . '"
//                id="' . $element_id . '_' . $dataKes . ' " name="' . $name . '[' . $dataKes . ']" type="text" datepicker-append-to-body = "true">
//                <span class="input-group-addon">
//                <span class="fa fa-calendar"></span>
//                </span></div>';
                break;
            default:
                $result = '';
                echo "<span style='color: red; font-weight: bold'>component library: Something was wrong! please check your input type element</span>";
        }
        return $result;
    }


    public  function generateTrackingNo($process_type_id,$trackingPrefix,$processListId){
        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-7,7) )+1,1),7,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$process_type_id' and table2.id!='$processListId' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processListId' and table2.id='$processListId'");
    }

     public  function trackingNoWithShortCode($process_type_id,$trackingPrefix,$processListId){
        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$process_type_id' and table2.id!='$processListId' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processListId' and table2.id='$processListId'");
    }


    public function fileUrlEncode($url){
        if(!empty($url)){
              $expiredTime =Carbon::now()->addMinutes(3)->format('Y-m-d H:i:s');
              $encodedUrl = Encryption::encode("$url@expiredtime@$expiredTime");
              return url('bscic-attachment/'.$encodedUrl);
        }else{
            return '';
        }
    }

    public function nidVerifyData($request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        $rules = [];
        $messages = [];
        $rules['nid_number'] = 'required|bd_nid';
        $rules['user_DOB'] = 'required|date|date_format:d-M-Y';
        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-200',
                'data' => [],
                'message' => $validation->errors()
            ]);
        }

        try {
            // Get NID Authorization token
            $nid_verification = new NIDverification();
            $nid_auth_token = $nid_verification->getAuthToken();
            if (empty($nid_auth_token)) {
                return response()->json([
                    'status' => "error",
                    'statusCode' => 'SIGN-UP-212',
                    'data' => [],
                    'message' => 'NID auth token not found! Please try again'
                ]);
            }

            Session::forget('nid_info');
            Session::forget('eTin_info');
            $nid_number = $request->get('nid_number');
            $user_DOB = $request->get('user_DOB');

            $nid_data = [
                'nid_number' => $nid_number,
                'user_DOB' => $user_DOB,
            ];

            $nid_verify_response = $nid_verification->verifyNID($nid_data, $nid_auth_token);
            $data = [];
            if (isset($nid_verify_response->status) && $nid_verify_response->status === 200) {

                $nid_verify_response->data->nationalId = $nid_number; // WE ADD for new api ONLY FOR bscic
                $nid_verify_response->data->gender = 'male'; // defult set mail
                // Add NID number with nid info
                Session::put('nid_info', Encryption::encode(json_encode($nid_verify_response->data)));

                $data['nameEn'] = $nid_verify_response->data->name;
                $data['dob'] = $nid_verify_response->data->dateOfBirth;
                $data['photo'] = $nid_verify_response->data->photo;
//                $data['gender'] = $nid_verify_response->data->gender; //response not found we will do later
                $data['gender'] = 'male';
            }else{
                $data['response_messages'] = $nid_verify_response->data;
            }
            $nid_verify_response->data = $data;
            return response()->json($nid_verify_response);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-201',
                'data' => [],
                'message' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }


    public function etinVerifyData($request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        $rules = [];
        $messages = [];
        $rules['etin_number'] = 'required|digits_between:10,15';
        $rules['user_DOB'] = 'required|date|date_format:d-M-Y';
        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-202',
                'data' => [],
                'message' => $validation->errors()
            ]);
        }

        try {
            // Get TIN Authorization token
            $etin_verification = new ETINverification();
            $etin_auth_token = $etin_verification->getAuthToken();
            if (empty($etin_auth_token)) {
                return response()->json([
                    'status' => "error",
                    'statusCode' => 'SIGN-UP-213',
                    'data' => [],
                    'message' => 'e-TIN auth token not found! Please try again'
                ]);
            }

            Session::forget('eTin_info');
            Session::forget('nid_info');
            $etin_number = $request->get('etin_number');
            $user_DOB = $request->get('user_DOB');

            $etin_verify_response = $etin_verification->verifyETIn($etin_number, $etin_auth_token);

            $data = [];
            if (isset($etin_verify_response['status']) && $etin_verify_response['status'] === 'success') {

                // Validate Date of birth
                if (date('d-M-Y', strtotime($etin_verify_response['data']['dob'])) != $user_DOB) {
                    return response()->json([
                        'status' => "error",
                        'statusCode' => 'SIGN-UP-203',
                        'data' => [],
                        'message' => 'Sorry! Invalid date of birth. Please provide valid information.'
                    ]);
                }

                // Add etin number with etin_info
                $etin_verify_response['data']['etin_number'] = $etin_number;
                Session::put('eTin_info', Encryption::encode(json_encode($etin_verify_response['data'])));

                // Re-arrange e-tin response
                // Send only some specific data
                $data['nameEn'] = $etin_verify_response['data']['assesName'];
                $data['father_name'] = $etin_verify_response['data']['fathersName'];
                $data['dob'] = $etin_verify_response['data']['dob'];
            }
            $etin_verify_response['data'] = $data;
            return response()->json($etin_verify_response);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-204',
                'data' => [],
                'message' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }


    public function getPassport(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $base64_split = explode(',', substr($request->get('file'), 5), 2);

        // This API need passport base64 data
//        $url = "https://api-k8s.oss.net.bd/api/passport-service/passport";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->passport_verify_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $base64_split[1]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $http_code = 0;
        }

        curl_close($ch);

        $response = \GuzzleHttp\json_decode($result);

        $returnData = [
            'success' => true,
            'code' => '',
            'msg' => '',
            'data' => []
        ];

        if (isset($response->code) && $response->code == '200') {
            $returnData['data'] = $response->data;
            if($response->has_image==true){
                Session::put('passport_image',$response->text_image);
            }
            $returnData['code'] = '200';
            $returnData['nationality_id'] = Countries::where('iso3', 'like', '%' . $response->data->nationality . '%')->value('id');
        } else if (isset($response->code) && in_array($response->code, ['400', '401', '405'])) {
            $returnData['msg'] = $response->msg;
            $returnData['code'] = $response->code;
        }

        // uncomment the below line for python API
        //unlink($file_temp_path);
        return response()->json($returnData);
    }

    public function nidDataManipulation($user, $request){

        $nid_info = json_decode(Encryption::decode(Session::get('nid_info')), true);
        $user_nid = $nid_info['nationalId'];
        $user_name_en = $nid_info['name'];
//                $user_name_en = $nid_info['nameEn'];
//        $user_name_bn = $nid_info['name'];
        $user_DOB = $nid_info['dateOfBirth'];
//        $postOffice = $nid_info['presentAddress']['postOffice'] ?? "";
        $postalCode = $nid_info['presentAddress']['postalCode'] ?? "";
        $villageOrRoad = $nid_info['presentAddress']['villageOrRoad'] ?? "";
        $homeOrHoldingNo = $nid_info['presentAddress']['homeOrHoldingNo'] ?? "";
        if (!empty($user_DOB)) {
            $user->user_DOB = CommonFunction::changeDateFormat(date('d-M-Y', strtotime($user_DOB)), true);
        }

        $user->user_nid = $user_nid;
        $user->post_code = $postalCode;
        $user->contact_address = $homeOrHoldingNo .', '. $villageOrRoad;
        $user->house_no = $homeOrHoldingNo;
        $user->user_first_name = $user_name_en;
        if (!empty($request->nid_base64_value)) {

            $path = 'users/upload/';

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $image_parts = explode(";base64,", $request->nid_base64_value);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($image_parts[1], 250, 250));
            $base64ResizeImage = base64_decode($base64ResizeImage);
            $user_pic_name = trim(uniqid('BSCIC_PP_CID-' . time() . '_', true) . '.' . $image_type);
            file_put_contents($path . $user_pic_name, $base64ResizeImage);
            $user->user_pic = $path . $user_pic_name;
        }
    }

    public function etinDataManipulation($user, $request){
        $eTin_info = json_decode(Encryption::decode(Session::get('eTin_info')), true);

        $user_name_en = $eTin_info['assesName'];
        $user_DOB = $eTin_info['dob'];
        $user->user_tin = $eTin_info['etin_number'];
        $user->user_first_name = $user_name_en;
        if (!empty($user_DOB)) {
            $user->user_DOB = CommonFunction::changeDateFormat(date('d-M-Y', strtotime($user_DOB)), true);
        }
        if (!empty($request->get('correspondent_photo_base64'))){
            $split_user_pic = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
            $base64_image = $split_user_pic[1];
            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($base64_image, 250, 250));
            $base64ResizeImage = base64_decode($base64ResizeImage);
            $path = 'users/upload/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $user_pic_name = trim(uniqid('BSCIC_PP_CID-' .time() . '_', true) . '.' . 'jpeg');
            file_put_contents($path . $user_pic_name, $base64ResizeImage);
            $user->user_pic = $path . $user_pic_name;
        }
    }

    public function passportDataManipulation($user, $request){
        $passport_info = json_decode(Encryption::decode(Session::get('passport_info')), true);
        $user_name_en = $passport_info['passport_given_name'] . ' ' . $passport_info['passport_surname'];
        $user_DOB = $passport_info['passport_DOB'];

        $user->user_first_name = $user_name_en;
        $user->passport_nationality_id = $passport_info['passport_nationality'];
        $user->passport_type = $passport_info['passport_type'];
        $user->passport_no = $passport_info['passport_no'];
        $user->passport_surname = $passport_info['passport_surname'];
        $user->passport_given_name = $passport_info['passport_given_name'];
        $user->passport_personal_no = $passport_info['passport_personal_no'];
        $user->passport_DOB = CommonFunction::changeDateFormat($passport_info['passport_DOB'], true);
        $user->passport_date_of_expire = CommonFunction::changeDateFormat($passport_info['passport_date_of_expire'], true);
        $user->passport_date_of_expire = CommonFunction::changeDateFormat($passport_info['passport_date_of_expire'], true);


        if (!empty($user_DOB)) {
            $user->user_DOB = CommonFunction::changeDateFormat(date('d-M-Y', strtotime($user_DOB)), true);
        }
//                 Profile image store
        if (!empty(session::get('passport_image'))) {
            $split_user_pic = 'data:image/jpg;base64,';
            $image = $split_user_pic . session::get('passport_image');
            $split_user_pic = explode(',', substr($image, 5), 2);
            $base64_image = $split_user_pic[1];
            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($base64_image, 250, 250));
            $base64ResizeImage = base64_decode($base64ResizeImage);
            $path = 'users/upload/';

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $user_pic_name = trim(uniqid('BSCIC_PP_CID-' . time() . '_', true) . '.' . 'jpeg');
            file_put_contents($path . $user_pic_name, $base64ResizeImage);
            $user->user_pic = $path . $user_pic_name;


        }else{
            if (!empty($request->get('correspondent_photo_base64'))){
                $split_user_pic = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
                $base64_image = $split_user_pic[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($base64_image, 150, 150));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $path = 'users/upload/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $user_pic_name = trim(uniqid('BSCIC_PP_CID-' . time() . '_', true) . '.' . 'jpeg');
                file_put_contents($path . $user_pic_name, $base64ResizeImage);
                $user->user_pic = $path . $user_pic_name;
            }

        }
    }


}


// end of class //
































class DBClass
{

    protected $process_type_id;
    protected $appInfo;
    protected $viewMode;
    protected $additional_sql;
    protected $tableData;

    protected function AppDocuments()
    {
        $clr_document = AppDocuments::where('process_type_id', $this->process_type_id)->where('ref_id', $this->appInfo->id)->get();
        $clrDocuments = [];

        foreach ($clr_document as $documents) {
            $clrDocuments[$documents->doc_info_id]['doucument_id'] = $documents->id;
            $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
            $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
        }

        return $clrDocuments;
    }

    protected function getAttachmentName()
    {
        if ($this->additional_sql) {
            $document = DB::select(DB::raw($this->additional_sql));
        } else {
            $document = DocInfo::where('process_type_id', $this->process_type_id)->where('is_archive', 0)->orderBy('order')->get();
        }
        return $document;
    }

    protected function getSelectValues($item)
    {
        try {
            if (isset($item['db_table'])) {
                $lists = DB::table($item['db_table'])->pluck($item['option_text'], $item['option_value'])->toArray();
            } else if(isset($item['sql'])){
                $sqlRaw = DB::select(DB::raw($item['sql']));
                foreach ($sqlRaw as $row):$lists[$row->id] = $row->value; endforeach;
            }else{
                return false;
            }
            return  $lists;
        } catch (Exception $e) {
            $messages = $e->getMessage();
            echo "<span style='color: red'>component library: $messages</span>";
        }


    }

    private function checkIsdate($date){

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        }elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$date)){
            return true;

        }elseif (preg_match("/^(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-[0-9]{4}$/",$date)) {
            return true;
        } else {
            return false;
        }

    }

    public function storeChildRow($request, $fieldNames, $app_id, $filed_id, $table)
    {


        $arrayData = [];
        $dataseFields = [];

        if(array_keys($fieldNames) !== range(0, count($fieldNames) - 1)){
            $dataseFields= array_values($fieldNames);
            $fieldNames = array_keys($fieldNames);
        }



        foreach ($request[$fieldNames[0]] as $key => $sponsor_name) {
            if($sponsor_name !=''){
                for ($i = 0; $i < count($fieldNames); $i++) {
                 if($this->checkIsdate($request[$fieldNames[$i]][$key])){
                    $request[$fieldNames[$i]][$key] = date('Y-m-d', strtotime($request[$fieldNames[$i]][$key]));

                    }

                if($dataseFields){
                     $array[$dataseFields[$i]] = $request[$fieldNames[$i]][$key];
                 }else{
                    $array[$fieldNames[$i]] = $request[$fieldNames[$i]][$key];
                 }

                $array[$filed_id] = $app_id;
                $array['created_at'] = \Carbon\Carbon::now();

            }
            $arrayData[] = $array;
            }


        }
        DB::table($table)->where($filed_id, $app_id)->delete();
        DB::table($table)->insert($arrayData);


    }


}

function CommonComponent()
{
    return new CommonComponent();
}
