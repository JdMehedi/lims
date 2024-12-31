<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class QRCodeDetails extends Model
{
    protected $table = 'qr_code_details';
    protected $fillable = [
        'payment_config_id',
        'app_id',
        'process_type_id',
        'process_type_name',
        'app_tracking_no',
        'payment_step_id',
        'pay_mode',
        'pay_mode_code',
        'transaction_id',
        'request_id',
        'payment_date',
        'ref_tran_no',
        'qr_code_url',
        'created_at',
        'created_by',
    ];
    public $timestamps = false;
}
