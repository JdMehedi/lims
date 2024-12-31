<?php

namespace App\Modules\REUSELicenseIssue\Models\TC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TCLicenseMaster extends Model
{
    protected $table = 'tc_license_master';
    protected $guarded = ['id'];
}
