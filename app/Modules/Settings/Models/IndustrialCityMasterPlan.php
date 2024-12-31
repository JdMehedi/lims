<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IndustrialCityMasterPlan extends Model
{

    protected $table = 'industrial_city_master_plan';
    protected $fillable = array(
        'id',
        'industrial_city_id',
        'name',
        'name_en',
        'remarks',
        'remarks_en',
        'document',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_by',
        'created_by',
    );

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });

    }
}
