<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class UserDevice extends Model {

    protected $table = 'user_device';
    protected $fillable = array(
        'id',
        'user_id',
        'os',
        'ip',
        'browser',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = CommonFunction::getUserId();
                $post->updated_by = CommonFunction::getUserId();
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = CommonFunction::getUserId();
            }
        });
    }


    /*     * ***************************** Users Model Class ends here ************************* */
}
