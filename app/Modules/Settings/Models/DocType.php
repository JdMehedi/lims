<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class DocType extends Model {

    protected $table = 'doc_type';
    protected $fillable = [
        'id',
        'process_type_id',
        'label_name',
        'sql',
        'filed_type',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

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

    /*     * *****************************End of Model Function********************************* */
}
