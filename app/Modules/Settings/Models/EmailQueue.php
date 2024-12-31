<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{

    protected $table = 'email_queue';
    protected $fillable = array(
        'id',
        'app_id',
        'process_type_id',
        'status_id',
        'caption',
        'email_content',
        'email_from',
        'email_to',
        'email_cc',
        'email_status',
        'email_subject',
        'attachment',
        'attachment_certificate_name',
        'sms_content',
        'sms_to',
        'sms_status',
        'response',
        'sent_on',
        'cron_id',
        'email_no_of_try',
        'web_notification',
        'others_info',
        'email_response',
        'email_response_id',
        'sms_response',
        'sms_response_id',
        'secret_key',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });

    }

    /*     * *******************************************End of Model Class********************************************* */
}
