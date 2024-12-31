<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'sp_payment_details';
    protected $fillable = [
        'sp_payment_id',
        'payment_distribution_id',
        'pay_amount',
        'receiver_ac_no',
        'purpose',
        'fix_status',
        'distribution_type',
        'confirm_amount_sbl',
        'is_verified',
        'verification_request',
        'verification_response',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = getCurrentUserId();
            $post->updated_by = getCurrentUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = getCurrentUserId();
        });
    }
}
