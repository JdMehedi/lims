<?php namespace App\Modules\ReportsV2\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reports extends Model {

	protected $table = 'custom_reports';


    protected $fillable = ['category_id', 'report_title', 'report_para1','status','user_id','selection_type'];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
//            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    public static function isReportAdmin()
    {
        return ['1x101','10x101', '15x151','7x707'];
    }
}
