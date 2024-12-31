<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class SonaliPaymentHistory extends Model
{
    protected $table = 'sp_payment_history';
    protected $guarded = ['id'];

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
