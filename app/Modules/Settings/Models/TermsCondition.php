<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TermsCondition extends Model {

    protected $table = 'terms_condition';
    protected $fillable = array(
        'id',
        'subject',
        'description',
        'showing_order',
        'status',
        'pdf_link',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
