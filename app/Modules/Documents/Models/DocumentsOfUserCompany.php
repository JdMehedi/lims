<?php


namespace App\Modules\Documents\Models;


use App\Libraries\CommonFunction;
use App\Modules\Settings\Models\DocumentName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentsOfUserCompany extends Model
{
    protected $table = 'doc_of_user_company';

    protected $fillable = [
        'user_id',
        'company_id',
        'doc_id',
        'uploaded_path',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    public static function getUserCompanyDocuments()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return DocumentName::leftJoin('doc_of_user_company', function ($on) {
            $on->on('doc_of_user_company.doc_id', '=', 'doc_name.id')
                ->where('doc_of_user_company.user_id', '=', CommonFunction::getUserId())
                ->where('doc_of_user_company.company_id', '=', Auth::user()->working_company_id);
        })
            ->where([
                'doc_name.status' => 1,
                'doc_name.is_archive' => 0
            ])->get([
                DB::raw('@rownum  := @rownum  + 1 AS sl'),
                'doc_name.id',
                'doc_name.name',
                'doc_of_user_company.id as u_d_id',
                'doc_of_user_company.uploaded_path',
                 'doc_of_user_company.updated_at'
            ]);
    }
}