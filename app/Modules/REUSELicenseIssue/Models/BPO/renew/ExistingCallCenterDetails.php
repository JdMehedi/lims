<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\renew;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ExistingCallCenterDetails extends Model {

    protected $table = 'existing_call_center_details';
    protected $guarded = [ 'id' ];

}
