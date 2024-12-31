<?php

namespace App\Modules\REUSELicenseIssue\Models\ICX\issue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ICXLicenseMaster extends Model
{
    protected $table = 'icx_license_master';
    protected $guarded = ['id'];
}
