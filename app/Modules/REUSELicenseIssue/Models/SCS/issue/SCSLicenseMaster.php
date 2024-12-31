<?php

namespace App\Modules\REUSELicenseIssue\Models\SCS\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCSLicenseMaster extends Model
{
    protected $table = 'scs_license_master';
    protected $guarded = ['id'];
}
