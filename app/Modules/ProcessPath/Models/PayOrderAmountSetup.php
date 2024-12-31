<?php namespace App\Modules\ProcessPath\Models;

use Illuminate\Database\Eloquent\Model;
class PayOrderAmountSetup extends Model {

    protected $table = 'pay_order_amount_setup';
    protected $fillable = array(
        'id',
        'min_amount_bdt',
        'max_amount_bdt',
        'min_amount_usd',
        'max_amount_usd',
        'p_o_amount_bdt',
        'p_o_amount_usd',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}
