<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConfiguration extends Model
{

    protected $table = 'sp_payment_configuration';
    protected $fillable = array(
        'id',
        'process_type_id',
        'payment_category_id',
        'payment_step_id',
        'payment_name',
        'amount',
        'vat_tax_percent',
        'trans_charge_percent',
        'trans_charge_min_amount',
        'trans_charge_max_amount',
        'status',
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
