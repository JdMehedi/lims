<?php

namespace App\Modules\IndustryReRegistration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryReRegistration extends Model
{
    protected $table = 'rr_ind_apps';
    protected $guarded = ['id'];
}
