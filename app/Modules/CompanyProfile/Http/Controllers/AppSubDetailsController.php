<?php

namespace App\Modules\CompanyProfile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyProfile\Models\Designation;
use App\Modules\CompanyProfile\Models\InvestorInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AppSubDetailsController extends Controller
{
    //    protected $process_type_id;

    public function __construct()
    {
        //        $this->process_type_id = 13;
    }

    public function loadUserIdentificationModal()
    {
        $is_source = 'normal';
        $nationality = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->where(
                'nationality',
                '!=',
                ''
            )->orderby('nationality', 'asc')->pluck('nationality', 'id')->toArray();
        $designation = ['' => 'পদবী নির্বাচন করুন'] + Designation::where('status', 1)->where('is_archive', 0)->pluck('name_bn', 'id')->toArray();
        $passport_nationalities = Countries::orderby('nationality')->where(
            'nationality',
            '!=',
            ''
        )->where('nationality', '!=', 'Bangladeshi')
            ->pluck('nationality', 'id')->toArray();
        $passport_types = [
            'ordinary' => 'Ordinary',
            'diplomatic' => 'Diplomatic',
            'official' => 'Official',
        ];

        $viewMode = 'off';
        return view(
            'CompanyProfile::director.create',
            compact(
                'nationality',
                'designation',
                'viewMode',
                'passport_nationalities',
                'passport_types',
                'is_source'
            )
        );
    }

    public function createInfo($app_id = '')
    {
        $is_source = 'ceoInfo';
        $nationality = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->where(
                'nationality',
                '!=',
                ''
            )->orderby('nationality', 'asc')->pluck('nationality', 'id')->toArray();
        $designation = ['' => 'পদবী নির্বাচন করুন'] + Designation::where('status', 1)->where('is_archive', 0)->pluck('name_bn', 'id')->toArray();
        $passport_nationalities = Countries::orderby('nationality')->where(
            'nationality',
            '!=',
            ''
        )->where('nationality', '!=', 'Bangladeshi')
            ->pluck('nationality', 'id')->toArray();
        $passport_types = [
            'ordinary' => 'Ordinary',
            'diplomatic' => 'Diplomatic',
            'official' => 'Official',
        ];

        $viewMode = 'off';

        return view(
            'CompanyProfile::director.create',
            compact(
                'nationality',
                'designation',
                'viewMode',
                'passport_nationalities',
                'passport_types',
                'is_source',
                'app_id'
            )
        );
    }

    public function editDirector($id)
    {
        $director_list = Session::get('directorsInfo')[$id];
        $designation = ['' => 'পদবী নির্বাচন করুন'] + Designation::where('status', 1)->where('is_archive', 0)->pluck('name_bn', 'id')->toArray();
        $nationality = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->orderby('nationality', 'asc')->pluck('nationality', 'id')->all();
        return view('CompanyProfile::director.edit', compact('id', 'director_list', 'designation', 'nationality'));
    }

    public function storeVerifyDirectorSession(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [ASDC-1027]';
        }

        $rules = [];
        $messages = [];

        if ($request->btn_save == 'NID') {
            $nid_tin_passport = $request->user_nid;

            $rules['user_nid'] = 'required|numeric';
            $rules['nid_dob'] = 'required|date|date_format:d-M-Y';
            $rules['nid_name'] = 'required';
            $rules['nid_designation'] = 'required';
            $rules['nid_nationality'] = 'required';
        } elseif ($request->btn_save == 'ETIN') {
            $nid_tin_passport = $request->user_tin;

            $rules['user_etin'] = 'required|numeric';
            $rules['etin_dob'] = 'required|date|date_format:d-M-Y';
            $rules['etin_name'] = 'required';
            $rules['etin_designation'] = 'required';
            $rules['etin_nationality'] = 'required';
        } elseif ($request->btn_save == 'passport') {
            $nid_tin_passport = $request->passport_no;

            $rules['passport_surname'] = 'required';
            $rules['passport_given_name'] = 'required';
            $rules['passport_DOB'] = 'required';
            $rules['passport_type'] = 'required';
            $rules['passport_no'] = 'required';
            $rules['passport_date_of_expire'] = 'required';
        }

        $rules['nationality_type'] = 'required|in:bangladeshi,foreign';
        $rules['identity_type'] = 'required|in:nid,tin,passport';
        $rules['gender'] = 'required|in:male,female,other';


        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {
            $directorInfo = [];
            $directorInfo['nationality_type'] = $request->nationality_type;
            $directorInfo['identity_type'] = $request->identity_type;

            if ($request->btn_save == 'NID') {
                //NID session data
                $nid_data = json_decode(Encryption::decode(Session::get('nid_info')));
                $directorInfo['l_director_name'] = $nid_data->name;
                $directorInfo['l_father_name'] = $nid_data->father;
                $directorInfo['date_of_birth'] = date(
                    'Y-m-d',
                    strtotime($nid_data->dateOfBirth)
                );
                $directorInfo['nid_etin_passport'] = $nid_data->nationalId;
                $directorInfo['l_director_designation'] = $request->nid_designation;
                $directorInfo['designation'] = $request->nid_designation;
                //                $directorInfo['designation'] = Designation::where('id', $request->nid_designation)->value('name_bn');
                $directorInfo['l_director_nationality'] = $request->nid_nationality;
                $directorInfo['nationality'] = Countries::where('id', $request->nid_nationality)->value('nationality');
            } elseif ($request->btn_save == 'ETIN') {
                //ETIN session data
                $eTin_data = json_decode(Encryption::decode(Session::get('eTin_info')));

                $directorInfo['l_director_name'] = $eTin_data->assesName;
                $directorInfo['nid_etin_passport'] = $eTin_data->etin_number;
                $directorInfo['date_of_birth'] = date('Y-m-d', strtotime($eTin_data->dob));
                $directorInfo['l_director_designation'] = $request->etin_designation;
                $directorInfo['designation'] = $request->etin_designation;
                //                $directorInfo['designation'] = Designation::where('id', $request->etin_designation)->value('name_bn');
                $directorInfo['l_director_nationality'] = $request->etin_nationality;
                $directorInfo['nationality'] = Countries::where('id', $request->etin_nationality)->value('nationality');
            } elseif ($request->btn_save == 'passport') {
                $directorInfo['l_director_name'] = ucfirst(strtolower($request->passport_given_name)) . ' ' . ucfirst(strtolower($request->passport_surname));
                $directorInfo['date_of_birth'] = date('Y-m-d', strtotime($request->passport_DOB));
                $directorInfo['l_director_nationality'] = $request->passport_nationality;
                $directorInfo['nationality'] = Countries::where('id', $request->passport_nationality)->value('nationality');
                $directorInfo['l_director_designation'] = $request->l_director_designation;
                $directorInfo['designation'] = $request->l_director_designation;
                //                $directorInfo['designation'] = Designation::where('id', $request->l_director_designation)->value('name_bn');
                $directorInfo['passport_type'] = $request->passport_type;
                $directorInfo['nid_etin_passport'] = $request->passport_no;
                $directorInfo['date_of_expiry'] = date('Y-m-d', strtotime($request->passport_date_of_expire));

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

                $directorInfo['passport_scan_copy'] = $passport_pic_name;
            }

            $directorInfo['gender'] = $request->gender;

            //            dd(Session::get('directorsInfo'));
            //check existing director from list


            if (!empty($request->source)
                && $request->source == 'ceoInfo') { // from company profile info
                Session::put('ceoInfo', $directorInfo);
            } else {
                if (Session::get('directorsInfo')) {
                    //                dd(333);
                    $session_nid_etin_passport = array_column(Session::get('directorsInfo'), 'nid_etin_passport');
                    foreach ($session_nid_etin_passport as $session_nid_etin_passport) {
                        if ($session_nid_etin_passport == $nid_tin_passport) {
                            return response()->json([
                                'error' => true,
                                'status' => 'The director information already exists for this application. [ASDC-1083]'
                            ]);
                        }
                    }
                }

                Session::push('directorsInfo', $directorInfo);
            }

            /*
             * destroy NID session data ...
             */
            if ($directorInfo['identity_type'] == 'nid') {
                Session::forget('nid_info');
            }

            /*
             * destroy ETIN session data ...
             */
            if ($directorInfo['identity_type'] == 'tin') {
                Session::forget('eTin_info');
            }

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => '/client/company-profile/list-of/director/'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('CPStoreVerifyDirector : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ASDC-1050]');
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . '[ASDC-1050]'
            ]);
        }
    }

    public function updateDirectorSession(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [ASDC-1008]';
        }
        $id = Encryption::decodeId($request->get('id'));
        try {
            $director = Session::get("directorsInfo.$id");

            if (!$director) {
                return response()->json([
                    'error' => true,
                    'status' => 'The director information is missing. [ASDC-1084]'
                ]);
            }

            if (Session::get("directorsInfo.$id")['identity_type'] == 'nid') {
                $director['l_director_designation'] = $request->nid_designation;
                $director['designation'] = $request->nid_designation;
                //                $director['designation'] = Designation::where('id', $request->nid_designation)->value('name_bn');
                $director['l_director_nationality'] = $request->nid_nationality;
                $director['nationality'] = Countries::where('id', $request->nid_nationality)->value('nationality');
            } elseif (Session::get("directorsInfo.$id")['identity_type'] == 'tin') {
                $director['l_director_designation'] = $request->tin_designation;
                $director['designation'] = $request->tin_designation;
                //                $director['designation'] = Designation::where('id', $request->tin_designation)->value('name_bn');
                $director['l_director_nationality'] = $request->tin_nationality;
                $director['nationality'] = Countries::where('id', $request->tin_nationality)->value('nationality');
            } else {
                $director['l_director_name'] = $request->passport_name;
                $director['date_of_birth'] = date('Y-m-d', strtotime($request->passport_dob));
                $director['l_director_nationality'] = $request->passport_nationality;
                $director['nationality'] = Countries::where('id', $request->passport_nationality)->value('nationality');
                $director['l_director_designation'] = $request->passport_designation;
                $director['designation'] = $request->passport_designation;
                //                $director['designation'] = Designation::where('id', $request->passport_designation)->value('name_bn');
                $director['passport_type'] = $request->passport_type;
                $director['nid_etin_passport'] = $request->passport_no;
                $director['date_of_expiry'] = date('Y-m-d', strtotime($request->date_of_expiry));
            }

            $director['gender'] = $request->gender;

            Session::forget("directorsInfo.$id");

            Session::put("directorsInfo.$id", $director);

            return response()->json([
                'success' => true,
                'status' => 'Data has been updated successfully',
                //                'link' => '/client/company-profile/list-of/director/'.Encryption::encodeId($director->app_id).'/'.Encryption::encodeId($director->process_type_id)
            ]);
        } catch (\Exception $e) {
            Log::error('BRUpdateDirector : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ASDC-1052]');
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . ' [ASDC-1052]'
            ]);
        }
    }

    public function loadListOfDirectorsSession(Request $request)
    {
        //        $process_type_id = Encryption::decodeId($request->process_type_id);
        //
        //        DB::statement(DB::raw('set @rownum=0'));
        //        $getData = ListOfDirectors::leftJoin('country_info', 'list_of_directors.l_director_nationality', '=',
        //            'country_info.id')
        //            ->where('app_id', $app_id)
        //            ->where('process_type_id', $process_type_id)
        //            ->orderBy('list_of_directors.id', 'DESC')
        //            ->get([
        //                DB::raw('@rownum := @rownum+1 AS sl'),
        //                'list_of_directors.id',
        //                'list_of_directors.l_director_name',
        //                'list_of_directors.l_director_designation',
        //                'country_info.nationality as nationality',
        //                'list_of_directors.nid_etin_passport'
        //            ]);

        $data = ['responseCode' => 1, 'data' => Session::get('directorsInfo'), 'ceoInfo' => Session::get('ceoInfo')];
        return response()->json($data);
    }

    public function deleteDirectorSession($id)
    {
        //        $id = Encryption::decodeId($id);
        //        $delete = ListOfDirectors::where('id', $id)->delete();
        //
        //        if ($delete) {
        //            Session::flash('success', 'Data is deleted successfully!');
        //        }
        //        return Redirect::back();

        Session::forget("directorsInfo.$id");
        return response()->json([
            'responseCode' => 1,
            'msg' => 'Director Removed!'
        ]);
    }

    public function loadListOfDirectors(Request $request)
    {
        if (!empty($request->app_id)) {
            $app_id = Encryption::decodeId($request->app_id);
            //        $process_type_id = Encryption::decodeId($request->process_type_id);

            DB::statement(DB::raw('set @rownum=0'));
            $data = InvestorInfo::leftJoin(
                'country_info',
                'company_investor_info.nationality',
                '=',
                'country_info.id'
            )
                ->where('company_id', $app_id)
                ->orderBy('company_investor_info.id', 'DESC')
                ->get([
                    DB::raw('@rownum := @rownum+1 AS sl'),
                    'company_investor_info.id',
                    'company_investor_info.investor_nm',
                    'company_investor_info.nationality_type',
                    'company_investor_info.designation',
                    'company_investor_info.identity_type',
                    'company_investor_info.nationality as nationality_dir',
                    'country_info.nationality',
                    'company_investor_info.gender',
                    'company_investor_info.identity_no as nid_etin_passport'
                ]);
        }

        if (session::get("directorsInfoAll")) {
            session::forget("directorsInfoAll");
        }
        $arrays = [];
        foreach ($data as $row) {
            $array['nationality_type'] = $row->nationality_type;
            $array['identity_type'] = $row->identity_type;
            $array['l_director_name'] = $row->investor_nm;
            $array['nid_etin_passport'] = $row->nid_etin_passport;
            $array['date_of_birth'] = $row->date_of_birth;
            $array['l_director_designation'] = $row->designation;
            $array['designation'] = $row->designation;
            $array['l_director_nationality'] = $row->nationality_dir;
            $array['nationality'] = $row->nationality;
            $array['gender'] = $row->gender;

            $arrays[] = $array;
        }
        $data = Session::get('directorsInfo');


        $totalData = null;
        if (empty($data)) {
            $totalData = $arrays;
        } else {
            $totalData = $data;
        }


        if (!empty($arrays) && !empty($data)) {
            $totalData = array_merge($arrays, $data);
        }

        session::put("directorsInfoAll", $totalData);

        $data = ['responseCode' => 1, 'data' => $totalData, 'ceoInfo' => Session::get('ceoInfo')];
        return response()->json($data);
    }

    public function deleteDirectorDB($id)
    {
        $ids = explode('-', $id);
        if (isset($ids[1])) { // remove session if first time delete
            Session::forget("directorsInfoAll.$ids[0]");
            Session::forget("directorsInfo");
        } else {
            InvestorInfo::where('id', $id)->delete();
        }

        return response()->json([
            'responseCode' => 1,
            'msg' => 'Director Removed!'
        ]);
    }

    public function editDirectorDB($id)
    {
        $director_list = InvestorInfo::where('id', $id)->first()->toArray();;
        $designation = ['' => 'পদবী নির্বাচন করুন'] + Designation::where('status', 1)->where('is_archive', 0)->pluck('name_bn', 'id')->toArray();
        $nationality = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->where(
                'nationality',
                '!=',
                ''
            )->orderby('nationality', 'asc')->pluck('nationality', 'id')->all();
        return view('CompanyProfile::director.edit', compact('id', 'director_list', 'designation', 'nationality'));
    }

    public function setSingleDirectorInfo(Request $request)
    {
        $id = $request->get('sessionId');
        $type = $request->get('type');
        if ($type == 'db') {

            $data = InvestorInfo::leftJoin(
                'country_info',
                'company_investor_info.nationality',
                '=',
                'country_info.id'
            )
                ->where('company_investor_info.id', $id)
                ->first([
                    DB::raw('@rownum := @rownum+1 AS sl'),
                    'company_investor_info.id',
                    'company_investor_info.investor_nm',
                    'company_investor_info.nationality_type',
                    'company_investor_info.designation',
                    'company_investor_info.date_of_birth',
                    'company_investor_info.identity_type',
                    'company_investor_info.nationality as nationality_dir',
                    'country_info.nationality',
                    'company_investor_info.gender',
                    'company_investor_info.identity_no as nid_etin_passport'
                ]);

            $directorInfo['nationality_type'] = $data->nationality_type;
            $directorInfo['identity_type'] = $data->identity_type;
            $directorInfo['l_director_name'] = $data->investor_nm;
            $directorInfo['nid_etin_passport'] = $data->nid_etin_passport;
            $directorInfo['date_of_birth'] = $data->date_of_birth;
            $directorInfo['l_director_designation'] = $data->designation;
            $directorInfo['designation'] = $data->designation;
            $directorInfo['l_director_nationality'] = $data->nationality_dir;
            $directorInfo['nationality'] = $data->nationality;
            $directorInfo['gender'] = $data->gender;
        } else {
            $directorInfo = Session::get('directorsInfo')[$id];
        }

        Session::put('ceoInfo', $directorInfo);
        return response()->json(['responseCode' => 0, 'status' => 'success']);
    }

    public function selectDirectorInfo(Request $request)
    {
        $company_id = Encryption::decodeId($request->app_id);
        $directorList = InvestorInfo::leftJoin(
            'country_info',
            'company_investor_info.nationality',
            '=',
            'country_info.id'
        )
            ->where('company_id', $company_id)
            ->get(['company_investor_info.*', 'country_info.nationality as nationality_name']);
        return response()->json(['responseCode' => 1, 'data' => $directorList]);
        //        dd($request->all());
    }
}
