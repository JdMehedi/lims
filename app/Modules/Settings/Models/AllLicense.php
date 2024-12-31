<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class AllLicense extends Model {

    protected $table = 'all_license';
    protected $fillable = array(
        'name',
    );
    public $timestamps = false;

}
