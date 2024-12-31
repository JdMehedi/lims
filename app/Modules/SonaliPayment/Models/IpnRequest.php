<?php

namespace App\Modules\SonaliPayment\Models;

use Illuminate\Database\Eloquent\Model;

class IpnRequest extends Model
{
    protected $table = 'sp_ipn_request';

    protected $fillable = [
        'id',
        'request_ip',
        'transaction_id',
        'pay_mode_code',
        'trans_time',
        'ref_tran_no',
        'ref_tran_date_time',
        'trans_status',
        'trans_amount',
        'pay_amount',
        'json_object',
        'ipn_response',
        'is_authorized_request',
        'is_archive',
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

    static public function getVisitorRealIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
        // if above code is not working properly then
        /*
        if(!empty($_SERVER['HTTP_X_REAL_IP'])){
            $ip=$_SERVER['HTTP_X_REAL_IP'];
        }
        */

        //dd($request->ip(),$request->getClientIp(), $request->REMOTE_ADDR, $ip,$_SERVER['HTTP_X_REAL_IP']);

        return $ip;

    }
}