<?php


namespace App\Modules\Documents\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocuments extends Model
{
    protected $table = 'doc_of_applications';

    protected $fillable = [
        'id',
        'process_type_id',
        'ref_id',
        'doc_list_for_service_id',
        'doc_name',
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