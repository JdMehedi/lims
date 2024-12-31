<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class UserManual extends Model {

    protected $table = 'user_manual';
    protected $fillable = array(
      'process_type_id','pdfFile','status','typeName','order'
    );

    public $timestamps = false;

}
