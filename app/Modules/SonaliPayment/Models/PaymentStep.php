<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStep extends Model
{

    protected $table = 'sp_payment_steps';
    protected $fillable = array(
        'id',
        'name',
        'status',
        'amount',
        'is_archive',
        'created_by',
        'updated_by'
    );

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
