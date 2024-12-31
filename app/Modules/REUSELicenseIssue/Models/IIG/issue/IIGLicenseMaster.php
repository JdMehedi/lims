<?php

namespace App\Modules\REUSELicenseIssue\Models\IIG\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IIGLicenseMaster extends Model
{
    protected $table = 'iig_license_master';
    protected $guarded = ['id'];
}
