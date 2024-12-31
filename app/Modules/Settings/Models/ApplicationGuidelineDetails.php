<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApplicationGuidelineDetails extends Model {

    protected $table = 'application_guideline_details';
    protected $guarded = ['id'];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}