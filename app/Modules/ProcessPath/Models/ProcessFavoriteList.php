<?php

namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
class ProcessFavoriteList extends Model {

    protected $table = 'process_favorite_list';
    protected $fillable = array(
        'id',
        'user_id',
        'process_id',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
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

    /*     * *****************************End of Model Class********************************** */
}