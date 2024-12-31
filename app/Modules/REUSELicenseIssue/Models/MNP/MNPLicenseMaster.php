<?php

namespace App\Modules\REUSELicenseIssue\Models\MNP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MNPLicenseMaster extends Model
{
    protected $table = 'mnp_license_master';
    protected $guarded = ['id'];
}
