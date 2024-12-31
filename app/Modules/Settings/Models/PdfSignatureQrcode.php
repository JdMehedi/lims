<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;


class PdfSignatureQrcode extends Model
{

    protected $table = 'pdf_signature_qrcode';
    protected $fillable = array(
        'id',
        'signature',
        'qr_code',
        'app_id',
        'service_id',
        'user_id'
    );

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
}
