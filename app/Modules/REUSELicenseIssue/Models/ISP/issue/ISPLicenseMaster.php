<?php

namespace App\Modules\REUSELicenseIssue\Models\ISP\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISPLicenseMaster extends Model
{
    protected $table = 'isp_license_master';
    protected $guarded = ['id'];
}
