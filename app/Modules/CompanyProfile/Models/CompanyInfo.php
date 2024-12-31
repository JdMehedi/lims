<?php

namespace App\Modules\CompanyProfile\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model {

    protected $table = 'company_info';
    protected $fillable = array(
        'id',
        'org_nm',
        'org_nm_bn',
        'regist_type',
        'org_type',
        'type',
        'invest_type',
        'investment_limit',
        'ind_category_id',
        'ins_sector_id',
        'ins_sub_sector_id',
        'incorporation_num',
        'office_division',
        'office_district',
        'office_thana',
        'office_location',
        'office_postcode',
        'office_email',
        'office_mobile',
        'office_phone',
        'factory_division',
        'factory_district',
        'factory_thana',
        'factory_location',
        'factory_postcode',
        'factory_email',
        'factory_mobile',
        'factory_phone',
        'ceo_name',
        'ceo_father_nm',
        'nationality',
        'director_type',
        'passport',
        'nid',
        'dob',
        'designation',
        'ceo_division',
        'ceo_district',
        'ceo_thana',
        'ceo_location',
        'ceo_postcode',
        'ceo_email',
        'ceo_mobile',
        'ceo_phone',
        'bscic_office_id',
        'main_activities',
        'commercial_operation_dt',
        'project_deadline',
        'is_archived',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_approved',
        'is_rejected',
        'approved_at',
        'company_status',
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
