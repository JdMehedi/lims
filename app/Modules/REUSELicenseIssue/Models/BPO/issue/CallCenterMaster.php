<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\issue;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CallCenterMaster extends Model
{
    protected $table = 'call_center_master';
    protected $guarded = ['id'];
}
