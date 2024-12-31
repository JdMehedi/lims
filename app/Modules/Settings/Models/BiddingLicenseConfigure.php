<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BiddingLicenseConfigure extends Model
{

    protected $table = 'bidding_license_configure';
    protected $fillable = array(
        'id',
        'module_names',
        'start_date',
        'end_date',
        'status',
        'created_at',
        'updated_at'
    );
}
