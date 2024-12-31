<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HomeContent extends Model {

    protected $table = 'home_page_content';
    protected $fillable = array(
        'id',
        'type',
        'title',
        'title_en',
        'heading_en',
        'heading',
        'details',
        'details_en',
        'details_url',
        'order',
        'image',
        'icon',
        'status',
        'created_at',
        'is_archive',
        'created_by',
        'updated_by',
        'is_active',
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
