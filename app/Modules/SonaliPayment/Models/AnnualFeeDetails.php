<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualFeeDetails extends Model{
    protected $table = 'sp_annual_fee_details';

    protected $fillable = [
        'id',
        'years',
        'payment_due_date',
        'paid_at',
        'payment_step',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'exp_notice_30',
        'exp_notice_180',
        'contact_number',
        'contact_email',
        'ref_id',
    ];
}
