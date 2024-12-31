<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DynamicProcess extends Model {

    protected $table = 'process_type_dynamic_data';
    protected $fillable = array(
        'id',
        'process_type_id',
        'dynamic_data',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
/*********************************************End of Model Class**********************************************/
}
