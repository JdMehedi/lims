<?php

namespace App\Modules\REUSELicenseIssue\Models\ISP\renew;

use Illuminate\Database\Eloquent\Model;

class ISPLicenseRenewShareHolder extends Model
{
    protected $table = 'isp_license_renew_shareholders';
    protected $guarded = ['id'];
}
