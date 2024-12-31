<?php

namespace App\Modules\REUSELicenseIssue\Models\NTTN;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NTTNLicenseMaster extends Model
{
    protected $table = 'nttn_license_master';
    protected $guarded = ['id'];
}
