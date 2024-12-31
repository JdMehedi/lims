<?php namespace App\Modules\Signup\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;
class UserVerificationData extends Model
{
    protected $table = 'users_verification_data';

    protected $fillable = array(
        'user_email', 'nationality_type', 'identity_type', 'nid_info', 'eTin_info', 'passport_info'
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
