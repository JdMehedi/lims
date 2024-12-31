<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use App\Modules\Settings\Models\Area;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Users extends Model
{

    protected $table = 'users';
    protected $fillable = array(
        'id',
        'user_type',
        'user_sub_type',
        'working_company_id',
        'working_user_type',
        'office_ids', // office_ids == ministry_id
        'service_ids',
        'organization',
        'desk_id',
        'last_login_type',
        'user_first_name',
        'user_middle_name',
        'user_last_name',
        'designation',
        'user_email',
        'password',
        'delegate_to_user_id',
        'delegate_by_user_id',
        'user_hash',
        'user_status',
        'user_verification',
        'email_verified',
        'pin_number',
        'user_pic',
        'user_nid',
        'user_DOB',
        'user_gender',
        'user_mobile',
        'authorization_file',
        'passport_nid_file',
        'signature',
        'signature_encode',
        'user_first_login',
        'user_language',
        'security_profile_id',
        'details',
        'division',
        'divisionName',
        'district',
        'districtName',
        'thana',
        'thanaName',
        'union',
        'unionName',
        'country',
        'nationality',
        'passport_no',
        'state',
        'province',
        'contact_address',
        'post_code',
        'user_fax',
        'remember_token',
        'login_token',
        'user_hash_expire_time',
        'delegated_at',
        'auth_token_allow',
        'auth_token',
        'user_agreement',
        'first_login',
        'otp_expire_time',
        'identity_type',
        'user_status_comment',
        'is_approved',
        'is_locked',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = CommonFunction::getUserId();
                $post->updated_by = CommonFunction::getUserId();
            }
        });

        static::saved(function ($post) {
//            $body = [];
//            $client = ClientBuilder::create()->build();
//            $body['index'] = 'users';
//            $body['id'] = $post->id;
//            $body['body'] = $post->toArray();
//            $client->index($body);
        });

        static::updating(function ($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = CommonFunction::getUserId();
            }
        });
    }

    function chekced_verified($TOKEN_NO, $data)
    {
        DB::table($this->table)
            ->where('user_hash', $TOKEN_NO)
            ->update($data);
    }

    function profile_update($table, $field, $check, $value)
    {
        return DB::table($table)->where($field, $check)->update($value);
    }

    public static function getUserList()
    {

        $officeIds = CommonFunction::getUserOfficeIds();
        $user_list_query = Users::leftJoin('user_types as ut', 'ut.id', '=', 'users.user_type')
//            ->leftJoin('area_info as district', 'district.area_id', '=', 'users.district')
//            ->leftJoin('area_info as thana', 'thana.area_id', '=', 'users.thana')
            ->orderBy('users.id', 'desc')
            ->orderBy('users.created_at', 'desc')
            ->groupBy('users.id')
            ->where('users.user_status', '!=', 'rejected');

        if (in_array(Auth::user()->user_type, ['5x505', '6x606'])) {
            $user_company_id = Auth::user()->working_company_id;
        } elseif (!in_array(Auth::user()->user_type, ['1x101','10x101', '2x202', '8x808', '30x330'])) {
            $user_list_query->where('users.id', Auth::user()->id);
        } elseif (in_array(Auth::user()->user_type, ['8x808'])) {
            $user_list_query->where('users.user_type', '4x404');
        } elseif (in_array(Auth::user()->user_type, ['30x330'])) {
            $user_list_query->whereIn('users.office_ids', $officeIds);
        }
        return $user_list_query->get([
            'users.id',
            DB::raw("CONCAT_WS(' ', users.user_first_name, users.user_middle_name, users.user_last_name) as user_full_name"),
            'users.created_at',
            'users.user_email',
            'users.user_status',
            'users.login_token',
            'users.user_first_login',
            'users.user_type',
//            'users.user_status_comment as reject_reason',
            'ut.type_name',
//            \DB::raw("GROUP_CONCAT(ci.company_name) as company_name")
        ]);

    }

    public static function getRejectedUserList()
    {
        return Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
            ->leftJoin('area_info', 'users.district', '=', 'area_info.area_id')
            ->leftJoin('area_info as ai', 'users.thana', '=', 'ai.area_id')
//            ->leftJoin('company_info as ci', 'users.user_sub_type', '=', 'ci.id') // will be applied only in case of applicant users
            ->orderBy('users.id', 'desc')
            ->orderBy('users.created_at', 'desc')
            ->where('users.user_status', 'rejected')
            ->get([
                'users.id',
                DB::raw("CONCAT_WS(' ', users.user_first_name, users.user_middle_name, users.user_last_name) as user_full_name"),
                'users.created_at',
                'users.user_sub_type',
                'users.user_email',
                'users.user_status',
                'users.login_token',
                'users.user_first_login',
                'users.user_type',
                'users.user_status_comment as reject_reason',
                'users.updated_at',
                'ai.area_nm as thana',
                'area_info.area_nm as users_district',
                'mty.type_name',
//                'ci.company_name'
            ]);

    }

    function getHistory($email)
    {
        $users_type = Auth::user()->user_type;
        $type = explode('x', $users_type)[0];
        if ($type == 1) { // 1x101 for Super Admin
            return DB::table('failed_login_history')->where('user_email', $email)->get(['user_email', 'remote_address', 'created_at']);
//                            ->where('users.user_type', '!=', Auth::user()->user_type
        }
    }


    function getUserRow($user_id)
    {
        return Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
            ->leftJoin('registration_office as pi', 'pi.id', '=', 'users.office_ids')
            ->where('users.id', $user_id)
            ->first(['users.*', 'pi.id as ezid', 'pi.name_bn as ez_name', 'mty.type_name', 'mty.id as type_id']);
    }

    function checkEmailAndGetMemId($user_email)
    {
        return DB::table($this->table)
            ->where('user_email', $user_email)
            ->pluck('id');
    }

    public static function setLanguage($lang)
    {
        Users::find(Auth::user()->id)->update(['user_language' => $lang]);
    }

    /**
     * @param $users object of logged in user
     * @return array
     */
    public static function getUserSpecialFields($users)
    {
        $additional_info = [];
        $user_type = explode('x', $users->user_type)[0];

        switch ($user_type) {

            case 4:  //SB
                $additional_info = [
                    [
                        'caption' => 'District',
                        'value' => $users->district != 0 ? Area::where('area_id', $users->district)->pluck('area_nm') : '',
                        'caption_thana' => 'Thana',
                        'value_thana' => $users->thana != 0 ? Area::where('area_id', $users->thana)->pluck('area_nm') : ''
                    ]
                ];
                break;
        }
        return $additional_info;
    }

    /*     * ***************************** Users Model Class ends here ************************* */
}
