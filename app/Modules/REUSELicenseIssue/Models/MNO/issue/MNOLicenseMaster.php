<?php

namespace App\Modules\REUSELicenseIssue\Models\MNO\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MNOLicenseMaster extends Model
{
    protected $table = 'mno_license_master';
    protected $guarded = ['id'];
}
