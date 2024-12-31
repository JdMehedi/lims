<?php

namespace App\Modules\Dashboard\Models;
use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DynamicPayment extends Model
{
    protected $table = 'dynamic_service_payment';
    protected $fillable = [
        'app_id',
        'process_type_id',
        'app_tracking_no',
        'payment_status',
        'is_verified',
        'payment_type',
        'pay_order_number',
        'pay_order_date',
        'bank_id',
        'branch_id',
        'pay_order_copy',
        'created_by',
        'updated_by'
    ];


    public static function boot()
    {
        parent::boot();
        // Before update
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

}
