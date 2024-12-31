<?php

namespace App\Modules\REUSELicenseIssue\Models\BPO\surrender;

use App\Modules\REUSELicenseIssue\Interfaces\FormInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ExistingCallCenterSurrenderDetails extends Model {

    protected $table = 'existing_call_center_surrender_details';
    protected $guarded = [ 'id' ];

}
