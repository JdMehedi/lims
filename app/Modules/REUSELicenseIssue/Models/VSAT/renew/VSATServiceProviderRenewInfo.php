<?php

namespace App\Modules\REUSELicenseIssue\Models\VSAT\renew;

use Illuminate\Database\Eloquent\Model;

class VSATServiceProviderRenewInfo extends Model
{
    protected $table = 'vsat_license_renew_service_provider_info';
    protected $guarded = ['id'];
}
