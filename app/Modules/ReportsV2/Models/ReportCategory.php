<?php namespace App\Modules\ReportsV2\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReportCategory extends Model {

    protected $table = 'report_category';


    protected $fillable = ['category_name', 'status','is_archive','created_at','created_by' ,'updated_at' ,'updated_by'];


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