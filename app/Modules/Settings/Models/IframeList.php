<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IframeList extends Model {

    protected $table = 'iframe_list';
    protected $fillable = array(
        'id',
        'key',
        'title',
        'title_en',
        'body',
        'status',
        'created_at',
        'is_archive',
        'created_by',
        'updated_by',
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
