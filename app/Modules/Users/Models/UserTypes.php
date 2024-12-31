<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserTypes extends Model {

    protected $table = 'user_types';
    protected $fillable = array(
        'id',
        'type_name',
        'is_registrable',
        'access_code',
        'user_manual_name',
        'permission_json',
        'delegate_to_types',
        'status',
    );


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

    /************************ Users Model Class ends here ****************************/
}
