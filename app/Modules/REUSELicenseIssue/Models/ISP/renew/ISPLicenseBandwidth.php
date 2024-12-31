<?php
namespace App\Modules\REUSELicenseIssue\Models\ISP\renew;

use Illuminate\Database\Eloquent\Model;

class ISPLicenseBandwidth extends Model
{
    protected $table = 'isp_license_bandwidth';
    protected $guarded = ['id'];

    protected $fillable = [
        'isp_license_renew_id',
        'name_of_primary_iig',
        'allocation',
        'upstream',
        'downstream',
        'created_at',
    ];
}
