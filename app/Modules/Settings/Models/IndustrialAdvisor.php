<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IndustrialAdvisor extends Model {

    protected $table = 'industrial_advisor';
    protected $fillable = array(
        'id',
        'name_en',
        'name',
        'profile_photo',
        'designation_en',
        'designation',
        'office_en',
        'office',
        'details',
        'details_en',
        'mobile_no',
        'email',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_by',
        'is_active',
        'created_by',
    );

    public static function boot()
    {
        parent::boot();
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    /*     * ******************End of Model Class***************** */
}
