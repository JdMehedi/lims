<?php

namespace App\Modules\Signup\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\ETINverification;
use App\Libraries\ImageProcessing;
use App\Libraries\NIDverification;
use App\Modules\CompanyAssociation\Models\CompanyAssociationMaster;
use App\Modules\Signup\Models\UserVerificationData;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Modules\CompanyProfile\Models\CompanyInfo;

class SignupController extends Controller
{

    public function identityVerifyMobile()
    {
        if (!ACL::getAccsessRight('user', 'E')) {
            abort('400', 'You have no access right!. Contact with system admin for more information.');
        }
        if (!Session::has('oauth_token') or !Session::has('oauth_data')) {
            Session::flash('error', 'You have no access right! This incidence will be reported. Contact with system admin for more information. [SC-1010]');
            return redirect()->to('/login');
        }
        try {
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->pluck('nicename', 'id')->toArray();
            $passport_types = [
                'diplomatic' => 'Diplomatic',
                'official' => 'Official',
                'ordinary' => 'Ordinary',
            ];
            $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')
                ->pluck('nationality', 'id')->toArray();
            $passport_nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->where('nationality', '!=', 'Bangladeshi')
                ->pluck('nationality', 'id')->toArray();

            $getPreviousVerificationData = UserVerificationData::where('user_email', Session::get('oauth_data')->user_email)->where('created_at', '>=', Carbon::now()->subDay())->first();

            return view('Signup::identity-verify-mobile', compact('countries', 'passport_types', 'nid_auth_token', 'etin_auth_token', 'nationalities', 'passport_nationalities', 'getPreviousVerificationData'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1011]');
            return \redirect()->back();
        }

    }

    public function identityVerify()
    {
        try {
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->pluck('nicename', 'id')->toArray();
            $passport_types = [
                'diplomatic' => 'Diplomatic',
                'official' => 'Official',
                'ordinary' => 'Ordinary',
            ];
            $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')
                ->pluck('nationality', 'id')->toArray();
            $passport_nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->where('nationality', '!=', 'Bangladeshi')
                ->pluck('nationality', 'id')->toArray();

            $getPreviousVerificationData = UserVerificationData::where('user_email', Session::get('oauth_data')->user_email ?? '')->where('created_at', '>=', Carbon::now()->subDay())->first();


            return view("Signup::identity-verify", compact('countries', 'passport_types', 'nationalities', 'passport_nationalities', 'getPreviousVerificationData'));
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1009]');
            return \redirect()->back();
        }
    }

