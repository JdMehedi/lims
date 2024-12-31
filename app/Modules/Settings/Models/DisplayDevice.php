<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DisplayDevice extends Model {

    protected $table = 'display_device_config';
    protected $fillable = array(
        'id',
        'device_name',
        'device_code',
        'configuration',
        'scroll_msg',
        'scroll_msg_status',
        'popup_msg',
        'popup_msg_status',
        'is_default_configuration',
        'status',
        'updated_at',
        'updated_by',
        'created_at',
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
