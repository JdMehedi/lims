<?php

namespace App\Modules\REUSELicenseIssue\Models\SS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSLicenseMaster extends Model
{
    protected $table = 'ss_license_master';
    protected $guarded = ['id'];
}
