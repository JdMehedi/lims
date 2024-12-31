<?php namespace App\Modules\CompanyAssociation\Models;

use App\Libraries\ReportHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libraries\CommonFunction;

class CompanyAssociationMaster extends Model {

    protected $table = 'company_association_master';


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });


        static::updating(function ($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }
/********************End of Model Class*****************/
}
