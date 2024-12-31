<?php
namespace App\Modules\Web\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class IndustrialAdviceRequest extends Model {

    protected $table = 'industrial_advice_request';
    protected $fillable = array(
        'id',
        'name',
        'advisor_id',
        'organization_name',
        'business_type',
        'country_id',
        'mobile_no',
        'email',
        'question',
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
