<?php

namespace App\Modules\ProcessPath\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
class HelpText extends Model {

    protected $table = 'help_text';
    protected $fillable = array(
        'id',
        'filed_id',
        'help_text',
        'is_active',
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