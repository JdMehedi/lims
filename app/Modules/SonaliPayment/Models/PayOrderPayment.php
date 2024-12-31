<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class PayOrderPayment extends Model
{
    protected $table = 'pay_order_payment';
    protected $guarded = [];
//    protected $fillable = [
//        'payment_config_id',
//        'app_id',
//        'process_type_id',
//        'app_tracking_no',
//        'payment_step_id',
//        'payment_category_id',
//        'pay_mode',
//        'pay_mode_code',
//        'transaction_id',
//        'request_id',
//        'payment_date',
//        'ref_tran_no',
//        'ref_tran_date_time',
//        'pay_amount',
//        'transaction_charge_amount',
//        'vat_amount',
//        'receiver_ac_no',
//        'sender_ac_no',
//        'contact_name',
//        'contact_email',
//        'contact_no',
//        'payer_id',
//        'address',
//        'sl_no',
//        'status_code',
//        'payment_status',
//        'is_verified',
//        'payment_request_xml',
//        'payment_request',
//        'payment_response_xml',
//        'payment_response',
//        'verification_request_xml',
//        'verification_request',
//        'verification_response_xml',
//        'verification_response',
//        'created_at',
//        'created_by',
//        'updated_at',
//        'updated_by',
//        'spg_trans_date_time',
//        'pay_order_number',
//        'pay_order_date',
//        'bank_id',
//        'branch_id',
//        'pay_order_copy'
//    ];

//    public static function boot()
//    {
//        parent::boot();
//        static::creating(function ($post) {
//            $post->created_by = getCurrentUserId();
//            $post->updated_by = getCurrentUserId();
//        });
//
//        static::updating(function ($post) {
//            $post->updated_by = getCurrentUserId();
//        });
//    }
}
