<?php

namespace App\Modules\REUSELicenseIssue\Models\IGW\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IGWLicenseMaster extends Model
{
    protected $table = 'igw_license_master';
    protected $guarded = ['id'];
}
