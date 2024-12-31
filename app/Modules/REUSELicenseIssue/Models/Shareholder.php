<?php

namespace App\Modules\REUSELicenseIssue\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shareholder extends Model
{
    protected $table = 'shareholders';
    protected $guarded = ['id'];
}
