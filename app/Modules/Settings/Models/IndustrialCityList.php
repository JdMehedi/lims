<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IndustrialCityList extends Model
{

    protected $table = 'industrial_city_list';
    protected $fillable = array(
        'id',
        'office_short_code',
        'h_office_id',
        'name',
        'name_en',
        'district',
        'district_en',
        'upazila',
        'upazila_en',
        'image',
        'details',
        'details_en',
        'latitude',
        'longitude',
        'establish_year',
        'land_amount',
        'per_acre_price',
        'used_plot',
        'ind_plot_no',
        'total_plot_allocated',
        'ind_unit_total',
        'ind_unit_under_prod',
        'ind_unit_under_cons',
        'ind_unit_off',
        'ind_unit_allocate_wait',
        'order',
        'status',
        'type',
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
