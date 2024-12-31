<?php

namespace App\Modules\ProcessPath\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessDoc;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\ShadowFile;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProcessListController extends Controller
{
    public function index()
    {
        return view("ProcessPath::index");
    }

    public function getProcessTypes()
    {
        try {
            $userType = Auth::user()->user_type;
            $ProcessType = ProcessType::select(
                DB::raw("CONCAT(name_bn,' ',group_name) AS name"),
                'id'
            )
                ->whereStatus(1)
                ->where(function ($query) use ($userType) {
                    $query->where('active_menu_for', 'like', "%$userType%");
                })
                ->orderBy('name')
                ->get('name', 'id');
            return response()->json($ProcessType);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PLC-1020]');
            return redirect()->back();
        }
    }

    public function getProcessTypeInfo($process_type_id)
    {
        $process_type_id = Encryption::decodeId($process_type_id);
        $ProcessType = ProcessType::where('id', $process_type_id)
            ->where('status', 1)
            ->first();
        return response()->json($ProcessType);
    }

    public function getList(Request $request, $status = '-1000', $desk = '')
    {
        try {
            $process_type_id = $request->get('process_type_id');
            $user_id = CommonFunction::getUserId();
            $userType = CommonFunction::getUserType();
            $companyId = Auth::user()->working_company_id;
            $userDeskIds = CommonFunction::getUserDeskIds();
            $userOfficeIds = CommonFunction::getUserOfficeIds();
            $delegatedUserDeskOfficeIds = CommonFunction::getDelegatedUserDeskOfficeIds();


            $search_input = $request->get('search');
            $limit = $request->get('limit');
            $order = $request->get('sort');
            $column_name = $request->get('column_name');

            // Base Query
            $query = ProcessList::leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
                ->leftjoin('process_status', function ($on) {
                    $on->on('process_list.status_id', '=', 'process_status.id')
                        ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
                })
                ->leftjoin('users as locked_by_user', function ($on) {
                    $on->on('process_list.locked_by', '=', 'locked_by_user.id');
                })
                ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_type.active_menu_for', 'like', "%$userType%");


            // Query for favourite list or else
            if ($desk == 'favorite_list') {
                $query->Join('process_favorite_list', 'process_list.id', '=', 'process_favorite_list.process_id')
                    ->where('process_favorite_list.user_id', CommonFunction::getUserId());
            } else {
                $query->leftjoin('process_favorite_list', function ($on) {
                    $on->on('process_favorite_list.process_id', '=', 'process_list.id')
                        ->where('process_favorite_list.user_id', CommonFunction::getUserId());
                });
            }


            // System admin can only view the application without Draft and Shortfall status
            if ($userType == '1x101' || $userType == '10x101' || $userType == '2x202') {
                $query->whereNotIn('process_list.status_id', [-1]);
            }
            // General users can only view the applications related to their company
            elseif (in_array($userType, ['5x505', '6x606'])) {
                $query->where('process_list.company_id', $companyId);
            } else {

                /*
                 * Condition applied for my-desk data only
                 *
                 * Desk User can only view the applications related to their desk and assigned office
                 * and status id is not Draft or Shortfall
                 */

                if ($desk == 'my-desk') {
                    $query->where(function ($query1) use ($userDeskIds, $userOfficeIds, $user_id) {
                        $query1->whereIn('process_list.desk_id', $userDeskIds)
                            ->where(function ($query2) use ($user_id) {
                                $query2->where('process_list.user_id', $user_id)
                                    ->orWhere('process_list.user_id', 0);
                            })
                            ->whereIn('process_list.office_id', $userOfficeIds)
                            ->where('process_list.desk_id', '!=', 0)
                            ->whereNotIn('process_list.status_id', [-1]);
                    });
                } else if ($desk == 'my-delg-desk') {
                    $query->where(function ($query) use ($delegatedUserDeskOfficeIds) {
                        if (empty($delegatedUserDeskOfficeIds)) {
                            $query->where('process_list.desk_id', 5555);
                        } else {
                            $i = 0;
                            foreach ($delegatedUserDeskOfficeIds as $data) {
                                $queryInc = '$query' . $i;

                                if ($i == 0) {
                                    $query->where(function ($queryInc) use ($data) {
                                        $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                            ->where(function ($query3) use ($data) {
                                                $query3->where('process_list.user_id', $data['user_id'])
                                                    ->orWhere('process_list.user_id', 0);
                                            })
                                            ->whereIn('process_list.office_id', $data['office_ids'])
                                            ->where('process_list.desk_id', '!=', 0)
                                            ->whereNotIn('process_list.status_id', [-1]);
                                    });
                                } else {
                                    $query->orWhere(function ($queryInc) use ($data) {
                                        $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                            ->where(function ($query3) use ($data) {
                                                $query3->where('process_list.user_id', $data['user_id'])
                                                    ->orWhere('process_list.user_id', 0);
                                            })
                                            ->whereIn('process_list.office_id', $data['office_ids'])
                                            ->where('process_list.desk_id', '!=', 0)
                                            ->whereNotIn('process_list.status_id', [-1]);
                                    });
                                }
                                $i++;
                            }
                        }
                    });
                }
            }


            // Global search list
            if ($request->process_search == 'true') {

                /*
                 *In the case of a desk officer, a search is done for the desk and all applications within the office.
                 * In that case, if another officer delegates his desk to that officer, then the search will be made
                 * between the desk of the delegated officer and all applications under the office.
                 */
                if ($userType == '4x404') {
                    $getSelfAndDelegatedUserDeskOfficeIds = CommonFunction::getDelegatedUserDeskOfficeIds();
                    $query->where(function ($query1) use ($getSelfAndDelegatedUserDeskOfficeIds) {
                        $i = 0;
                        foreach ($getSelfAndDelegatedUserDeskOfficeIds as $data) {
                            $queryInc = '$query' . $i;

                            if ($i == 0) {
                                $query1->where(function ($queryInc) use ($data) {
                                    $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                        ->where(function ($query3) use ($data) {
                                            $query3->where('process_list.user_id', $data['user_id'])
                                                ->orWhere('process_list.user_id', 0);
                                        })
                                        ->whereIn('process_list.office_id', $data['office_ids'])
                                        ->where('process_list.desk_id', '!=', 0)
                                        ->whereNotIn('process_list.status_id', [-1]);
                                });
                            } else {
                                $query1->orWhere(function ($queryInc) use ($data) {
                                    $queryInc->whereIn('process_list.desk_id', $data['desk_ids'])
                                        ->where(function ($query3) use ($data) {
                                            $query3->where('process_list.user_id', $data['user_id'])
                                                ->orWhere('process_list.user_id', 0);
                                        })
                                        ->whereIn('process_list.office_id', $data['office_ids'])
                                        ->where('process_list.desk_id', '!=', 0)
                                        ->whereNotIn('process_list.status_id', [-1]);
                                });
                            }
                            $i++;
                        }
                    });
                }

                // The draft application cannot be searched by Desk user
                if ($userType != '5x505') {
                    $query->whereNotIn('process_list.status_id', [-1]);
                }

                $query = $this->scopeSearch($query, $request);
            } else {

                // List wise search
                if ($search_input) {
                    $query->where(function ($query) use ($search_input) {
                        $query->where('tracking_no', 'like', '%' . $search_input . '%')
                            ->orWhere('user_desk.desk_name', 'like', '%' . $search_input . '%')
                            ->orWhere('process_type.name', 'like', '%' . $search_input . '%')
                            ->orWhere('json_object', 'like', '%' . $search_input . '%')
                            ->orWhere('process_status.status_name', 'like', '%' . $search_input . '%');
                    });
                }

                if ($process_type_id) {
                    $query->where('process_list.process_type_id', $process_type_id);
                }

                $from = Carbon::now();
                $to = Carbon::now();

                // applicant 4 years and other desk users 6 months of data will be shown by default
                $previous_month = (in_array($userType, ['5x505', '6x606']) ? 36 : 6);
                $from->subMonths($previous_month);
                $query->whereBetween('process_list.created_at', [$from, $to]);
            }

            // Order By
            if ($column_name) {
                $query->orderBy($column_name, $order);
            } else {
                $query->orderBy('process_list.created_at', 'desc');
            }

            $data = $query->paginate($limit, [
                'process_list.id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'json_object',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.priority',
                'process_list.process_desc',
                'process_list.updated_at',
                'process_list.updated_by',
                DB::raw("CONCAT_WS(' ', locked_by_user.user_first_name, locked_by_user.user_middle_name, locked_by_user.user_last_name) as locked_by_user"),
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.created_by',
                'process_list.read_status',
                'user_desk.desk_name',
                'process_status.status_name',
                'process_type.name as process_name',
                'process_type.form_url',
                'process_type.form_id',
                DB::Raw('IFNULL( `process_favorite_list`.`id` , 0 ) as is_favourite')
            ]);

            $data->getCollection()->transform(function ($value) {
                $value->encoded_process_type_id = Encryption::encodeId($value->process_type_id);
                $value->encoded_ref_id = Encryption::encodeId($value->ref_id);
                $value->encoded_process_id = Encryption::encodeId($value->id);
                return $value;
            });

            return response()->json($data);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.' . $e->getMessage() . '[PLC-1003]');
            return redirect()->back();
        }
    }

    public function scopeSearch($query, $request)
    {
        if (!empty($request->get('search_date'))) {
            $from = Carbon::parse($request->get('search_date'));
            $to = Carbon::parse($request->get('search_date'));
        } else {
            $from = Carbon::now();
            $to = Carbon::now();
        }
        switch ($request->get('search_time')) {
            case 30:
                $from->subMonth();
                $to->addMonth();
                break;
            case 15:
                $from->subWeeks(2);
                $to->addWeeks(2);
                break;
            case 7:
                $from->subWeek();
                $to->addWeek();
                break;
            case 1:
                $from->subDay();
                $to->addDay();
                break;
            default:
        }


        if (!empty($request->get('search_date'))) {
            $query->whereBetween('process_list.created_at', [$from, $to]); //date time wise search
        }
        if (strlen($request->get('search_text')) > 2) { //for search text data
            $query->where(function ($query1) use ($request) {
                $query1->where('process_list.json_object', 'like', '%' . $request->get('search_text') . '%')
                    ->orWhere('process_list.tracking_no', 'like', '%' . $request->get('search_text') . '%');
            });
        }
        if ($request->get('search_type') > 0) {
            $query->where('process_list.process_type_id', $request->get('search_type'));
            if (CommonFunction::getUserType() != '5x505') {
                $query->where('process_list.status_id', '!=', -1);
            }
        } else { // search from dashbord box
            if (CommonFunction::getUserType() != '5x505') {
                $query->where('process_list.status_id', '!=', -1);
            }
        }
        if ($request->get('search_status') > 0 || $request->get('search_status') == -1) {
            $query->whereIn('process_list.status_id', explode(",", $request->get('search_status')));
        }

        return $query;
    }


    public function getAuthData()
    {
        return response()->json(Auth::user());
    }


    public function getStatusListByProcessType($process_type_id)
    {
        $status =  ProcessStatus::where('process_type_id', $process_type_id)
            ->whereNotIn('id', [-1, 3])
            ->orderBy('status_name')
            ->select(DB::raw("CONCAT(id,' ') AS id"), 'status_name')
            ->get([
                'status_name', 'id'
            ]);

        return response()->json($status);
    }



    public function applicationView($encoded_app_id, $encoded_process_type_id)
    {
        try {
            $process_type_id = Encryption::decodeId($encoded_process_type_id);
            $application_id = Encryption::decodeId($encoded_app_id);

            $data['process_info'] = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('pdf_print_requests_queue as pdf', function ($join) use ($process_type_id) {
                    $join->on('pdf.app_id', '=', 'process_list.ref_id');
                    $join->on('pdf.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->where([
                    'process_list.ref_id' => $application_id,
                    'process_list.process_type_id' => $process_type_id,
                ])->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.office_id',
                    'process_list.cat_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.priority',
                    'process_list.json_object',
                    'process_list.updated_at',
                    'process_list.created_by',
                    'process_list.user_id',
                    'process_list.read_status',
                    'process_list.submitted_at',
                    'process_type.name',
                    'process_type.acl_name',
                    'process_type.form_id',
                    'ps.status_name',
                    'pdf.certificate_link',
                    'user_desk.desk_name',
                ]);

            $data['process_info']->encoded_process_type_id = Encryption::encodeId($data['process_info']->process_type_id);
            $data['process_info']->encoded_ref_id = Encryption::encodeId($data['process_info']->ref_id);
            $data['process_info']->encoded_process_list_id = Encryption::encodeId($data['process_info']->process_list_id);
            $data['process_info']->encoded_status_from = Encryption::encodeId($data['process_info']->status_id);
            $data['process_info']->encoded_desk_from = Encryption::encodeId($data['process_info']->desk_id);
            $data['process_info']->encoded_cat_id = Encryption::encodeId($data['process_info']->cat_id);
            $data['process_info']->encoded_office_id = Encryption::encodeId($data['process_info']->office_id);


            // ViewMode, EditMode permission setting
            $data['viewMode'] = 'on';
            $data['openMode'] = 'view';
            $data['mode'] = '-V-';


            $form_id = json_decode($data['process_info']->form_id, true);
            $data['url'] = (isset($form_id[$data['openMode']]) ? $form_id[$data['openMode']] : '');


            // Update process read status from applicant user
            if ($data['process_info']->created_by == Auth::user()->id && in_array($data['process_info']->status_id, [5, 6, 25]) && $data['process_info']->read_status == 0) {
                $this->updateProcessReadStatus($application_id);
            }


            /**
             * if this user has access to application processing,
             * then check the permission for this application with the corresponding desk, office etc.
             */
            $accessMode = ACL::getAccsessRight($data['process_info']->acl_name);
            $data['hasDeskOfficeWisePermission'] = false;
            if (ACL::isAllowed($accessMode, '-UP-')) {
                $data['hasDeskOfficeWisePermission'] = CommonFunction::hasDeskOfficeWisePermission($data['process_info']->desk_id, $data['process_info']->office_id);

                // Update process read status from desk officer user
                if ($data['hasDeskOfficeWisePermission'] && $data['process_info']->read_status == 0) {
                    $this->updateProcessReadStatus($application_id);
                }

                if ($data['hasDeskOfficeWisePermission']) {
                    $process_type_id = $data['process_info']->process_type_id;
                    $process_list_id = $data['process_info']->process_list_id;
                    $process_status_id = $data['process_info']->status_id;

                    $data['remarks_attachment'] = DB::select(DB::raw(
                        "select * from
                                                `process_documents`
                                                where `process_type_id` = $process_type_id and `ref_id` = $process_list_id and `status_id` = $process_status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$process_list_id AND process_type_id=$process_type_id AND status_id=$process_status_id)
                                                ORDER BY id ASC"
                    ));

                    if (!empty($data['remarks_attachment'])) {
                        foreach ($data['remarks_attachment'] as $remarks) {
                            $remarks->file = url($remarks->file);
                        }
                    }
                }
            }

            $data['delegateUserInfo'] = CommonFunction::DelegateUserInfo($data['process_info']->desk_id);


            /*
            * Conditional data for desk user, system admin
            */
            $data['processVerificationData'] = [];
            if ($data['hasDeskOfficeWisePermission']) {

                /**
                 * Lock application by the current user,
                 * if the current user's desk id is not equal to zero (0) and
                 * application desk id is in user's authorized desk
                 */
                $userDeskIds = CommonFunction::getUserDeskIds();

                if (Auth::user()->desk_id != 0 && (in_array($data['process_info']->desk_id, $userDeskIds) || in_array($data['process_info']->desk_id, $this->getDelegateUsers()))) {
                    ProcessList::where('id', $data['process_info']->process_list_id)->update([
                        'locked_by' => Auth::user()->id,
                        'locked_at' => date('Y-m-d H:i:s')
                    ]);
                }
                // End Lock application by current desk user


                /**
                 * $processVerificationData variable must should be same as $existProcessInfo variable
                 * of checkApplicationValidity() function, it's required for application verification
                 */

                $processVerificationData['id'] = $data['process_info']->process_list_id;
                $processVerificationData['status_id'] = $data['process_info']->status_id;
                $processVerificationData['desk_id'] = $data['process_info']->desk_id;
                $processVerificationData['user_id'] = $data['process_info']->user_id;
                $processVerificationData['office_id'] = $data['process_info']->office_id;
                $processVerificationData['tracking_no'] = $data['process_info']->tracking_no;
                $processVerificationData['created_by'] = $data['process_info']->created_by;

                /**
                 * When one officer is processing an application, another officer may want to open the application.
                 * If the 2nd officer forcibly opens the application, the previous officer should be alerted,
                 * this is done through process data verification. That's why the 'locked_by' field is a must.
                 *
                 * Locked by field updates when the application is open. Since the database will not be updated
                 * before the transaction is completed, we will give the value directly.
                 */
                $processVerificationData['locked_by'] = Auth::user()->id;
                $processVerificationData = (object)$processVerificationData;

                $data['processVerificationData'] = Encryption::encode(\App\Libraries\UtilFunction::processVerifyData($processVerificationData));
            }

            return response()->json($data);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Sorry, something went wrong. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PLC-1004]'
            ], 400);
        }
    }


    public function updateProcessReadStatus($application_id)
    {
        ProcessList::where('ref_id', $application_id)->update(['read_status' => 1]);
    }

    public function updateProcessVue(Request $request)
    {

        $rules = [
            'status_id' => 'required',
        ];

        if ($request->get('is_remarks_required') == 1) {
            $rules['remarks'] = 'required';
        }

        if ($request->get('is_file_required') == 1) {
            $rules['attach_file'] = 'required|array';
        }

        if ($request->has('pin_number')) {
            if ($request->get('pin_number') == '') {
                $rules['pin_number'] = 'required';
            }
        }
        $customMessages = [
            'status_id.required' => 'Apply Status Field Is Required',
            'remarks.required' => 'Remarks Field Is Required',
            'attach_file.required' => 'Attach File Field Is Required',
            'attach_file.array' => 'Attach File Field should be an array',
        ];

        $this->validate($request, $rules, $customMessages);

        try {

            // if isset Application processing PIN number, then match the PIN
            if ($request->has('pin_number')) {
                $security_code = trim($request->get('pin_number'));
                $user_id = CommonFunction::getUserId();
                $count = Users::where('id', $user_id)->where(['pin_number' => $security_code])->count();
                if ($count <= 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Application approval pin number doesn\'t match.'
                    ], 400);
                }
            }
            // End if isset Application processing PIN number, then match the PIN

            if (empty(Auth::user()->signature_encode)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please upload your signature before application processing.'
                ], 400);
            }

            DB::beginTransaction();

            $process_list_id = Encryption::decodeId($request->get('process_list_id'));
            $cat_id = Encryption::decodeId($request->get('cat_id'));
            $statusID = trim($request->get('status_id'));
            $deskID = (empty($request->get('desk_id')) ? 0 : trim($request->get('desk_id')));

            $existProcessInfo = ProcessList::where('id', $process_list_id)
                ->first([
                    'id',
                    'ref_id',
                    'tracking_no',
                    'office_id',
                    'process_type_id',
                    'status_id',
                    'desk_id',
                    'hash_value',
                    'user_id',
                    'created_by',
                    'locked_by'
                ]);

            /*
             * Verify Process Path
             * Check whether the application's process_type_id and cat_id and status_from
             * and desk_from and desk_to and status_to are equal with any of one row from process_path table
             */
            $process_path_count = DB::select(DB::raw("select count(*) as procss_path from process_path
                                        where process_type_id = $existProcessInfo->process_type_id
                                        AND cat_id = $cat_id
                                        AND status_from = $existProcessInfo->status_id
                                        AND desk_from = $existProcessInfo->desk_id
                                        AND desk_to = $deskID
                                        AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'"));
            if ($process_path_count[0]->procss_path == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, invalid process request.[PLC-1002]'
                ], 400);
            }
            /*
             * End Verify Process Path
             */


            // Desk user identify checking
            $user_id = 0;
            if (!empty($request->get('is_user'))) {
                $user_id = trim($request->get('is_user'));
                $findUser = Users::where('id', $user_id)->first();
                if (empty($findUser)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Selected desk user not found!.[PLC-1019]'
                    ], 400);
                }
            }
            // End Desk user identify checking


            // Process data verification, if verification is true then proceed for Processing
            $verificationData = [];
            $verificationData['id'] = $existProcessInfo->id;
            $verificationData['status_id'] = $existProcessInfo->status_id;
            $verificationData['desk_id'] = $existProcessInfo->desk_id;
            $verificationData['user_id'] = $existProcessInfo->user_id;
            $verificationData['office_id'] = $existProcessInfo->office_id;
            $verificationData['tracking_no'] = $existProcessInfo->tracking_no;
            $verificationData['created_by'] = $existProcessInfo->created_by;
            $verificationData['locked_by'] = $existProcessInfo->locked_by;
            $verificationData = (object)$verificationData;

            if (Encryption::decode($request->data_verification) == \App\Libraries\UtilFunction::processVerifyData($verificationData)) {

                // On Behalf of desk id
                $on_behalf_of_user = 0;
                $my_desk_ids = CommonFunction::getUserDeskIds();
                if (!in_array($existProcessInfo->desk_id, $my_desk_ids)) {
                    $on_behalf_of_user = Encryption::decodeId($request->get('on_behalf_user_id'));
                }

                // Process attachment store
                if ($request->hasFile('attach_file')) {
                    $attach_file = $request->file('attach_file');
                    foreach ($attach_file as $afile) {
                        $original_file = $afile->getClientOriginalName();
                        $afile->move('uploads/', time() . $original_file);
                        $file = new ProcessDoc();
                        $file->process_type_id = $existProcessInfo->process_type_id;
                        $file->ref_id = $process_list_id;
                        $file->desk_id = $deskID;
                        $file->status_id = $statusID;
                        $file->file = 'uploads/' . time() . $original_file;
                        $file->save();
                    }
                }
                // End Process attachment store

                // Updating process list
                $status_from = $existProcessInfo->status_id;
                $deskFrom = $existProcessInfo->desk_id;

                if (empty($deskID)) {
                    $whereCond = "select * from process_path
                                where process_type_id='$existProcessInfo->process_type_id'
                                AND status_from = '$status_from'
                                AND desk_from = '$deskFrom'
                                AND status_to REGEXP '^([0-9]*[,]+)*$statusID([,]+[,0-9]*)*$'";
                    $processPath = DB::select(DB::raw($whereCond));

                    $deskList = null;
                    // if ext_sql not null
                    if (count($processPath) > 0 && $processPath[0]->ext_sql1 != "NULL" && $processPath[0]->ext_sql1 != "") {
                        $fullSql = $processPath[0]->ext_sql . $existProcessInfo->ref_id; // concat app id
                        $ext_sql_desk_list = \DB::select(DB::raw($fullSql));
                        if ($ext_sql_desk_list[0]->returnStatus == 1) {
                            $deskList = $ext_sql_desk_list; // assign new desk list from new query
                        }
                    }
                    if (!empty($deskList[0]->deskIsnull) && $deskList[0]->deskIsnull != -100) {
                        $deskID = $deskList[0]->deskIsnull;
                    } else {
                        $deskID = 0;
                        $user_id = 0;
                        if (count($processPath) > 0 && $processPath[0]->desk_to == '0')  // Sent to Applicant
                            $deskID = 0;
                        if (count($processPath) > 0 && $processPath[0]->desk_to == '-1') {  // Keep in same desk
                            $deskID = $deskFrom;
                            $user_id = CommonFunction::getUserId(); //user wise application assign
                        }
                    }
                }

                // Process data for modification
                $processData['desk_id'] = $deskID;
                $processData['status_id'] = $statusID;
                $processData['process_desc'] = $request->get('remarks');
                $processData['user_id'] = $user_id;
                $processData['on_behalf_of_user'] = $on_behalf_of_user;
                $processData['updated_by'] = Auth::user()->id;
                $processData['locked_by'] = 0;
                $processData['locked_at'] = null;
                $processData['read_status'] = 0;

                $processTypeFinalStatus = ProcessType::where('id', $existProcessInfo->process_type_id)->first(['final_status']);
                $finalStatus = explode(",", $processTypeFinalStatus->final_status);
                $closed_by = 0;
                if (in_array($statusID, $finalStatus)) {
                    $closed_by = CommonFunction::getUserId();
                }
                $processData['closed_by'] = $closed_by;

                /*
                 * Process Hash value generate
                 */


                $resultData = $existProcessInfo->id . '-' . $existProcessInfo->tracking_no .
                    $deskID . '-' . $statusID . '-' . $processData['user_id'] . '-' .
                    $processData['updated_by'];

                $processData['previous_hash'] = $existProcessInfo->hash_value;
                $processData['hash_value'] = Encryption::encode($resultData);
                /*
                 * End Process Hash value generate
                 */

                ProcessList::where('id', $existProcessInfo->id)->update($processData);


                /*
                 * process type wise, process status wise additional info update
                 * application certificate generation, email or sms sending function,
                 * During the processing of the application, the data provided by the desk user in the add-on form is given
                 * CertificateMailOtherData() comes from app\Modules\ProcessPath\helper.php
                 */
                $result = CertificateMailOtherData($existProcessInfo->id, $statusID, $existProcessInfo->desk_id, $request->all());


                if ($result == false) {
                    DB::rollback();
                    // Session error message will come from CertificateMailOtherData() function (if needed)
                    // return Redirect::back()->withInput();

                    return response()->json([
                        'status' => false,
                        'message' => Session::get("error")
                    ], 400);
                }

                DB::commit();
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, Process data verification failed. [PLC-1003]'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Process updated successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Sorry, something went wrong. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PLC-1004]'
            ], 400);
        }
    }

    public function getShadowFileHistory($process_type_id, $ref_id)
    {
        $process_type_id = Encryption::decodeId($process_type_id);
        $ref_id = Encryption::decodeId($ref_id);
        $getShadowFile = ShadowFile::where('user_id', CommonFunction::getUserId())
            ->where('ref_id', $ref_id)
            ->where('process_type_id', $process_type_id)
            ->orderBy('id', 'DESC')
            ->get();

        if (!empty($getShadowFile)) {
            foreach ($getShadowFile as $file) {
                $file->file_path = empty($file->file_path) ? '' : url($file->file_path);
            }
        }

        return response()->json($getShadowFile);
    }


    public function getApplicationHistory($process_list_id)
    {
        try {
            $decoded_process_list_id = Encryption::decodeId($process_list_id);
            $process_history = DB::select(DB::raw("select  `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`,
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                CONCAT_WS(' ', users.user_first_name, users.user_middle_name, users.user_last_name) as user_full_name,
                                `process_list_hist`.`updated_by`,
                                `process_list_hist`.`status_id`,
                                `process_list_hist`.`process_desc`,
                                `process_list_hist`.`process_id`,
                                `process_list_hist`.`updated_at`,
                                 group_concat(`pd`.`file`) as files
                                from `process_list_hist`
                                left join `process_documents` as `pd` on `process_list_hist`.`id` = `pd`.`process_hist_id`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`

                                left join `process_status` as `as` on `process_list_hist`.`status_id` = `as`.`id`
                                and `process_list_hist`.`process_type_id` = `as`.`process_type_id`
                                where `process_list_hist`.`process_id`  = '$decoded_process_list_id'
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc
                    "));

            if (!empty($process_history)) {
                foreach ($process_history as $history) {

                    if ($history->files) {
                        $fileList = explode(',', $history->files);

                        foreach ($fileList as $key => $file) {
                            $fileList[$key] = url($file);
                        }

                        $history->files = implode(',', $fileList);
                    }
                }
            }

            // dd($process_history);
            return response()->json($process_history);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, something went wrong. ' . CommonFunction::showErrorPublic($e->getMessage()) . '[PLC-1004]'
            ], 400);
        }
    }

    public static function statusWiseAppsCount($process_type_id)
    {
        // dd($process_type_id);

        // $process_type_id = 0;
        $userType = Auth::user()->user_type;

        if (($userType == "1x101" || $userType == "10x101" || $userType == "4x404" || $userType == "5x505") && $process_type_id != '') {

            $status_wise_apps = ProcessList::statuswiseAppInDesks($process_type_id);
            // dd($status_wise_apps);
            return response()->json($status_wise_apps);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Process type id not found in request'
            ], 400);
        }
    }
}
