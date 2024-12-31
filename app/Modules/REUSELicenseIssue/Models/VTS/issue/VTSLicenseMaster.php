<?php

namespace App\Modules\REUSELicenseIssue\Models\VTS\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VTSLicenseMaster extends Model
{
    protected $table = 'vts_license_master';
    protected $guarded = ['id'];
}
