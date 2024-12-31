<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\Amendment;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ExistingCallCenterAmendmentDetails extends Model {

    protected $table = 'existing_call_center_amendment_details';
    protected $guarded = [ 'id' ];

}
