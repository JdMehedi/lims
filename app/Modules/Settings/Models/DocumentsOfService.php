<?php


namespace App\Modules\Settings\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentsOfService extends Model
{
    protected $table = 'doc_list_for_service';

    protected $fillable = [
        'id',
        'process_type_id',
        'doc_id',
        'doc_type_for_service_id',
        'order',
        'is_required',
        'autosuggest_status',
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
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

    public static function getAllServiceDocument()
    {
        DB::statement(DB::raw('set @rownum = 0'));
        return DocumentsOfService::leftJoin('process_type', 'process_type.id', '=', 'doc_list_for_service.process_type_id')
            ->leftJoin('doc_name', 'doc_name.id', '=', 'doc_list_for_service.doc_id')
            ->leftJoin('doc_type_for_service', 'doc_type_for_service.id', '=', 'doc_list_for_service.doc_type_for_service_id')
            ->where('doc_list_for_service.is_archive', 0)
            ->orderBy('doc_list_for_service.created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum + 1 AS sl'),
                'doc_list_for_service.id',
                'doc_name.name as doc_name',
                'process_type.name as process_type',
                'doc_type_for_service.name as doc_type',
                'doc_list_for_service.is_required',
                'doc_list_for_service.status',
            ]);
    }
}
