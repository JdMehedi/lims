<?php


namespace App\Modules\Settings\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentName extends Model
{
    protected $table = 'doc_name';

    protected $fillable = [
        'id',
        'name',
        'max_size',
        'min_size',
        'status',
        'company_doc_type',
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

    public static function getDocList()
    {
        DB::statement(DB::raw('set @rownum = 0'));
        return DocumentName::where('is_archive', 0)
            ->orderBy('created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum + 1 AS sl'),
                'id',
                'name',
                'simple_file',
                'status'
            ]);
    }
}