    public function nidTinVerify(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $rules = [];
        $messages = [];

        if ($request->identity_type == 'nid') {
            $rules['nid_number'] = 'required|bd_nid';
        } elseif ($request->identity_type == 'tin') {
            $rules['etin_number'] = 'required|digits_between:10,15';
        }

        $rules['user_DOB'] = 'required|date|date_format:d-M-Y';

        $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-200',
                'data' => [],
                'message' => $validation->getMessageBag()->toArray()
            ]);
        }

        try {
            Session::forget('nid_info');
            Session::forget('eTin_info');
            $nid_number = $request->get('nid_number');
            $etin_number = $request->get('etin_number');
            $user_DOB = $request->get('user_DOB');
            $identity_type = $request->get('identity_type');

            if ($identity_type == 'nid') {

                $identity_ver_response = $this->getNidResponse($nid_number, $user_DOB);
            } else {
                $identity_ver_response = $this->getTinResponse($etin_number, $user_DOB);
            }

            return response()->json($identity_ver_response);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "error",
                'statusCode' => 'SIGN-UP-201',
                'data' => [],
                'message' => CommonFunction::showErrorPublic($e->getMessage() . '[SC-1008]')
            ]);
        }
    }

    public function getNidResponse($nid_number, $user_DOB)
    {
        try {
            $nid_data = [
                'nid_number' => $nid_number,
                'user_DOB' => $user_DOB,
            ];

            $nid_verification = new NIDverification();
            $nid_verify_response = $nid_verification->verifyNID($nid_data);

            $data = [];
            if (isset($nid_verify_response['status']) && $nid_verify_response['status'] === 'success') {

                $nid_verify_response['data']['nationalId'] = $nid_number; // WE ADD for new api ONLY FOR bscic
                $nid_verify_response['data']['gender'] = 'male'; // defult set mail
                // Add NID number with nid info
                Session::put('nid_info', Encryption::encode(json_encode($nid_verify_response['data'])));

                $data['nameEn'] = $nid_verify_response['data']['name'];
                $data['dob'] = $nid_verify_response['data']['dateOfBirth'];
                $data['photo'] = $nid_verify_response['data']['photo'];
                $data['gender'] = 'male';
                $nid_verify_response['data'] = $data;
                return $nid_verify_response;
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1007]');
            return \redirect()->back();
        }
    }

    public function getTinResponse($etin_number, $user_DOB)
    {
        try {
            $etin_verification = new ETINverification();
            $etin_verify_response = $etin_verification->verifyETIn($etin_number);

            $data = [];
            if (isset($etin_verify_response['status']) && $etin_verify_response['status'] === 'success') {

                // Validate Date of birth
                if (date('d-M-Y', strtotime($etin_verify_response['data']['dob'])) != $user_DOB) {
                    return [
                        'status' => "error",
                        'statusCode' => 'SIGN-UP-203',
                        'data' => [],
                        'message' => 'Sorry! Invalid date of birth. Please provide valid information. [SC-1004]'
                    ];
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
            return $etin_verify_response;
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1006]');
            return \redirect()->back();
        }
    }

    /**
     * Identity verification and redirect to sign-up page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function identityVerifyConfirm(Request $request)
    {
        try {
            $nationality_type = $request->get('nationality_type');
            if ($nationality_type === 'foreign') {
                $identity_type = $request->get('identity_type_foreign');
            } else {
                $identity_type = $request->get('identity_type_bd');
            }

            Session::put('nationality_type', $nationality_type);
            Session::put('identity_type', $identity_type);
            Session::forget('passport_info');

            $passport_info = [];
            if ($identity_type === 'nid') {
                $image = json_decode(Encryption::decode(Session::get('nid_info')), true);
            } elseif ($identity_type === 'tin') {
                if (!Session::has('eTin_info')) {
                    Session::flash('error', 'Something went wrong! please try again' . ' SIGN-UP-207');
                    return \redirect()->back();
                }
                if (empty(Encryption::decode(Session::get('eTin_info')))) {
                    Session::flash('error', 'Something went wrong! please try again' . ' SIGN-UP-208');
                    return \redirect()->back();
                }
            } elseif ($identity_type === 'passport') {
                $passport_info['passport_nationality'] = $request->passport_nationality;
                $passport_info['passport_type'] = $request->passport_type;
                $passport_info['passport_no'] = $request->passport_no;
                $passport_info['passport_surname'] = $request->passport_surname;
                $passport_info['passport_given_name'] = $request->passport_given_name;
                $passport_info['passport_personal_no'] = $request->passport_personal_no;
                $passport_info['passport_DOB'] = $request->passport_DOB;
                $passport_info['passport_date_of_expire'] = $request->passport_date_of_expire;

                // Passport copy upload
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $passport_pic_name = trim(uniqid('BSCIC_PC_PN-' . $request->passport_no . '_', true) . '.' . 'jpeg');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                if (!empty($request->get('passport_upload_manual_file'))) {
                    $passport_split = explode(',', substr($request->get('passport_upload_manual_file'), 5), 2);
                    $passport_image_data = $passport_split[1];
                    $passport_base64_decode = base64_decode($passport_image_data);
                    file_put_contents($path . $passport_pic_name, $passport_base64_decode);
                } else {
                    $passport_split = explode(',', substr($request->get('passport_upload_base_code'), 5), 2);
                    $passport_image_data = $passport_split[1];
                    $passport_base64_decode = base64_decode($passport_image_data);
                    file_put_contents($path . $passport_pic_name, $passport_base64_decode);
                }
                // End Passport copy upload

                $passport_info['passport_copy'] = $passport_pic_name;
                Session::put('passport_info', Encryption::encode(json_encode($passport_info)));
            }
            return \redirect('client/signup/registration');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . '[SC-1005]');
            return \redirect()->back();
        }
    }

    /**
     * @param $verification_id
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector\
     */
    public function identityVerifyConfirmWithPreviousData($verification_id)
    {
        try {
            $decoded_verification_id = Encryption::decodeId($verification_id);
            $user_verification_data = UserVerificationData::find($decoded_verification_id);
            if (empty($user_verification_data)) {
                Session::flash('error', 'Your previous verification data not found. Please try to sign up with the new verification. [SC-1003]');
                return \redirect()->back();
            }

            Session::put('nationality_type', $user_verification_data->nationality_type);
            Session::put('identity_type', $user_verification_data->identity_type);
            if ($user_verification_data->identity_type == 'tin') {
                Session::put('eTin_info', $user_verification_data->eTin_info);
            } elseif ($user_verification_data->identity_type == 'nid') {
                Session::put('nid_info', $user_verification_data->nid_info);
            } elseif ($user_verification_data->identity_type == 'passport') {
                Session::put('passport_info', $user_verification_data->passport_info);
            }
            return \redirect('client/signup/registration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! something went wrong, Please try again. SIGN-UP-214');
            return \redirect()->back();
        }
    }

    /**
     * Registration form
     * @param UserVerificationDataController $userVerificationDataController
     * @return \BladeView|bool|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function OSSsignupForm(UserVerificationDataController $userVerificationDataController)
    {

        try {

            // Store verification data of users for further usage
            if (session::has('oauth_data')) {
                $userVerificationDataController->storeUserVerificationData(session('oauth_data')->user_email);
            }

            return view('Signup::registration');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . ' SIGN-UP-210');
            return \redirect()->back();
        }
    }

    public function getPassportData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        try {
            $base64_split = explode(',', substr($request->get('file'), 5), 2);
            $passport_verify_url = config('app.PASSPORT_VERIFY_URL');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $passport_verify_url);
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

            $response = json_decode($result);

            $returnData = [
                'success' => true,
                'code' => '',
                'msg' => '',
                'data' => []
            ];

            if (isset($response->code) && $response->code == '200') {
                $returnData['data'] = $response->data;
                if ($response->has_image == true) {
                    Session::put('passport_image', $response->text_image);
                }
                $returnData['code'] = '200';
                $returnData['nationality_id'] = Countries::where('iso3', 'like', '%' . $response->data->nationality . '%')->value('id');
            } elseif (isset($response->code) && in_array($response->code, ['400', '401', '405'])) {
                $returnData['msg'] = $response->msg;
                $returnData['code'] = $response->code;
            }

            // uncomment the below line for python API
            //unlink($file_temp_path);
            Session::put('passport_info', Encryption::encode(json_encode($returnData)));
            return response()->json($returnData);
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong ! [SC-1083]');
            return \redirect()->back();
        }

    }

    /**
     * New user store
     * @param Request $request
     * @return mixed
     */
    public function OSSsignupStore(Request $request)
    {
        $rules['email'] = 'required|email|unique:users,user_email';
        $rules['name'] = 'required';
        $rules['mobile'] = 'required|numeric|unique:users,user_mobile|digits:11';
        $this->validate($request, $rules);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
//            $nationality_type = Encryption::decode($request->get('nationality_type'));
//            $identity_type = Encryption::decode($request->get('identity_type'));

            $nationality_type = '';
            $identity_type = '';

            $user_email = $request->get('email');
            $user_mobile = $request->get('mobile');
            $user_gender = $request->get('gender') ? $request->get('gender') : '';
            $user_name = $request->get('name');
//            PSR
            if (!empty(Session::get('oauth_data')->gender) && in_array(Session::get('oauth_data')->gender, ['male', 'female', 'other'])) {
                $user_gender = ucfirst(Session::get('oauth_data')->gender);
            }

            $user = Users::firstOrNew(['user_email' => $user_email]);

            $user->nationality_type = $nationality_type;
            $user->identity_type = $identity_type;

            if ($identity_type === 'nid') {
                $nid_info = json_decode(Encryption::decode(Session::get('nid_info')), true);
                $user_nid = $nid_info['nationalId'];
                $user_name_en = $nid_info['name'];
                $user_DOB = $nid_info['dateOfBirth'];
                $postalCode = $nid_info['presentAddress']['postalCode'] ? $nid_info['presentAddress']['postalCode'] : "";
                $villageOrRoad = $nid_info['presentAddress']['villageOrRoad'] ? $nid_info['presentAddress']['villageOrRoad'] : "";
                $homeOrHoldingNo = $nid_info['presentAddress']['homeOrHoldingNo'] ? $nid_info['presentAddress']['homeOrHoldingNo'] : "";

                $user->user_nid = $user_nid;

                $user->post_code = $postalCode;
                $user->contact_address = $homeOrHoldingNo . ', ' . $villageOrRoad;

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
                    $user_pic_name = trim(uniqid('OSSP_PP_CID-' . time() . '_', true) . '.' . $image_type);
                    file_put_contents($path . $user_pic_name, $base64ResizeImage);
                    $user->user_pic = $path . $user_pic_name;
                }
            } elseif ($identity_type === 'tin') {
                if ($request->get('correspondent_photo_base64') == null) {
                    Session::flash('error', 'Image field cannot be empty! [SC-1002]');
                    return Redirect::back()->withInput();
                }
                $eTin_info = json_decode(Encryption::decode(Session::get('eTin_info')), true);

                $user_name_en = $eTin_info['assesName'];
                $user_DOB = $eTin_info['dob'];
                $user->user_tin = $eTin_info['etin_number'];

                if (!empty($request->get('correspondent_photo_base64'))) {
                    $split_user_pic = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
                    $base64_image = $split_user_pic[1];
                    $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($base64_image, 250, 250));
                    $base64ResizeImage = base64_decode($base64ResizeImage);
                    $path = 'users/upload/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $user_pic_name = trim(uniqid('OSSP_PP_CID-' . time() . '_', true) . '.' . 'jpeg');
                    file_put_contents($path . $user_pic_name, $base64ResizeImage);
                    $user->user_pic = $path . $user_pic_name;
                }
            } elseif ($identity_type === 'passport') {
                $passport_info = json_decode(Encryption::decode(Session::get('passport_info')), true);
                $user_name_en = $passport_info['passport_given_name'] . ' ' . $passport_info['passport_surname'];

                $user->passport_nationality_id = $passport_info['passport_nationality'];
                $user->passport_type = $passport_info['passport_type'];
                $user->passport_no = $passport_info['passport_no'];
                $user->passport_surname = $passport_info['passport_surname'];
                $user->passport_given_name = $passport_info['passport_given_name'];
                $user->passport_personal_no = $passport_info['passport_personal_no'];
                $user->passport_DOB = CommonFunction::changeDateFormat($passport_info['passport_DOB'], true);
                $user->passport_date_of_expire = CommonFunction::changeDateFormat($passport_info['passport_date_of_expire'], true);

                // Profile image store
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
                    $user_pic_name = trim(uniqid('OSSP_PP_CID-' . time() . '_', true) . '.' . 'jpeg');
                    file_put_contents($path . $user_pic_name, $base64ResizeImage);
                    $user->user_pic = $path . $user_pic_name;
                } else {
                    if (!empty($request->get('correspondent_photo_base64'))) {
                        $split_user_pic = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
                        $base64_image = $split_user_pic[1];
                        $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($base64_image, 150, 150));
                        $base64ResizeImage = base64_decode($base64ResizeImage);
                        $path = 'users/upload/';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $user_pic_name = trim(uniqid('OSSP_PP_CID-' . time() . '_', true) . '.' . 'jpeg');
                        file_put_contents($path . $user_pic_name, $base64ResizeImage);
                        $user->user_pic = $path . $user_pic_name;
                    }
                }
                $user_DOB = $passport_info['passport_DOB'];
            }

            // user profile picture
            if (!empty($request->correspondent_photo_base64)) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/users/profile-pic/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $splited = explode(',', substr($request->get('correspondent_photo_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $user_picture_name = trim(uniqid('profile_pic_', true) . '.' . 'jpeg');
                file_put_contents($path . $user_picture_name, $base64ResizeImage);

                $user->user_pic = $yearMonth . $user_picture_name;
            }

            $user->user_first_name = $user_name;
            $user->user_middle_name = '';
            $user->user_last_name = '';
            $user->user_gender = $user_gender;

            if (!empty($user_DOB)) {
                $user->user_DOB = CommonFunction::changeDateFormat(date('d-M-Y', strtotime($user_DOB)), true);
            }
            if ($request->get('signup_company') == 'new') {
                $is_exist = CompanyInfo::where('org_nm', $request->get('company_name'))->first();
                if ($is_exist && $is_exist->id) {
                    Session::flash('error', 'Company Already Exist');
                    return Redirect::back()->withInput();
                }
                $companyObj = new CompanyInfo();
                $companyObj->org_nm = $request->get('company_name');
                $companyObj->company_status = 1;
                $companyObj->save();
            } else {
                $companyObj = CompanyInfo::find($request->get('existing_company_id'));
                if (empty($companyObj)) {
                    Session::flash('error', 'Company Not Exist');
                    return Redirect::back()->withInput();
                }
            }

            $user->user_type = '5x505';
            $user->user_mobile = $user_mobile;
//            $user->working_company_id = 0;
            $user->working_company_id = $request->get('signup_company') == 'existing' ? intval($request->get('existing_company_id')) : $companyObj->id;
            $user->save();

            if (isset($companyObj) && $companyObj->id) {
                $companyAssociation = new CompanyAssociationMaster();
                $companyAssociation->company_id = $companyObj->id;
                $companyAssociation->type = 'Employee';
                $companyAssociation->request_from_user_id = $user->id;
                $companyAssociation->is_active = 1;
                $companyAssociation->status = 25;
                $companyAssociation->is_archive = 0;
                $companyAssociation->save();
            }

            /**
             * if user signup with OSSPID or Google then set user as active, approve and verified
             */
            if (Session::has('oauth_data')) {
                $user->user_agreement = 1;
                $user->user_verification = 'yes';
                $user->first_login = 0;
                $user->last_login_type = 'general';
                $user->user_status = 'active';
                $user->is_approved = 1; // 1 = auto approved
                $user->login_token = Encryption::encode(Session::getId());

                // Remove user verification data
                $UserVerificationDataController = new UserVerificationDataController();
                $UserVerificationDataController->removeUserVerificationData($user_email);

                // User login
                Auth::loginUsingId($user->id);
                CommonFunction::GlobalSettings();
                UtilFunction::entryAccessLog();

                $session_msg = 'Your user registration has been successfully completed. To login, please select "Login with Mobile" and enter your mobile number to receive an OTP. When you have entered the OTP, you will be logged in to your account.';
                $redirect_link = '/dashboard';
            } else {
                $this->sendEmailVerificationLink($user_email, $user_mobile, $user);
                $session_msg = 'Account has been created successfully! An email has been sent to the email for account activation.';
                $redirect_link = '/login';
            }

            $user->save();

            DB::commit();

            // Forget session data
            Session::forget('nationality_type');
            Session::forget('identity_type');
            Session::forget('nid_info');
            Session::forget('imgPath');
            Session::forget('eTin_info');
            Session::forget('passport_info');
            Session::forget('oauth_token');
            Session::forget('oauth_data');

            Session::flash('success', $session_msg);
            return Redirect::to($redirect_link);
        } catch (\Exception $e) {
            DB::rollback();
//            dd('M-'.$e->getMessage().'L-'.$e->getLine().'F-'.$e->getFile());
            Session::flash('error', ' Image url time is expired SIGN-UP-211: ' . $e->getMessage() . '[SC-1001]');
            return Redirect::back()->withInput();
        }
    }


    public function OSSsignupByMobile(Request $request)
    {
        $rules['login_mobile'] = 'required|numeric|digits:11|unique:users,user_mobile';
        $rules['login_name'] = 'required';
        $rules['login_email'] = 'required|email|unique:users,user_email';
        $rules['company_name'] = [
            function ($attribute, $value, $fail){
                $companyName = $this->formatCompanyName($value);
                $company = CompanyInfo::query()
                    ->where('org_nm', 'LIKE', "%$companyName%")
                    ->first();
                if ($company){
                    $fail('This Company has been Created once.');
                }
            }
        ];

        $messages['login_mobile.required'] = 'Mobile number need to be provided.';
        $messages['login_mobile.unique'] = 'This mobile number has been used once.';
        $messages['login_name.required'] = 'Name need to be provided.';
        $messages['login_email.email'] = 'Invalid Email.';
        $messages['login_email.unique'] = 'This email has been used once.';
        $this->validate($request, $rules, $messages);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $user_mobile = $request->get('login_mobile');
            $user_name = $request->get('login_name');
            $user_email = $request->get('login_email');
            $user_gender = $request->get('signup_gender') ? $request->get('signup_gender') : '';
            //dd($user_mobile,$user_name,$user_email,$user_gender);
            $user_email = ($user_email != '') ? $user_email : $user_mobile . '@btrc-lims.bd.com';
            $password = Hash::make(rand(10000000000, 99999999999999));

            $user_count = Users::where('user_mobile', $user_mobile)->count();

            if ($user_count > 0) {
                Session::flash('error', 'This mobile number already exist!!!');
                return redirect('/');
            }


            #PSR
            if (!empty(Session::get('oauth_data')->gender) && in_array(Session::get('oauth_data')->gender, ['male', 'female', 'other'])) {
                $user_gender = ucfirst(Session::get('oauth_data')->gender);
            }

            $user = Users::firstOrNew(['user_mobile' => $user_mobile]);

            $user->nationality_type = '';
            $user->identity_type = '';

            $user->user_first_name = $user_name;
            $user->user_middle_name = '';
            $user->user_last_name = '';


            if ($request->get('signup_company') == 'new') {
                $is_exist = CompanyInfo::where('org_nm', $request->get('company_name'))->first();
                if ($is_exist && $is_exist->id) {
                    Session::flash('error', 'Company Already Exist');
                    return Redirect::back()->withInput();
                }
                $companyObj = new CompanyInfo();
                $companyObj->org_nm = $request->get('company_name');
                $companyObj->company_status = 0;
                $companyObj->save();
            } else {
                $companyObj = CompanyInfo::find($request->get('existing_company_id'));
                if (empty($companyObj)) {
                    Session::flash('error', 'Company Not Exist');
                    return Redirect::back()->withInput();
                }
            }

            $user->user_gender = $user_gender;
            $user->user_type = '5x505';
            $user->password = $password;
            $user->user_verification = 'yes';
            $user->is_approved = 1;
            $user->user_email = $user_email;
//            $user->working_company_id = 0;
            $user->working_company_id = $request->get('signup_company') == 'existing' ? intval($request->get('existing_company_id')) : $companyObj->id;
            $user->save();

            if (isset($companyObj) && $companyObj->id) {
                $companyAssociation = new CompanyAssociationMaster();
                $companyAssociation->company_id = $companyObj->id;
                $companyAssociation->type = 'Employee';
                $companyAssociation->request_from_user_id = $user->id;
                $companyAssociation->is_active = 1;
                $companyAssociation->status = 25;
                $companyAssociation->is_archive = 0;
                $companyAssociation->save();
            }

            /**
             * if user signup with OSSPID or Google then set user as active, approve and verified
             */
//            if (Session::has('oauth_data')) {
//                $user->user_agreement = 1;
//                $user->user_verification = 'yes';
//                $user->first_login = 0;
//                $user->last_login_type = 'general';
//                $user->user_status = 'active';
//                $user->is_approved = 1; // 1 = auto approved
//                $user->login_token = Encryption::encode(Session::getId());
//
//                // Remove user verification data
//                $UserVerificationDataController = new UserVerificationDataController();
//                $UserVerificationDataController->removeUserVerificationData($user_email);
//
//                // User login
//                Auth::loginUsingId($user->id);
//                CommonFunction::GlobalSettings();
//                UtilFunction::entryAccessLog();
//
//                $session_msg = 'আপনি সফলভাবে সাইন-আপ সম্পন্ন করেছেন। এই পোর্টালে উল্লেখিত সমস্ত পরিষেবা পেতে প্রথমে প্রাথমিক তথ্য (প্রতিষ্ঠান এর প্রোফাইল) পূরণ করুন।';
//                //$redirect_link = '/dashboard';
//                Session::flash('success', $session_msg);
//                return redirect('/');
//            }
//            else {
//                $token_no = hash('SHA256', "-" . $user_email . "-");
//                $encrypted_token = Encryption::encodeId($token_no);
//                $user_hash_expire_time = new Carbon('+6 hours');
//
//                $user->user_hash = $encrypted_token;
//                $user->user_hash_expire_time = $user_hash_expire_time->toDateTimeString();
//                $user_email = ($user_email != '') ? $user_email : $user_mobile.'@btrc-lims.bd.com';
//
//                $receiverInfo[] = [
//                    'user_email' => $user_email,
//                    'user_mobile' => $user_mobile
//                ];
//
//                $appInfo = [
//                    'verification_link' => url('users/verify-created-user/' . ($encrypted_token))
//                ];
//
//                CommonFunction::sendEmailSMS('CONFIRM_ACCOUNT', $appInfo, $receiverInfo);
//
//                $session_msg = 'Account has been created successfully! An email has been sent to the email for account activation.';
//                $redirect_link = '/';
//            }

            $user->save();
            DB::commit();

            // Forget session data
            Session::forget('nationality_type');
            Session::forget('identity_type');
            Session::forget('nid_info');
            Session::forget('imgPath');
            Session::forget('eTin_info');
            Session::forget('passport_info');
            Session::forget('oauth_token');
            Session::forget('oauth_data');

            //
            $session_msg = 'Your user registration has been successfully completed. To login, please select "Login with Mobile" and enter your mobile number to receive an OTP. When you have entered the OTP, you will be logged in to your account.';
            Session::flash('success', $session_msg);
            return Redirect::to('/');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', ' Image url time is expired SIGN-UP-211: ' . $e->getMessage() . '[SC-1001]');
            return Redirect::back()->withInput();
        }
    }

    public function verifyEmailMobileExist(Request $request)
    {
        $login_mobile = trim($request->get('login_mobile'));
        $user_email = trim($request->get('login_email'));


        $EmailObject = Users::where('user_email', $user_email)->count();
        $MobileObject = Users::where('user_mobile', $login_mobile)->count();

        //dd($EmailObject,$MobileObject);

        if ($EmailObject > 0) {

            return response()->json([
                'status' => "error",
                'statusCode' => 0,
                'data' => [],
                'message' => 'This email address already exist. If your email is verified, you can login through your email address'
            ]);
        }
        if ($MobileObject > 0) {
            return response()->json([
                'status' => "error",
                'statusCode' => 0,
                'data' => [],
                'message' => 'This mobile number already exist. You can login through your mobile number using mobile OTP'
            ]);
        }

        return response()->json([
            'status' => "success",
            'statusCode' => 1,
            'data' => [],
            'message' => ''
        ]);
    }

    public function sendEmailVerificationLink($user_email, $user_mobile, $userObj)
    {
        $token_no = hash('SHA256', '-' . $user_email . '-');
        $encrypted_token = Encryption::encodeId($token_no);
        $user_hash_expire_time = new Carbon('+6 hours');

        $userObj->user_hash = $encrypted_token;
        $userObj->user_hash_expire_time = $user_hash_expire_time->toDateTimeString();

        $receiverInfo[] = [
            'user_email' => $user_email,
            'user_mobile' => $user_mobile
        ];

        $appInfo = [
            'verification_link' => url('users/verify-created-user/' . ($encrypted_token))
        ];

        CommonFunction::sendEmailSMS('CONFIRM_ACCOUNT', $appInfo, $receiverInfo);

    }


    public function formatCompanyName($companyName)
    {
        $arr = config('app.company_end_word');

        foreach ($arr as $val) {
            $name = strpos($companyName, $val);
            if ($name) {
                $companyName = trim(substr($companyName, 0, strlen($companyName) - strlen(substr($companyName, $name))));
            }
        }

        return $companyName;
    }
}
