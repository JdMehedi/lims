<?php

namespace App\Modules\REUSELicenseIssue\Models\ITC\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITCLicenseMaster extends Model
{
    protected $table = 'itc_license_master';
    protected $guarded = ['id'];
}
