<?php

namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use Carbon\Carbon;
use DB;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProcessList extends Model
{

    //
    protected $table = 'process_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_id',
        'company_id',
        'license_no',
        'office_id',
        'cat_id',
        'tracking_no',
        'license_json',
        'json_object',
        'desk_id',
        'user_id',
        'process_type_id',
        'status_id',
        'read_status',
        'on_behalf_of_user',
        'process_desc',
        'closed_by',
        'locked_at',
        'locked_by',
        'submitted_at',
        'resubmitted_at',
        'completed_date',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'previous_hash',
        'hash_value',
        'initial_pay',
        'nothi_receipt_no',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }


    public static function getApplicationList($process_type_id = 0, $status = 0, $request, $desk)
    {
        $userType = CommonFunction::getUserType();
        $companyId = Auth::user()->working_company_id;
        $userDeskIds = CommonFunction::getUserDeskIds();
        $userOfficeIds = CommonFunction::getUserOfficeIds();
        $delegatedUserDeskOfficeIds = CommonFunction::getDelegatedUserDeskOfficeIds();
        $user_id = CommonFunction::getUserId();

        $query = ProcessList::leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
            ->leftjoin('process_status', function ($on) {
                $on->on('process_list.status_id', '=', 'process_status.id')
                    ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
            })
            ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
            ->where('process_type.active_menu_for', 'like', "%$userType%");

        // System admin can only view the application without Draft and Shortfall status
        if ($userType == '1x101' || $userType == '10x101' || $userType == '2x202') { // System Admin
            $query->whereNotIn('process_list.status_id', [-1]);

        } // General users can only view the applications related to their company
        elseif (in_array($userType, ['5x505', '6x606'])) {
            $query->where('process_list.company_id', $companyId);
        } else {
            /*
             * Desk User can only view the applications related to their desk and assigned office
             * and status id is not Draft or Shortfall
             */


            //Condition applied for my-desk data only
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

        if ($desk == 'favorite_list') {
            $query->Join('process_favorite_list', 'process_list.id', '=', 'process_favorite_list.process_id')
                ->where('process_favorite_list.user_id', CommonFunction::getUserId());
        }

        if ($request->has('process_search')) { //work for search parameter

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
                        if ($i == 0) {
                            $query1->where(function ($queryInc) use ($data) {
                                $queryInc->whereIn('process_list.office_id', $data['office_ids']);
                            });
                        } else {
                            $query1->orWhere(function ($queryInc) use ($data) {
                                $queryInc->whereIn('process_list.office_id', $data['office_ids']);
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
            $query->search($request); //calling of scopeSearch function

        } else {
            if ($process_type_id) {
                $query->where('process_list.process_type_id', CommonFunction::vulnerabilityCheck($process_type_id, 'integer'));
            }
            // $from = Carbon::now();
            // $to = Carbon::now();
            // // applicant 4 years and other desk users 6 months of data will be shown by default
            // $previous_month = (in_array($userType, ['5x505', '6x606']) ? 36 : 6);
            // $from->subMonths($previous_month); //maximum 5month data selection by default
            // $query->whereBetween('process_list.created_at', [$from, $to]);
        }

        $query->orderBy('process_list.created_at', 'desc')
            ->distinct('process_list.id');
        return $query->select([
            'process_list.id',
            'process_list.ref_id',
            'process_list.tracking_no',
            'process_list.license_no',
            'process_list.user_id',
            'process_list.license_json',
            'json_object',
            'process_list.desk_id',
            'process_list.process_type_id',
            'process_list.status_id',
            'process_list.priority',
            'process_list.process_desc',
            'process_list.updated_at',
            'process_list.updated_by',
            'process_list.locked_by',
            'process_list.locked_at',
            'process_list.created_by',
            'process_list.read_status',
            'user_desk.desk_name',
            'process_status.status_name',
            'process_type.name as process_name',
            'process_type.drop_down_label',
            'process_type.form_url',
            'process_type.form_id'
        ]);
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
        if($request->get('search_status') == 'bulk_status'){
            $query->where('process_list.bulk_status',1);
        }else if ($request->get('search_status') == 100) {
            $query->where('process_list.bulk_status',1);
        }else if ($request->get('search_status') == 0) {
            $query->where('process_list.bulk_status',0)->whereNotIn('process_list.status_id',['-1','1','2','5','15','16','25']);
        }else if ($request->get('search_status') > 0 || $request->get('search_status') == -1) {
            $query->whereIn('process_list.status_id', explode(",", $request->get('search_status')))
                  ->where('process_list.bulk_status', 0);
        }
        return $query;
    }

    public static function statuswiseAppInDesks($process_type_id)
    {
        $userType = CommonFunction::getUserType();
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $getSelfAndDelegatedUserDeskOfficeIds = CommonFunction::getSelfAndDelegatedUserDeskOfficeIds();

        $status_wise_apps_query = ProcessStatus::leftJoin('process_list', function ($join) use ($process_type_id) {
            $join->on('process_status.id', '=', 'process_list.status_id');
            $join->on('process_list.process_type_id', '=', DB::raw($process_type_id));
            $join->where('bulk_status', 0);
        })->where('process_status.process_type_id', $process_type_id)
        ;

        // System admin can only view the application without Draft and Waiting for Payment Confirmation status
        if ($userType == '1x101' || $userType == '10x101' || $userType == '2x202') {
            $status_wise_apps_query->whereNotIn('process_list.status_id', [-1, 3]);

        } // General users can only view the applications related to their company
        elseif ($userType == '5x505') {
            $status_wise_apps_query->where('process_list.company_id', $companyIds);

        } // For desk user
        else {
            /*
             * Desk users will find their own office applications.
             * Besides, if another user delegates his desk to the user,
             * he will also get all the user's office applications.
             */
            $status_wise_apps_query->where(function ($query1) use ($getSelfAndDelegatedUserDeskOfficeIds) {
                $i = 0;
                foreach ($getSelfAndDelegatedUserDeskOfficeIds as $data) {
                    if ($i == 0) {
                        $query1->where(function ($queryInc) use ($data) {
                            $queryInc->whereIn('process_list.office_id', $data['office_ids']);
                        });
                    } else {
                        $query1->orWhere(function ($queryInc) use ($data) {
                            $queryInc->whereIn('process_list.office_id', $data['office_ids']);
                        });
                    }
                    $i++;
                }
            })->whereNotIn('process_list.status_id', [-1, 3]);
        }

        $get_status_wise_apps = $status_wise_apps_query
                ->orderBy('process_status.id')
                ->groupBy('process_status.id')
                ->get([
                    'process_status.id',
                    'status_name',
                    'process_status.color',
                    'process_status.process_type_id',
                    DB::raw('count(process_list.ref_id) AS totalApplication')
                ]);

        $appsStatus = ProcessStatus::where('process_type_id', $process_type_id)
            ->whereNotIn('id', [-1, 3])
            ->where('status', 1)
            ->get(['id', 'status_name', 'color']);


        $status_wise_apps = [];


        foreach ($appsStatus as $status) {
            $status_wise_apps[$status->id] = [
                'id' => $status->id,
                'status_name' => $status->status_name,
                'color' => $status->color,
                'process_type_id' => $process_type_id,
                'totalApplication' => 0,
            ];
        }

        foreach ($get_status_wise_apps as $app) {
            $status_wise_apps[$app->id]['totalApplication'] = $app->totalApplication;
        }
//        dd($status_wise_apps);
        return $status_wise_apps;
    }
}
